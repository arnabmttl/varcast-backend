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
use Helper;

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
            $take = !empty($request->take)?$request->take:15;
            $page = !empty($request->page)?$request->page:0;
            $skip = ($page * $take);

            $data = (object)[];
            $countData = DB::connection('mongodb')->collection('podcasts')->count();
            $listData = Podcast::with([
                'comments' => function($c){
                    $c->with('user:_id,name');
                },
                'likes'
                ])->orderBy('_id','desc')->take($take)->skip($skip)->get();
            
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

        \App\Models\ApiRequestLog::create(['request' => json_encode($request->all())]);

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            
            $validator = \Validator::make($request->all(),[
                // 'title' => 'required',
                // 'overview' => 'required'
                'image' => 'required|file',
                'audio' => 'required|file'
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
            // $params['slug'] = \Str::slug($params['title']);
            $params['isActive'] = true;
            /* Image File */
            $fileImage = $request->file('image');
            $file_name_image= time()."_".$fileImage->getClientOriginalName();
            $locationImage="uploads/podcasts/";
            $fileImage->move($locationImage,$file_name_image);
            $imagefilename=$locationImage."".$file_name_image;
            $params['image']=$imagefilename;
            /* Audio File*/
            $fileAudio = $request->file('audio');
            $file_name_audio= time()."_".$fileAudio->getClientOriginalName();
            $locationAudio="uploads/podcasts/";
            $fileAudio->move($locationAudio,$file_name_audio);
            $audiofilename=$locationAudio."".$file_name_audio;
            $params['audio']=$audiofilename;
            
            $podcast = Podcast::create($params);
            
            /* Add Activity */
            Helper::addActivity($user->_id,'create_podcast','Created a podcast');

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
                /* Add Activity */
                Helper::addActivity($user->_id,'liked_podcast','Liked a podcast');
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

            /* Add Activity */
            Helper::addActivity($user->_id,'comment_podcast','Commented a podcast');
    
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
