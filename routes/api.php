<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix' => 'conversation'], function () {
        Route::get('index', 'ChatController@conversations');
        Route::get('messages', 'ChatController@messages');
        Route::post('send', 'ChatController@send');
        Route::post('delete-conv', 'ChatController@destroy');

    });
    Route::get('cities', 'CountryController@getCities');
    Route::post('country/cities', 'CountryController@getCity')->name('getcities');


    Route::group(['prefix' => 'info'], function () {
        Route::get('terms', 'AppInfoController@getTermsNConditions');
        Route::get('words', 'AppInfoController@getProhibitedWords');

    });


    Route::get('appversion', 'AppinfoController@getMobVer');



    //Auth

    Route::group(['prefix' => 'auth'], function () {
        Route::post('verify', 'AuthController@verify');
        Route::post('resend-code', 'AuthController@resendCode');
        Route::post('reset-password', 'AuthController@resetPassword');
        Route::post('reset', 'AuthController@reset');

        Route::group(['middleware' => ['BannedUnUsers']], function () {
            Route::post('login', 'AuthController@login');
            Route::post('social/login', 'AuthController@loginSocial');

            Route::post('register', 'AuthController@register');
            Route::post('social/register', 'AuthController@registerSocial');
        });

    });

 
    Route::post('room/webhook', 'RoomController@webhook');
    Route::get('leaderboard_2', 'Store\TopUserController@topUsers2');
    Route::get('getGiftsUser', 'Store\TopUserController@getGiftsUser');
    Route::get('getstreameradmins', 'Store\TopUserController@getstreameradmins');
    Route::post('user/streamers/promote', 'Store\TopUserController@promoteuser');

    Route::group(['middleware' => ['auth:api', 'BannedUsers']], function () {

        Route::group(['prefix' => 'info'], function () {
            Route::get('events', 'AppInfoController@getEvents');
            Route::get('ads', 'AppInfoController@getAds');
            Route::get('leaderboard', 'Store\TopUserController@topUsers');
        });
        Route::group(['prefix' => 'conversation'], function () {
            Route::get('index', 'ChatController@conversations');
            Route::get('messages', 'ChatController@messages');
            Route::post('send', 'ChatController@send');
            Route::post('delete-conv', 'ChatController@destroy');
    
        });

        Route::group(['prefix' => 'room'], function () {
            Route::get('index', 'RoomController@getRooms');
            Route::post('create', 'RoomController@createRoom');
            Route::get('details', 'RoomController@getRoomDetails');
            Route::post('update', 'RoomController@update');
            Route::get('/', 'RoomController@getMyRoom');

            Route::group(['prefix' => 'musiclist'], function () {
                Route::get('get', 'RoomController@getRoomMusicList');
                Route::post('upload', 'RoomController@uploadRoomMusic');
                Route::post('update', 'RoomController@updateRoomMusic');
                Route::get('delete', 'RoomController@deleteRoomMusic');
            });
            Route::post('/dice', 'RoomController@getDiceRoom');

            // Route::post('/driver/create', 'RoomController@createDriver');
            // Route::post('/driver/delete', 'RoomController@deleteDriver');
            Route::post('/driver/mute', 'RoomController@muteDriver');
            Route::post('/user/block', 'RoomController@blockMember');
            Route::post('/driver/request', 'RoomController@driverRequest');
            Route::post('/driver/response', 'RoomController@driverResponse');
            Route::get('/driver/senders', 'RoomController@getDriverGiftSenders');
            Route::post('/sitDrivers', 'RoomController@sitDrivers');
            Route::post('/message', 'RoomController@sendMessage');

            Route::get('members/count', 'RoomController@getRoomMembersCount');
            Route::get('members', 'RoomController@getRoomMembersDetails');

            Route::group(['prefix' => 'guessgame'], function () {
                Route::get('/', 'GuessGameController@getShapesAndPackages');
                Route::post('start', 'GuessGameController@startGame');
                Route::post('accept', 'GuessGameController@acceptGame');

            });

        });

        Route::group(['prefix' => 'profile'], function () {
            Route::post('update', 'UserController@update');
            Route::post('details', 'UserController@profileDetails');
            Route::post('user', 'UserController@showUserDetails');
            Route::post('support', 'UserController@sendMessageToAdmin');

            Route::post('pin/create', 'UserController@createPin');
            Route::post('pin/change', 'UserController@changePin');

            Route::post('inc-diamond', 'UserController@inc_diamond');

            Route::get('get-charisma', 'UserController@get_charisma');

        });

        

        Route::group(['prefix' => 'users'], function () {

            Route::post('search', 'UserController@searchUsers');

            Route::get('streamer/progress', 'StreamerController@getStreamerProgress')->middleware('role:streamer');
            Route::get('streamer/report', 'StreamerController@AgentReport')->middleware('role:streamer_admin');

            Route::get('followers', 'UserController@getMyFollowers');
            Route::get('following', 'UserController@getFollowing');
            Route::post('follow', 'UserController@followUser');
            Route::post('unfollow', 'UserController@unFollowUser');

            Route::get('Favorites', 'UserController@getFavorites');
            Route::get('FavoriteTo', 'UserController@getFavoriteTo');
            Route::post('addFavorite', 'UserController@addFavorite');
            Route::post('removeFavorite', 'UserController@removeFavorite');

            // Route::post('attachment/create', 'UserController@uploadImage');


            Route::group(['prefix' => 'friends'], function () {
                Route::get('/', 'UserController@getMyFriends');
                Route::get('/requests', 'UserController@getMyFriendRequests');
                Route::post('request/send', 'UserController@sendFriendRequest');
                Route::post('request/accept', 'UserController@acceptFriendRequest');
                Route::post('request/cancel', 'UserController@cancelFriendRequest');
                Route::post('unfriend', 'UserController@unFriendUser');
                Route::post('block', 'UserController@blockUser');
                Route::post('unblock', 'UserController@unblockUser');
                Route::get('blocking/list', 'UserController@getMyBlocking');
            });

            Route::post('reports/create', 'UserController@createReport');

        });

        Route::group(['prefix' => 'store', 'namespace' => 'Store'], function () {

            // Route::post('diamonds/convert', 'DiamondController@convertDiamondsToGolds');
            Route::post('gems/convert', 'DiamondController@convertGemsToDiamonds');


            Route::group(['prefix' => 'gifts'], function () {
                Route::get('/', 'GiftController@getGifts');
                // Route::post('create', 'GiftController@buyGift');
                Route::get('get', 'GiftController@getMyGifts');
                Route::post('send', 'GiftController@sendGift');
                Route::post('buy-and-send', 'GiftController@buyAndSendGift');
            });

            Route::group(['prefix' => 'emojis'], function () {
                Route::get('/', 'EmojiController@getEmojis');
                Route::post('send', 'EmojiController@sendEmoji');
            });

            Route::group(['prefix' => 'badges'], function () {
                Route::get('/', 'BadgeController@getBadges');
                Route::post('create', 'BadgeController@buyBadge');
                Route::get('get', 'BadgeController@getMyBadges');
            });

            Route::group(['prefix' => 'frames'], function () {
                Route::get('/', 'FrameController@getFrames');
                Route::post('create', 'FrameController@buyFrame');
                Route::get('get', 'FrameController@getMyFrames');
                Route::post('set', 'FrameController@setFrame');
            });

            Route::group(['prefix' => 'loginsigns'], function () {
                Route::get('/', 'LoginSignController@getLoginSigns');
                Route::post('create', 'LoginSignController@buyLoginSign');
                Route::get('get', 'LoginSignController@getMyLoginSigns');
                Route::post('set', 'LoginSignController@setLoginSign');
            });
        });

        Route::post('/send-lucky-gift', 'LuckyGiftGameController@sendLuckyGift');
        Route::get('/user-lucky-gift', 'LuckyGiftGameController@userLuckyGift');
        Route::get('/games-slider', 'LuckyGiftGameController@gamesSlider');
            Route::get('/main-slider', 'LuckyGiftGameController@mainSlider');

    });

    // payment
    Route::group(['prefix' => 'payment'], function () {
        Route::group(['middleware' => ['auth:api', 'BannedUsers']], function () {
            Route::post('prepare/checkout', 'PaymentController@prepareCheckout');
        });
        Route::get('get/status',       'PaymentController@get_payment_status');
    });

    Route::group(['prefix' => 'v1'], function () {

        Route::group(['middleware' => ['auth:api', 'BannedUsers'] ], function () {
            Route::group(['namespace' => 'Store' ], function () {

                Route::get('diamonds-price', 'DiamondController@getDiamondsPrice');
                Route::post('buy-diamonds', 'DiamondController@buyDiamonds');
            });
            Route::get('backgrounds', 'BackgroundController@index');
        });

        Route::group(['prefix' => 'auth', 'middleware' => ['BannedUnUsers']], function () {
            Route::post('register', 'AuthController@register_V1');
            Route::post('social/register', 'AuthController@registerSocial_V1');
        });
    });


    Route::group(['prefix' => 'v2'], function () {

        Route::group(['middleware' => ['auth:api']], function () {
        });
    });

    Route::post('delete-data', 'UserController@deleteData');

    Route::group(['middleware' => ['session.start', 'game.auth', 'auth:api']], function () {
        // moon gift game
        Route::get('/moon-gift-game', 'MoonGiftGameController@moonGiftGame');
        Route::get('/moon-gift-scores', 'MoonGiftGameController@moonGiftScores');
        Route::get('/moon-gift/game', 'MoonGiftGameController@moonGiftFullGame')->name('moon-game');
        Route::get('/moon-gift/play', 'MoonGiftGameController@moonGiftPlay');

        // lucky gift game
        Route::get('/lucky-gift-game', 'LuckyGiftGameController@luckyGiftGame')->name('lucky-game');
        Route::get('/lucky-gift-info', 'LuckyGiftGameController@luckyGiftInfo');
        Route::get('/lucky-gift-scores', 'LuckyGiftGameController@luckyGiftScores');
        Route::post('/lucky-gift-play', 'LuckyGiftGameController@luckyGiftPlay');
        Route::get('/lucky-gift-news', 'LuckyGiftGameController@news');
    });

    Route::group([
        'prefix' => 'gameox',
        'namespace' => 'External',
    ], function () {
        Route::group(['prefix' => 'coinsable'], function () {
            Route::get('/', 'GameOxCoinsableController@show');
            Route::post('/', 'GameOxCoinsableController@update');
        });
    });

});
