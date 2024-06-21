<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use App\Models\Notification;
use Helper;

class NotificationController extends Controller
{
    //

    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }

    /**
     * List of user notifications
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

            $countData = Notification::where('userId', $userId)->count();
            $listData = Notification::where('userId', $userId)->orderBy('_id', 'desc')->take($take)->skip($skip)->get();

            $isPrev = $isNext = false;
            if(count($listData) != 0 ){
                if(count($listData) >= $take) {
                    $isNext = true;
                }
            }                           
            if($page > 0){
                $isPrev = true;
            }
            
            return \Response::json([
                'status' => true,
                'message' => "My notifications",
                'data' => array(
                    'countData' => $countData,
                    'isNext' => $isNext,
                    'isPrev' => $isPrev,
                    'listData' => $listData
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
