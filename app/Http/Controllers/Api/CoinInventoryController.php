<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use App\Models\Gift;
use App\Models\UserCoin;
use App\Models\Podcast;
use App\Models\CoinPrice;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use MongoDB\BSON\ObjectID;
use Helper;

class CoinInventoryController extends Controller
{
    //
    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }

    /**
     * User Coin History
     * GET
     *
     * @return \Illuminate\Http\Response
    */

    public function index(Request $request): JsonResponse
    {
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
            $countData = UserCoin::where('userId', $userId)->count();
            $data = UserCoin::with('podcast:id,title,overview,imageUrl,videoUrl,slug')->where('userId', $userId)->orderBy('_id', 'desc')->take($take)->skip($skip)->get();

            $debitSum = UserCoin::where('userId',$userId)->where('type','debit')->sum('coin_value');
            $creditSum = UserCoin::where('userId',$userId)->where('type','credit')->sum('coin_value');
            
            $total = ($creditSum - $debitSum);
            // echo $creditSum ;
            
            return \Response::json([
                'status' => true,
                'message' => "My Coins",
                'data' => array(
                    'countData' => $countData,
                    'total' => $total,
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

    /**
     * List of coin pricings or plans
     * GET
     *
     * @return \Illuminate\Http\Response
     */


    public function plans() : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $userId = $user->_id;

            $data = CoinPrice::where('status', 'A')->get();

            return \Response::json([
                'status' => true,
                'message' => "Coin Pricings",
                'data' => array(
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

    public function add(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $userId = $user->_id;
            $request->validate([
                'coin_id' => 'required|exists:mongodb.coin_prices,_id'
            ]);
            $coin_id = $request->coin_id;

            $coin = CoinPrice::find($coin_id);
            $coin_value = $coin->from_coin;

            

            $params = $request->except('_token');
            $params['userId'] = $userId;
            $params['coin_value'] = $coin_value;            
            $params['type'] = 'credit';
            UserCoin::create($params);

            /* Add Activity */
            $activityMessage = "Purchased ".$coin_value." coins";
            Helper::addActivity($user->_id,'credit_coin',$activityMessage);

            /* Add Notification */
                $notificationMsg = "You purchased ".$coin_value." coins successfully";
                Helper::addNotification($user->_id, 'credit_coin', $notificationMsg);
            
            
            return \Response::json([
                'status' => true,
                'message' => "Coin added to your inventory successfully",
                'data' => $params
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
