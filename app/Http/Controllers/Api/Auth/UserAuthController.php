<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Business;
use JWTAuth;
use Illuminate\Support\Facades\Hash;
use Helper;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Mail;
use Storage;
class UserAuthController extends Controller
{
    public function __construct(Request $request)
    {
        // $this->middleware('jwt.auth')->except(['login','Vendorlogin','getUser','getVendor','register']);
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', @$token);
    }

    // user login
    public function login(Request $request)
    { 
        if (is_numeric($request->email)):
            $validator = Validator::make($request->all(), [
                'email' => 'required|numeric',
                'password' => 'required|string|min:6',
            ]);
        else:
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);
        endif;
        if ($validator->fails()) {
            return response()->json([
                "code"=> 200,
                'status' => 'error',
                'message' => @$validator->errors()->first()
            ],200);
        }
        // dd($request->all());
        try{
            @auth()->shouldUse('web');
            if (is_numeric(@$request->email)) {
                @$user = User::where('phone',@$request->email)->where('status','!=','D')->first();
                $request['email'] = @$user->email;
            } else {
                $user = User::where('email',@$request->email)->where('status','!=','D')->first();
            }
            // dd($user);
            if(!empty(@$user)){ 
                
                $userData = [
                    'name' => @$user->name,
                    // 'type' => @$user->type,
                    'email' => @$user->email,
                    'phone' => @$user->phone,
                    'is_phone_verify' => @$user->is_phone_verify,
                    'is_email_verify' => @$user->is_email_verify,
                    'status' => @$user->status,
                    // 'is_approved' => @$user->is_approved,
                ];
                if(@$user->is_email_verify == 'N'){
                    $otpCode = sprintf("%04d", mt_rand(1, 9999));
                    $mailTo = [@$user['email']];
                    $mailToName = [@$user['name']];
                    $mailData = [
                        'subject' => @trans('success.otp_confirmation'),
                        'to_mail' => @$mailTo,
                        'to_mail_name' => @$mailToName,
                        'short_title' => @trans('success.otp_confirmation'),
                        'body' => 'OTP:-'.@$otpCode,
                    ];
                    Helper::sendMailToUser(@$mailData);
                    @$user->update(['email_vcode' => @$otpCode]);
                    return response()->json([
                        "code"=> 200,
                        'status' => 'error',
                        'message' => @trans('error.email_verify'),
                        'data' => @$userData,
                    ],200);
                }
                else if(@$user->is_phone_verify == 'N'){
                    $otpCode = sprintf("%04d", mt_rand(1, 9999));
                    $mailTo = [@$user['email']];
                    $mailToName = [@$user['name']];
                    $mailData = [
                        'subject' => @trans('success.otp_confirmation'),
                        'to_mail' => @$mailTo,
                        'to_mail_name' => @$mailToName,
                        'short_title' => @trans('success.otp_confirmation'),
                        'body' => 'OTP:-'.@$otpCode,
                    ];
                    Helper::sendMailToUser(@$mailData);
                    @$user->update(['phone_vcode' => @$otpCode]);
                    return response()->json([
                        "code"=> 200,
                        'status' => 'error',
                        'message' => @trans('error.phone_verify'),
                        'data' => @$userData,
                    ],200);
                }
                else if(@$user->status == 'I'){
                    return response()->json([
                        "code"=> 200,
                        'status' => 'inactive_account',
                        'message' => @trans('error.inactive_account'),
                        'data' => @$userData,
                    ],200);
                }
                else if(@$user->status == 'U'){
                    return response()->json([
                        "code"=> 200,
                        'status' => 'error',
                        'message' => @trans('error.verify_account'),
                        'data' => @$userData,
                    ],200);
                }
                else if(@$user->is_approved == 'N'){
                    return response()->json([
                        "code"=> 200,
                        'status' => 'error',
                        'message' => @trans('error.upapprove_account'),
                        'data' => @$userData,
                    ],200);
                }
                
                
                $credentials = [
                    'email' => @$request->email,
                    'password' => @$request->password,
                    'status' => 'A'
                ];

                // $token = JWTAuth::attempt($credentials, ['exp' => Carbon::now()->addDays(7)->timestamp]);
                $remember_me = $request->has('remember') ? true : false;
                // $remember_me = ['exp' => Carbon::now()->addDays(7)->timestamp];
                // dd($remember_me);
                if(JWTAuth::attempt($credentials,$remember_me)){
                    $token = JWTAuth::fromUser(@$user); 
                    // $userData = [
                    //     'name' => @$user->name,
                    //     'type' => @$user->type,
                    //     'email' => @$user->email,
                    //     'phone' => @$user->phone,
                    //     'is_phone_verify' => @$user->is_phone_verify,
                    //     'is_email_verify' => @$user->is_email_verify,
                    //     'status' => @$user->status,
                    //     'is_approved' => @$user->is_approved,
                    // ];
                    if(@$user->type == 'V'){ 
                        $business_data = Business::where('vendor_id',@$user->id)->where('status','!=','D')->first();
                        if(!empty(@$business_data)){
                            $user['is_business'] = 'Y';
                        }
                        else{
                            $user['is_business'] = 'N';
                        }
                    }
                    if(!empty(@$request->device_token)){
                        Helper::storeDeviceTokenUserWise(@$user->id, @$request->device_token);
                        // $notiData['diviceToken'] = [@$request->device_token];
                        // $notiData['title'] = 'Login successfully.';
                        // $notiData['body'] = 'Your account login successfully.';
                        // Helper::sendNotification(@$notiData);
                    }
                    return response()->json([
                        "code"=> 200,
                        'status' => 'success',
                        'message' => @trans('success.fetch'),
                        'data' => @$user,
                        'auth_token' => @$token,
                    ],200);
                }
                else{
                    return response()->json([
                        "code"=> 200,
                        'status' => 'error',
                        'message' => @trans('error.invalid_credentials'),
                    ],200);
                }
            }
            else{
                return response()->json([
                    "code"=> 200,
                    'status' => 'error',
                    'message' => @trans('error.invalid_credentials'),
                ],200);
            }
        }
        catch(\Exception $e)
        {
            return response()->json([
                "code"=> 403,
                'status' => 'error',
                'message' => $e->getMessage(),
            ],403);
        }
    }
    // get user details
    public function getUser(Request $request){
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    "code"=> 200,
                    'status' => 'error',
                    'message' => @trans('error.not_found'),
                ], 200);
            }
            else{
                if($user->status == 'I')
                {
                    return response()->json([
                        'status' => false,
                        'message' => "Inactive user",
                        'data' =>  (object)[]
                    ],200);
                }
                $userId = $user->_id;
                $latest = 10;
                $data = User::select('_id','name','phone','email','username','gender')->where('_id', $userId)->first();
                $count_podcasts = \DB::connection('mongodb')->collection('podcasts')->where('userId', $userId)->count();
                $count_videos = \DB::connection('mongodb')->collection('videos')->where('userId', $userId)->count();
                $count_followings = \DB::connection('mongodb')->collection('follows')->where('authId', $userId)->count();
                $count_followers = \DB::connection('mongodb')->collection('follows')->where('userId', $userId)->count();

                $latest_podcasts = \App\Models\Podcast::where('userId', $userId)->orderBy('_id', 'desc')->take($latest)->get();
                $latest_videos = \App\Models\Video::where('userId', $userId)->orderBy('_id', 'desc')->take($latest)->get();
                $latest_followings = \App\Models\Follow::where('userId', $userId)->with('followings:_id,name,email,phone')->orderBy('_id', 'desc')->take($latest)->get();
                $latest_followers = \App\Models\Follow::where('userId', $userId)->with('followers:_id,name,email,phone')->orderBy('_id', 'desc')->take($latest)->get();

                $data->count_podcasts = $count_podcasts;
                $data->count_videos = $count_videos;
                $data->count_followings = $count_followings;
                $data->count_followers = $count_followers;
                $data->latest_podcasts = $latest_podcasts;
                $data->latest_videos = $latest_videos;
                $data->latest_followings = $latest_followings;
                $data->latest_followers = $latest_followers;


                return response()->json([
                    'status' => true,
                    'message' => "My Profile",
                    'data' => $data
                ],200);
            }
        }
        catch(\Exception $e)
        {
            return response()->json([
                "code"=> 403,
                'status' => 'token_expire',
                'message' => $e->getMessage(),
            ],403);
        }
    }

    // both end registration 
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'  => 'required|string|max:199',
            'email' =>  [
                'required','string','email','max:199',
                // Rule::unique('users','email')->where(function($query) use ($request){
                //     @$query->where('status','!=','D');
                // }),
            ],
            'phone' => [
                'required','string','numeric','digits_between:10,15',
                // Rule::unique('users','phone')->where(function($query) use ($request){
                //     @$query->where('status','!=','D');
                // }),                
            ],
            'password' => 'required|string|min:6',
            'register_for' => 'required|in:app,google,apple',
            'country_id' => 'required|string',
            'dob' => 'required|date',
            'gender' => 'required|in:M,F,O',
            'govt_id_card' => 'nullable',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                "code"=> 200,
                'status' => 'error',
                'message' => @$validator->errors()->first()
            ],200);
        }

        // dd($request->all());
        try{
            $otpCode = sprintf("%04d", mt_rand(1, 9999));
            $data = [
                'name' => @$request->name,
                'email' => @$request->email,
                'phone' => @$request->phone,
                'username' => $this->generateUniqueUserName(@$request->name),
                // 'address' => @$request->address,
                'password' => Hash::make(@$request->password),
                'email_vcode' => @$otpCode,
                'phone_vcode' => @$otpCode,
                'is_phone_verify' => 'N',
                'is_email_verify' => 'N',
                'status' => 'A',
                // 'is_approved' =>  @$isApproved,
                'register_for' =>  @$request->register_for,
                'country_id' =>  @$request->country_id,
                'dob' =>  @$request->dob,
                'gender' =>  @$request->gender,
            ];
            if ($request->hasFile('govt_id_card')) {
                $file      = $request->file('govt_id_card');
                $time      = Carbon::now();
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->storeAs('public/documents', @$filename);
                $data['govt_id_card'] = $filename;
            }
            $create = User::create(@$data);
            if(!empty(@$create)){
                $this->sendNotification($data);
                if(!empty(@$request->device_token)){
                    Helper::storeDeviceTokenUserWise(@$create->_id, @$request->device_token);
                }
                return response()->json([
                    "code"=> 200,
                    'status' => 'success',
                    'message' => @trans('success.registration_success'),
                    'data' => @$create
                ],200);
            }
            else{
                return response()->json([
                    "code"=> 200,
                    'status' => 'error',
                    'message' => @trans('error.problem'),
                ],200);
            }
        }
        catch(\Exception $e)
        {
            return response()->json([
                "code"=> 403,
                'status' => 'error',
                'message' => $e->getMessage(),
            ],403);
        }
    }
    public function generateUniqueUserName($name)
    {
        $name_parts = explode(" ", @$name);
        do {
            $username = "@".strtolower(@$name_parts[0]).rand(1,999999999);
        } while (User::where("username", "=", $username)->first());
        return $username;
    }

    // notification send
    private function sendNotification($data,$type=null){
        try{
            $mailTo = [@$data['email']];
            $mailToName = [@$data['name']];
            if(App::getLocale() == 'en'){
                $mailData = [
                    'mail_type' => "registration",
                    'subject' => 'Hi, '.@$data['name']." - Your registration is successful.",
                    'mail_title' => "Welcome to VARCAST !",
                    'to_mail' => @$mailTo,
                    'otp' => @$data['email_vcode'],
                    'to_mail_name' => @$mailToName,
                    'short_title' => "Thank You, ".@$data['name']." - register with us.",
                    'body' => "Designing a successful mobile app announcement email isn't easy, as you should balance your app branding guidelines and crazy creativity.",
                ];
            }
            else{
                $mailData = [
                    'mail_type' => "registration",
                    'subject' => 'مرحبًا '.@$data['name'].' - لقد تم تسجيلك بنجاح.',
                    'mail_title' => "مرحبًا بك في فاركاست!",
                    'to_mail' => @$mailTo,
                    'to_mail_name' => @$mailToName,
                    'otp' => @$data['email_vcode'],
                    'short_title' => "شكرًا لك ".@$data['name']." - سجل معنا.",
                    'body' => "إن تصميم بريد إلكتروني ناجح لإعلان تطبيق الهاتف المحمول ليس بالأمر السهل، حيث يجب عليك الموازنة بين إرشادات العلامة التجارية لتطبيقك والإبداع المجنون.",
                ];
            }
            Helper::sendMailToUser(@$mailData);
        }
        catch(\Exception $e){
            return response()->json([
                'data' => [
                    "code"=> 403,
                    "status"  => 'catch_error',
                    "message" => $e->getMessage(),
                ],
            ], 403);
        }
    }

    // user verify account
    public function verifyAccount(Request $request){
        if (is_numeric(@$request->email)):
            $validator = Validator::make($request->all(), [
                'email' => 'required|numeric',
                'otp' => 'required|numeric',
                'type' => 'required|in:forgot_password,account_verification',
            ]);
        else:
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'otp' => 'required|numeric',
                'type' => 'required|in:forgot_password,account_verification',
            ]);
        endif;
        if ($validator->fails()) {
            return response()->json([
                "code"=> 200,
                'status' => 'error',
                'message' => @$validator->errors()->first()
            ],200);
        }
        try{
            if (is_numeric(@$request->email)) {
                if(@$request->type == 'forgot_password'){
                    @$user = User::where('phone',@$request->email)->where('phone_vcode',@$request->otp)->where('status','!=','D')->first();                    
                    $message = @trans('success.password_change');
                }
                else{
                    @$user = User::where('phone',@$request->email)->where('phone_vcode',@$request->otp)->where('status','!=','D')->where('is_phone_verify','N')->first();
                    $message = @trans('success.phone_otp_match');
                }
                $upData = [
                    'is_phone_verify' => 'Y',
                    'phone_vcode' => null,
                    'status' => 'A',
                    'phone_verified_at' => Carbon::now()
                ];
                
            } else {
                if($request->type == 'forgot_password'){
                    @$user = User::where('email',@$request->email)->where('email_vcode',@$request->otp)->where('status','!=','D')->first();
                    $message = @trans('success.password_change');;
                }
                else{
                    @$user = User::where('email',@$request->email)->where('email_vcode',@$request->otp)->where('status','!=','D')->where('is_email_verify','N')->first();
                    $message = @trans('success.email_otp_match');
                }
                $upData = [
                    'is_email_verify' => 'Y',
                    'email_vcode' => null,
                    'status' => 'A',
                    'email_verified_at' => Carbon::now()
                ];
                
            }
            if(!empty(@$user)){
                @$user->update(@$upData);
                @$user = User::where('_id',@$user->_id)->first();
                $userData = [
                    'name' => @$user->name,
                    'email' => @$user->email,
                    'phone' => @$user->phone,
                    'is_phone_verify' => @$user->is_phone_verify,
                    'is_email_verify' => @$user->is_email_verify,
                    'status' => @$user->status,
                    // 'is_approved' => @$user->is_approved,
                ];
                $token = '';
                if(@$user->is_phone_verify == 'Y' && @$user->is_email_verify == 'Y' && @$user->status == 'A'){
                    if(@$request->type != 'forgot_password'){
                        $token = JWTAuth::fromUser(@$user);
                    }
                }
                if(!empty($token)){
                    return response()->json([
                        "code"=> 200,
                        'status' => 'success',
                        'message' => @$message,
                        'data' => @$user,
                        'auth_token' => @$token,
                    ],200);
                } else{
                    return response()->json([
                        "code"=> 200,
                        'status' => 'success',
                        'message' => @$message,
                        'data' => @$userData,
                    ],200);
                }
            }
            else{
                return response()->json([
                    "code"=> 200,
                    'status' => 'error',
                    'message' => @trans('success.otp_not_match'),
                ],200);
            }
        }
        catch(\Exception $e)
        {
            return response()->json([
                "code"=> 403,
                'status' => 'error',
                'message' => $e->getMessage(),
            ],403);
        }
    }

    // both resent otp
    public function resentOtp(Request $request){
        if (is_numeric(@$request->email)):
            $validator = Validator::make($request->all(), [
                'email' => 'required|numeric',
                'otp_for' => 'required|in:forgot_password,account_verification'
            ]);
        else:
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'otp_for' => 'required|in:forgot_password,account_verification'
            ]);
        endif;
        if ($validator->fails()) {
            return response()->json([
                "code"=> 200,
                'status' => 'error',
                'message' => @$validator->errors()->first()
            ],200);
        }
        try{
            if (is_numeric(@$request->email)) {
                @$user = User::where('phone',@$request->email)->where('status','!=','D')->first();
            } else {
                @$user = User::where('email',@$request->email)->where('status','!=','D')->first();
            }
            if(!empty(@$user)){
                $otpCode = sprintf("%04d", mt_rand(1, 9999));
                if (is_numeric(@$request->email)) {
                    @$user->update(['phone_vcode' => @$otpCode]);
                }
                else{
                    @$user->update(['email_vcode' => @$otpCode]);
                }
                $mailTo = [@$user['email']];
                $mailToName = [@$user['name']];
                if(@$request->otp_for == 'account_verification') {
                    $mailData = [
                        'subject' => @trans('success.otp_confirmation'),
                        'to_mail' => @$mailTo,
                        'to_mail_name' => @$mailToName,
                        'short_title' => @trans('success.otp_confirmation'),
                        'body' => 'OTP:-'.@$otpCode,
                    ];
                }
                else{
                    $mailData = [
                        'mail_type' => "reset_password",
                        'subject' => @trans('success.reset_password'),
                        'mail_title' => @trans('success.reset_password'),
                        'to_mail' => @$mailTo,
                        'to_mail_name' => @$mailToName,
                        'otp' => @$otpCode,
                        'short_title' => @trans('success.reset_password_title'),
                        'body' => @trans('success.reset_password_body'),
                    ];
                }
                Helper::sendMailToUser(@$mailData);
                return response()->json([
                    "code"=> 200,
                    'status' => 'success',
                    'message' => @trans('success.resent_otp'),
                    'email' => @$request->email,
                    'token' => encrypt(@$user->_id),
                    'otp' => @$otpCode
                ],200);
            }
            else{
                return response()->json([
                    "code"=> 200,
                    'status' => 'error',
                    'message' => @trans('error.not_found'),
                ],200);
            }
        }
        catch(\Exception $e)
        {
            return response()->json([
                "code"=> 403,
                'status' => 'error',
                'message' => $e->getMessage(),
            ],403);
        }
    }


    // user reset password
    public function UserresetPassword(Request $request){
        if (is_numeric(@$request->email)):
            $validator = Validator::make($request->all(), [
                // 'email' => 'required|numeric',
                'otp' => 'required|numeric',
                'token' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ]);
        else:
            $validator = Validator::make($request->all(), [
                // 'email' => 'required|email',
                'otp' => 'required|numeric',
                'token' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ]);
        endif;
        if ($validator->fails()) {
            return response()->json([
                "code"=> 200,
                'status' => 'error',
                'message' => @$validator->errors()->first()
            ],200);
        }
        try{
            @$user = User::where('_id',decrypt(@$request->token))
            ->where('status','!=','D')
            ->where('is_email_verify','Y')
            ->first();
            $upData = [
                'password' => Hash::make(@$request->password),
                'email_vcode' => null,
            ];
            
            if(!empty(@$user)){
                if(@$user->email_vcode == @$request->otp){
                    @$user->update(@$upData);
                    return response()->json([
                        "code"=> 200,
                        'status' => 'success',
                        'message' => @trans('success.change_password'),
                    ],200);
                }
                else{
                    return response()->json([
                        "code"=> 200,
                        'status' => 'error',
                        'message' => @trans('success.otp_not_match'),
                    ],200);
                }
            }
            else{
                return response()->json([
                    "code"=> 200,
                    'status' => 'error',
                    'message' => @trans('success.already_change_password'),
                ],200);
            }
        }
        catch(\Exception $e)
        {
            return response()->json([
                "code"=> 403,
                'status' => 'error',
                'message' => $e->getMessage(),
            ],403);
        }
    } 
}
