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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use MongoDB\BSON\ObjectID;
use Helper;

class GiftController extends Controller
{
    //

    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }

    /**
     * All active available gifts
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
            $data = Gift::where('status', '!=', 'D')->orderBy('gift_name')->get();

            return \Response::json([
                'status' => true,
                'message' => "All gifts",
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

    /**
     * Send gift for podcast
     * POST
     *
     * @return \Illuminate\Http\Response
    */

    public function send(Request $request): JsonResponse
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            
            $validator = \Validator::make($request->all(),[
                'podcastId' =>'required|exists:mongodb.podcasts,_id',
                'userId' => 'required|exists:mongodb.users,_id',
                'giftId' => 'required|exists:mongodb.gifts,_id'
            ]);

            if($validator->fails()){
                foreach($validator->errors()->messages() as $key => $value){
                    return \Response::json([
                        'status' => false,
                        'message' =>  $value[0],
                        'data' => (object)[]
                    ], 400);
                }
            }

            $params = $request->except('_token');
            $params['authId'] = $user->_id;

            // dd($params['authId']);

            if($params['authId'] == $params['userId']){
                return \Response::json([
                    'status' => false,
                    'message' =>  "Invalid id.  This user id is yours.",
                    'data' => (object)[]
                ], 400);
            }

            $podcastData = Podcast::find($params['podcastId']);
            $podcastUserId = $podcastData->userId;

            if($podcastUserId == $user->_id){
                return \Response::json([
                    'status' => false,
                    'message' =>  "This is your podcast",
                    'data' => (object)[]
                ], 400);
            }

            $giftData = Gift::find($params['giftId']);
            $coin_value = $giftData->coin_value;
            $gift_name = $giftData->gift_name;

            ## Credit To User Coin
            UserCoin::create([
                'podcastId' => $params['podcastId'],
                'userId' => $params['userId'],
                'giftId' => $params['giftId'],
                'type' => 'credit',
                'coin_value' => $coin_value
            ]);
            ## Debit Auth User Coin 
            UserCoin::create([
                'podcastId' => $params['podcastId'],
                'userId' => $params['authId'],
                'giftId' => $params['giftId'],
                'type' => 'debit',
                'coin_value' => $coin_value
            ]);

            /* Add Activity */
            $activityMessage = "Send a gift";
            Helper::addActivity($user->_id,'send_gift',$activityMessage);

            /* Add Notification */
            if($videoUserId != $user->_id){
                $authUserName = $user->name;
                $notificationMsg = $authUserName." send a ".$gift_name." gift of ".$coin_value." coin to you";
                Helper::addNotification($params['userId'], 'send_gift', $notificationMsg);
            }
            


            return \Response::json([
                'status' => true,
                'message' => " Gift sent successfully ",
                'data' => array(
                    'params' => $params
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
