<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Room;
use App\Models\User;
use App\Events\RoomDice;
use App\Models\GiftSends;
use App\Models\GuessGame;
use App\Models\RoomMusic;
use App\Events\SeatLocked;
use App\Models\BannedUser;
use App\Events\DriverAdded;
use App\Events\MessageSent;
use App\Events\RoomUpdated;
use App\Events\UserBlocked;
use App\Events\SeatUnlocked;
use App\Models\RoomsDrivers;
use Illuminate\Http\Request;
use App\Events\DriverRemoved;
use App\Events\DriverRequest;
use App\Models\CurrencyWorth;
use App\Events\DriverResponse;
use App\Events\DriversUpdated;
use App\Events\RoomActiveMusic;
use App\Events\RequestCancelled;
use App\Jobs\RemoveFromBlacklist;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RoomResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\RoomMusicResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\RoomDetailedResource;

class RoomController extends BaseController
{

    public function getMyRoom(Request $request)
    {
        try {

            $room = Room::where('host_id', Auth::id())->first();

            if($room){
                $room->update(['status' => 'active']);
                $this->sitDrivers((new Request())->merge([
                    'room_id' => $room->id,
                    'sit_num' => 0,
                    'action' => 'connect'
                ]));
                if($room->music) $room->update(['music_time' => (int) Carbon::now()->valueOf()]);
            }

            return response()->json([
                'status_code' => 200,
                'room_id' => $room->id ?? 0,
            ], 200);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function getRooms(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'status'           => 'in:active,inactive'
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            if(BannedUser::find(auth()->user()->id)){
                return response()->json([
                    'status_code' => 407,
                    'message' => 'You are banned!',
                ], 407);
            }

            $rooms = Room::with(['host'])
                ->when($request->has('status'), function($q) use($request){
                    $q->where('status', $request->input('status'));
                })->latest()->get();

            $user_counts = Room::getRoomsWithUsersCount();

            $rooms = $rooms->map(function($room) use($user_counts) {
                if(!isset($user_counts[$room->id]) && now()->diffInSeconds($room->updated_at) > 5) {
                    $room->update(['status' => 'inactive']);
                }
                $room->members_count = $user_counts[$room->id] ?? 0;
                return $room;
            })->filter(fn($room) => $room->members_count > 0)->sortByDesc('members_count');

            $prohibited_words = DB::table('prohibited_words')->pluck('word')->toArray();

            return response()->json([
                'status_code' => 200,
                'rooms' => RoomResource::collection($rooms),
                'prohibited_words' => $prohibited_words,
            ], 200);

        }catch(\Exception $ex){
            return $this->internalServerError($ex);
        }
    }

    public function getRoomDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id'         => 'required|exists:rooms,id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $room = Room::with(['host:id,user_name,profile_pic', 'rooms_drivers.driver', 'blacklist', 'mutes', 'music_list'])->find($request->input('id'));
            $user = Auth::user();

            if($room->blacklist->find($user->id)){
                return response()->json([
                    'status_code' => 403,
                    'message' => 'blocked',
                ], 403);
            }

            if($room->banlist->find($user->id)){
                return response()->json([
                    'status_code' => 403,
                    'message' => 'banned by user id',
                ], 403);
            }

            if($room->banlist->where('fcm_key', $user->fcm_key)->first()){
                return response()->json([
                    'status_code' => 403,
                    'message' => 'banned by fcm token',
                ], 403);
            }

            if($room->type == 1 || $room->host_id == Auth::id() || ($room->type == 2 && $room->password == $request->input('password'))) {
                $room->agora_token = $room->token();
            }

            return response()->json([
                'status_code' => 200,
                'data' => new RoomDetailedResource($room),
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function getRoomMembersCount(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id'         => 'required|exists:rooms,id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $room_status = Room::find($request->input('id'))->status;
            $members_count = $this->getRoomUsersCount($request->input('id'));

            return response()->json([
                'status_code' => 200,
                'room_status' => $room_status,
                'members_count' => $members_count,
            ], 200);

        }catch(\Exception $ex){
            return $this->internalServerError($ex);
        }
    }

    public function getRoomMembersDetails(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id'         => 'required|exists:rooms,id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $room_status = Room::find($request->input('id'))->status;
            $members_count = $this->getRoomUsersCount($request->input('id'));
            $members = $this->getRoomUsers($request->input('id'));

            return response()->json([
                'status_code' => 200,
                'room_status' => $room_status,
                'members_count' => $members_count,
                'members' => $members,
            ], 200);

        }catch(\Exception $ex){
            return $this->internalServerError($ex);
        }
    }

    public function createRoom(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name'          => 'required',
                'type'          => 'in:public,private',
                'password'      => 'required_if:type,private',
                'background'    => 'nullable|string',
                'music'         => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }
            DB::beginTransaction();

            $input = $request->all();
            $input['host_id'] = Auth::id();
            $input['status'] = 'active';

            $user_have_room = Room::where('host_id', $input['host_id'])->first();
            if($user_have_room){
                DB::commit();
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Please Wait ! ',
                ]);
            }else{
                $room = Room::create($input);
                DB::commit();
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Room created successfully ',
                    'room' => new RoomResource($room),
                ]);
            }

            // $room = Room::create($input);
            // DB::commit();
            // return response()->json([
            //     'status_code' => 200,
            //     'message' => 'Room created successfully ',
            //     'room' => new RoomResource($room),
            // ]);

        } catch (\Exception $ex) {

            DB::rollBack();
            return $this->internalServerError($ex);
        }
    }

    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'room_id'       => 'required|exists:rooms,id',
                'status'        => 'in:active,inactive',
                'background'    => 'nullable|string',
                'music'         => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $user = Auth::user();
            $room = Room::find($request->input('room_id'));

            if ($room->host_id !== $user->id) {
                return $this->apiErrorResponse('Sorry you aren\'t the hoster of the room!', 409);
            }

            $room->update($request->except('host_id'));

            if(count(array_intersect(array_keys($room->getChanges()), ['name', 'music', 'background', 'show_calculator', 'music_volume']))){
                broadcast(new RoomUpdated($room));
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
            ]);

        }catch(\Exception $ex){
            return $this->internalServerError($ex);
        }
    }

    public function sendMessage(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'room_id'           => 'required|exists:rooms,id',
                'message'           => 'required',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            broadcast(new MessageSent($request->room_id, Auth::user(), $request->message));

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function muteDriver(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'room_id'           => 'required|exists:rooms_drivers,room_id,driver_id,'.Auth::id(),
                'user_id'           => 'nullable|exists:rooms_drivers,driver_id,room_id,'.$request->room_id,
                'muted'             => 'required|integer|min:0|max:1'
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $room = Room::find($request->room_id);

            $driver_id = $request->filled('user_id') ? $request->input('user_id') : Auth::id();

            $muted_by_admin = $request->muted && Auth::id() == $room->host_id && $request->filled('user_id');

            $updated = DB::table('room_mutes')->updateOrInsert([
                'room_id' => $request->input('room_id'),
                'user_id' => $driver_id,
            ],[
                'muted' => $request->muted,
                'by_admin' => $muted_by_admin
            ]);

            if($updated){
                broadcast(new DriversUpdated($room));
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function blockMember(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'room_id'           => 'required|exists:rooms,id,host_id,'. Auth::id(),
                'user_id'           => 'required|exists:users,id',
                'block'             => 'required|in:1,0'
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            if((bool) $request->block){
                DB::table('room_blacklist')->updateOrInsert($request->only('room_id', 'user_id'));
                broadcast(new UserBlocked($request->room_id, $request->user_id));
                RemoveFromBlacklist::dispatch($request->room_id, $request->user_id)->delay(now()->addMinutes(1));
            }else{
                DB::table('room_blacklist')->where([
                    ['room_id', $request->room_id],
                    ['user_id', $request->user_id],
                ])->delete();
            }

            return response()->json([
                'response_code' => 200,
                'message' => 'success'
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function getDriverGiftSenders(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'room_id'       => 'required|exists:rooms,id',
                'sit_num'       => 'required|integer|min:0|max:7',
            ]);

            if ($validator->fails() != null) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $driver  = RoomsDrivers::where('room_id', $request->room_id)
                ->where('sit_num', $request->sit_num)
                ->where('status', 'connected')
                ->first();

            if (!$driver) return $this->apiErrorResponse('driver not found', 409);

            $ratio = CurrencyWorth::firstOrCreate(['diamond' => 1, 'gem' => 1, 'charisma' => 1,  'contribution' => 1]);
            $charisma_factor = $ratio->charisma / $ratio->diamond;

            $senders = GiftSends::with('sender')
                ->where('created_at', '>=', $driver->created_at)
                ->where('to_user_id', $driver->driver_id)
                ->groupBy('from_user_id')
                ->selectRaw('from_user_id, sum(price) as price')
                ->get()->map(function($user) use($charisma_factor) {
                    return [
                        'sender' => new UserResource($user->sender->setAttribute('user_name', $user->sender->name)),
                        'charisma' => $user->price * $charisma_factor
                    ];
                })->sortByDesc('charisma')->values();

            return response()->json([
                'response_code' => 200,
                'message' => 'success',
                'senders' => $senders,
            ]);

        } catch (\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function webhook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'events'           => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->apiErrorResponse($validator->errors()->first(), 409);
        }

        $events = collect($request->input('events'));

        $presence = $events->first(function($item){
            return str_contains($item['channel'], 'presence-users');
        });

        if($presence && $presence['name'] == 'member_removed'){
            $room = Room::find(substr($presence['channel'], 15));

            // check if driver has already been removed
            if(Cache::get("room.{$room->id}.driver.{$presence['user_id']}.disconnected")){
                Cache::forget("room.{$room->id}.driver.{$presence['user_id']}.disconnected");
                return response()->json();
            }

            $driver = $room->rooms_drivers()->where('driver_id', $presence['user_id'])->first();
            if($driver){
                $driver->delete();
                broadcast(new DriversUpdated($room));
                // $room->broadcastCharismaCounter();
            }

            if($room->host_id == $presence['user_id']) $room->update(['status' => 'inactive']);
        }

        if($presence && $presence['name'] == 'channel_vacated'){
            $room_id = substr($presence['channel'], 15);
            $room = Room::find($room_id);
            $room->update(['status' => 'inactive']);
            $room->rooms_drivers()->where('rooms_drivers.status', 'connected')->get()->each->delete();
            if($room->music) $room->update(['music_time' => 0]);
            RoomMusic::where('room_id', $room_id)->where('is_played', 1)->update(['is_active' => 0]);
        }

        return response()->json();
    }

    public function driverRequest(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'room_id'       => 'required|exists:rooms,id',
                'sit_num'       => 'required',
            ]);

            if ($validator->fails() != null) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            if(RoomsDrivers::where('room_id', $request->room_id)->where('sit_num', $request->sit_num)->exists()){
                return $this->apiErrorResponse('seat reserved', 409);
            }

            broadcast(new DriverRequest($request->room_id, $request->sit_num, Auth::user()));

            return response()->json([
                'response_code' => 200,
                'message' => 'success'
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function driverResponse(Request $request)
    {
        try {

            $request->merge(['host_id' => Auth::id()]);

            $validator = Validator::make($request->all(), [
                'host_id'       => 'exists:rooms,host_id',
                'user_id'       => 'required|exists:users,id',
                'sit_num'       => 'required',
                'response'      => 'required|in:0,1',
            ],[
                'host_id' => 'Room not found!'
            ]);

            if ($validator->fails() != null) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $room = Room::firstWhere('host_id', Auth::id());

            $info = $this->getRoomUsers($room->id);

            if($request->input('response', 0) && $info && collect($info->users)->firstWhere('id', $request->user_id)){
                return $this->sitDrivers($request->merge(['room_id' => $room->id, 'action' => 'connect']));
            }else{
                broadcast(new RequestCancelled($room->id, $request->user_id));
            }

            return response()->json([
                'response_code' => 200,
                'message' => 'success'
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function sitDrivers(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'room_id'       => 'required|exists:rooms,id',
                'user_id'       => 'exists:users,id',
                'sit_num'       => 'required',
                'action'        => 'required|in:connect,disconnect,lock,unlock',
                'status'        => 'nullable|in:active,inactive',
            ]);

            if ($validator->fails() != null) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $room = Room::find($request->room_id);

            $driver_id = $request->input('user_id', Auth::id());

            $lock = Cache::lock("drivers-{$room->id}-{$driver_id}", 5);

            if($lock->get()) {
                $sit_exist = RoomsDrivers::where('room_id', $request->room_id)
                    ->where('sit_num', $request->sit_num)->latest()->first();

                $action = $request->action;

                $new = RoomsDrivers::make([
                    'driver_id' => $driver_id,
                    'room_id' => $room->id,
                    'sit_num' => $request->sit_num,
                    'status' => $action . 'ed',
                ]);

                if($sit_exist){
                    if($new->only(['driver_id', 'status']) == $sit_exist->only(['driver_id', 'status'])){
                        $lock->release();
                        return $this->apiResponse([], 'Success. No change required');
                    }
                }

                $response = $this->callAction($action, [$new, $sit_exist ?? new RoomsDrivers(), $room]);

                $lock->release();

                if($request->filled('status') && $room->host_id == Auth::id()){
                    $room->update(['status' => $request->input('status')]);
                }

                return $response;
            }

            return $this->apiErrorResponse('Another request for the same driver is being processed righ now.', 409);

        } catch (\Exception $ex) {
            if (isset($lock)) $lock->release();
            return $this->internalServerError($ex);
        }
    }

    protected function connect($new, $old, $room)
    {
        if(in_array($old->status, ['connected', 'locked']) && Auth::id() != $room->host_id){
            return $this->apiErrorResponse('You are unauthorized to do this action', 409);
        }

        if($driver = RoomsDrivers::where([['room_id', $room->id],['status', 'connected'],['driver_id', $new->driver_id]])->first()){
            if($driver->driver_id == Auth::id()){
                $driver->delete();
                // broadcast(new DriverRemoved($room, $driver->sit_num));
            }else{
                return $this->apiErrorResponse('Driver already exists', 409);
            }
        }

        if($old->id){
            $old->delete();
        }

        $new->save();

        broadcast(new DriversUpdated($room));
        // $room->broadcastCharismaCounter();

        return $this->apiResponse([], 'Connected successfuly');
    }

    protected function disconnect($new, $old, $room)
    {
        if(!$old->id){
            return $this->apiErrorResponse('Driver not found', 409);
        }

        if($old->status == 'locked'){
            return $this->apiErrorResponse('Sorry! you should use unlock action', 409);
        }

        // if(Auth::id() != $old->driver_id && Auth::id() != $room->host_id){
        //     return $this->apiErrorResponse('You are unauthorized to do this action', 409);
        // }

        $old->delete();

        if(request()->filled('status')){
            Cache::put("room.{$room->id}.driver.{$old->driver_id}.disconnected", true, 10);
        } // to prevent pusher webhook from removing the driver again

        broadcast(new DriversUpdated($room));
        // $room->broadcastCharismaCounter();

        return $this->apiResponse([], 'Disconnected successfuly');
    }

    protected function lock($new, $old, $room)
    {
        if(Auth::id() != $room->host_id){
            return $this->apiErrorResponse('You are unauthorized to do this action', 409);
        }

        $new->driver_id = null;

        if($old->status == 'connected'){
            $old->update($new->toArray());
        }else{
            $new->save();
        }

        broadcast(new DriversUpdated($room));

        return $this->apiResponse([], 'Locked successfuly');
    }

    protected function unlock($new, $old, $room)
    {
        if(Auth::id() != $room->host_id){
            return $this->apiErrorResponse('You are unauthorized to do this action', 409);
        }

        if($old->status == 'locked'){
            $old->delete();
        }else{
            return $this->apiErrorResponse('Invalid action', 409);
        }

        broadcast(new DriversUpdated($room));

        return $this->apiResponse([], 'Unlocked successfuly');
    }

    protected function getRoomUsersCount($room_id)
    {
        $array = is_array($room_id);

        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            ['cluster' => config('broadcasting.connections.pusher.options.cluster')]
        );

        $user_counts = [];

        foreach($array ? $room_id : [$room_id] as $id){
            $info = $pusher->get_channel_info("presence-users.{$id}", ['info' => 'user_count']);
            // $user_counts[$id] = $info->user_count;
            if($info){
                $user_counts[$id] = $info->user_count;
            }else{
                $user_counts[$id] = 0;
            }
        }

        return $array ? $user_counts : $user_counts[$room_id];
    }

    protected function getRoomUsers($room_id)
    {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            ['cluster' => config('broadcasting.connections.pusher.options.cluster')]
        );


        $info = $pusher->get_users_info("presence-users.{$room_id}");

        return $info;
    }





    public function getRoomMusicList(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'room_id'         => 'required|exists:rooms,id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $music_list = RoomMusic::where('room_id', $request->input('room_id'))->with('room')->get();
            if($music_list->count() == 0){
                return $this->apiErrorResponse('This room don\'t have music list ');
            }else{
                return $this->apiResponse(RoomMusicResource::collection($music_list));
            }

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }


    public function updateRoomMusic(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'music_id'         => 'required|exists:room_musics,id',
                'is_active'         => 'nullable|numeric|in:0,1',
                'is_played'         => 'nullable|numeric|in:0,1',
                'seek_to'         => 'nullable|numeric',
                'start_time'         => 'nullable|numeric',
                'music_volume'         => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $music_id = $request->input('music_id');
            $music_object = RoomMusic::find($music_id);
            $music_object->update($request->all());

            if($request->is_active == 1){
                RoomMusic::where('id', '<>', $music_id)->get()->each->update([ 'is_active' => 0 ]);
            }
            if($request->music_volume == true){$music_object->room()->update(['music_volume' => $request->music_volume]);}

            broadcast(new RoomActiveMusic($music_object));
            return $this->apiResponse(new RoomMusicResource($music_object) , 'Music updated successfully!');

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }


    public function uploadRoomMusic(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'room_id'         => 'required|exists:rooms,id',
                'name'         => 'required',
                'music'         => 'nullable|string',
                'music_url'         => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $music_object = RoomMusic::create($request->all());
            if($request->music_url){
                $music_object->update(['music' => $request->music_url]);
            }

            return $this->apiResponse(new RoomMusicResource($music_object) , 'Upload Successed!');

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }


    public function deleteRoomMusic(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'music_id'         => 'required|exists:room_musics,id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $music = RoomMusic::find($request->music_id);
            $music->update(['is_active' => 0, 'is_played' => 0]);

            broadcast(new RoomActiveMusic($music, 4));
            $music->delete();

            return $this->apiResponse([], 'Deleted Successfuly!');

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function getDiceRoom(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'room_id'         => 'required|exists:rooms,id',
                'num'             => 'required',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            broadcast(new RoomDice($request->room_id, $request->num));
            return $this->apiResponse([], 'Dice Generated Successfuly!');
        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }
}
