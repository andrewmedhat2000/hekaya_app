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
use App\Events\EmojiSent;


class EmojiController extends BaseController
{

    public function getEmojis(Request $request)
    {
        try {

            $user = Auth::user();
            $emojis = Emoji::latest()->get();
            return $this->apiResponse($emojis);
        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function sendEmoji(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'room_id'         => 'required|exists:rooms,id',
                'emoji_id'         => 'required|exists:emojis,id',
            ]);
            if ($validator->fails() != null) {
                return $this->apiErrorResponse($validator->errors()->first());
            }

            $user = Auth::user();
            if (!$user) {
                return $this->apiErrorResponse('Wrong credentials', 422);
            }

            $emoji = Emoji::where('id', $request->get('emoji_id'))->first();
            if (!$emoji) {
                $this->apiErrorResponse('Emoji Not Found', 404);
            }


            $room_id = $request->get('room_id');
            $user_id = $user->id;
            $emoji_sends = EmojiSends::create([
                'emoji_id'         => $request->get('emoji_id'),
                'room_id'         => $request->get('room_id'),
                'user_id'         => $user->id,
            ]);

            broadcast(new EmojiSent($emoji_sends->emoji, $user_id, $room_id));

            return $this->apiResponse([
                'emoji' =>$emoji_sends->emoji()->latest()->get(),
                'user_id' =>$emoji_sends->user->id,
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }


}
