<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\UserEmailVerificationMail;
use Carbon\Carbon;
use Response;
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
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * for password reset form
     */
    public function showLinkRequestForm() {
        return view('auth.passwords.email');
    }

    /**
     * for password reset otp form
     */
    public function showOTPForm() {
        return view('auth.otp_verify');
    }

    /**
     * password reset otp send
     */
    public function sendResetLinkEmail(Request $request) {
        // dd($request->all());
        if (is_numeric($request->email)):
            $validator = Validator::make($request->all(), [
                'email' => 'required|numeric',
            ]);
        else:
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
        endif;
        if($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => $validator->errors(),
            ]);
        }

        try {
            if (is_numeric($request->email)) {
                $user = User::where('phone',$request->email)->where('status','!=','D')->first();
                if(!empty($user)){
                    if(@$user->is_phone_verify == 'N'){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Your phone no not verified. Please verify then continue.',
                        ]);
                    }
                }
            } else {
                $user = User::where('email',$request->email)->where('status','!=','D')->first(); 
                if(!empty($user)){
                    if(@$user->is_email_verify == 'N'){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Your email not verified. Please verify then continue.',
                        ]);
                    }
                }
            }

            // if email not found in db throw error msg
            if(empty($user)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'The email is not regsitered with us. SignUp to continue.',
                ]);
            }

            // $user = User::where('phone', $phone)->first();
            //generate otp
            $otp = $this->genarateOtp();

            //send sms
            $data['otp'] = $otp;
            $data['msg'] = 'Your forgot password verification code is '.$otp.'.';
            $data['subject'] = 'User Reset Password OTP Mail';
            $data['name'] = $user->name;
            $data['url'] = "{{ route('password.reset.otp') }}";
            \Mail::to($user->email)->send(new UserEmailVerificationMail($data));

            //store in db
            $user->email_vcode = $otp;
            $user->phone_vcode = $otp;
            // $user->otp_sent_at = Carbon::now();
            $user->save();
            \Session::put('email', $user->email);
            \Session::put('phone', $user->phone);
            // dump(1);
            // return redirect()->route('password.reset.otp');
            // return view('auth.otp_verify');
            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent on registered email for OTP verification.',
            ]);
        } catch(\Exception $e) {
            // Session::flash('error',@$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * password reset otp form
     */
    public function resetResendOTP(Request $request) {
        try {
            $email = $request->session()->get('email');
            $phone = $request->session()->get('phone');
            if(@$email) {
                $user = User::where('email', $email)->where('status','!=','D')->first();

                // if email not found in db throw error msg
                if(empty($user)){
                    \Session::flash('error', 'The email is not regsitered with us. SignUp to continue');
                    return redirect()->route('login');
                }
                //generate otp
                $otp = $this->genarateOtp();

                //send mail
                $data['otp'] = $otp;
                $data['msg'] = 'Your forgot password verification code is '.$otp.'.';
                $data['subject'] = 'User Reset Password OTP Mail';
                $data['name'] = $user->name;
                $data['url'] = route('password.reset.otp');
                Mail::to($email)->send(new UserEmailVerificationMail($data));

                //store in db
                $user->email_vcode = $otp;
                $user->phone_vcode = $otp;
                // $user->otp_sent_at = Carbon::now();
                $user->save();

                // \Session::flash('success', 'OTP sent on registered email for verification!');
                // return redirect()->route('password.reset.otp');
                // return view('auth.otp_verify');
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP sent on registered email for OTP verification.',
                ]);
            } else if(@$phone) {
                $user = User::where('phone', $phone)->where('status','!=','D')->first();

                // if email not found in db throw error msg
                if(empty($user)){
                    \Session::flash('error', 'The phone number is not regsitered with us. SignUp to continue');
                    return redirect()->route('login');
                }
                //generate otp
                $otp = $this->genarateOtp();

                //send mail
                $data['otp'] = $otp;
                $data['msg'] = 'Your forgot password verification code is '.$otp.'.';
                $data['subject'] = 'User Reset Password OTP Mail';
                $data['name'] = $user->name;
                $data['url'] = route('password.reset.otp');
                Mail::to($email)->send(new UserEmailVerificationMail($data));

                //store in db
                // $user->otp = $otp;
                $user->email_vcode = $otp;
                $user->phone_vcode = $otp;
                // $user->otp_sent_at = Carbon::now();
                $user->save();

                // \Session::flash('success', 'OTP sent on registered phone for verification!');
                // return redirect()->route('password.reset.otp');
                // return view('auth.otp_verify');
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP sent on registered email for OTP verification.',
                ]);
            } else {
                // return redirect()->route('login');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something wents to wrong. Please try again.',
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

    /**
     * password reset otp form
     */
    public function otpVerify(Request $request) {
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'otp' => 'required'
        ]);
        if($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => $validator->errors(),
            ]);
        }
        try {
            $user = User::where('email_vcode',$request->otp)->where('phone_vcode',$request->otp)->first();

            // if email not found in db throw error msg
            if(empty($user)){
                // \Session::flash('error', 'Invalid OTP');
                // return redirect()->back();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP!',
                ]);
            }

            // update user db
            $user->email_vcode = null;
            $user->phone_vcode = null;
            // $user->otp_sent_at = null;
            $user->verify_token = time().uniqid();
            $user->save();
            \Session::forget('email');
            \Session::forget('phone');
            \Session::put('token', $user->verify_token);
            // return redirect()->route('password.reset');
            // return view('auth.otp_verify');
            return response()->json([
                'status' => 'success',
                'token' => $user->verify_token,
                'message' => 'OTP verified successfully!',
            ]);
        } catch(\Exception $e) {
            // Session::flash('error',@$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * unique otp genarate
     */
    private function genarateOtp() {
        $otp = rand(1111,9999);
        $check = User::where('email_vcode',$otp)->orWhere('phone_vcode',$otp)->first();
        if(@$check) {
            return $this->genarateOtp();
        } else {
            return $otp;
        }
    }
}
