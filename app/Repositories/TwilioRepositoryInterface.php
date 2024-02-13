<?php
namespace App\Repositories;

interface TwilioRepositoryInterface
{
    /**
     * Get all Channels created
     *
     * @param int
     */
    public function getChannels();

    /**
     * Show all info of the channel.
     *
     * @return mixed
     */
    public function showChannels($channel_sid);

    /**
     * Get Message for specific channel.
     *
     * @param int
     */
    public function getMessages($channel_sid);

    /**
     * Get Members for specific channel.
     *
     * @param int
     * @param array
     */
    public function getMembers($channel_sid);

    
    /**
     * Get voice media for specific message.
     *
     * @param int
     * @param array
     */
    public function getMedia($media_sid);

}