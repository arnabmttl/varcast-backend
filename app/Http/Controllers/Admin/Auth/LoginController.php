<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admins for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect admins after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.guest:admin', ['except' => 'logout']);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Log the admin out of the application.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route('admin.home');
    }

    public function login(Request $post){
        $rules = array(
            'email' => 'required|email|exists:admins',
            'password' => 'required'
        );

        $validator = \Validator::make($post->all(), $rules);
        if($validator->fails()){
            foreach($validator->errors()->messages() as $key => $value){
                return response()->json(['status' => $value[0]], 400);
            }
        }

        $user = Admin::where('email', $post->email)->first();
        if(!$user){
            return response()->json(['status' => 'The email address you entered is invalid'], 400);
        }

        if($user->status == 0){
            return response()->json(['status' => 'Your account has been deactivated. To activate your account contact or write to us.'], 400);
        }

        if(\Auth::guard('admin')->validate(['email' => $post->email, 'password' => $post->password])){
            if(\Auth::guard('admin')->attempt(['email' => $post->email, 'password' => $post->password])){
                \Session::flash('success', 'Logedin Successfully');
                return response()->json(['status' => 'Logedin Successfully'], 200);
            } else{
                return response()->json(['status' => 'Account may be blocked'], 400);
            }
        } else{
            return response()->json(['status' => 'Invalid credentials.'], 400);
        }
    }
}
