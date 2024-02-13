<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserStreamer;

class UserObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        $user->diamonds_acquired = $user->diamonds ?? 0;

    }

    /**
     * Handle the user "updating" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updating(User $user)
    {
        /**
         * 1- If request from GuessGame will increase and decrease the
         * diamonds and diamonds_aquired to not affect in dimaond_spent
         *
         * 2- If request from UpdateUserDashboard will increase and decrease the diamonds
         * and diamonds_aquired to not affect in dimaond_spent
         *
         * 3- If request from any another api will only increase
         * the diamonds and diamonds_aquired to actually affect in dimaond_spent
         */
        if($user->isDirty('diamonds')){
            if(
                request()->is('api/moon-gift/play')
                || request()->is('api/room/guessgame/accept')
                || request()->is('dashboard/user/*')
                || $user->diamonds > $user->getOriginal('diamonds')
            ){
                $user->diamonds_acquired += ($user->diamonds - $user->getOriginal('diamonds'));
            }
        }

    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        UserStreamer::where('streamer_id', $user->id)->orWhere('streamer_admin_id', $user->id)->get()->each->delete();
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
