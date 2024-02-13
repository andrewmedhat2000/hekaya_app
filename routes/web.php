<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'namespace' => 'Dashboard', 'middleware' => ['auth']], function () {

    Route::get('/', function () {
        return view('home');
    });


    // Route::get('/friendsfor', function () {
    //     foreach(App\Models\User::all() as $user){
    //         $user->update(['friends_count' => $user->friends->count() ]);
    //     }
    //     return 'done';
    // });

    Route::group(['middleware' => ['auth', 'role:admin|super_admin|streamer_admin']], function () {
        Route::group(['prefix' => 'streamer'], function () {
            Route::get('friends', 'StreamerController@getFriends')->name('streamer.friends');
            Route::get('followers', 'StreamerController@getFollowers')->name('streamer.followers');
            Route::get('following', 'StreamerController@getFollowing')->name('streamer.following');
            Route::get('pay', 'StreamerController@pay')->name('streamer.pay');
            Route::get('import', 'StreamerController@importView')->name('streamer.import.view');
            Route::post('import', 'StreamerController@import')->name('streamer.import');
        });
        Route::resource('streamer', 'StreamerController');

        Route::post('user/toggle/status', 'UserController@toggleStatus')->name('user.toggle.status');
        Route::post('user/ban', 'UserController@banUser')->name('user.ban');
        Route::post('user/unban', 'UserController@unbanUser')->name('user.unban');
        Route::post('user/ban/view', 'UserController@banView')->name('user.banview');
        Route::post('user/status/view', 'UserController@statusView')->name('user.statusview');
        Route::post('user/activate', 'UserController@activateUser')->name('user.activate');
        Route::get('user/streamers', 'UserController@getStreamers')->name('user.streamers');
        Route::post('user/streamers/promote/view', 'UserController@promoteView')->name('user.promote.view');
        Route::post('user/streamers/promote', 'UserController@promote')->name('user.promote');
        Route::post('user/streamers/unpromote', 'UserController@unpromote')->name('user.unpromote');
        Route::post('user/distributor/toggle', 'UserController@toggleDistributor')->name('user.distributor.toggle');
        Route::post('user/streamer-admin/promote', 'UserController@promoteToStreamerAdmin')->name('user.streamer-admin.promote');
    });    

    Route::group(['middleware' => ['auth', 'role:admin|super_admin']], function () {
        Route::resource('user', 'UserController');
    });

    Route::group(['middleware' => ['auth', 'role:admin|super_admin']], function () {


        Route::post('ads/remove/image', 'AdsController@imageDelete')->name('ads.image.delete');
        Route::post('events/remove/image', 'EventController@imageDelete')->name('events.image.delete');


        Route::group(['prefix' => 'users'], function () {
            Route::get('friends', 'UserController@getFriends')->name('user.friends');
            Route::get('followers', 'UserController@getFollowers')->name('user.followers');
            Route::get('following', 'UserController@getFollowing')->name('user.following');
        });


        Route::group(['prefix' => 'streameradmin'], function () {
            Route::get('friends', 'StreamerAdminController@getFriends')->name('streameradmin.friends');
            Route::get('followers', 'StreamerAdminController@getFollowers')->name('streameradmin.followers');
            Route::get('following', 'StreamerAdminController@getFollowing')->name('streameradmin.following');
        });


        Route::group(['prefix' => 'streameradminreporting'], function () {
            Route::get('friends', 'StreamerAdminController@getFriends')->name('streameradminreporting.friends');
            Route::get('followers', 'StreamerAdminController@getFollowers')->name('streameradminreporting.followers');
            Route::get('following', 'StreamerAdminController@getFollowing')->name('streameradminreporting.following');
        });

        Route::group(['prefix' => 'support'], function () {
            Route::get('chat', 'AdminController@getChatSupport')->name('admin.chat');
        });

        Route::resource('settings', 'SettingsController');

        Route::group(['prefix' => 'settings'], function () {
            Route::get('prohibited-words', 'SettingsController@prohibitedWords');
            Route::post('prohibited-words/update', 'SettingsController@updateProhibitedWords')->name('settings.prohibited-words.update');
        });

        Route::get('moon-gift/reset', 'MoonGiftController@reset')->name('moon-gift.reset');
        Route::get('moon-gift/view', 'MoonGiftController@show')->name('moon-gift.view');
        Route::resource('moon-gift', 'MoonGiftController');

        Route::get('lucky-gift/reset', 'LuckyGiftController@reset')->name('lucky-gift.reset');
        Route::get('lucky-gift/view', 'LuckyGiftController@show')->name('lucky-gift.view');
        Route::resource('lucky-gift', 'LuckyGiftController');
        Route::resource('lucky-round', 'LuckyRoundController');
        
        Route::resource('games-slider', 'GamesController');
        
        Route::resource('admin', 'AdminController');
        Route::get('currency_worth/edit', 'CurrencyWorthController@edit')->name('currency_worth.edit');
        Route::patch('currency_worth/update', 'CurrencyWorthController@update')->name('currency_worth.update');

        Route::resource('country', 'CountryController');
        Route::resource('city', 'CityController');
        Route::resource('tag', 'TagController');
        Route::resource('category', 'CategoryController');
        Route::resource('terms', 'TermsController');
        Route::resource('conditions', 'ConditionsController');
        Route::resource('gifts', 'StoreController');
        Route::resource('currencies', 'CurrencyController');
        Route::resource('packages', 'PackageController');
        Route::resource('targets', 'TargetController');

        Route::resource('events', 'EventController');
        Route::resource('ads', 'AdsController');

        Route::resource('loginsigns', 'LoginSignController');
        Route::resource('badges', 'BadgeController');
        Route::resource('frames', 'FrameController');

        Route::resource('backgrounds', 'BackgroundController');

        Route::get('streameradmin/streamers', 'StreamerAdminController@getStreamers')->name('streameradmin.streamers');
        Route::resource('streameradmin', 'StreamerAdminController');
        Route::get('streameradminreporting/export_mapping/all', 'StreamerAdminReportingController@export_all_mapping')->name('streameradminreporting.export_all_mapping');
        Route::resource('streameradminreporting', 'StreamerAdminReportingController');


        Route::get('local/channel/get', 'LocalChannelController@show')->name('local.channel.get');
        Route::post('local/channel/send', 'LocalChannelController@send')->name('local.channel.send');


        Route::group(['prefix' => 'local', 'as' => 'local.'], function () {

            Route::resource('channel', 'LocalChannelController');
        });


        Route::group(['prefix' => 'channels'], function () {

            Route::get('/', 'ChannelController@index')->name('channels.index');
            Route::get('show', 'ChannelController@show')->name('channels.show');
            Route::get('members', 'ChannelController@getMembers')->name('channels.members');
            Route::get('messages', 'ChannelController@getMessages')->name('channels.messages');
            Route::get('media', 'ChannelController@getMedia')->name('channels.media');
        });

        Route::resource('emojis', 'EmojiController');

        Route::group(['prefix' => 'guessgame', 'namespace' => 'GuessGame', 'as' => 'guessgame.'], function () {
            Route::resource('shapes', 'ShapeController');
            Route::resource('packages', 'PackageController');

        });

        Route::get('reports', 'ReportController@index')->name('reports.index');

    });


    //mobile version
    Route::get('mobileversion/edit', 'MobileVersionController@edit')->name('mobile-version.edit');
    Route::post('mobileversion/update', 'MobileVersionController@update')->name('mobile-version.update');

});

Route::group(['namespace' => 'Dashboard', 'middleware' => ['auth', 'role:super_admin|distributor']], function () {
    Route::get('send-diamonds', 'UserController@distributorView')->name('distribute');
    Route::post('send', 'UserController@sendDiamonds')->name('send');
});


Auth::routes(['register' => false]);

// Route::get('/home', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');

Route::get('deletion', 'Api\\UserController@confirmDeletion');


// Route::get('dashboard/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('adminLogs');
