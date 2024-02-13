<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Room;
use App\Models\Frame;
use App\Models\LoginSign;
use App\Models\FrameUser;
use App\Models\Background;
use App\Models\BannedUser;
use App\Events\UserBannedd;
use App\Events\RoomUpdated;
use App\Models\UserStreamer;
use Illuminate\Http\Request;
use App\Models\LoginSignUser;
use App\Jobs\FCMNotification;
use App\Models\DiamondTransfer;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\web\UserCreateRequest;
use App\Http\Requests\web\UserUpdateRequest;

class UserController extends Controller
{
    //
    protected $mainUrl = 'dashboard.user.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'client');
        });

        if ($request->get('q')) {
            $users->where('name', 'like', '%' . $request->get('q') . '%')
                ->orwhere('user_name', 'like', '%' . $request->get('q') . '%')
                ->orwhere('email', 'like', '%' . $request->get('q') . '%')
                ->orwhere('phone', 'like', '%' . $request->get('q') . '%')
                ->orwhere('id', $request->get('q'));
        }

        $data['users'] = $users->paginate(10);
        return view($this->mainUrl . 'index', $data);
    }

    public function getStreamers(Request $request)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'streamer');
        });

        if ($request->get('q')) {
            $users->where('name', 'like', '%' . $request->get('q') . '%')
                ->orwhere('user_name', 'like', '%' . $request->get('q') . '%')
                ->orwhere('email', 'like', '%' . $request->get('q') . '%')
                ->orwhere('phone', 'like', '%' . $request->get('q') . '%')
                ->orwhere('id', $request->get('q'));
        }

        $data['users'] = $users->paginate(10);
        return view($this->mainUrl . 'index', $data);
    }

    public function create()
    {
        return view($this->mainUrl . 'create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(UserCreateRequest $request)
    {
        $data = $request->all();
        $user_exist = User::where('email', $request->input('email'))->withTrashed()->first();
        if ($user_exist) {

            // if the client is soft deleted restore him
            if ($user_exist->deleted_at !== null) {
                $user_exist->restore();
                $user_exist->update($data);
                $user_exist->assignRole('client');
                return redirect()->route($this->mainUrl . 'index')->with('success', [
                    'type' => 'success',
                    'message' => 'User created successfully.'
                ]);
            }

            return redirect()->route($this->mainUrl . 'index')->with('success', [
                'type' => 'success',
                'message' => 'User Is Already Exist !.'
            ]);
        }


        $user = User::create($data);
        /** @var User $logged_in_user */
        $logged_in_user = Auth::user();
        if($logged_in_user->hasRole('streamer_admin'))
        {
            $user->assignRole('streamer');

            UserStreamer::create([
                'streamer_id' => $user->id,
                'streamer_admin_id' => $logged_in_user->id,
                'cost_per_hour' => $request->get('cost_per_hour')
            ]);

            return redirect()->route($this->mainUrl . 'streamers')->with('success', [
                'type' => 'success',
                'message' => 'User account created successfully.'
            ]);

        }
        else
        {
            $user->assignRole('client');
        }

        return redirect()->route($this->mainUrl . 'index')->with('success', [
            'type' => 'success',
            'message' => 'User account created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $data['resource'] = $user;
        return view($this->mainUrl . 'view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $data['resource'] = $user;
        $data['frames'] = Frame::all()->pluck('name', 'id');
        $data['backgrounds'] = Background::all()->pluck('name', 'id');
        $data['login_signs'] = LoginSign::all()->pluck('name', 'id');

        if($user->hasRole('streamer')){
            $data['resource']->cost_per_hour = UserStreamer::where('streamer_id', $user->id)->first()->cost_per_hour;
        }
        return view($this->mainUrl . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->all();
        
        if ($request->frame_id) {
            FrameUser::where('user_id', $user->id)->update(['is_active'  => 0]);
            $user->framesHistory()
            ->attach($request->frame_id, [
                'is_active'  => 1,
                'status'     => 0,
                'bought_at'  => now(),
            ]);
        }

        if ($request->background_id) {
            $background = Background::find($request->background_id); 
            $room = Room::where('host_id', $user->id)->first();
            $room->update(['background'  => $background->image_url]);
            broadcast(new RoomUpdated($room));
        }

        if ($request->login_signs_id) {
            LoginSignUser::where('user_id', $user->id)->update(['is_active'  => 0]);
            $user->loginSignsHistory()
            ->attach($request->login_signs_id, [
                'is_active'  => 1,
                'status'     => 0,
            ]);
        }

        $user->update($data);

        if($user->hasRole('streamer')){
            UserStreamer::where('streamer_id', $user->id)->first()->update(['cost_per_hour' => $data['cost_per_hour']]);
        }

        if (optional($user->roles->first())->name == 'streamer') {
            return redirect()->route('dashboard.user.streamers');
        } else {
            return redirect()->route($this->mainUrl . 'index');
        }
    }

    public function destroy($id, Request $request)
    {
        $user = User::where('id', $id)->first();

        // $user->forceDelete();
        $user->delete();

        if (optional($user->roles->first())->name == 'streamer') {
            return redirect()->route('dashboard.user.streamers');
        } else {
            return redirect()->route($this->mainUrl . 'index');
        }
    }

    public function getFriends(Request $request)
    {
        $user = User::where('id', $request->get('user_id'))->first();
        $data['resource'] = $user;
        $data['friends'] = $user->friends;
        return view($this->mainUrl . 'friends', $data);
    }

    public function getFollowers(Request $request)
    {
        $user = User::where('id', $request->get('user_id'))->first();
        $data['resource'] = $user;
        $data['followers'] = $user->myFollowers()->get();
        return view($this->mainUrl . 'followers', $data);
    }

    public function getFollowing(Request $request)
    {
        $user = User::where('id', $request->get('user_id'))->first();
        $data['resource'] = $user;
        $data['followings'] = $user->amFollow()->get();
        return view($this->mainUrl . 'following', $data);
    }


    public function statusView(Request $request)
    {
        $user = User::where('id', $request->get('user_id'))->first();
        $data['resource'] = $user;
        return view($this->mainUrl . 'deactivate', $data);
    }
    public function toggleStatus(Request $request)
    {
        $user = User::where('id', $request->input('user_id'))->first();
        $message = '';
        $end_time = $request->get('end_time');
        if ($end_time == 1) {
            $end_time_after = Carbon::now()->addHours(1);
            $user->update([
                'active' => 0,
                'active_end_time' => $end_time_after
            ]);
        } else if ($end_time == 2) {
            $end_time_after = Carbon::now()->addHours(24);
            $user->update([
                'active' => 0,
                'active_end_time' => $end_time_after
            ]);
        } else if ($end_time == 3) {
            $end_time_after = Carbon::now()->addHours(72);
            $user->update([
                'active' => 0,
                'active_end_time' => $end_time_after
            ]);
        } else if ($end_time == 4) {
            $end_time_after = Carbon::now()->addHours(168);
            $user->update([
                'active' => 0,
                'active_end_time' => $end_time_after
            ]);
        } else if ($end_time == 5) {
            $end_time_after = Carbon::now()->addHours(720);
            $user->update([
                'active' => 0,
                'active_end_time' => $end_time_after
            ]);
        } else if ($end_time == 6) {
            $end_time_after = Carbon::now()->addCentury(2);
            $user->update([
                'active' => 0,
                'active_end_time' => $end_time_after
            ]);
        }
        if (optional($user->roles->first())->name == 'streamer') {
            return redirect()->route('dashboard.user.streamers');
        } else {
            return redirect()->route($this->mainUrl . 'index');
        }
    }

    public function activateUser(Request $request)
    {
        $user = User::where('id', $request->input('user_id'))->first();
        $message = '';

        $user->update([
            'active' => 1,
            'active_end_time' => null,
        ]);

        if (optional($user->roles->first())->name == 'streamer') {
            return redirect()->route('dashboard.user.streamers');
        } else {
            return redirect()->route($this->mainUrl . 'index');
        }
    }

    public function banView(Request $request)
    {
        $user = User::where('id', $request->get('user_id'))->first();
        $data['resource'] = $user;
        return view($this->mainUrl . 'ban', $data);
    }



    public function banUser(Request $request)
    {
        $user = User::find($request->input('user_id'));
        // if ($user->mobile_uuid) {
            if ($request->get('end_time') == 1) {
                $end_time = Carbon::now()->addDays(7);
                $ban_user = BannedUser::create([
                    'user_id' => $user->id,
                    'mobile_uuid' => $user->mobile_uuid,
                    'end_time'    => $end_time,
                    'fcm_key'    => $user->fcm_key,
                    'social_id'    => $user->social_id,
                    // 'email'    => $user->email
                ]);
                $duration = '7 days';
            } else {
                $end_time = Carbon::now()->addCentury(2);
                $ban_user = BannedUser::create([
                    'user_id' => $user->id,
                    'mobile_uuid' => $user->mobile_uuid,
                    'end_time'    => $end_time,
                    'fcm_key'    => $user->fcm_key,
                    'social_id'    => $user->social_id,
                    // 'email'    => $user->email
                ]);
                $duration = 'ever';
            }

            // ($userIds, $title, $body, $data, $click_action = null)
            FCMNotification::dispatch(
                [$request->input('user_id')],
                'You just have been banned',
                'The Admin has banned you from the app for ' . $duration,
                [
                    'type' => 'ban',
                    'sender_id' => $request->input('user_id'),
                    'title' => 'Hekaya Admin',
                    'message' => 'You just have been banned!',
                ],
                // 'ban'
            );

            // broadcast(new UserBannedd($ban_user->id));

            if (optional($user->roles->first())->name == 'streamer') {
                return redirect()->route('dashboard.user.streamers')->with('alert', 'Banned!');
            } else {
                return redirect()->route($this->mainUrl . 'index')->with('alert', 'Banned!');
            }
        // } else {
        //     if (optional($user->roles->first())->name == 'streamer') {
        //         return redirect()->route('dashboard.user.streamers')->with('alert', 'Cannot ban user doesnot have mobile uuid yet!');
        //     } else {
        //         return redirect()->route($this->mainUrl . 'index')->with('alert', 'Cannot ban user doesnot have mobile uuid yet!');
        //     }
        // }
    }




    public function unbanUser(Request $request)
    {
        $user = User::where('id', $request->get('user_id'))->first();

        $ban_user = BannedUser::where('user_id', $user->id)->where('mobile_uuid', $user->mobile_uuid)->first();
        $ban_user->delete();
        return redirect()->back()->with('alert', 'UnBanned!');
    }


    public function promoteView(Request $request)
    {
        $streamer_admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'streamer_admin');
        })->get()->pluck('name', 'id')->toArray();

        $data['streamer_admins'] = $streamer_admins;

        $user = User::where('id', $request->get('user_id'))->first();
        $data['resource'] = $user;
        return view($this->mainUrl . 'promote', $data);
    }
    public function promote(Request $request)
    {
        $user = User::where('id', $request->input('user_id'))->first();

        $user->syncRoles(['streamer']);
        if ($request->get('stream_admin_id')) {
            UserStreamer::create([
                'streamer_id' => $user->id,
                'streamer_admin_id' => $request->get('stream_admin_id'),
                // 'cost_per_hour' => $request->get('cost_per_hour')
            ]);
        }

        if (optional($user->roles->first())->name == 'streamer') {
            return redirect()->route('dashboard.user.streamers');
        } else {
            return redirect()->route($this->mainUrl . 'index');
        }
    }

    public function unpromote(Request $request)
    {

        $user = User::where('id', $request->get('user_id'))->first();
        $user->syncRoles(['client']);

        if ($user->currentStreamerAdminsPivot) {
            $user->currentStreamerAdminsPivot->update(['ends_at' => today()]);
            $user->update(['gems' => 0]);
        }

        /** @var User $auth */
        $auth = Auth::user();

        if (optional($user->roles->first())->name == 'streamer' || $auth->hasRole('streamer_admin')) {
            return redirect()->route('dashboard.user.streamers');
        } else {
            return redirect()->route($this->mainUrl . 'index');
        }
    }

    public function promoteToStreamerAdmin(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $user->syncRoles('streamer_admin');

        return redirect()->back();
    }

    public function toggleDistributor(Request $request)
    {
        /** @var User $user */
        $user = User::findOrFail($request->user_id);

        if($user->hasRole('distributor')){
            $user->removeRole('distributor');
        }else{
            $user->assignRole('distributor');
        }

        return redirect()->back();
    }

    public function distributorView(Request $request)
    {
        if ($request->ajax()) {

            $subquery = DiamondTransfer::selectRaw('SUM(diamonds)')
                ->whereDate('created_at', today()->toDateString())
                ->where('from_user_id', auth()->id())
                ->whereColumn('to_user_id', 'users.id');

            $query = User::select(['id', 'name', 'diamonds_received' => $subquery])
                ->where('id', '!=', auth()->id());

            $table = DataTables::of($query);

            $table->addColumn('send', 'dashboard.user.partials.send');

            $table->rawColumns(['send']);

            return $table->make(true);
        }

        config()->set('adminlte.menu', []);
        config()->set('adminlte.sidebar_collapse', true);

        return view('dashboard.user.distribute');
    }

    public function sendDiamonds(Request $request)
    {
        DB::transaction(function () use($request) {
            $distributor = User::lockforUpdate()->find(auth()->id());

            $request->validate([
                'user_id' => 'required',
                'diamonds' => 'required|numeric|min:0|max:'.$distributor->diamonds,
            ]);

            $user = User::lockforUpdate()->findOrFail($request->input('user_id'));

            $distributor->diamonds -= $request->diamonds;
            $distributor->save();

            $user->diamonds += $request->diamonds;
            $user->save();

            DiamondTransfer::create([
                'from_user_id' => $distributor->id,
                'to_user_id' => $user->id,
                'diamonds' => $request->diamonds,
            ]);

        }, 3);


        return redirect()->back();
    }
}
