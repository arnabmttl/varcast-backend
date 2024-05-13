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

            $existUser = User::where('_id', $params['userId'])->first();
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
            $params['authId'] = $authId;
            // dd($params);
            if(!empty($existFollow)){
                Follow::where('_id', $existFollow->_id)->delete();
                $msg = "Unfollowed";
            } else {
                Follow::create($params);
                $msg = "Followed";
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
}
