<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Session;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * for password reset form
     */
    public function showResetForm(Request $request) {
        $data['token'] = $request->session()->get('token');
        return view('auth.passwords.reset',$data);
    }

    /**
     * for password reset
     */
    public function reset(Request $request) {
        $validator = Validator::make($request->all(),[
            'password' => 'required|min:8|max:15|confirmed',
            'password_confirmation' => 'required',
            'token' => 'required',
        ],[
            'token.required' => 'Please try again',
        ]);
        if($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => $validator->errors(),
            ]);
        }
        // dd($request->all());
        try {
            //generate otp
            $user = User::where('verify_token', $request->token)->first();
            if(@$user) {

                $user->password = Hash::make($request->password);
                $user->verify_token = null;
                $user->save();

                \Session::forget('token');
                // \Session::flash('success',"Password reset successfully. Now you can login your new password.");
                // return redirect()->route('login');
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password reset successfully. Now you can login your new password.',
                ]);
            } else {
                // \Session::flash('error',"Password reset token expire.");
                // return redirect()->back();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password reset token expire.',
                ]);
            }

        } catch(\Exception $e) {
            // Session::flash('error',@$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
