<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use App\Models\Live;
use App\Models\LiveLike;
use App\Models\LiveComment;
use App\Models\LiveView;
use App\Models\Follow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use MongoDB\BSON\ObjectID;
use Helper;

class LiveController extends Controller
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

    public function list(Request $request) : JsonResponse {

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $data = (object)[];
            $userId = $user->_id;
            $take = !empty($request->take)?$request->take:15;
            $page = !empty($request->page)?$request->page:0;
            $skip = ($page * $take);

            $countData = DB::connection('mongodb')->collection('lives')->count();
            $listData = Live::with([
                'comments' => function($c){
                    $c->with('user:_id,name,email');
                },
                'likes' => function($l){
                    $l->with('user:_id,name,email');
                }
                ])->orderBy('_id', 'desc')->take($take)->skip($skip)->get();
            // $listData = Live::with('user:_id,name,email,phone')->orderBy('_id', 'desc')->get();
            return \Response::json([
                'status' => true,
                'message' => "All live lists",
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

    /**
     * Create Lives
     * POST
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request) : JsonResponse {

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $validator = \Validator::make($request->all(),[
                'title' => 'required',
                'overview' => 'required',
                'imageUrl' => 'required',
                'videoUrl' => 'required' 
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
            // $params['userId'] = $user->_id;
            // $params['isActive'] = true;
            // $params['slug'] = \Str::slug($params['title']);
            
            $live = new Live;
            $live->userId = $user->_id;   
            $live->title = $params['title'];
            $live->slug = \Str::slug($params['title']);
            $live->overview = $params['overview'];
            $live->imageUrl = $params['imageUrl'];
            $live->videoUrl = $params['videoUrl'];
            $live->isActive = true;                         
            $live->save();

            /* Add Activity */
            Helper::addActivity($user->_id,'create_live','Created a live');

            /* Add Notification */
            $authUserName = $user->name;
            $notificationMsg = $authUserName." created a new live";
            $followers = Follow::with('followers:_id,name,email,phone')->where('userId', $user->_id)->get();
            if(!empty($followers)){
                foreach($followers as $follow){
                    Helper::addNotification($follow->followers->_id, 'create_live', $notificationMsg);
                }
            }
            
            
            return \Response::json([
                'status' => true,
                'message' => "Live Created",
                'data' =>  $live    
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
        
    }

    
    /**
     * Like / Dislike Lives
     * POST
     *
     * @return \Illuminate\Http\Response
     */

    public function like(Request $request): JsonResponse {

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $validator = \Validator::make($request->all(),[
                'liveId' =>'required|exists:mongodb.lives,_id'           
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
            $userId = $user->_id;    
            $liveId = $params['liveId'];  
            
            $live = Live::find($liveId);
            $liveUserId = $live->userId;


            $existLiked = LiveLike::where('liveId', $liveId)->where('userId', $userId)->first();
            $msg = "";
            if(!empty($existLiked)){
                LiveLike::where('_id', $existLiked->_id)->delete();
                $msg = "Disliked";
            } else {
                $liveLike = new LiveLike;
                $liveLike->liveId = $liveId;
                $liveLike->userId = $userId;
                $liveLike->save();
                $msg = "Liked";

                /* Add Activity */
                Helper::addActivity($user->_id,'like_live','Liked a live');

                /* Add Notification */
                if($liveUserId != $user->_id){
                    $authUserName = $user->name;
                    $notificationMsg = $authUserName." liked your live";
                    Helper::addNotification($liveUserId, 'like_live', $notificationMsg);
                }


            }
            
            return \Response::json([
                'status' => true,
                'message' => $msg,
                'data' =>  (object)[]
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
     * Comment On Lives
     * POST
     *
     * @return \Illuminate\Http\Response
    */

    public function comment(Request $request) : JsonResponse {

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $validator = \Validator::make($request->all(),[
                'liveId' =>'required|exists:mongodb.lives,_id',
                'comment' => 'required'
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
            // $params['userId'] = $user->_id;

            $live = Live::find($params['liveId']);
            $liveUserId = $live->userId;

            $liveComment = new LiveComment;
            $liveComment->liveId = $params['liveId'];
            $liveComment->userId = $user->_id;
            $liveComment->comment = $params['comment'];
            $liveComment->save();
    
            $msg = "Commented successfully";

            /* Add Activity */
            Helper::addActivity($user->_id,'comment_live','Liked a live');

            /* Add Notification */
            if($liveUserId != $user->_id){
                $authUserName = $user->name;
                $notificationMsg = $authUserName." commented on your your live";
                Helper::addNotification($liveUserId, 'comment_live', $notificationMsg);
            }
            
            
            return \Response::json([
                'status' => true,
                'message' => $msg,
                'data' =>  $liveComment
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
    }

    /**
     * Details
     * POST
     *
     * @return \Illuminate\Http\Response
    */

    public function details(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $validator = \Validator::make($request->all(),[
                'liveId' =>'required|exists:mongodb.lives,_id'
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

            $userId = $user->_id;

            $liveId = !empty($request->liveId)?$request->liveId:'';
            $data = Live::with('user:_id,name,email,phone')->find($liveId);
            $isLiked = LiveLike::where('liveId', $liveId)->where('userId', $userId)->count();
            $isLiked = (!empty($isLiked))?true:false;

            $latestComments = LiveComment::with('user:_id,name,email,phone,username')->where('liveId', $liveId)->orderBy('_id','desc')->take(15)->get();
            

            /* Add View For Each New User */
            $existView = LiveView::where('userId', $userId)->where('liveId',$liveId)->first();
            if(empty($existView)){
                LiveView::create([
                    'userId' => $userId,
                    'liveId' => $liveId
                ]);
            }
            $data->countView = LiveView::where('liveId',$liveId)->count();
            $data->countLike = LiveLike::where('liveId',$liveId)->count();
            $data->isLiked = $isLiked;
            $data->latestComments = $latestComments;

            return \Response::json([
                'status' => true,
                'message' => "Live Details",
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
     * Comments
     * POST
     *
     * @return \Illuminate\Http\Response
    */

    public function comments(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $validator = \Validator::make($request->all(),[
                'liveId' =>'required|exists:mongodb.lives,_id'
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

            $userId = $user->_id;

            $liveId = !empty($request->liveId)?$request->liveId:'';
            $take = !empty($request->take)?$request->take:15;
            $page = !empty($request->page)?$request->page:0;
            $skip = ($page*$take);

            $totalData = LiveComment::where('liveId',$liveId)->count();
            $listData = LiveComment::with('user:_id,name,email,phone,username')->where('liveId', $liveId)->orderBy('_id','desc')->take($take)->skip($skip)->get();
            
            return \Response::json([
                'status' => true,
                'message' => "Live Comments",
                'data' =>  array(
                    'totalData' => $totalData,
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
