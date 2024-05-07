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
}
