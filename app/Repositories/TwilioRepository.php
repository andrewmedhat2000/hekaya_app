<?php

namespace App\Repositories;

use Twilio\Rest\Client;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class TwilioRepository implements TwilioRepositoryInterface
{

    protected $sid;
    protected $token;
    protected $service_sid;
    protected $key;
    protected $secret;
    protected $client;
    
    protected $service_instance;

    public function __construct()
    {
        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->service_sid = config('services.twilio.service_sid');
        $this->key = config('services.twilio.key');
        $this->secret = config('services.twilio.secret');

        $this->client = new Client($this->sid, $this->token);
        $this->service_instance =  $this->client->chat->v2->services($this->service_sid);

    }

    /**
     * Get all Channels created
     *
     * @param int
     */
    public function getChannels()
    {

        $rooms = [];
        try {
            $allRooms = $this->service_instance->channels->read();

            $rooms = array_map(function ($room) {
                return [
                    'sid' => $room->sid,
                    'uniqueName' => $room->uniqueName,
                    'friendlyName' => $room->friendlyName,
                    'type' => $room->type,
                    'attributes' => json_decode($room->attributes),
                    'createdBy' => $room->createdBy,
                    'membersCount' => $room->membersCount,
                    'messagesCount' => $room->messagesCount,
                    'url' => $room->url,
                ];
            }, $allRooms);

            return $rooms;
        } catch (Exception $ex) {
            Log::info('twilio exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return [];
        }
    }

    /**
     * Show all info of the channel.
     *
     * @return mixed
     */
    public function showChannels($channel_sid)
    {

        $channel_resource = null;
        $rooms = [];
        try {

            //$client = new Client($this->sid, $this->token);

            $get_channel = $this->service_instance
                ->channels($channel_sid)->fetch();

            $channel_resource['sid'] = $get_channel->sid;
            $channel_resource['accountSid'] = $get_channel->accountSid;
            $channel_resource['serviceSid'] = $get_channel->serviceSid;
            $channel_resource['friendlyName'] = $get_channel->friendlyName;
            $channel_resource['uniqueName'] = $get_channel->uniqueName;
            $channel_resource['attributes'] = json_decode($get_channel->attributes);
            $channel_resource['type'] = $get_channel->type;
            $channel_resource['dateCreated'] = $get_channel->dateCreated;
            $channel_resource['dateUpdated'] = $get_channel->dateUpdated;
            $channel_resource['createdBy'] = $get_channel->createdBy;
            $channel_resource['membersCount'] = $get_channel->membersCount;
            $channel_resource['messagesCount'] = $get_channel->messagesCount;
            $channel_resource['url'] = $get_channel->url;
            $channel_resource['links'] = $get_channel->links;

            $channel_resource['_d0'] = optional($channel_resource['attributes'])->_d0;
            $channel_resource['_d1'] = optional($channel_resource['attributes'])->_d1;
            $channel_resource['_d2'] = optional($channel_resource['attributes'])->_d2;
            $channel_resource['_d3'] = optional($channel_resource['attributes'])->_d3;
            $channel_resource['_d4'] = optional($channel_resource['attributes'])->_d4;
            $channel_resource['_d5'] = optional($channel_resource['attributes'])->_d5;
            $channel_resource['_d6'] = optional($channel_resource['attributes'])->_d6;
            $channel_resource['_d7'] = optional($channel_resource['attributes'])->_d07;
            
            return($channel_resource);
        } catch (Exception $ex) {
            Log::info('twilio exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return [];
        }
    }

    /**
     * Get Message for specific channel.
     *
     * @param int
     */
    public function getMessages($channel_sid)
    {

        $channel_resource = null;
        $rooms = [];
        try {

           // $client = new Client($this->sid, $this->token);

            $message = $this->service_instance
                ->channels($channel_sid)
                ->messages
                ->read();

            $messages = [];

            foreach ($message as $record) {
                $value = array(
                    'from' => $record->from,
                    'message' => $record->body,
                    'message_id' => $record->sid,
                    'attributes' => json_decode($record->attributes, true),
                    'media' => json_decode(json_encode($record->media)),
                    'to_channel_id' => $record->to,
                    'dateCreated' => $record->dateCreated->format('Y-m-d H:i:s'),
                );

                array_push($messages, $value);
            }

            return $messages;
        } catch (Exception $ex) {

            Log::info('twilio exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return [];
        }
    }

    /**
     * Get Members for specific channel.
     *
     * @param int
     * @param array
     */
    public function getMembers($channel_sid)
    {
        $rooms = [];
        try {

            $members_twilio = $this->client->chat->v2->services($this->service_sid)
                ->channels($channel_sid)
                ->members->read();

            $members = [];

            foreach ($members_twilio as $record) {
                $value = array(
                    'identity' => $record->identity,
                );

                array_push($members, $value);
            }

            return $members;
        } catch (Exception $ex) {

            Log::info('twilio exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return [];
        }
    }


    /**
     * Get voice media for specific message.
     *
     * @param int
     * @param array
     */
    public function getMedia($media_sid){
        try {
            //

            $url = 'https://mcs.us1.twilio.com/v1/Services/'.$this->service_sid.'/Media/'.$media_sid;
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $this->sid.':'.$this->token,
                CURLINFO_HEADER_OUT => true
            ));
            
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                return ['error'=>1,'error_type' => $err];
            } else {

                $url = optional(json_decode($response)->links)->content_direct_temporary;
                
                return ['error'=>0,'url' => $url];

            }
        }
        catch(Exception $ex){
            Log::info('twilio exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return [];
        }
    }
}
