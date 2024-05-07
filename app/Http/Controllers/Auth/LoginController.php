<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Mail\UserEmailVerificationMail;
use Carbon\Carbon;
use Mail;
use Response;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * for login page
     */
    public function showLoginForm() {
        return redirect()->back();
        // return view('auth.login');
    }

    /**
     * for user login
     */
    public function login(Request $request) {
        // dump($request->all());
        if (is_numeric($request->email)):
            $validator = Validator::make($request->all(), [
                'email' => 'required|numeric',
                'password' => 'required|string|min:8',
            ]);
        else:
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ]);
        endif;

        if ($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => $validator->errors(),
            ]);
        }

        try{
            if (is_numeric($request->email)) {
                $user = User::where('phone',$request->email)->where('status','!=','D')->first();
                $request['email'] = $user->email;
            } else {
                $user = User::where('email',$request->email)->where('status','!=','D')->first();
            }

            // if email not found in db throw error msg
            if(empty($user)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'The email is not regsitered with us. SignUp to continue.',
                ]);
            }

            // if email not verified throw error msg
            if($user->is_email_verify == 'N') {
                \Session::put('email', $user->email);

                //generate otp
                $otp = $this->genarateEmailOtp();

                //send mail
                $data['otp'] = $otp;
                $data['msg'] = 'Your email verification code is '.$otp.'.';
                $data['subject'] = 'User Email Verification OTP Mail';
                $data['name'] = $user->name;
                $data['url'] = route('home1');
                Mail::to($user->email)->send(new UserEmailVerificationMail($data));

                //store in db
                $user->email_vcode = $otp;
                // $user->otp_sent_at = Carbon::now();
                $user->save();
                return response()->json([
                    'status' => 'error',
                    'verify' => 'email',
                    'msg' => 'OTP sent on registered email for email verification!',
                    'message' => 'Your email has not been verified. Please verify email.',
                ]);
            }

            // if phone not verified throw error msg
            if($user->is_phone_verify == 'N') {
                \Session::put('phone', $user->phone);

                //generate otp
                $otp = $this->genaratePhoneOtp();

                //send sms
                $data['otp'] = $otp;
                $data['msg'] = 'Your phone verification code is '.$otp.'.';
                $data['subject'] = 'User Phone Verification OTP Mail';
                $data['name'] = $user->name;
                $data['url'] = "{{ route('home1') }}";
                Mail::to($user->email)->send(new UserEmailVerificationMail($data));

                //store in db
                $user->phone_vcode = $otp;
                // $user->otp_sent_at = Carbon::now();
                $user->save();
                return response()->json([
                    'status' => 'error',
                    'verify' => 'phone',
                    'msg' => 'OTP sent on registered email for phone verification!',
                    'message' => 'Your phone has not been verified. Please verify phone no.',
                ]);
            }

            //if not approved by admin throw error msg
            if($user->is_approved == 'N'){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your account not approve by Admin, waiting for admin approval.',
                ]);
            }

            // if status has I or Deactive throw error msg
            if($user->status == 'I'){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your account is temporarily deactivated by admin. Please activate account to continue.',
                ]);
            }

            $remember_me = $request->has('remember') ? true : false;

            $credentials = request(['email','password']);
            $credentials['status'] = 'A';
            // dd($user);

            if(Auth::attempt($credentials,$remember_me)){

                //User login successful
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successfully.',
                    'data' => $user,
                    'redirectTo' => route('my.account')
                ]);
                // return redirect()->route('home');
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials! Please enter a valid email and password.',
                ]);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        // if (is_numeric($request->email)) {
        //     $user = User::where('phone',$request->email)->where('status','!=','D')->first();
        //     $request['email'] = $user->email;
        // } else {
        //     $user = User::where('email',$request->email)->where('status','!=','D')->first();
        // }

        // if email not found in db throw error msg
        // if(empty($user)){
        //     // \Session::flash('error', 'The email is not regsitered with us. SignUp to continue');
        //     // return redirect()->back();
        //     return response()->json([
        //         "code"=> 403,
        //         'status' => 'error',
        //         'message' => 'The email is not regsitered with us. SignUp to continue.',
        //     ],403);
        // }

        // if email not verified throw error msg
        // if($user->email_verified_at == ''/*  || $user->status == 'U' */) {
        //     // \Session::put('email', $user->email);
        //     // return redirect()->route('resend.email.verify');
        //     return response()->json([
        //         "code"=> 403,
        //         'status' => 'error',
        //         'message' => 'Your email has not been verified. Please verify email.',
        //     ],403);
        // }

        // if phone not verified throw error msg
        // if($user->phone_verified_at == ''/*  || $user->status == 'U' */) {
        //     // \Session::put('phone', $user->phone);
        //     // return redirect()->route('resend.phone.verify');
        //     return response()->json([
        //         "code"=> 403,
        //         'status' => 'error',
        //         'message' => 'Your phone has not been verified. Please verify phone no.',
        //     ],403);
        // }

        //if not approved by admin throw error msg
        // if($user->is_approved == 'N'){
        //     // \Session::flash('error', 'Your account not approve by Admin, waiting for admin approval!');
        //     // return redirect()->back();
        //     return response()->json([
        //         "code"=> 403,
        //         'status' => 'error',
        //         'message' => 'Your account not approve by Admin, waiting for admin approval.',
        //     ],403);
        // }

        // if status has I or Deactive throw error msg
        // if($user->status == 'I'){
        //     // \Session::flash('error', 'Your account is temporarily deactivated by admin. Please activate account to continue!');
        //     // return redirect()->back();
        //     return response()->json([
        //         "code"=> 403,
        //         'status' => 'error',
        //         'message' => 'Your account is temporarily deactivated by admin. Please activate account to continue.',
        //     ],403);
        // }

        // $remember_me = $request->has('remember') ? true : false;

        // $credentials = request(['email','password']);

        // if(Auth::attempt($credentials,$remember_me)){
        //     // $user = User::where('email',filter_var($request->input('email'), FILTER_VALIDATE_EMAIL))->first();

        //     //User login successful
        //     return response()->json([
        //         "code"=> 200,
        //         'status' => 'success',
        //         'message' => 'fetch successfully.',
        //         'data' => @$userData,
        //         // 'auth_token' => @$token,
        //     ],200);
        //     // return redirect()->route('home');
        // }else{
        //     // \Session::flash('error', 'Invalid credentials! Please enter a valid email and password.');
        //     // return redirect()->back();
        //     return response()->json([
        //         "code"=> 403,
        //         'status' => 'error',
        //         'message' => 'Invalid credentials! Please enter a valid email and password.',
        //     ],403);
        // }
    }

    /**
     * View registration from
     */
    public function logout(){
        @Auth::logout();
        \Session::flush();
        return redirect()->route('home1');
    }

    /**
     * unique otp genarate
     */
    private function genarateEmailOtp() {
        $otp = rand(1111,9999);
        $check = User::where('email_vcode',$otp)->first();
        if(@$check) {
            return $this->genarateEmailOtp();
        } else {
            return $otp;
        }
    }

    /**
     * unique otp genarate
     */
    private function genaratePhoneOtp() {
        $otp = rand(1111,9999);
        $check = User::where('phone_vcode',$otp)->first();
        if(@$check) {
            return $this->genaratePhoneOtp();
        } else {
            return $otp;
        }
    }
}
