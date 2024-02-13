<?php

namespace App\Http\Controllers\Api\Store;

use DB;
use App\Models\User;

use App\Models\GiftSends;
use Illuminate\Http\Request;
use App\Models\CurrencyWorth;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\BaseController;

use App\Models\UserStreamer;


class TopUserController extends BaseController
{
    public function topUsers(Request $request)
    {
        try{
            $users = Cache::remember('topUsers', 5, function() use($request) {
                $factors = [
                    'sender' => CurrencyWorth::getContributionRatio(),
                    'receiver' => CurrencyWorth::getCharismaRatio(),
                ];

                foreach(['sender', 'receiver'] as $metric){
                    foreach(['daily', 'weekly', 'monthly'] as $timeframe){
                        $users[$metric][$timeframe] = GiftSends::query()
                            ->when($request->filled('room_id'), fn($q) => $q->where('room_id', $request->room_id))
                            ->{$timeframe}()->{$metric}()->limit(10)->get()
                            ->transform(function($user, $key) use($factors, $metric) {
                                if(!$user->{$metric}) return false;
                                return [
                                    'user' => new UserResource($user->{$metric}),
                                    'rank' => $key + 1,
                                    'score' => $user->price * $factors[$metric],
                                ];
                            })->filter()->values();
                    }
                }

                return $users;
            });

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'type' => 'query',
                'users' => $users,
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function topUsers2(Request $request)
    {
        try{

            $totalcharisma=0;
            $totalcontr=0;
            $from = date('Y-m-d h:m:i', strtotime('-7 days') );
            $to = date('Y-m-d h:m:i');

            $users = GiftSends::where('to_user_id','=',$request->user_id)->whereBetween('updated_at', [$from, $to])->get();
            for($i=0;$i<sizeof($users);$i++)
            {
                

                $totalcharisma=$totalcharisma+$users[$i]['price'];
            }
            $users2 = GiftSends::where('from_user_id','=',$request->user_id)->whereBetween('updated_at', [$from, $to])->get();
                for($i=0;$i<sizeof($users2);$i++)
                {
                    $totalcontr=$totalcontr+$users2[$i]['price'];
                }

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'charisma' => $totalcharisma,
                'contribution' => $totalcontr,
                
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function getGiftsUser(Request $request)
    {
        try{

           $gifts_user=DB::table('gift_users')
            ->select('gifts.id as gift_id', 'gifts.name as gift_name', 'gifts.image as gift_image', DB::raw("COUNT(*) as gift_count"))
            ->join('gifts','gift_users.gift_id','=','gifts.id')
            ->where('gift_users.to_user_id','=',$request->user_id)
            ->groupBy('gift_users.gift_id')
            ->get();

            // dd($gifts_user);

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'gifts_user' => $gifts_user
                
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function getstreameradmins(Request $request)
    {
        try{

        //    $streamer_admins = User::whereHas('roles', function ($query) {
        //     $query->where('name', 'streamer_admin');
        // })->get()->pluck('name', 'id')->toArray();

            // dd($gifts_user);

            $streamer_admins = User::whereHas('roles', function ($query) {
                $query->where('name', 'streamer_admin');
            })->get();
            // ->pluck('name', 'id')->toArray()
            // dd($streamer_admins);
            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'streamer_admins' => $streamer_admins
                
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function promoteuser(Request $request)
    { 
            try{

                $userstreame = UserStreamer::where('streamer_id', $request->user_id)->first();
                //dd( ($userstreame->ends_at < date("Y-m-d")));
                if(isset($userstreame->ends_at)  && ($userstreame->ends_at > date("Y-m-d")) ){

                    $stream_admin = User::where('id', $userstreame->streamer_admin_id)->first();

                    return response()->json([
                        'status_code' => 200,
                        'message' => 'أنت بالفعل مشترك مع وكالة '.$stream_admin->name.' ولا يمكنك الاشتراك مرة اخري ',
                    
                        
                    ]);
                }
                else
                {
                    $user = User::where('id', $request->user_id)->first();
                    $stream_admin = User::where('id', $request->stream_admin_id)->first();

                    $user->syncRoles(['streamer']);
                    if ($request->stream_admin_id) {
                        UserStreamer::create([
                            'streamer_id' => $user->id,
                            'streamer_admin_id' => $request->stream_admin_id,
                            // 'cost_per_hour' => $request->get('cost_per_hour')
                        ]);
                    }

                    return response()->json([
                        'status_code' => 200,
                        'message' => 'تم التسجيل بنجاح مع وكالة '.$stream_admin->name.'',
                    
                        
                    ]);

                    
                }


               
            } catch (\Exception $ex) {
                return $this->internalServerError($ex);
            }

    }


}
