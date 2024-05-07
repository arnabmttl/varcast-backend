<?php

use Illuminate\Support\Facades\Route;

// Login
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Register
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Reset Password
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Confirm Password
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

// Verify Email
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

Route::middleware('admin.auth')->group(function(){
	// Dashboard
	Route::get('/dashboard', 'HomeController@index')->name('home');
	//profile part
	Route::get('edit-profile','Modules\Profile\ProfileController@index')->name('edit.profile');
	Route::post('edit-profile','Modules\Profile\ProfileController@update')->name('update.profile');
	Route::get('change-password','Modules\Profile\ProfileController@passwordChange')->name('change.password');
	Route::post('update-password','Modules\Profile\ProfileController@updatePassword')->name('update.password');

	//Customer Management
	Route::get('user-management/{type?}','Modules\Customer\CustomerController@index')->name('customer.management');
	Route::get('user-change-status-{id?}-{status?}','Modules\Customer\CustomerController@userStatusChange')->name('customer.change.status');
	Route::get('user-approve-{id?}','Modules\Customer\CustomerController@userApprove')->name('customer.approve');
	Route::get('user-details-{id?}','Modules\Customer\CustomerController@customerDetails')->name('customer.details');
	Route::get('user-delete-{id?}','Modules\Customer\CustomerController@customerDelete')->name('customer.delete');
	Route::get('/add/user/{id?}', 'Modules\Customer\CustomerController@user_add')->name('customer.add');
	Route::post('/store/user/{type?}', 'Modules\Customer\CustomerController@storeUsers')->name('customer.store');



	// contact management
	Route::get('/contact-list','Modules\Contact\ContactController@index')->name('contact.list');
	Route::any('/contact-delete/{id?}','Modules\Contact\ContactController@contactDelete')->name('contact.delete');

	// product management
	Route::get('/product-management','Modules\Product\ProductController@index')->name('product.management');
	Route::get('/add-product','Modules\Product\ProductController@create')->name('product.add');
	Route::post('/add-product','Modules\Product\ProductController@store')->name('product.store');
	Route::any('/product-status/{id?}','Modules\Product\ProductController@status')->name('product.status');
	Route::get('/edit-product/{id?}','Modules\Product\ProductController@edit')->name('product.edit');
	Route::any('/delete-product/{id?}','Modules\Product\ProductController@delete')->name('product.delete');


	//banner management
	Route::get('/banner-management','Modules\Banner\BannerController@index')->name('banner.management');
	Route::get('/add-banner','Modules\Banner\BannerController@create')->name('banner.add');
	Route::post('/add-banner','Modules\Banner\BannerController@store')->name('banner.store');
	Route::any('/banner-status/{id?}','Modules\Banner\BannerController@status')->name('banner.status');
	Route::get('/edit-banner/{id?}','Modules\Banner\BannerController@edit')->name('banner.edit');
	Route::any('/delete-banner/{id?}','Modules\Banner\BannerController@delete')->name('banner.delete');

	//faq management
	Route::get('/faq-management','Modules\Faq\FaqController@index')->name('faq.management');
	Route::get('/add-faq','Modules\Faq\FaqController@create')->name('faq.add');
	Route::post('/add-faq','Modules\Faq\FaqController@store')->name('faq.store');
	Route::get('/edit-faq/{id?}','Modules\Faq\FaqController@edit')->name('faq.edit');
	Route::get('/faq-status/{id?}','Modules\Faq\FaqController@status')->name('faq.status');
	Route::any('/delete-faq/{id?}','Modules\Faq\FaqController@delete')->name('faq.delete');

	// setting management
	Route::get('/setting-management','Modules\Setting\SettingController@index')->name('setting.management');
	Route::post('/store-setting','Modules\Setting\SettingController@store')->name('setting.store');



	//content management
	Route::get('/content-management/{page?}','Modules\Cms\CmsController@index')->name('content.management');
	Route::post('/content-store','Modules\Cms\CmsController@store')->name('content.store');
	// home content
	Route::get('home-content','Modules\Cms\CmsController@homeContentPage')->name('home.content.management');
	Route::post('home-content','Modules\Cms\CmsController@HomeContentstore')->name('store.home.content.management');
	// scorecard content
	Route::get('scorecard-content','Modules\Cms\CmsController@scorecardContentPage')->name('scorecard.content.management');
	Route::post('scorecard-content','Modules\Cms\CmsController@scorecardContentStore')->name('store.scorecard.content.management');

	//testimonial
	Route::get('/testimonial','Modules\Cms\CmsController@testimonialPage')->name('testimonial.page');
	Route::get('/add-testimonial','Modules\Cms\CmsController@testimonialCreatePage')->name('add.testimonial.page');
	Route::post('/add-testimonial','Modules\Cms\CmsController@testimonialStore')->name('testimonial.store');
	Route::get('/status-change-testimonial/{id?}','Modules\Cms\CmsController@testimonialStatus')->name('testimonial.status.change');
	Route::get('/delete-testimonial/{id?}','Modules\Cms\CmsController@testimonialDelete')->name('testimonial.delete');
	Route::get('/edit-testimonial/{id?}','Modules\Cms\CmsController@testimonialEdit')->name('testimonial.edit');

	// subscribe list
	Route::get('subscribe-list','Modules\Contact\ContactController@subscribeList')->name('subscribe.list');
	Route::get('subscribe-delete/{id?}','Modules\Contact\ContactController@subscribeDelete')->name('subscribe.delete');
	Route::post('send-notification','Modules\Contact\ContactController@sentSubscribeNotification')->name('subscribe.notification.send');


	// Category
	Route::get('/category', 'Modules\Category\CategoryController@index')->name('category');
	Route::get('/add-category', 'Modules\Category\CategoryController@create')->name('category.add');
	Route::post('/store-category', 'Modules\Category\CategoryController@store')->name('category.store');
	Route::get('/edit-category/{id}', 'Modules\Category\CategoryController@edit')->name('category.edit');
	Route::post('/update-category', 'Modules\Category\CategoryController@update')->name('category.update');
	Route::get('/delete-category/{id}', 'Modules\Category\CategoryController@delete')->name('category.delete');
	Route::get('/category-status/{id}', 'Modules\Category\CategoryController@status')->name('category.status');
	Route::post('/get-sub-category', 'Modules\Category\CategoryController@getSubcategory')->name('get.sub.category');
	//coin Price
	Route::prefix('/coin-price')->name('coin.price.')->group(function(){
		Route::get('/index', 'Modules\CoinPrice\CoinPriceController@index')->name('index');
		Route::get('/add', 'Modules\CoinPrice\CoinPriceController@create')->name('add');
		Route::post('/store', 'Modules\CoinPrice\CoinPriceController@store')->name('store');
		Route::get('/edit/{id}', 'Modules\CoinPrice\CoinPriceController@edit')->name('edit');
		Route::get('/delete/{id}', 'Modules\CoinPrice\CoinPriceController@delete')->name('delete');
		Route::get('/status/{id}', 'Modules\CoinPrice\CoinPriceController@status')->name('status');
	});
	//emoji management
	Route::prefix('/emoji')->name('emoji.')->group(function(){
		Route::get('/index','Modules\Emoji\EmojiController@index')->name('index');
		Route::get('/add','Modules\Emoji\EmojiController@create')->name('add');
		Route::post('/store','Modules\Emoji\EmojiController@store')->name('store');
		Route::any('/status/{id?}','Modules\Emoji\EmojiController@status')->name('status');
		Route::get('/edit/{id?}','Modules\Emoji\EmojiController@edit')->name('edit');
		Route::any('/delete/{id?}','Modules\Emoji\EmojiController@delete')->name('delete');
	});
	//tag management
	Route::prefix('/tag')->name('tag.')->group(function(){
		Route::get('/index','Modules\Tag\TagController@index')->name('index');
		Route::get('/add','Modules\Tag\TagController@create')->name('add');
		Route::post('/store','Modules\Tag\TagController@store')->name('store');
		Route::any('/status/{id?}','Modules\Tag\TagController@status')->name('status');
		Route::get('/edit/{id?}','Modules\Tag\TagController@edit')->name('edit');
		Route::any('/delete/{id?}','Modules\Tag\TagController@delete')->name('delete');
	});
	Route::prefix('/my-music')->name('my.music.')->group(function(){
		Route::get('/index','Modules\MyMusic\MyMusicController@index')->name('index');
		Route::get('/add','Modules\MyMusic\MyMusicController@create')->name('add');
		Route::post('/store','Modules\MyMusic\MyMusicController@store')->name('store');
		Route::any('/status/{id?}','Modules\MyMusic\MyMusicController@status')->name('status');
		Route::get('/edit/{id?}','Modules\MyMusic\MyMusicController@edit')->name('edit');
		Route::any('/delete/{id?}','Modules\MyMusic\MyMusicController@delete')->name('delete');
	});

});
