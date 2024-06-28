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
use App\Models\PodcastView;
use App\Models\PodcastCommentMessage;
use App\Models\Follow;
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
            $page = isset($request->page)?$request->page:0;
            $skip = ($page * $take);

            $data = (object)[];
            $countData = DB::connection('mongodb')->collection('podcasts')->count();
            $listData = Podcast::with([
                'comments' => function($c){
                    $c->with('user:_id,name');
                },
                'likes'
                ])->orderBy('_id','desc')->take($take)->skip($skip)->get();

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
                'message' => "All podcast lists",
                'data' => array(
                    'countData' => $countData,
                    'isPrev' => $isPrev,
                    'isNext' => $isNext,
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

        // \App\Models\ApiRequestLog::create(['request' => json_encode($request->all())]);

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

            /* Add Notification */
            $authUserName = $user->name;
            $notificationMsg = $authUserName." created a new podcast";

            $followers = Follow::with('followers:_id,name,email,phone')->where('userId', $user->_id)->get();
            if(!empty($followers)){
                foreach($followers as $follow){
                    Helper::addNotification($follow->followers->_id, 'create_podcast', $notificationMsg);
                }
            }
            

            


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

            $podcast = Podcast::find( $params['podcastId'] );
            $podcastUserId = $podcast->userId;
    
            
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

                /* Add Notification */
                if($podcastUserId != $user->_id){
                    $authUserName = $user->name;
                    $notificationMsg = $authUserName." liked your podcast";
                    Helper::addNotification($podcastUserId, 'like_podcast', $notificationMsg);
                }
                
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

            $podcast = Podcast::find( $params['podcastId'] );
            $podcastUserId = $podcast->userId;
    
                        
            $data = PodcastComment::create($params);
            $msg = "Commented";

            /* Add Activity */
            Helper::addActivity($user->_id,'comment_podcast','Commented a podcast');

            /* Add Notification */
            if($podcastUserId != $user->_id){
                $authUserName = $user->name;
                $notificationMsg = $authUserName." commented on your podcast";
                Helper::addNotification($podcastUserId, 'comment_podcast', $notificationMsg);
            }
    
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
                'podcastId' =>'required|exists:mongodb.podcasts,_id'
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

            $podcastId = !empty($request->podcastId)?$request->podcastId:'';
            $data = Podcast::with('user:_id,name,email,phone')->find($podcastId);
            $isLiked = PodcastLike::where('podcastId', $podcastId)->where('userId', $userId)->count();
            $isLiked = (!empty($isLiked))?true:false;

            $latestComments = PodcastComment::with('user:_id,name,email,phone,username')->where('podcastId', $podcastId)->orderBy('_id','desc')->take(15)->get();
            

            /* Add View For Each New User */
            $existView = PodcastView::where('userId', $userId)->where('podcastId',$podcastId)->first();
            if(empty($existView)){
                PodcastView::create([
                    'userId' => $userId,
                    'podcastId' => $podcastId
                ]);
            }
            $data->countView = PodcastView::where('podcastId',$podcastId)->count();
            $data->countLike = PodcastLike::where('podcastId',$podcastId)->count();
            $data->isLiked = $isLiked;
            $data->latestComments = $latestComments;       

            return \Response::json([
                'status' => true,
                'message' => "Podcast Details",
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
                'podcastId' =>'required|exists:mongodb.podcasts,_id'
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

            $podcastId = !empty($request->podcastId)?$request->podcastId:'';
            $take = !empty($request->take)?$request->take:15;
            $page = !empty($request->page)?$request->page:0;
            $skip = ($page*$take);

            $totalData = PodcastComment::where('podcastId',$podcastId)->count();
            $listData = PodcastComment::with('user:_id,name,email,phone,username')->where('podcastId', $podcastId)->orderBy('_id','desc')->take($take)->skip($skip)->get();

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
                'message' => "Podcast Comments",
                'data' =>  array(
                    'totalData' => $totalData,
                    'isPrev' => $isPrev,
                    'isNext' => $isNext,
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
     * Message Post On Comments
     * POST
     *
     * @return \Illuminate\Http\Response
    */

    public function message_comment(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $validator = \Validator::make($request->all(),[
                'commentId' =>'required|exists:mongodb.podcast_comments,_id',
                'message' =>'required'
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
            $userId = $user->_id;
            $comment =  Helper::getSingleCollectionData('podcast_comments', $params['commentId']);
            $params['podcastId'] = $comment['podcastId'];
            $params['userId'] = $userId;

            $data = PodcastCommentMessage::create($params);
            
            return \Response::json([
                'status' => true,
                'message' => "Message posted on comment successfully",
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
     * Message List On Comments
     * GET
     *
     * @return \Illuminate\Http\Response
    */

    public function comment_messages(Request $request,$id) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            // dd($id);
            $comment = PodcastComment::findOrFail($id);
            $messages = PodcastCommentMessage::with('user:_id,name,email,phone,image,govt_id_card')->where('commentId',$id)->orderBy('_id', 'desc')->get();
            return response()->json([
				'status' => false,
				'message' =>"Messages",
                'data' => $messages
			],200);

        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
    }



}
