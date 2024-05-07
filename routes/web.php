<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Login
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('submit.login');
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Register
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('submit.register');
Route::post('check-email', [App\Http\Controllers\Auth\RegisterController::class, 'checkEmail'])->name('check.email');
Route::post('check-phone', [App\Http\Controllers\Auth\RegisterController::class, 'checkPhone'])->name('check.phone');
Route::get('email-verify', [App\Http\Controllers\Auth\RegisterController::class, 'emailVerification'])->name('email.verify');
Route::post('email-verify', [App\Http\Controllers\Auth\RegisterController::class, 'emailVerify'])->name('email.verify.vcode');
Route::get('resend-email-verify', [App\Http\Controllers\Auth\RegisterController::class, 'resendEmailVcode'])->name('resend.email.verify');
Route::get('phone-verify', [App\Http\Controllers\Auth\RegisterController::class, 'phoneVerification'])->name('phone.verify');
Route::post('phone-verify', [App\Http\Controllers\Auth\RegisterController::class, 'phoneVerify'])->name('phone.verify.vcode');
Route::get('resend-phone-verify', [App\Http\Controllers\Auth\RegisterController::class, 'resendPhoneVcode'])->name('resend.phone.verify');

// Reset Password
Route::get('password/reset-form', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/resend-otp', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetResendOTP'])->name('password.resend.otp');
Route::get('password/reset-otp', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showOTPForm'])->name('password.reset.otp');
Route::post('password/verify-otp', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'otpVerify'])->name('password.otp.verify');
Route::get('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Confirm Password
Route::get('password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'confirm']);

// Verify Email
// Route::get('email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
// Route::get('email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
// Route::post('email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');

Route::get('/app-download', [App\Http\Controllers\Modules\Home\HomeController::class, 'download'])->name('download.app');
Route::get('/', [App\Http\Controllers\Modules\Home\HomeController::class, 'index'])->name('home1');
Route::get('search/{categoryIds?}', [App\Http\Controllers\Modules\Category\CategoryController::class, 'categoryList'])->name('search.list');
Route::get('category-business-list/{slug?}', [App\Http\Controllers\Modules\Category\CategoryController::class, 'businessList'])->name('category.business.list');
Route::get('business-details/{slug?}/{catslug?}', [App\Http\Controllers\Modules\Business\BusinessController::class, 'details'])->name('business.details');

// for cms pages
Route::get('about-us', [App\Http\Controllers\Modules\Cms\CmsController::class, 'aboutUs'])->name('about.us');
Route::get('terms-and-conditions', [App\Http\Controllers\Modules\Cms\CmsController::class, 'termsAndConditions'])->name('terms.and.conditions');
Route::get('privacy-policies', [App\Http\Controllers\Modules\Cms\CmsController::class, 'privacyPolicies'])->name('privacy.policies');

// for Faq pages
Route::get('faq', [App\Http\Controllers\Modules\Faq\FaqController::class, 'index'])->name('faq');

// for contact us pages
Route::get('contact-us', [App\Http\Controllers\Modules\Contact\ContactController::class, 'index'])->name('contact.us');
Route::post('contact-us', [App\Http\Controllers\Modules\Contact\ContactController::class, 'store'])->name('contact.store');

// for profile update
Route::get('/profile', [App\Http\Controllers\Modules\Profile\ProfileController::class, 'myAccount'])->name('my.account')->middleware(['auth','checkUser']);
Route::get('resend-otp/{user_id?}', 'App\Http\Controllers\Modules\Profile\ProfileController@resendOtp')->name('resend.otp');
Route::post('/verify-otp-/{userid?}', 'App\Http\Controllers\Modules\Profile\ProfileController@verifyOtpUpdatePhone')->name('verify.otp.update.phone');
Route::get('edit-profile', [App\Http\Controllers\Modules\Profile\ProfileController::class, 'edit'])->name('edit.profile')->middleware(['auth','checkUser']);
Route::get('change-password', [App\Http\Controllers\Modules\Profile\ProfileController::class, 'changePassword'])->name('change.password')->middleware(['auth','checkUser']);
Route::post('update-profile', [App\Http\Controllers\Modules\Profile\ProfileController::class, 'update'])->name('update.profile')->middleware(['auth','checkUser']);
Route::post('update-password', [App\Http\Controllers\Modules\Profile\ProfileController::class, 'password'])->name('update.password')->middleware(['auth','checkUser']);

// for address
Route::get('/my-address', [App\Http\Controllers\Modules\Address\AddressController::class, 'myAddress'])->name('my.address')->middleware(['auth','checkUser']);
Route::post('/add-address', [App\Http\Controllers\Modules\Address\AddressController::class, 'store'])->name('add.address')->middleware(['auth','checkUser']);
Route::post('/edit-address', [App\Http\Controllers\Modules\Address\AddressController::class, 'edit'])->name('edit.address')->middleware(['auth','checkUser']);
Route::post('/update-address', [App\Http\Controllers\Modules\Address\AddressController::class, 'update'])->name('update.address')->middleware(['auth','checkUser']);
Route::post('/delete-address', [App\Http\Controllers\Modules\Address\AddressController::class, 'delete'])->name('delete.address')->middleware(['auth','checkUser']);
Route::post('/state-list', [App\Http\Controllers\Modules\Address\AddressController::class, 'state'])->name('state.list');
Route::post('/city-list', [App\Http\Controllers\Modules\Address\AddressController::class, 'city'])->name('city.list');
Route::get('/set-default-address/{addressId?}', [App\Http\Controllers\Modules\Address\AddressController::class, 'setDefaultAddress'])->name('set.default.address')->middleware(['auth','checkUser']);
Route::get('/delete-address/{addressId?}', [App\Http\Controllers\Modules\Address\AddressController::class, 'deleteAddress'])->name('delete.address')->middleware(['auth','checkUser']);

// for dashboard
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['auth','checkUser']);

// for enquery request
Route::get('my-requirement', [App\Http\Controllers\Modules\Lead\LeadController::class, 'index'])->name('my.requirement')->middleware(['auth','checkUser']);
Route::get('post-requirement', [App\Http\Controllers\Modules\Lead\LeadController::class, 'create'])->name('requirement')->middleware(['auth','checkUser']);
Route::post('send-enquiry', [App\Http\Controllers\Modules\Lead\LeadController::class, 'store'])->name('send.enquiry')->middleware(['auth','checkUser']);

// for rating
Route::get('review', [App\Http\Controllers\Modules\Review\ReviewController::class, 'index'])->name('review')->middleware(['auth','checkUser']);
Route::post('review-store', [App\Http\Controllers\Modules\Review\ReviewController::class, 'store'])->name('review.store')->middleware(['auth','checkUser']);

// get category on popup
Route::post('get-category','App\Http\Controllers\Modules\Category\CategoryController@getCategory')->name('get.category');
// for business listing
Route::get('scorecard','App\Http\Controllers\Modules\Business\BusinessController@scorecardPage')->name('scorecard.page');
Route::post('add-listing','App\Http\Controllers\Modules\Business\BusinessController@businessStepone')->name('add.business');
Route::get('add-business/{upslug?}','App\Http\Controllers\Modules\Business\BusinessController@addListingBusiness')->name('add.business.listing');
Route::post('verify-otp-business','App\Http\Controllers\Modules\Business\BusinessController@verifyOtpForBusiness')->name('verify.otp.business');
Route::post('business-type-update','App\Http\Controllers\Modules\Business\BusinessController@businessUpdateType')->name('edit.type.business');
Route::post('/get-sub-category', 'App\Http\Controllers\Modules\Business\BusinessController@getSubcategory')->name('get.sub.category');
Route::post('/edit-business-category', 'App\Http\Controllers\Modules\Business\BusinessController@businessAddCategory')->name('edit.category.business');
Route::post('/add-business-image', 'App\Http\Controllers\Modules\Business\BusinessController@addBusinessImage')->name('add.business.image');
Route::get('/get-state/{id?}','App\Http\Controllers\Modules\Business\BusinessController@getState')->name('get.state');
Route::get('/get-city/{id?}','App\Http\Controllers\Modules\Business\BusinessController@getCity')->name('get.city');
Route::post('/add-business-location','App\Http\Controllers\Modules\Business\BusinessController@addUpdateLocation')->name('add.business.location');
Route::post('/add-business-details','App\Http\Controllers\Modules\Business\BusinessController@addUpdateDetails')->name('add.business.details');
Route::get('business-list','App\Http\Controllers\Modules\Business\BusinessController@businessList')->name('business.list')->middleware('checkVendor');
Route::get('view-business-details','App\Http\Controllers\Modules\Business\BusinessController@ViewBusiness')->name('view.business')->middleware('checkVendor');
Route::get('business-lead-details/{slug?}','App\Http\Controllers\Modules\Business\BusinessController@ViewBusinessLead')->name('view.business.lead')->middleware('checkVendor');
Route::get('business-profile/{slug?}','App\Http\Controllers\Modules\Business\BusinessController@ViewBusinessProfile')->name('view.business.profile')->middleware('checkVendor');
//business edit part
Route::middleware(['checkVendor'])->group(function() {
	Route::post('business-edit-step-one','App\Http\Controllers\Modules\Business\BusinessController@editPartOne')->name('business.edit.step.one');
	Route::post('/get-sub-category-business', 'App\Http\Controllers\Modules\Business\BusinessController@getSubcategory2')->name('get.sub.category.business');
	Route::post('business-edit-step-second','App\Http\Controllers\Modules\Business\BusinessController@editPartSecond')->name('business.edit.step.second');
	Route::post('business-edit-step-third','App\Http\Controllers\Modules\Business\BusinessController@editPartThird')->name('business.edit.step.third');
	Route::post('business-edit-step-four','App\Http\Controllers\Modules\Business\BusinessController@editPartFour')->name('business.edit.step.four');
	Route::get('last-step-business/{businessSlug?}','App\Http\Controllers\Modules\Business\BusinessController@lastStepAddListing')->name('last.step.business');
});

// vendor notification page
Route::get('notification','App\Http\Controllers\Modules\Profile\ProfileController@notificationPage')->name('notification.page')->middleware(['auth','checkUser']);
Route::post('notification','App\Http\Controllers\Modules\Profile\ProfileController@editNotofication')->name('notification.update')->middleware(['auth','checkUser']);
Route::post('delete-account','App\Http\Controllers\Modules\Profile\ProfileController@deleteAccount')->name('delete.account')->middleware('checkVendor');

// post subscribe
Route::post('subscribe-us','App\Http\Controllers\Modules\Home\HomeController@postSubscribe')->name('post.subscribe');
// keyword part
Route::post('autocomplete-keyword','App\Http\Controllers\Modules\Home\HomeController@searchKeyword')->name('auto.complete.keyword');
Route::get('/sse-update','App\Http\Controllers\Api\PushNotification\NotificationController@sendSSE');

Route::get('/welcome', function (){
	return view('welcome');
});
