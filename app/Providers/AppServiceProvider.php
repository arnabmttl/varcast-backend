<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        $email_settings = Setting::first();
        config(['mail.mailers.smtp.host' => $email_settings ? $email_settings->mail_host : env('MAIL_HOST', 'smtp.mailgun.org')]);
        config(['mail.mailers.smtp.port' => $email_settings ? $email_settings->mail_port : env('MAIL_PORT', 587)]);
        config(['mail.mailers.smtp.encryption' => $email_settings ? $email_settings->mail_encryption : env('MAIL_ENCRYPTION', 'tls')]);
        config(['mail.mailers.smtp.username' => $email_settings ? $email_settings->mail_username : env('MAIL_USERNAME')]);
        config(['mail.mailers.smtp.password' => $email_settings ? $email_settings->mail_password : env('MAIL_PASSWORD')]);
        config(['mail.from.address' => $email_settings ? $email_settings->mail_from_address :  env('MAIL_FROM_ADDRESS', 'hello@example.com')]);
        config(['mail.from.name' => $email_settings ? $email_settings->mail_from_name : env('MAIL_FROM_NAME', 'Example')]);
    }
}
