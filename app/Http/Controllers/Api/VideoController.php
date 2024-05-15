<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use App\Models\Video;
use App\Models\VideoLike;
use App\Models\VideoComment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use MongoDB\BSON\ObjectID;

class VideoController extends Controller
{

    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }


    /**
     * List of all video.
     * GET
     *
     * @return \Illuminate\Http\Response
     */
    public function list(): JsonResponse
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $data = (object)[];
            $countData = DB::connection('mongodb')->collection('videos')->count();
            $listData = Video::with([
                'comments' => function($c){
                    $c->with('user:_id,name');
                },
                'likes'
                ])->get();
            return \Response::json([
                'status' => true,
                'message' => "All videos",
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
     * Create Video
     * POST
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) : JsonResponse
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
                'title' => 'required',
                'overview' => 'required',
                'imageUrl' => 'required',
                'videoUrl' => 'required' 
            ]);
            if($validator->fails()){
                foreach($validator->errors()->messages() as $key => $value){
                    return response()->json(['status' => $value[0]], 400);
                }
            }
            $params = $request->except('_token');
            $params['userId'] = $user->_id;
            $params['isActive'] = true;
            $params['slug'] = \Str::slug($params['title']);
            // dd($params);
            $data = Video::create($params);
            
            return \Response::json([
                'status' => true,
                'message' => "Video Created",
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

    /**
     * Like / Dislike Video
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
                'videoId' =>'required|exists:mongodb.videos,_id'           
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
    
            $existLiked = VideoLike::where('videoId', $params['videoId'])->where('userId', $params['userId'])->first();
    
            $msg = "";
            if(!empty($existLiked)){
                VideoLike::where('_id', $existLiked->_id)->delete();
                $msg = "Disliked";
            } else {
                VideoLike::create($params);
                $msg = "Liked";
            }
            
            return \Response::json([
                'status' => true,
                'message' => $msg,
                'data' =>  (object)[]
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
     * Comment On Video
     * POST
     *
     * @return \Illuminate\Http\Response
     */
    public function comment(Request $request): JsonResponse {

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $validator = \Validator::make($request->all(),[
                'videoId' =>'required|exists:mongodb.videos,_id',
                'comment' => 'required'
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
            $params['userId'] = $user->_id;
            
            $data = VideoComment::create($params);
            $msg = "Commented";
    
            return \Response::json([
                'status' => true,
                'message' => $msg,
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

    
}
