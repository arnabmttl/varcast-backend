<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use App\Models\Podcast;
use App\Models\PodcastLike;
use App\Models\PodcastComment;

class PodcastController extends Controller
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
            $countData = DB::connection('mongodb')->collection('podcasts')->count();
            $listData = Podcast::with([
                'comments' => function($c){
                    $c->with('user:_id,name');
                },
                'likes'
                ])->orderBy('_id','desc')->get();
            
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

    /**
     * Create Live Post
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
                'overview' => 'required'
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
            $params['slug'] = \Str::slug($params['title']);
            $params['isActive'] = true;
            
            $podcast = Podcast::create($params);
            // echo $podcast->toJson();
            return \Response::json([
                'status' => true,
                'message' => "Podcast Created",
                'data' =>  $podcast
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
     * Like / Dislike Podcast
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
                'podcastId' =>'required|exists:mongodb.podcasts,_id'           
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
    
            
            $existLiked = PodcastLike::where('podcastId', $params['podcastId'])->where('userId', $params['userId'])->first();
    
            $msg = "";
            if(!empty($existLiked)){
                PodcastLike::where('_id', $existLiked->_id)->delete();
                $msg = "Disliked";
            } else {
                PodcastLike::create($params);
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
     * Comment On Podcast
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
                'podcastId' =>'required|exists:mongodb.podcasts,_id',
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
    
                        
            $data = PodcastComment::create($params);
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
