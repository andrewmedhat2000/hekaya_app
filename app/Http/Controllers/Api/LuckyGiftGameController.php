<?php

namespace App\Http\Controllers\Api;
use App\Models\Ads;
use App\Models\Game;
use App\Models\Gift;
use App\Models\Room;
use App\Models\User;
use App\Events\GiftSent;
use App\Models\Settings;
use App\Models\RoomsDrivers;
use Illuminate\Http\Request;
use App\Models\CurrencyWorth;
use App\Models\UserLuckyGift;
use App\Models\LuckyGiftRound;
use App\Models\LuckyGiftScore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\SliderResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserLuckyGiftResource;
use App\Jobs\ExpensiveGift as JobsExpensiveGift;

class LuckyGiftGameController extends BaseController
{
    public function luckyGiftGame()
    {
        return view('lucky_game');
    }

    public function luckyGiftInfo()
    {
        $luckyGiftRounds = LuckyGiftRound::get();
        $luckyGifts = Gift::where('game_gift', Gift::GAME_GIFTS['lucky_gift'])->get();
        $user = auth()->user();

        echo '{
            "data":[
                {
                "user":{
                  "id": '. $user->id .',
                  "name": "'. $user->name .'",
                  "current_profile_image_url": "'.$user->ProfilePicUrl.'",
                  "wallet":{
                     "diamonds":'. $user->diamonds .'
                    }
                },
                "pricing":{
        ';
                foreach ($luckyGiftRounds as $key => $luckyGiftRound) {
                    echo'"'. $luckyGiftRound->round_number.'": "'. $luckyGiftRound->round_value .'"';
                    if ($key + 1 != count($luckyGiftRounds)) {
                        echo ',';
                    }
                }
        echo '  }
            ,
            "gifts":
                [
                    ';
                    foreach ($luckyGifts as $key => $luckyGift) {
                        echo'{
                        "id": '. $luckyGift->id .',
                        "name": "'. $luckyGift->name .'",
                        "image_path": "'. $luckyGift->image_url .'"
                        }';
                        if ($key + 1 != count($luckyGifts)) {
                            echo ',';
                        }
                    }
        echo '
                ]
        }],
            "success":true
        }';
    }

    public function luckyGiftPlay(Request $request)
    {
        $user = auth()->user();
        $gifts = Gift::where('game_gift', Gift::GAME_GIFTS['lucky_gift'])->orderBy('price', 'DESC')->get();
        $luckyGift = LuckyGiftRound::where('round_number', $request->input('rounds'))->first();
        $user_score = LuckyGiftScore::where('user_id', $user->id)->first();
        $play_first_time = 0;
        if ($user_score) {
            $play_first_time = 1;
            $user_score->update(['round_value' => $user_score->round_value + $luckyGift->round_value]);
        }

        $diamonds_won = 0;
        $winnig_gifts = [];

        echo '{
            "data":{
                "gifts":[';
        for ($i = 0; $i < $luckyGift->round_number; $i++) {
            $rand_num = rand($luckyGift->minimum_win, $luckyGift->maximum_win);
            foreach ($gifts as $gift) {
                if ($rand_num >= $gift->price) {
                    UserLuckyGift::create([
                        "user_id" => $user->id,
                        "gift_id" => $gift->id
                    ]);
                    $diamonds_won = $diamonds_won + $gift->price;
                    array_push($winnig_gifts, $gift);                    
                    $rand_num = 0;
                }
            }
        }

        $winnig_gifts = collect($winnig_gifts)->groupBy('id')->map(function($group) {
            $first = $group->first();
            $first['quantity'] = $group->count();
            return $first;
        });

        $counter = 0;
        foreach ($winnig_gifts as $item) {
            echo '{
                "id":'.$item->id.',
                "image_path":"'.$item->image_url.'",
                "name":"'.$item->name.'",
                "quantity": '.$item->quantity.'
            }';
            if( $counter != count( $winnig_gifts ) - 1) {
                echo ',';
            }
            $counter = $counter + 1;
        }

        echo '
                ],
                "user":{
                    "name":"'.$user->name.'",
                    "current_profile_image_url":"'.$user->ProfilePicUrl.'",
                    "wallet":{
                        "diamonds":'. $user->diamonds .'
                    }
                },
                "success":true
            }

        }';

        if ($play_first_time == 0) {
            LuckyGiftScore::create([
                'user_id'        => $user->id,
                'points'         => $diamonds_won,
                'round_value'    => $luckyGift->round_value,
                'rounds_number'  => $request->rounds
            ]);
        } else {
            $user_score->increment('points', $diamonds_won);
            $user_score->increment('rounds_number', $request->rounds);
        }
    }

    public function news()
    {
        //
    }


    public function luckyGiftScores()
    {
        $luckyGiftScores = LuckyGiftScore::with('user')->orderBy('points', 'desc')->get();
        echo '{
            "data":[
        ';
        foreach ($luckyGiftScores as $key => $luckyGiftScore) {
            echo '{
                "id":'.$luckyGiftScore->user->id.',
                "name":"'.$luckyGiftScore->user->name.'",
                "current_profile_image_url":"'.$luckyGiftScore->user->ProfilePicUrl.'",
                "decoration_image_path":"https:\/\/dzbu0otr6r2c9.cloudfront.net\/decorations\/2966ac8d1a3cfcc840a2a08b807c6ae2.jpg",
                "points": '.$luckyGiftScore->points.'
            }';
            if ($key + 1 != count($luckyGiftScores)) {
                echo ',';
            }
        }
        echo '
            ]
        }';
    }

    public function sendLuckyGift(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'room_id'         => 'required|exists:rooms,id',
                'gift_id'         => 'required|exists:lucky_gift_user,gift_id,user_id,'.auth()->id(),
                'to_user_id'      => 'required|exists:users,id',
            ]);

            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }

            $gift = Gift::where('game_gift', Gift::GAME_GIFTS['lucky_gift'])->where('id', $request->get('gift_id'))->first();
            $user = auth()->user();
            $to_user = User::where('id', $request->get('to_user_id'))->first();

            DB::transaction(function() use($request, $user, $gift, $to_user){

                $ratio = CurrencyWorth::firstOrCreate(['diamond' => 1, 'gem' => 1, 'charisma' => 1,  'contribution' => 1]);

                $contribution_factor = $ratio->contribution / $ratio->diamond;
                $gem_factor = $ratio->gem / $ratio->diamond;
                $charisma_factor = $ratio->charisma / $ratio->diamond;

                UserLuckyGift::where('user_id', $user->id)->where('gift_id', $gift->id)->orderBy('id', 'asc')->first()->delete();
                $user->increment('contribution', $gift->price * $contribution_factor);

                $gift->getUsersSend()->attach([
                    $user->id => [
                        'to_user_id' => $request->get('to_user_id'),
                        'room_id' => $request->input('room_id'),
                        'price' => $gift->price,
                    ],
                ]);

                $charisma = $gift->price * $charisma_factor;
                $to_user->increment('gems', $gift->price);
                $to_user->increment('charisma', $charisma);
                RoomsDrivers::where('driver_id', $to_user->id)->latest()->increment('charisma', $charisma);

                $room = Room::find($request->room_id);

                if ($to_user->hasRole('streamer')) {
                    $user_streamer = $to_user->currentStreamerAdminsPivot;

                    if($user_streamer)
                        $user_streamer->streamerInfo()->where('mins', 0)->latest()->first()
                        ->increment('gold', $gift->price);
                }

                if ($room->host_id != $to_user->id && $room->host->hasRole('streamer')) {
                    $user_streamer = $room->host->currentStreamerAdminsPivot;

                    if ($user_streamer) {
                        $bonus = $gift->price * config('streamers.host_bonus_percent', 0);
                        $info = $user_streamer->streamerInfo()->where('mins', 0)->latest()->first();
                        $info ? $info->increment('gold', $bonus) : null;
                    }
                }

               broadcast(new GiftSent($room, $gift, $user, $to_user));

                if($gift->price >= Settings::getValue('view_banner_price')) {
                    JobsExpensiveGift::dispatch($gift, $user, $to_user);
                }

            }, 5);

            return response()->json([
                'status_code' => 200,
                'message'     => 'success',
                'diamonds'    => $user->diamonds,
                'user'        => $user,
                'to_user'     => $to_user,
                'gift'        => $gift,
            ]);

        } catch (\Exception $ex) {
            // MYSQL out of range exception on diamonds column (negative value)
            if($ex instanceof \PDOException && $ex->getCode() == 22003){
                return response()->json([
                    'status_code' => 409,
                    'message' => 'Insufficient Diamonds Credit',
                ]);
            }
            return $this->internalServerError($ex);
        }
    }

    public function userLuckyGift()
    {
        $user = auth()->user();
        return UserLuckyGiftResource::collection($user->luckyGifts);
    }

    public function gamesSlider()
    {

        $games_slider = Game::get();

        $games_slider->transform(function($game) {
            $link = $game->getOriginal('link');
            $is_constant = in_array($link, Game::ROUTES);

            return $is_constant
                ? $game->setAttribute('link', URL::signedRoute($link, ['user_id' => auth()->id()]))
                : $game;
        });

        return response()->json([
            'status_code' => 200,
            'data' => $games_slider,
        ], 200);
    }

    public function mainSlider()
    {
        

        $games_slider = Game::get();
        // $token = DB::table('oauth_access_tokens')->where('user_id','=','75192118')->get();
        // dd(sizeof($games_slider));
        // dd($games_slider[1]->getOriginal('link')."?uid=75192118&token=".$token[0]->id);
       
        $games_slider =  $games_slider->transform(function($game) {

            $token = DB::table('oauth_access_tokens')->where('user_id','=','75192118')->get();

            //$link = $game->getOriginal('link')."?uid=75192118&token=".$token[0]->id;

            $link = $game->getOriginal('link');
            //$link = $game->getOriginal('link')."?uid=".auth()->id()."&token=".$token->id;
           // $game->setAttribute('link',$link);

        //    dd($link);
            $is_constant = in_array($link, Game::ROUTES);
            return $is_constant
                ? $game->setAttribute('link', URL::signedRoute($link, []))//'uid' => '75192118'//auth()->id()// URL::signedRoute($link, ['user_id' => '75192118'])
                : $game;

        })->toArray();
        //dd($games_slider);
        // for($i=0;$i<sizeof($games_slider);$i++)
        // {
        //     // $arr=array();
        //     // $arr['link']=$games_slider[$i]->getOriginal('link');
        //     // dd($games_slider[$i]);
        //     // $games_slider[$i]=$games_slider[$i]->getOriginal('link');
        //   $token = DB::table('oauth_access_tokens')->where('user_id','=','75192118')->get();

        //     $games_slider[$i]= array([
        //         "title"=> $games_slider[$i]->getOriginal('title'),
        //         "link"=>$games_slider[$i]->getOriginal('link')."?uid=75192118&token=".$token[0]->id,
        //         "image"=> $games_slider[$i]->getOriginal('image'),
        //         "created_at"=> $games_slider[$i]->getOriginal('created_at')
        //     ]);
        // }

      
        $ads = Ads::orderBy('created_at','desc')->get()->toArray();
       //dd($ads);
        $data = array_merge($games_slider, $ads);
        return SliderResource::collection($data);

      

    }
}
