<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Audio;
use JWTAuth;
use File;
use Helper;

class AudioController extends Controller
{
    //

    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }

    public function create(Request $request) : JsonResponse {

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $userId = $user->_id;

            $file = $request->file('audio');
            $file_name= time()."_".$file->getClientOriginalName();
            $location="uploads/audios/";
            $file->move($location,$file_name);
            $filename=$location."".$file_name;
            $params['filename']=$file->getClientOriginalName();
            $params['fileurl']=$filename;
            $params['userId'] = $userId;

            Audio::create($params);

            return \Response::json([
                'status' => true,
                'message' => "Audio Created",
                'data' =>  $params
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
            $take = !empty($request->take)?$request->take:25;
            $page = !empty($request->page)?$request->page:0;
            $skip = ($page*$take);
            $data = Audio::with('user:_id,name')->where('userId', $userId)->orderBy('_id','desc')->take($take)->skip($skip)->get();
            $countData = Audio::where('userId', $userId)->count();

            return \Response::json([
                'status' => true,
                'message' => "My audios",
                'data' =>  array(
                    'countData' => $countData,
                    'listData' => $data
                )
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
    }
}
