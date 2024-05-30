<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use App\Models\Activity;

class ActivityController extends Controller
{
    //
    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }

    /**
     * List of user activity list
     * GET
     *
     * @return \Illuminate\Http\Response
     */


    public function index(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $take = !empty($request->take)?$request->take:15;
            $page = !empty($request->page)?$request->page:0;
            $skip = ($page * $take);

            $userId = $user->_id;

            $countData = Activity::where('userId', $userId)->count();
            $data = Activity::where('userId', $userId)->orderBy('_id', 'desc')->take($take)->skip($skip)->get();

            
            return \Response::json([
                'status' => true,
                'message' => "My Activities",
                'data' => array(
                    'countData' => $countData,
                    'listData' => $data
                )
            ], 200);


        } catch (\Throwable $e) {
            return response()->json([
				"code"=> 403,
				'status' => 'token_expire',
				'message' => $e->getMessage(),
			],403);
        }
    }
}
