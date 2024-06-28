<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PodcastController;
use App\Http\Controllers\Api\LiveController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\GiftController;
use App\Http\Controllers\Api\CoinInventoryController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\AudioController;
use App\Http\Controllers\Api\PlaylistController;
use App\Http\Controllers\Api\HelpCentreController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\MasterController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\UserShortsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Auth\UserAuthController;
use App\Http\Controllers\Api\Profile\ProfileController;

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
    Route::get('/get-user',[UserAuthController::class, 'getUser']);
    Route::post('/edit-profile',[ProfileController::class, 'edituserProfile']);
    Route::post('/uploadProfilePicture',[ProfileController::class, 'uploadProfilePicture']);
    Route::post('/uploadGovtId',[ProfileController::class, 'uploadGovtId']);
    Route::post('/update-password',[ProfileController::class, 'updatePassword']);
    Route::post('/user-update-data',[ProfileController::class, 'updateUserEmailorPhone']);
    Route::post('/all-tag',[MasterController::class, 'allTags']);
    Route::post('/all-category','Api\CategoryController@getCetegory'); 
    Route::post('/all-coin-plan',[MasterController::class, 'coinPlan']); 
    Route::post('/all-emoji',[MasterController::class, 'allEmoji']); 
    Route::post('/all-music',[MasterController::class, 'allMusic']); 
     
    // Podcast , Playlist 
    //user auth part
    Route::post('/login',[UserAuthController::class, 'login']);
    Route::post('/register',[UserAuthController::class, 'register']);
    Route::post('/user-verify-account',[UserAuthController::class, 'verifyAccount']);
    Route::post('/resent-otp',[UserAuthController::class, 'resentOtp']);
    Route::post('/user-password-change',[UserAuthController::class, 'UserresetPassword']);
    
    //country state city
    Route::post('/get-country',[HomeController::class, 'getCountry']);
    Route::post('/get-state',[HomeController::class, 'getState']);
    Route::post('/get-city',[HomeController::class, 'getCity']);
    Route::get('/home/index',[HomeController::class, 'index']);
    Route::post('/home/checkUserChat',[HomeController::class, 'checkUserChat']);
    Route::get('/home/chatUserList',[HomeController::class, 'chatUserList']);

    Route::post('/create-shorts',[UserShortsController::class, 'createShorts']);
    Route::post('/list-shorts',[UserShortsController::class, 'listShorts']);
    Route::post('/shorts-status-change',[UserShortsController::class, 'shortstatuschange']);

    Route::post('/list-follower-user',[UserController::class, 'listFollowersUsers']);
    Route::post('/user-profile',[UserController::class, 'profile']);

    Route::get('/test-event','Api\PushNotification\NotificationController@test');
    Route::post('/contact-us-store','Api\ContentController@contactUsFormstore');
    Route::post('/sent-push-message','Api\PushNotification\NotificationController@sentPushMessage');
    
    Route::prefix('podcast')->name('podcast.')->group(function(){
        Route::get('/list', [PodcastController::class, 'list'])->name('list');
        Route::post('/create', [PodcastController::class, 'create'])->name('create');
        Route::post('/like', [PodcastController::class, 'like'])->name('like');
        Route::post('/comment', [PodcastController::class, 'comment'])->name('comment');
        Route::post('/details', [PodcastController::class, 'details'])->name('details');
        Route::post('/comments', [PodcastController::class, 'comments'])->name('comments');
        Route::post('/message-comment', [PodcastController::class, 'message_comment'])->name('message_comment');
        Route::get('/comment-messages/{id}', [PodcastController::class, 'comment_messages'])->name('comment_messages');
    });

    Route::prefix('lives')->name('lives.')->group(function(){
        Route::get('/list', [LiveController::class, 'list'])->name('list');
        Route::post('/create', [LiveController::class, 'create'])->name('create');
        Route::post('/like', [LiveController::class, 'like'])->name('like');
        Route::post('/comment', [LiveController::class, 'comment'])->name('comment');
        Route::post('/details', [LiveController::class, 'details'])->name('details');
        Route::post('/comments', [LiveController::class, 'comments'])->name('comments');
    });

    Route::prefix('videos')->name('videos.')->group(function(){
        Route::get('/list', [VideoController::class, 'list'])->name('list');
        Route::post('/create', [VideoController::class, 'create'])->name('create');
        Route::post('/like', [VideoController::class, 'like'])->name('like');
        Route::post('/comment', [VideoController::class, 'comment'])->name('comment');
        Route::post('/details', [VideoController::class, 'details'])->name('details');
        Route::post('/comments', [VideoController::class, 'comments'])->name('comments');
        Route::post('/save_draft', [VideoController::class, 'save_draft'])->name('save_draft');
        Route::get('/list_draft', [VideoController::class, 'list_draft'])->name('list_draft');
        Route::post('/publish_draft', [VideoController::class, 'publish_draft'])->name('publish_draft');
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

    
    Route::prefix('test')->name('test.')->group(function(){
        Route::get('/index', [TestController::class, 'index'])->name('index');      
        Route::post('/upload', [TestController::class, 'upload'])->name('upload');      
        Route::post('/comments', [TestController::class, 'comments'])->name('comments');      
        Route::get('/sendMail', [TestController::class, 'sendMail'])->name('sendMail');           
    });

    Route::prefix('gift')->name('gift.')->group(function(){
        Route::get('/index', [GiftController::class, 'index'])->name('index');
        Route::post('/send', [GiftController::class, 'send'])->name('send');      
    });

    Route::prefix('coin-inventory')->name('coin-inventory.')->group(function(){
        Route::get('/index', [CoinInventoryController::class, 'index'])->name('index');
        Route::get('/plans', [CoinInventoryController::class, 'plans'])->name('plans');
        Route::post('/add', [CoinInventoryController::class, 'add'])->name('add');
    });

    Route::prefix('activity')->name('activity.')->group(function(){
        Route::get('/index', [ActivityController::class, 'index'])->name('index');
    });
    Route::prefix('notification')->name('notification.')->group(function(){
        Route::get('/index', [NotificationController::class, 'index'])->name('index');
    });
    Route::prefix('country')->name('country.')->group(function(){
        Route::get('/index', [CountryController::class, 'index'])->name('index');
    });
    Route::prefix('audio')->name('audio.')->group(function(){
        Route::post('/create', [AudioController::class, 'create'])->name('create');
        Route::get('/list', [AudioController::class, 'list'])->name('list');
    });
    Route::prefix('playlist')->name('playlist.')->group(function(){
        Route::get('/index', [PlaylistController::class, 'index'])->name('list');
        Route::post('/create', [PlaylistController::class, 'create'])->name('create');
        Route::post('/add_media', [PlaylistController::class, 'add_media'])->name('add_media');
    });
    Route::prefix('helpcentre')->name('helpcentre.')->group(function(){
        Route::post('/add', [HelpCentreController::class, 'add'])->name('add');
        Route::get('/list', [HelpCentreController::class, 'list'])->name('list');
    });
    Route::prefix('report')->name('report.')->group(function(){
        Route::post('/add', [ReportController::class, 'add'])->name('add');
        Route::get('/list', [ReportController::class, 'list'])->name('list');
    });  
    
});

