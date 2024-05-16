<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletHistory;
use JWTAuth;

class WalletController extends Controller
{
    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }

    /**
     * My wallet history
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
            $skip = ($page * $take);
            $countData = WalletHistory::where('userId', $userId)->count();
            $listData = WalletHistory::where('userId', $userId)->orderBy('_id','desc')->take($take)->skip($skip)->get();

            return \Response::json([
                'status' => true,
                'message' => "All podcast lists",
                'data' => array(
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
