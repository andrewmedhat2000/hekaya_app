<?php

namespace App\Models;

use App\Traits\Streamer;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    use HasApiTokens;

    use Streamer;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $fillable = [
        'user_name', 'name', 'email', 'password', 'phone', 'profile_pic', 'social_id', 'social_type', 'email_verified_at', 'active',
        'age', 'gender', 'golds', 'diamonds', 'gems', 'charisma', 'contribution', 'diamonds_acquired',
        'description', 'job', 'followers', 'following', 'friends_count', 'payment_pin', 'account_type',
        'profit', 'active_end_time', 'bio', 'fcm_key', 'country_flag', 'mobile_uuid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'social_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active_end_time' => 'datetime',
    ];

    protected $with = [
        'framesHistory',
        'loginSignsHistory',
    ];

    protected $appends = [
        'profile_pic_url',
        'level',
    ];

    public $upload_distination = '/uploads/images/users/';

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function setPaymentPinAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['payment_pin'] = bcrypt($value);
        }
    }

    public function setProfilePicAttribute($value)
    {
        if (!$value instanceof UploadedFile) {
            $this->attributes['profile_pic'] = $value;
            return;
        }
        $image_name = str_random(60);
        $image_name = $image_name . '.' . $value->getClientOriginalExtension(); // add the extention
        $value->move(public_path($this->upload_distination), $image_name);
        $this->attributes['profile_pic'] = $image_name;
    }
    public function getProfilePicUrlAttribute()
    {
        if (!$this->profile_pic) {
            return asset('/panel-assets/images/profile-picutre/01_img.png');
        }
        return strpos($this->profile_pic, 'http') !== false ? $this->profile_pic : asset($this->upload_distination . $this->profile_pic);
    }

    public function getDiamondsSpentAttribute()
    {
        return $this->diamonds_acquired - $this->diamonds;
    }

    public function getLevelAttribute()
    {
        $levels = collect(config('levels.diamonds_cumulative'));
        $target = $levels->search(fn($l) => $l > $this->diamonds_spent);

        if($target) {
            return $target - 1;
        } else {
            $last_key = $levels->keys()->last();
            $last_value = $levels->last();

            for($i = $last_key, $k = $last_value; $k < $this->diamonds_spent; $i++) {
                $k *= 1.02;
            }

            return $i - 1;
        }
    }

    public function getLevelProgress()
    {
        $levels = collect(config('levels.diamonds_cumulative'));
        $last_level = $levels->keys()->last();
        $current_level = $this->level;

        $previous = $current_level <= $last_level
            ? $levels[$current_level]
            : $last_level * (1.02**($current_level - $last_level));

        $next = $current_level < $last_level
            ? $levels[$current_level + 1]
            : $last_level * (1.02**($current_level + 1 - $last_level));

        $current = $this->diamonds_spent;

        return [
            'next' => $next - $previous,
            'current' => $current - $previous
        ];
    }

    public function getDiamindsByMonth()
    {
        $total_amount = $this->giftsReceived()->wherePivot('created_at', '>=', Carbon::now()->firstOfMonth())->get()->map(function ($item, $key) {
            return $item['price'];
        });

        $price = 0;
        foreach ($total_amount as $amount) {
            $price += $amount;
        }
        return $price;
    }

    public function myFollowers()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'user_id', 'follower_id')->withTimestamps()->withPivot('status');
    }

    public function amFollow()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'follower_id', 'user_id')->withTimestamps()->withPivot('status');
    }

    public function myFriends()
    {
        return $this->belongsToMany(User::class, 'user_friends', 'user_id', 'friend_id')->withTimestamps()->withPivot('status');
    }

    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'user_friends', 'friend_id', 'user_id');
    }

    public function getFriendsAttribute()
    {
        if (!array_key_exists('friends', $this->relations)) {
            $this->loadFriends();
        }

        return $this->getRelation('friends');
    }

    protected function loadFriends()
    {
        if (!array_key_exists('friends', $this->relations)) {
            $friends = $this->mergeFriends();

            $this->setRelation('friends', $friends);
        }
    }


    protected function mergeFriends()
    {
        return $this->myFriends->merge($this->friendOf);
    }

    public function myFriendRequests()
    {
        return $this->belongsToMany(User::class, 'user_friend_requests', 'user_id', 'requester_id')->withTimestamps()->withPivot('status');
    }

    public function friendBlocks()
    {
        return $this->belongsToMany(User::class, 'user_blocks', 'user_id', 'blocked_id')->withTimestamps()->withPivot('status');
    }

    public function usersBlockedMe()
    {
        return $this->belongsToMany(User::class, 'user_blocks', 'blocked_id', 'user_id')->withTimestamps()->withPivot('status');
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'users_favorites', 'user_id', 'favorite_id');
    }

    public function favoriteTo()
    {
        return $this->belongsToMany(User::class, 'users_favorites', 'favorite_id', 'user_id');
    }

    public function packageHistory()
    {
        return $this->belongsToMany(Package::class, 'package_users', 'user_id', 'package_id')->withTimestamps()->withPivot('status');
    }

    public function giftsHistory()
    {
        return $this->belongsToMany(Gift::class, 'gifts_users_history', 'user_id', 'gift_id')->withPivot('status')->withTimestamps();
    }


    public function giftsSend()
    {
        return $this->belongsToMany(Gift::class, 'gift_users', 'from_user_id', 'gift_id')->withPivot('gift_id')->withTimestamps()->withPivot('status');
    }

    public function giftsReceived()
    {
        return $this->belongsToMany(Gift::class, 'gift_users', 'to_user_id', 'gift_id')->withPivot('gift_id')->withTimestamps()->withPivot('status');
    }

    public function localChannels()
    {
        return $this->hasMany(Channel::class, 'name', 'user_name')->withTimestamps();
    }

    public function badgesHistory()
    {
        return $this->belongsToMany(Badge::class, 'badges_users', 'user_id', 'badge_id')->withTimestamps()->withPivot('status');
    }

    public function framesHistory()
    {
        return $this->belongsToMany(Frame::class, 'frames_users', 'user_id', 'frame_id')->withTimestamps()->withPivot('status', 'is_active', 'bought_at');
    }

    public function loginSignsHistory()
    {
        return $this->belongsToMany(LoginSign::class, 'login_signs_users', 'user_id', 'loginsign_id')->withTimestamps()->withPivot('status', 'is_active');
    }

    public function bannedUsers()
    {
        return $this->hasMany(BannedUser::class, 'user_id');
    }

    public function moonGiftScores()
    {
        return $this->hasMany(MoonGiftScore::class);
    }

    public function luckyGifts()
    {
        return $this->belongsToMany(Gift::class, 'user_lucky_gift');
    }

    /*------------------------- Fady Edits -------------------------*/
        // The rooms created by you
        public function rooms_hosting(){

            return $this->hasMany(Room::class, 'host_id');
        }



        // Pivot Tabel For ManyToMany Relationship
        public function rooms_drivers(){

            return $this->hasMany(RoomsDrivers::class, 'room_id');
        }
        // The rooms entred by you
        public function rooms_driving(){

            return $this->belongsToMany(Room::class, RoomsDrivers::class);
        }

    /*------------------------- Fady Edits -------------------------*/
}
