<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Mail\UserEmailVerificationMail;
use App\Mail\PasswordReset;
use Carbon\Carbon;
use Response;
use Session;
use Mail;
use Helper;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,temp_email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * for registration purpose
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:100',
            // 'first_name' => 'required|string|max:255',
            // 'last_name' => 'required|string|max:255',
            // 'address' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
            'type' => 'required|in:C,V',
            'email' =>  [
                'required','string','email','max:199',
                Rule::unique('users','email')->where(function($query) use ($request){
                    @$query->where('status','!=','D');
                }),
                Rule::unique('users','temp_email')->where(function($query) use ($request){
                    @$query->where('status','!=','D');
                }),
            ],
            'phone' => [
                'required','string','numeric','digits_between:10,15',
                Rule::unique('users')->where(function($query) use ($request){
                    @$query->where('status','!=','D');
                }),
                Rule::unique('users','temp_phone')->where(function($query) use ($request){
                    @$query->where('status','!=','D');
                })
            ],
        ],[
            'type.required' => 'Please select user type'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => $validator->errors(),
            ]);
        }
        // dd($request->all());

        try {
            //generate otp
            $otp = $this->genarateEmailOtp();

            // $new['name'] = $request->first_name.' '.$request->last_name;
            $new['name'] = $request->name;
            // $new['address'] = nl2br($request->address);
            $new['password'] = Hash::make($request->password);
            $new['email'] = $request->email;
            $new['phone'] = $request->phone;
            $new['type'] = $request->type;
            $new['email_vcode'] = $otp;
            $new['phone_vcode'] = $otp;
            if(@$request->type == 'C'){
                $new['is_approved'] = 'Y';
            }
            else{
                $new['is_approved'] = 'N';
            }
            $a = User::create($new);

            // send mail to user for verification
            $data['msg'] = 'Your email verification code is '.$otp.'.';
            $data['subject'] = 'User Email Verification OTP Mail';
            $data['name'] = $a->name;
            $data['url'] = route('home1');

            Mail::to($a->email)->send(new UserEmailVerificationMail($data));

            \Session::put('email', $a->email);
            \Session::put('phone', $a->phone);
            return response()->json([
                'status' => 'success',
                'msg' => 'OTP sent on registered email for verification.',
                'message' => 'Registration successfully.',
            ]);
            // Session::flash('success',"User registered successfully. OTP sent on registered email for verification!");
            // return view('auth.email_verify');
            // return redirect()->route('verification.notice');
        } catch(\Exception $e) {
            // Session::flash('error',@$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * for checking email address
     */
    public function checkEmail(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        if ($validated->fails()) {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
            // return response()->json($validated->messages(), 400);
            // foreach($validator->errors()->messages() as $key => $value){
            //     return response()->json(['status' => $value[0]], 400);
            // }
        }

        try {
            $check = User::where('status', '!=', 'D')->where(function($query) use ($request){
                $query->where('email', $request->email)
                ->orWhere('temp_email', $request->email);
            })->first();
            if(@$check) {
                $response['message'] = 'Email - '.$request->email.' already exists!';
                $response['status'] = 'fail';
            } else {
                $response['message'] = 'Email avaliable!';
                $response['status'] = 'success';
            }
            return response()->json($response);
        } catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
    }

    /**
     * for checking phone address
     */
    public function checkPhone(Request $request) {
        $validated = $request->validate([
            'phone' => 'required|digits_between:8,15'
        ]);

        // if ($validated->fails()) {
        //     return response()->json($validated->messages(), 400);
        // }

        try {
            $check = User::where('status', '!=', 'D')->where(function($query) use ($request){
                $query->where('phone', $request->phone)
                ->orWhere('temp_phone', $request->phone);
            })->first();
            if(@$check) {
                $response['message'] = 'Phone number - '.$request->phone.' already exists!';
                $response['status'] = 'fail';
            } else {
                $response['message'] = 'Phone number avaliable!';
                $response['status'] = 'success';
            }
            return response()->json($response);
        } catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
    }

    /**
     * for email verification form page
     */
    public function emailVerification() {
        return view('auth.email_verify');
    }

    /**
     * for phone verification form page
     */
    public function phoneVerification() {
        return view('auth.phone_verify');
    }

    /**
     * for resend email otp
     */
    public function resendEmailVcode(Request $request) {
        try{
            $email = $request->session()->get('email');
            if(@$email) {
                $user = User::where('email', $email)->first();
                //generate otp
                $otp = $this->genarateEmailOtp();

                //send mail
                $data['otp'] = $otp;
                $data['msg'] = 'Your email verification code is '.$otp.'.';
                $data['subject'] = 'User Email Verification OTP Mail';
                $data['name'] = $user->name;
                $data['url'] = route('home1');
                Mail::to($email)->send(new UserEmailVerificationMail($data));

                //store in db
                $user->email_vcode = $otp;
                // $user->otp_sent_at = Carbon::now();
                $user->save();

                // \Session::flash('success', 'OTP sent on registered email for verification!');
                // return view('auth.email_verify');
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP sent on registered email for email verification.',
                ]);
            } else {
                //return redirect()->route('login');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something wents to wrong. Please try again.',
                ]);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * for resend phone otp
     */
    public function resendPhoneVcode(Request $request) {
        try{
            $phone = $request->session()->get('phone');
            if(@$phone) {
                $user = User::where('phone', $phone)->first();
                //generate otp
                $otp = $this->genaratePhoneOtp();

                //send sms
                $data['otp'] = $otp;
                $data['msg'] = 'Your phone verification code is '.$otp.'.';
                $data['subject'] = 'User Phone Verification OTP Mail';
                $data['name'] = $user->name;
                $data['url'] = "{{ route('phone.verify') }}";
                Mail::to($user->email)->send(new UserEmailVerificationMail($data));

                //store in db
                $user->phone_vcode = $otp;
                // $user->otp_sent_at = Carbon::now();
                $user->save();

                // \Session::flash('success', 'OTP sent on registered phone for verification!');
                // return view('auth.phone_verify');
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP sent on registered email for phone verification.',
                ]);
            } else {
                // return redirect()->route('login');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something wents to wrong. Please try again.',
                ]);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * for email opt verification
     */
    public function emailVerify(Request $request) {
        //Validation rules and custom validation messages
        $validator = Validator::make($request->all(),[
            'email_vcode' => 'required|digits:4',
        ],
        [
            'email_vcode.required' => 'The otp field is required',
            'email_vcode.digits' => 'The otp Length must be 4 digits.'
        ]);

        //validate and throw error messages
        if($validator->fails()){
            // $errors = $validator->errors();
            // return redirect()->back()->withErrors($errors);
            return response()->json([
                'status' => 'errors',
                'message' => $validator->errors(),
            ]);
        }
        try {
            $user = User::where('email_vcode', @$request->email_vcode)->first();
            if(@$user) {
                $user->email_vcode = null;
                // $user->otp_sent_at = null;
                $user->is_email_verify = 'Y';
                $user->status = 'A';
                $user->email_verified_at = Carbon::now();
                $user->save();
                \Session::forget('email');
                // \Session::flash('success', 'Email verified successfully!');
                // return redirect()->route('login');
                return response()->json([
                    'status' => 'success',
                    'message' => 'Email verified successfully!',
                    'data' => @$user
                ]);
            } else {
                // \Session::flash('error', 'Invalid OTP!');
                // \Session::forget('success');
                // return redirect()->route('frontend.otp1');
                // return view('auth.email_verify');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP!',
                ]);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * for phone opt verification
     */
    public function phoneVerify(Request $request) {
        //Validation rules and custom validation messages
        $validator = Validator::make($request->all(),[
            'phone_vcode' => 'required|digits:4',
        ],
        [
            'phone_vcode.required' => 'The otp field is required',
            'phone_vcode.digits' => 'The otp Length must be 4 digits.'
        ]);

        //validate and throw error messages
        if($validator->fails()){
            // $errors = $validator->errors();
            // return redirect()->back()->withErrors($errors);
            return response()->json([
                'status' => 'errors',
                'message' => $validator->errors(),
            ]);
        }

        try {
            $user = User::where('phone_vcode', $request->phone_vcode)->first();
            if(@$user) {
                if($user->is_email_verify=='Y' && $user->email_verified_at!=null) {
                    $user->status = 'A';
                }
                $user->phone_vcode = null;
                // $user->otp_sent_at = null;
                $user->is_phone_verify = 'Y';
                $user->phone_verified_at = Carbon::now();
                $user->save();
                \Session::forget('phone');
                // \Session::flash('success', 'Phone verified successfully!');
                // return redirect()->route('login');
                if(@$user->type == 'V'){
                    $message = 'Phone verified successfully! Your account waiting for admin approval.';
                }
                else{
                    $message = 'Phone verified successfully!';
                }
                return response()->json([
                    'status' => 'success',
                    'message' => @$message,
                ]);
            } else {
                // \Session::flash('error', 'Invalid OTP!');
                // \Session::forget('success');
                // return redirect()->route('frontend.otp1');
                // return view('auth.phone_verify');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP!',
                ]);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
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
