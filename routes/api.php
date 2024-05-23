<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PodcastController;
use App\Http\Controllers\Api\LiveController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\TestController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'middleware' => ['api','localization']
], function ($router) {
    //authentication route
    // Route::group(['middleware' => 'checkUserStatus'], function () {
    Route::post('/get-user','Api\Auth\UserAuthController@getUser');
    Route::post('/edit-profile','Api\Profile\ProfileController@edituserProfile');
    Route::post('/update-password','Api\Profile\ProfileController@updatePassword');
    Route::post('/user-update-data','Api\Profile\ProfileController@updateUserEmailorPhone');
    Route::post('/all-tag','Api\MasterController@allTags');
    Route::post('/all-category','Api\CategoryController@getCetegory'); 
    Route::post('/all-coin-plan','Api\MasterController@coinPlan'); 
    Route::post('/all-emoji','Api\MasterController@allEmoji'); 
    Route::post('/all-music','Api\MasterController@allMusic'); 
    Route::post('/sent-push-message','Api\PushNotification\NotificationController@sentPushMessage'); 
    // Podcast , Playlist 
    //user auth part
    Route::post('/login','Api\Auth\UserAuthController@login');
    Route::post('/register','Api\Auth\UserAuthController@register');
    Route::post('/user-verify-account','Api\Auth\UserAuthController@verifyAccount');
    Route::post('/resent-otp','Api\Auth\UserAuthController@resentOtp');
    Route::post('/user-password-change','Api\Auth\UserAuthController@UserresetPassword');
    Route::post('/contact-us-store','Api\ContentController@contactUsFormstore');
    //country state city
    Route::post('/get-country','Api\HomeController@getCountry');
    Route::post('/get-state','Api\HomeController@getState');
    Route::post('/get-city','Api\HomeController@getCity');

    Route::post('/create-shorts','Api\UserShortsController@createShorts');
    Route::post('/list-shorts','Api\UserShortsController@listShorts');
    Route::post('/shorts-status-change','Api\UserShortsController@shortstatuschange');
    Route::post('/list-follower-user','Api\UserController@listFollowersUsers');
    Route::get('/test-event','Api\PushNotification\NotificationController@test');


    
    Route::prefix('podcast')->name('podcast.')->group(function(){
        Route::get('/list', [PodcastController::class, 'list'])->name('list');
        Route::post('/create', [PodcastController::class, 'create'])->name('create');
        Route::post('/like', [PodcastController::class, 'like'])->name('like');
        Route::post('/comment', [PodcastController::class, 'comment'])->name('comment');
    });

    Route::prefix('lives')->name('lives.')->group(function(){
        Route::get('/list', [LiveController::class, 'list'])->name('list');
        Route::post('/create', [LiveController::class, 'create'])->name('create');
        Route::post('/like', [LiveController::class, 'like'])->name('like');
        Route::post('/comment', [LiveController::class, 'comment'])->name('comment');
    });

    Route::prefix('videos')->name('videos.')->group(function(){
        Route::get('/list', [VideoController::class, 'list'])->name('list');
        Route::post('/create', [VideoController::class, 'create'])->name('create');
        Route::post('/like', [VideoController::class, 'like'])->name('like');
        Route::post('/comment', [VideoController::class, 'comment'])->name('comment');
    });

    Route::prefix('follow')->name('follow.')->group(function(){
        Route::post('/post', [FollowController::class, 'post'])->name('post');
        Route::get('/followings', [FollowController::class, 'followings'])->name('followings');
        Route::get('/followers', [FollowController::class, 'followers'])->name('followers');        
    });

    Route::prefix('stripe')->name('stripe.')->group(function(){
        Route::get('/test', [StripeController::class, 'test'])->name('test');         
        Route::post('/post', [StripeController::class, 'post'])->name('post');         
    });

    Route::prefix('wallet')->name('wallet.')->group(function(){
        Route::get('/index', [WalletController::class, 'index'])->name('index');        
    });

    Route::prefix('test')->name('test.')->group(function(){
        Route::get('/index', [TestController::class, 'index'])->name('index');      
    });
    
});

