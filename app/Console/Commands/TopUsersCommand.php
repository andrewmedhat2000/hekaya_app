<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CurrencyWorth;
use App\Models\Frame;
use App\Models\FrameUser;
use App\Models\Gift;
use App\Models\GiftSends;
use App\Models\GiftUserHistory;
use App\Models\Room;
use App\Models\RoomsDrivers;
use Carbon\Carbon;
use App\Http\Resources\UserResource;
use App\Http\Resources\RoomResource;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class TopUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:topUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Leaderboard Top Users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // return info('Get Leaderboard Top Users .. Called Every Minute');

        try{

            $sender_factor = CurrencyWorth::getContributionRatio();
            $receiver_factor = CurrencyWorth::getCharismaRatio();
            $room_factor = 1;

            foreach(['sender', 'receiver', 'room'] as $metric){
                foreach(['daily', 'weekly', 'monthly'] as $timeframe){
                    $users[$metric][$timeframe] = GiftSends::query()
                        ->{$timeframe}()->{$metric}()->limit(10)->get()
                        ->transform(function($user, $key) use($room_factor, $sender_factor, $receiver_factor, $metric) {
                            return [
                                'user' => $metric == 'room' ? new RoomResource($user->{$metric}) : new UserResource($user->{$metric}),
                                'rank' => $key + 1,
                                'score' => $user->price * ${$metric .'_factor'},
                            ];
                        });
                }
            }

            return Cache::put('leaderboardTopUsers', $users);


        } catch (\Exception $ex) {

            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 500,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 500);

        }


    }
}
