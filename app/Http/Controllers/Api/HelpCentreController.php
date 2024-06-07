<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use App\Models\HelpCentre;
use Helper;

class HelpCentreController extends Controller
{
    //

    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }

    public function add(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $validator = \Validator::make($request->all(),[
                'email' => 'required|email',
                'description' => 'required',
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
            $params['userId'] = $user->_id;
            
            $data = HelpCentre::create($params);

            return \Response::json([
                'status' => true,
                'message' => "Created",
                'data' =>  $data
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
        
    }

    public function list(Request $request) : JsonResponse {
        try {
            
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $userId = $user->_id;
            $take = !empty($request->take)?$request->take:15;
            $page = !empty($request->page)?$request->page:0;
            $skip = ($take*$page);

            $listData = HelpCentre::where('userId', $userId)->orderBy('_id','desc')->take($take)->skip($skip)->get();
            $countData = HelpCentre::where('userId', $userId)->count();


            return \Response::json([
                'status' => true,
                'message' => "My all help centre data list",
                'data' =>  array(
                    'countData' => $countData,
                    'listData' => $listData
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
}
