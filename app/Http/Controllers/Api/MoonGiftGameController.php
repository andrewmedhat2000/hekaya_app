<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\MoonGift;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Models\MoonGiftScore;
use App\Events\MoonGiftEvent;
use App\Http\Controllers\Controller;

class MoonGiftGameController extends Controller
{
    
    public function moonGiftScores()
    {
        
        $moonGiftScores = MoonGiftScore::with('user')->orderBy('diamond_win', 'desc')->get();
        echo '
            {
                "data":[
            ';
        foreach($moonGiftScores as $key => $moonGiftScore) {
            echo '
                {
                    "id": '.$moonGiftScore->user->id.',
                    "name": "'.$moonGiftScore->user->name.'",
                    "current_profile_image_url": "'.$moonGiftScore->user->ProfilePicUrl.'",
                    "score": '.$moonGiftScore->diamond_win.'
            }';
            if ($key + 1 != count($moonGiftScores)) {
                echo ',';
            }        
        }
        echo '   
                ],
                "success":true
            }
        ';
    }

    public function moonGiftGame()
    {
        $moonGifts = MoonGift::get();
        $user = auth()->user();
        echo '{
            "data":{
               "player":{
                  "id": '. $user->id .',
                  "name": "'. $user->name .'",
                  "current_profile_image_url": "'.$user->ProfilePicUrl.'",
                  "wallet":{
                     "diamonds":'. $user->diamonds .'
                  }
               },
               "game":{
                    "pricing":{
        ';        
        foreach ($moonGifts as $key => $moonGift) {
            echo'"'. $moonGift->round_number.'": "'. $moonGift->round_value .'"';
            if ($key + 1 != count($moonGifts)) {
                echo ',';
            } 
        }
        echo '      }
                }
            },
            "success":true
        }';      
    }

    public function moonGiftFullGame()
    {
        return view('moon_game');
    }

    public function moonGiftPlay(Request $request)
    {
        $settings = new Settings();
        $moonGiftScore = new MoonGiftScore();

        $user = auth()->user()->load('moonGiftScores');
        $moon_gift = MoonGift::where('round_number', $request->round)->first();
        
        for ($i = 0; $i < $moon_gift->round_number; $i++) {
            $random_diamonds_won = rand($moon_gift->minimum_win, $moon_gift->maximum_win);
            $winnig_diamonds []= [
                "count" => 1,
                "type"  => "diamond",
                "value" => $random_diamonds_won
            ];
        }

        // old edit showing special prize with turns number
        // $rounds_number = $moonGiftScore->sum('rounds_number');
        // if (
        //     $turns_number_to_show_prize >= $rounds_number + 1
        //     && $turns_number_to_show_prize <= $rounds_number + $request->round
        // ) {
        // $settings->where('name', 'turns_number_to_show_prize_moon_gift')->update(['value' => $rounds_number + 10]);
        // }

        // special prize set in settings in specific round
        $prize_moon_gift = $settings->getValue('turns_number_to_show_prize_moon_gift');
        $prize_value_moon_gift = $settings->getValue('prize_value_moon_gift');
        $app_profit = $settings->getValue('app_profit');
        if ($app_profit + $prize_moon_gift <=  $moonGiftScore->sum('diamond_lost') - $moonGiftScore->sum('diamond_win')) {
            $prize_value_moon_gift = rand($prize_value_moon_gift, $prize_value_moon_gift/2);
            $settings->where('name', 'app_profit')->update(['value' => ($prize_moon_gift - $prize_value_moon_gift) + $app_profit]);
            array_push($winnig_diamonds, [
                "count" => 1,
                "type"  => "diamond",
                "value" => $prize_value_moon_gift
            ]);
            $diamonds_won = (integer) array_sum(array_column($winnig_diamonds, 'value'));
            MoonGiftEvent::broadcast($diamonds_won, $user);
        }

        // update user diamonds
        $diamonds_won = (integer) array_sum(array_column($winnig_diamonds, 'value'));
        $user->update(['diamonds' => ($user->diamonds + $diamonds_won) - $moon_gift->round_value]);

        $user_score = $user->moonGiftScores->last();
        if (
            $user_score 
            && Carbon::parse($user_score->created_at)->format('Y-m-d') == Carbon::parse(now())->format('Y-m-d') 
        ) {
            $user_score->update([
                'diamond_win'   => $user_score->diamond_win + $diamonds_won,
                'diamond_lost'  => $user_score->diamond_lost + $moon_gift->round_value,
                'rounds_number' => $user_score->rounds_number + $request->round,
            ]);
        } else {
            $moonGiftScore->create([
                'user_id'       => $user->id,
                'diamond_win'   => $diamonds_won,
                'diamond_lost'  => $moon_gift->round_value,
                'rounds_number' => $request->round
            ]);
        }

        return response()->json([
            "picked_gifts"  => $winnig_diamonds,
            "diamonds"      => $user->diamonds,
            "success"       => true 
        ]);

        // unused code for group gifts if there's duplicate gift
        // $winnig_diamonds = collect($winnig_diamonds)->groupBy('value')->map(function($group) {
        //     $first = $group->first();
        //     $first['count'] = $group->count();
        //     return $first;
        // });
    }
}
