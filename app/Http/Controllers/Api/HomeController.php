<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Business;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\HomeContent;
use App\Models\Lead;
use App\Models\UserChat;
use App\Models\User;
use Carbon\Carbon;
use JWTAuth;
use File;

class HomeController extends Controller
{
	public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }
	public function getCountry(Request $request){
		try{
			$country = Country::where('phonecode', '!=', '0')->orderBy('sortname','asc');
			if(!empty(@$request->all())){
				if(!empty(@$request->keyword)){
					@$country = @$country->where(function($query) use ($request){
						$query->where('name', 'Like', '%' . @$request->keyword .'%')
						->orWhere('sortname', 'Like', '%' . @$request->keyword .'%')
						->orWhere('phonecode', 'Like', '%' . @$request->keyword .'%');
					});
				}
			}
			if(!empty(@$request->page))
			{
				$country = @$country->paginate(10);
			}
			else{
				$country = $country->get();
			}
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data' => @$country,
			],200);
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => $e->getMessage(),
			],403);
		}
	}
	public function getState(Request $request){
		try{
			$state = State::orderBy('name','asc');
			if(!empty(@$request->all())){
				if(!empty(@$request->keyword)){
					@$state = @$state->where(function($query) use ($request){
						$query->where('name', 'Like', '%' . @$request->keyword .'%');
					});
				}
				if (!empty(@$request->country_id)) {
					@$state = @$state->where('country_id',@$request->country_id);
				}
			}
			if(!empty(@$request->page))
			{
				$state = @$state->paginate(10);
			}
			else{
				$state = $state->get();
			}
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data' => @$state,
			],200);
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => $e->getMessage(),
			],403);
		}
	}
	public function getCity(Request $request){
		try{
			$city = City::orderBy('name','asc')->where('name','!=','');
			if(!empty(@$request->all())){
				if(!empty(@$request->keyword)){
					@$city = @$city->where(function($query) use ($request){
						$query->where('name', 'Like', '%' . @$request->keyword .'%');
					});
				}
				if (!empty(@$request->state_id)) {
					@$city = @$city->where('state_id',@$request->state_id);
				}
			}
			if(!empty(@$request->page))
			{
				$city = @$city->paginate(10);
			}
			else{
				$city = $city->get();
			}
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data' => @$city,
			],200);
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => $e->getMessage(),
			],403);
		}
	}
	public function index(Request $request){
		try {
			if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

			$userId = $user->_id;
			$latest = 10;

			$latest_podcasts = \App\Models\Podcast::where('userId', $userId)->orderBy('_id', 'desc')->take($latest)->get();
			$latest_videos = \App\Models\Video::where('userId', $userId)->orderBy('_id', 'desc')->take($latest)->get();
			$latest_lives = \App\Models\Live::where('userId', $userId)->orderBy('_id', 'desc')->take($latest)->get();
			$latest_followings = \App\Models\Follow::where('authId', $userId)->with('followings:_id,name,email,phone')->orderBy('_id', 'desc')->take($latest)->get();
			$latest_followers = \App\Models\Follow::where('userId', $userId)->with('followers:_id,name,email,phone')->orderBy('_id', 'desc')->take($latest)->get();
			$categories = \App\Models\Category::select('_id','name','slug')->where('status', 'A')->get();

			return response()->json([
				'status' => true,
				'message' => "Homepage",
                'data' => array(
					'latest_podcasts' => $latest_podcasts,
					'latest_videos' => $latest_videos,
					'latest_lives' => $latest_lives,
					'latest_followings' => $latest_followings,
					'latest_followers' => $latest_followers,
					'categories' => $categories
				)
			], 200);



		} catch (\Throwable $e) {
			return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
		}
	}

	public function checkUserChat(Request $request)
	{
		try {
			if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

			$authId = $user->_id;
			$request->validate([
				'userId' => 'required|unique:mongodb.users,id'
			]);
			$params = $request->except('_token');
			$userId = $params['userId'];

			if($authId == $userId){
				return response()->json([
					'status' => false,
					'message' => "This user id is yours",
					'data' => (object)[]
				],400);
			}

			$existsChat = UserChat::where(function($sender) use ($authId,$userId){
				$sender->where('senderId',$authId)->where('receiverId',$userId);
			})->orWhere(function($receiver) use ($authId,$userId){
				$receiver->where('receiverId',$authId)->where('senderId',$userId);
			})->first();

			$isExists = false;
			if(!empty($existsChat)){
				## return id
				$id = $existsChat->_id;
				$isExists = true;

			} else {
				## creat new id
				$data = UserChat::create([
					'senderId' => $authId,
					'receiverId' => $userId
				]);
				$id = $data->_id;
			}

			return response()->json([
				'status' => true,
				'message' => "Chat initiated",
                'data' => array(
					'isExists' => $isExists,
					'chatId' => $id
				)
			],200);


		} catch (\Throwable $e) {
			return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
		}
	}

	public function chatUserList(Request $request)
	{
		try {
			if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

			$authId = $user->_id;
			$search = !empty($request->search)?$request->search:'';

			$senderData = UserChat::where('senderId',$authId)->pluck('receiverId')->toArray();
			$receiverData = UserChat::where('receiverId',$authId)->pluck('senderId')->toArray();
			

			$userIds = array_merge($senderData,$receiverData);
			// dd($userIds);

			$data = User::select('_id','name','email','phone')->whereIn('_id', $userIds);
			if(!empty($search)){
				$data = $data->where('name', 'LIKE', '%'.$search.'%');
			}
			
			$data = $data->get()->toArray();
			// dd($data);
			return response()->json([
				'status' => true,
				'message' => "User List",
                'data' => array(
					'totalUser' => count($userIds),
					'listData' => $data
				)
			],200);


		} catch (\Throwable $e) {
			return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
		}
	}
}
