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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use MongoDB\BSON\ObjectID;

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

    public function list() : JsonResponse {

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $data = (object)[];

            $countData = DB::connection('mongodb')->collection('lives')->count();
            // $listData = Live::with([
            //     'comments' => function($c){
            //         $c->with('user:_id,name');
            //     },
            //     'likes'
            //     ])->get();
            $listData = Live::with(
                    'comments','likes'
                    )->get();
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
            $live->userId = new ObjectId($user->_id);   
            $live->title = $params['title'];
            $live->slug = \Str::slug($params['title']);
            $live->isActive = true;                         
            $live->save();
            
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
            $userId = new ObjectId($user->_id);    
            $liveId = new ObjectId($params['liveId']);            
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

            $liveComment = new LiveComment;
            $liveComment->liveId = new ObjectId($params['liveId']);
            $liveComment->userId = new ObjectId($user->_id);
            $liveComment->comment = $params['comment'];
            $liveComment->save();
    
            $msg = "Commented successfully";
            
            
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

}
