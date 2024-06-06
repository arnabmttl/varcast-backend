<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Business;
use App\Models\Review;
use JWTAuth;
use Illuminate\Support\Facades\Hash;
use Helper;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Str;
use Session;
use Carbon\Carbon;
use App\Mail\commonMail;
use Mail;
use Storage;
class ProfileController extends Controller
{
	public function __construct(Request $request)
    {
        // $this->middleware('jwt.auth')->except(['login','Vendorlogin','getUser','getVendor','register']);
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', @$token);
    }
	// edit user profile
	public function edituserProfile(Request $request){
		try {
			if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.not_found'),
				], 200);
			}
			
			$validator = Validator::make($request->all(), [
				'name'  => 'required|string|max:199',
				'image' => 'nullable|image',
				'dob' => 'nullable|date',
	            'gender' => 'nullable|in:M,F,O',
	            'govt_id_card' => 'nullable',
			]);
			if ($validator->fails()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @$validator->errors()->first()
				],200);
			}
			if (!empty(@$user)) {
				if(@$user->status == 'I')
				{
					return response()->json([
						"code"=> 200,
						'status' => 'inactive_account',
						'message' =>  @trans('error.inactive_account_by_admin')
					],200);
				}
				$message = '';
				$user_date = User::where('_id',@$user->_id)->first();
				$data = [];
				if(!empty(@$request->name)){
					$data['name'] = @$request->name;
				}
				if(!empty(@$request->dob)){
					$data['dob'] = @$request->dob;
				}
				if(!empty(@$request->gender)){
					$data['gender'] = @$request->gender;
				}
				if ($request->hasFile('image')) {
					if ($user_date->image) {
						if (File::exists($user_date->image)) {
							File::delete($user_date->image);
						}
					}
					// $file      = $request->file('image');
					// $time      = Carbon::now();
					// $extension = $file->getClientOriginalExtension();
					// $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
					// $file->storeAs('public/profile_pics', @$filename);
					// $data['image'] = $filename;


					$file = $request->file('image');
					$file_name= time()."_".$file->getClientOriginalName();
					$location="uploads/documents/";
					$file->move($location,$file_name);
					$filename=$location."".$file_name;
					$data['image']=$filename;

				}
				if ($request->hasFile('govt_id_card')) {
					if ($user_date->govt_id_card) {
						if (File::exists($user_date->govt_id_card)) {
							File::delete($user_date->govt_id_card);
						}
					}
					// $file      = $request->file('govt_id_card');
					// $time      = Carbon::now();
					// $extension = $file->getClientOriginalExtension();
					// $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
					// $file->storeAs('public/documents', @$filename);
					// $data['govt_id_card'] = $filename;

					$file = $request->file('govt_id_card');
					$file_name= time()."_".$file->getClientOriginalName();
					$location="uploads/documents/";
					$file->move($location,$file_name);
					$filename=$location."".$file_name;
					$data['govt_id_card']=$filename;
				}
				$user_date->update(@$data);
				$user_details = User::where('_id',@$user->_id)->first();
				return response()->json([
					"code"=> 200,
					'status' => 'success',
					'message' => @trans('success.profile_update'),
					'data' => @$user_details,
				],200);
			}
			else{
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.not_found'),
				], 200);
			}
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'token_expire',
				'message' => $e->getMessage(),
			],403);
		}
	}
	// update password
	public function updatePassword(Request $request){
		try {
			if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.not_found'),
				], 200);
			}
			
			$validator = Validator::make($request->all(), [
				'old_password' => 'required',
				'password' => 'required|confirmed',
			],['password.required_with' => "The password field is required."]);
			if ($validator->fails()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @$validator->errors()->first()
				],200);
			}
			if (!empty(@$user)) {
				if(@$user->status == 'I')
				{
					return response()->json([
						"code"=> 200,
						'status' => 'inactive_account',
						'message' =>  @trans('error.inactive_account_by_admin')
					],200);
				}
				$message = '';
				$user_date = User::where('_id',@$user->_id)->first();
				$data = [];

				if (Hash::check(@$request->old_password, @$user_date->password)) {
					$data['password'] = Hash::make(@$request->password);
				}
				else{
					return response()->json([
						"code"=> 200,
						'status' => 'error',
						'message' => @trans('error.password_mismatch'),
					],200);
				}
				
				$user_date->update(@$data);
				$user_details = User::where('_id',@$user->_id)->first();
				return response()->json([
					"code"=> 200,
					'status' => 'success',
					'message' => @trans('success.change_password'),
					'data' => @$user_details,
				],200);
			}
			else{
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.not_found'),
				], 200);
			}
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'token_expire',
				'message' => $e->getMessage(),
			],403);
		}
	}
}
