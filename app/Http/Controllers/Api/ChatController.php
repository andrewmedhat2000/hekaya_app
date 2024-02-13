<?php

namespace App\Http\Controllers\Api;

use App\Events\NewMessage;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\GenericResourceCollection;
use App\Http\Resources\MessageResource;
use App\Jobs\FCMNotification;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;

class ChatController extends BaseController
{

    public function conversations(Request $request)
    {
        try {
              $conversations = Conversation::whereHas('users', function($q) {
                $q->where('users.id', Auth::id());//Auth::id()
              })->when($request->filled('search'), function($q) use($request) {
                $q->whereHas('users', function($query) use($request) {
                    $query->where('users.id', '!=', Auth::id())->where('name', 'like', "%{$request->search}%");
                });
            })
            ->orderBy('updated_at', 'desc')
            ->with('users', 'lastMessage')
            ->get()->each->withUser();


            $blocked_users = auth()->user()->friendBlocks->merge(auth()->user()->usersBlockedMe)->pluck('id')->toArray();
            $conversations = $conversations->filter(function($conversation) use($blocked_users){
                return !in_array(optional($conversation->user)->id, $blocked_users);
            });


            return response()->json([
                'status_code' => 200,
                'data' => ConversationResource::collection($conversations),
            ]);

        } catch(\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function destroy($id, Request $request)
    {
       // dd('adas');
        try {
            $Conversation = Conversation::where('id', $request->id)->first();
            //$conversation = Conversation::find($request->conversation_id)
            $Conversation->delete();
            return response()->json([
                'status_code' => 200,
                'msg' => "Conversation deleted successfully",
                ]);
        } catch(\Exception $ex) {
            return $this->internalServerError($ex);
        }

    }

    public function messages(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'conversation_id'   => 'required_without:user_id|exists:conversation_user,conversation_id,user_id,'.Auth::id(),
                'user_id' => 'required_without:conversation_id|exists:users,id',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            if($request->has('conversation_id')) {
                $conversation = Conversation::find($request->conversation_id);
            } else {
                $conversation = Conversation::whereHas('users', function($q) {
                    $q->where('users.id', Auth::id());
                })->whereHas('users', function($q) use($request) {
                    $q->where('users.id', $request->user_id);
                })->firstOr(function() use($request) {
                    $conversation = Conversation::create();
                    $conversation->users()->attach([Auth::id(), $request->user_id]);
                    return $conversation;
                });
            }

            $messages = $conversation->messages()->latest()->paginate(20);

            return response()->json([
                'status_code' => 200,
                'conversation_id' => $conversation->id,
                'data' => new GenericResourceCollection($messages),
            ]);

        } catch(\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function send(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'conversation_id'   => 'required_without:user_id|exists:conversation_user,conversation_id,user_id,'.Auth::id(),
                'user_id' => 'required_without:conversation_id|exists:users,id',
                'text' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            if($request->has('conversation_id')) {
                $conversation = Conversation::find($request->conversation_id);
            } else {
                $conversation = Conversation::whereHas('users', function($q) {
                    $q->where('users.id', Auth::id());
                })->whereHas('users', function($q) use($request) {
                    $q->where('users.id', $request->user_id);
                })->first();
                if(!$conversation){
                    return $this->apiErrorResponse('The selected user id is invalid.', 409);
                }
            }

            $message = $conversation->messages()->create(['user_id' => Auth::id(), 'text' => $request->input('text')]);

            $conversation->touch();

            broadcast(new NewMessage($message));

            $pusher = new Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                ['cluster' => config('broadcasting.connections.pusher.options.cluster')]
            );

            $active_users_count = $pusher->get_channel_info(
                "conversation.{$conversation->id}", ['info' => 'subscription_count']
            )->subscription_count;

            if($active_users_count == 1){
                $sender = Auth::user();
                FCMNotification::dispatch(
                    $conversation->users->where('id', '!=', $sender->id)->pluck('id'),
                    $sender->user_name,
                    $message->text,
                    [
                        'type' => 'conversation',
                        'sender_id' => $sender->id,
                        'sender_name' => $sender->user_name,
                        'photo' => $sender->profile_pic_url,
                        'message' => $message->text,
                        'conversation_id' => $conversation->id
                    ],
                    'CHAT'
                );
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
            ]);

        } catch(\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }


}
