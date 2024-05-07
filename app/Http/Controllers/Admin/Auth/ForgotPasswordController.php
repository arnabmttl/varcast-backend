<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Exception;
use App\Models\Admin;
use App\Mail\AdminResetPasswordMail;
use Session;
use Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your admins. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.guest:admin');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('admin.auth.passwords.email');
    }
    public function sendResetLinkEmail(Request $request){
        $request->validate(['email' => 'required|email']);
        try{
            $checkDataOnadmin = Admin::where('email',@$request->email)->first();
            if(empty($checkDataOnadmin)){
                Session::flash('error', "We can't find a admin with that email address.");
                return redirect()->back();
            }
            $up['remember_token'] = Str::random(32);
            $checkDataOnadmin->update($up);
            $user = Admin::whereId(@$checkDataOnadmin->id)->first();
            $email = new AdminResetPasswordMail($user);
            Mail::to(@$user->email)->send(@$email);
            Session::flash('success', "We have emailed your password reset link!"); 
            return redirect()->back();
        }
        catch (Exception $e) {
            return $e;
            Session::flash('error', "Sorry a problem has occurred."); 
            return redirect()->back();
        }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('admins');
    }
}
