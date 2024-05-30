<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use MongoDB\BSON\ObjectID;
use Helper;

class FollowController extends Controller
{
    //

    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }

    /**
     * List of all lives.
     * GET
     *
     * @return \Illuminate\Http\Response
     */

    public function post(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $authId = $user->_id;
            $validator = \Validator::make($request->all(),[
                'userId' =>'required|exists:mongodb.users,_id'
            ]);
    
            if($validator->fails()){
                foreach($validator->errors()->messages() as $key => $value){
                    return \Response::json([
                        'status' => false,
                        'message' => "validation",
                        'data' =>  $value[0]
                    ], 400);
                }
            }
            $params = $request->except('_token');
            $userId = $params['userId'];

            $existUser = User::where('_id', $userId)->first();
            if(empty($existUser)){
                return response()->json([
                    "code"=> 400,
                    'status' => 'validation',
                    'message' => "Invalid user id"
                ],400);
            }

            if($authId == $userId){
                return response()->json([
                    "code"=> 400,
                    'status' => 'validation',
                    'message' => "Same user id"
                ],400);
            }

            $msg = "";
            $existFollow = Follow::where('authId', $authId)->where('userId', $userId)->first();
            
            // dd($params);
            if(!empty($existFollow)){
                Follow::where('_id', $existFollow->_id)->delete();
                $msg = "Unfollowed";
            } else {
                $follow = new Follow;
                $follow->authId = $authId;
                $follow->userId = $params['userId'];                
                $follow->save();
                $msg = "Followed";

                /* Add Activity */
                $authUserName = $user->name;
                $notificationMsg = $authUserName." is following you";
                Helper::addNotification($params['userId'], 'follow', $notificationMsg);


            }

            return \Response::json([
                'status' => true,
                'message' => $msg,
                'data' =>  $params
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
    }

    /**
     * List of all followings.
     * GET
     *
     * @return \Illuminate\Http\Response
     */

    public function followings(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $authId = $user->_id;

            // $data = Follow::where('authId', $authId)->get();
            $data = Follow::with('followings:_id,name,email,phone')->where('authId', $authId)->get();

            return \Response::json([
                'status' => true,
                'message' => "All followings",
                'data' =>  $data
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
    }

    /**
     * List of all followers.
     * GET
     *
     * @return \Illuminate\Http\Response
     */

    public function followers(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $authId = $user->_id;
            $data = Follow::with('followers:_id,name,email,phone')->where('userId', $authId)->get();

            return \Response::json([
                'status' => true,
                'message' => "All followers",
                'data' =>  $data
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
    }
}
