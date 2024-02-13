<?php

namespace App\Http\Controllers\Api\Store;

use Carbon\Carbon;
use App\Models\Gift;
use App\Models\Room;
use App\Models\User;
use App\Models\Badge;
use App\Models\Frame;
use App\Events\GiftSent;
use App\Models\FrameUser;
use App\Models\GiftSends;
use App\Models\LoginSign;
use App\Models\RoomsDrivers;
use Illuminate\Http\Request;
use App\Jobs\FCMNotification;

use App\Models\CurrencyWorth;
use App\Models\GiftUserHistory;
use App\Models\UserStreamerInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Http\Resources\UserResource;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

use App\Models\Emoji;
use App\Models\EmojiSends;
use App\Http\Controllers\Api\BaseController;

class BadgeController extends BaseController
{

    public function getBadges(Request $request)
    {
        try {

            $user = Auth::user();
            $badges = Badge::all();
            return $this->apiResponseCustom('badges', $badges);
        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }


    public function buyBadge(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'badge_id'         => 'required|exists:badges,id',
            ]);
            return $this->apiErrorResponse($validator->errors()->first());


            $user = Auth::user();
            $badge = Badge::where('id', $request->get('badge_id'))->first();
            if (!$badge) return $this->apiErrorResponse('Badge Not Found');


            $badges = $user->badgesHistory()->wherePivot('status', 0)->get();
            if ($badges->contains($badge)) return $this->apiErrorResponse('You already bought this badge', 409);


            if ($user->diamonds >= $badge->price) {
                DB::beginTransaction();

                $badge_bought = $user->badgesHistory()->attach($badge);
                $user->diamonds -= $badge->price;

                $ratio = CurrencyWorth::first();
                if(!$ratio) $ratio = CurrencyWorth::create(['diamond' => 1, 'gem' => 1, 'charisma' => 1,  'contribution' => 1]);
                $charisma_factor = $ratio->charisma / $ratio->diamond;
                $user->charisma += $badge->price * $charisma_factor;

                $user->save();

                DB::commit();
                return $this->apiResponse([]);
            } else {
                return $this->apiErrorResponse('Insufficient Diamonds Credit', 409);
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->internalServerError($ex);
        }
    }

    public function getMyBadges(Request $request)
    {
        try {

            $user = Auth::user();
            $badges = $user->badgesHistory()->wherePivot('status', 0)->get();
            return $this->apiResponseCustom('badges', $badges);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }





}
