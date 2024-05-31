<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Storage;
use JWTAuth;
use Validator;
use Str;
use File;
use Carbon\Carbon;
class UserController extends Controller
{
    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', @$token);
    }
    public function listFollowersUsers(Request $request){
    	try {
    		if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.not_found'),
				], 200);
			}

    		$users = User::where('status','!=','D')->whereNotNull('username')->select('username','name');
		
			if(!empty(@$request->keyword)){
				$users = $users->where(function($query) use ($request){
					$query->where('username', 'Like', '%' . @$request->keyword .'%')
					->orWhere('name', 'Like', '%' . @$request->keyword .'%');
				});
			}
			if(!empty(@$request->page))
			{
				$limit = @$request->limit ? @$request->limit : 10;
				$users = $users->paginate(@$limit);
			}
			else{
				$users = $users->get();
			}
			
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data' => @$users,
			],200);
    	}
    	catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'token_expire',
				'message' => $e->getMessage(),
			],403);
		}
    }

	public function profile(Request $request)
	{
		try {
			if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.not_found'),
				], 200);
			}
			$authId = $user->_id;

			$request->validate([
				'userId' => 'required|exists:mongodb.users,_id'
			]);


			$userId = !empty($request->userId)?$request->userId:'';
			$latest = 10;

			$data = User::select('_id','name','phone','email','username','gender')->where('_id', $userId)->first();
			$count_podcasts = \DB::connection('mongodb')->collection('podcasts')->where('userId', $userId)->count();
			$count_videos = \DB::connection('mongodb')->collection('videos')->where('userId', $userId)->count();
			$count_followings = \DB::connection('mongodb')->collection('follows')->where('authId', $userId)->count();
			$count_followers = \DB::connection('mongodb')->collection('follows')->where('userId', $userId)->count();

			$latest_podcasts = \App\Models\Podcast::where('userId', $userId)->orderBy('_id', 'desc')->take($latest)->get();
			$latest_videos = \App\Models\Video::where('userId', $userId)->orderBy('_id', 'desc')->take($latest)->get();
			$latest_followings = \App\Models\Follow::where('authId', $userId)->with('followings:_id,name,email,phone')->orderBy('_id', 'desc')->take($latest)->get();
			$latest_followers = \App\Models\Follow::where('userId', $userId)->with('followers:_id,name,email,phone')->orderBy('_id', 'desc')->take($latest)->get();

			$data->count_podcasts = $count_podcasts;
			$data->count_videos = $count_videos;
			$data->count_followings = $count_followings;
			$data->count_followers = $count_followers;
			$data->latest_podcasts = $latest_podcasts;
			$data->latest_videos = $latest_videos;
			$data->latest_followings = $latest_followings;
			$data->latest_followers = $latest_followers;
			// dd($data);
			return response()->json([
				'status' => true,
				'message' => "User Profile",
				'data' => $data
			],200);



		} catch (\Throwable $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'token_expire',
				'message' => $e->getMessage(),
			],403);
		}
	}
}
