<?php

use Illuminate\Support\Facades\Route;


// Login
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Register
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::post('check-email', 'Auth\RegisterController@checkEmail')->name('check.email');
Route::post('check-phone', 'Auth\RegisterController@checkPhone')->name('check.phone');
Route::get('email-verify', 'Auth\RegisterController@emailVerification')->name('email.verify');
Route::post('email-verify', 'Auth\RegisterController@emailVerify')->name('email.verify.vcode');
Route::get('resend-email-verify', 'Auth\RegisterController@resendEmailVcode')->name('resend.email.verify');
Route::get('phone-verify', 'Auth\RegisterController@phoneVerification')->name('phone.verify');
Route::post('phone-verify', 'Auth\RegisterController@phoneVerify')->name('phone.verify.vcode');
Route::get('resend-phone-verify', 'Auth\RegisterController@resendPhoneVcode')->name('resend.phone.verify');

// Reset Password
Route::get('password/reset-form', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/resend-otp', 'Auth\ForgotPasswordController@resetResendOTP')->name('password.resend.otp');
Route::get('password/reset-otp', 'Auth\ForgotPasswordController@showOTPForm')->name('password.reset.otp');
Route::post('password/verify-otp', 'Auth\ForgotPasswordController@otpVerify')->name('password.otp.verify');
Route::get('password/reset', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Confirm Password
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

// Verify Email
// Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
// Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
// Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// Dashboard
Route::get('/dashboard', 'HomeController@index')->name('home')->middleware('vendor.auth');

// For profile update
Route::get('/my-account', 'Modules\Profile\ProfileController@myAccount')->name('my.account')->middleware('vendor.auth');
Route::get('/edit-profile', 'Modules\Profile\ProfileController@edit')->name('edit.profile')->middleware('vendor.auth');
Route::post('/update-profile', 'Modules\Profile\ProfileController@update')->name('update.profile')->middleware('vendor.auth');
Route::post('/update-password', 'Modules\Profile\ProfileController@password')->name('update.password')->middleware('vendor.auth');

// home page
Route::get('/home', 'Modules\Home\HomeController@index')->name('home1');

// for cms pages
Route::get('about-us', 'Modules\Cms\CmsController@aboutUs')->name('about.us');
Route::get('terms-and-conditions', 'Modules\Cms\CmsController@termsAndConditions')->name('terms.and.conditions');
Route::get('privacy-policies', 'Modules\Cms\CmsController@privacyPolicies')->name('privacy.policies');

// for Faq pages
Route::get('faq', 'Modules\Faq\FaqController@index')->name('faq');

// for contact us pages
Route::get('contact-us', 'Modules\Contact\ContactController@index')->name('contact.us');
Route::post('contact-us', 'Modules\Contact\ContactController@store')->name('contact.store');
