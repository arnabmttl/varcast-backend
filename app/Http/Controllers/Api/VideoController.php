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
use App\Models\VideoView;
use App\Models\VideoCategory;
use App\Models\VideoDraft;
use App\Models\VideoDraftCategory;
use App\Models\Follow;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use MongoDB\BSON\ObjectID;
use File;
use Helper;

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
    public function list(Request $request): JsonResponse
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
            $take = !empty($request->take)?$request->take:15;
            $page = !empty($request->page)?$request->page:0;
            $skip = ($page * $take);

            $listData = Video::with([
                'comments' => function($c){
                    $c->with('user:_id,name,email');
                },
                'likes' => function($l){
                    $l->with('user:_id,name,email');
                }
                ])->orderBy('_id', 'desc')->take($take)->skip($skip)->get();
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
            \App\Models\ApiRequestLog::create(['request' => json_encode($request->all())]);
            $validator = \Validator::make($request->all(),[
                'title' => 'nullable',
                'description' => 'nullable',                
                'image' => 'required|file',
                'audioUrl' => 'nullable',
                'tags' => 'array',
                'categoryIds' => 'array'
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
            $params['isActive'] = true;
            // $params['slug'] = \Str::slug($params['title']);
            

            $categoryIds = $params['categoryIds'];
            if(!empty($categoryIds)){
                foreach($categoryIds as $cat){
                    $checkCategory = Category::find($cat);
                    if(empty($checkCategory)){
                        return response()->json([
                            'status' => false,
                            'message' => "Unknown category id ",
                            'data' => [
                                'category_id' => $cat
                            ]
                        ],400);
                    }

                    
                }
            }

            // dd($params);


            $file = $request->file('image');
            $file_name= time()."_".$file->getClientOriginalName();
            $location="uploads/videos/";
            //dd($location);
            $file->move($location,$file_name);
            $filename=$location."".$file_name;
            $params['image']=$filename;

            $ext = explode(".",$filename);
            $ext = end($ext);
            # image or video
            $videoExtensions = ["mp4","mov","wmv","avi","flv","avchd","f4v","swf","mkv"];
            $imageExtensions = ["jpg","png","jpeg","gif","svg","tiff"];

            // echo $ext; die;

            $image_type = "image";
            if(in_array($ext,$videoExtensions)){
                $image_type = "video";
            } else if (in_array($ext,$imageExtensions)){
                $image_type = "image";
            }

            // echo $image_type; die;

            $params['image_type'] = $image_type;
            unset($params['categoryIds']);
            $data = Video::create($params);
            $videoId = $data->_id;
            // dd($videoId);

            if(!empty($categoryIds)){
                foreach($categoryIds as $cat){
                    VideoCategory::create([
                        'videoId' => $videoId,
                        'categoryId' => $cat
                    ]);
                }
            }
            

            /* Add Activity */
            Helper::addActivity($user->_id,'create_video','Created a video');

            /* Add Notification */
            $authUserName = $user->name;
            $notificationMsg = $authUserName." created a new video";

            $followers = Follow::with('followers:_id,name,email,phone')->where('userId', $user->_id)->get();
            if(!empty($followers)){
                foreach($followers as $follow){
                    Helper::addNotification($follow->followers->_id, 'create_video', $notificationMsg);
                }
            }
            
            
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

            $video = Video::find($params['videoId']);
            $videoUserId = $video->userId;
    
            $existLiked = VideoLike::where('videoId', $params['videoId'])->where('userId', $params['userId'])->first();
    
            $msg = "";
            if(!empty($existLiked)){
                VideoLike::where('_id', $existLiked->_id)->delete();
                $msg = "Disliked";
            } else {
                VideoLike::create($params);
                $msg = "Liked";
                /* Add Activity */
                Helper::addActivity($user->_id,'like_video','Liked a video');

                /* Add Notification */
                if($videoUserId != $user->_id){
                    $authUserName = $user->name;
                    $notificationMsg = $authUserName." liked your video";
                    Helper::addNotification($videoUserId, 'like_video', $notificationMsg);
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

            $video = Video::find($params['videoId']);
            $videoUserId = $video->userId;
            
            $data = VideoComment::create($params);
            $msg = "Commented";

            /* Add Activity */
            Helper::addActivity($user->_id,'comment_video','Commented a video');


            /* Add Notification */
            if($videoUserId != $user->_id){
                $authUserName = $user->name;
                $notificationMsg = $authUserName." commented on your your video";
                Helper::addNotification($videoUserId, 'comment_video', $notificationMsg);
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
                'videoId' =>'required|exists:mongodb.videos,_id'
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

            $videoId = !empty($request->videoId)?$request->videoId:'';
            $data = Video::with('categories:_id,categoryId,videoId')->with('user:_id,name,email,phone')->find($videoId);

            $isLiked = VideoLike::where('videoId', $videoId)->where('userId', $userId)->count();
            $isLiked = (!empty($isLiked))?true:false;

            $latestComments = VideoComment::with('user:_id,name,email,phone,username')->where('videoId', $videoId)->orderBy('_id','desc')->take(15)->get();
            
            /* Add View For Each New User */
            $existView = VideoView::where('userId', $userId)->where('videoId',$videoId)->first();
            if(empty($existView)){
                VideoView::create([
                    'userId' => $userId,
                    'videoId' => $videoId
                ]);
            }

            $data->countView = VideoView::where('videoId',$videoId)->count();
            $data->countLike = VideoLike::where('videoId',$videoId)->count();
            $data->isLiked = $isLiked;
            $data->latestComments = $latestComments;

            return \Response::json([
                'status' => true,
                'message' => "Video Details",
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
                'videoId' =>'required|exists:mongodb.videos,_id'
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

            $videoId = !empty($request->videoId)?$request->videoId:'';
            $take = !empty($request->take)?$request->take:15;
            $page = !empty($request->page)?$request->page:0;
            $skip = ($page*$take);

            $totalData = VideoComment::where('videoId',$videoId)->count();
            $listData = VideoComment::with('user:_id,name,email,phone,username')->where('videoId', $videoId)->orderBy('_id','desc')->take($take)->skip($skip)->get();
            
            return \Response::json([
                'status' => true,
                'message' => "Videos Comments",
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

    /**
     * Save Draft
     * POST
     *
     * @return \Illuminate\Http\Response
    */

    public function save_draft(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $validator = \Validator::make($request->all(),[
                'title' => 'nullable',
                'description' => 'nullable',                
                'image' => 'required|file',
                'tags' => 'array',
                'categoryIds' => 'array'
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
            $params['isActive'] = true;
            
            $categoryIds = $params['categoryIds'];
            if(!empty($categoryIds)){
                foreach($categoryIds as $cat){
                    $checkCategory = Category::find($cat);
                    if(empty($checkCategory)){
                        return response()->json([
                            'status' => false,
                            'message' => "Unknown category id ",
                            'data' => [
                                'category_id' => $cat
                            ]
                        ],400);
                    }
                }
            }

            // dd($params);


            $file = $request->file('image');
            $file_name= time()."_".$file->getClientOriginalName();
            $location="uploads/videos/";
            //dd($location);
            $file->move($location,$file_name);
            $filename=$location."".$file_name;
            $params['image']=$filename;

            
            unset($params['categoryIds']);
            $data = VideoDraft::create($params);
            $videoDraftId = $data->_id;
            // dd($videoId);

            if(!empty($categoryIds)){
                foreach($categoryIds as $cat){
                    VideoDraftCategory::create([
                        'videoDraftId' => $videoDraftId,
                        'categoryId' => $cat
                    ]);
                }
            }
                        
            return \Response::json([
                'status' => true,
                'message' => "Draft Saved Successfully",
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
     * List Draft
     * GET
     *
     * @return \Illuminate\Http\Response
    */

    public function list_draft(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $userId = $user->_id;
            $listData = VideoDraft::with('categories')->where('userId', $userId)->orderBy('_id', 'desc')->get();
            return \Response::json([
                'status' => true,
                'message' => "My drafts",
                'data' => array(
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
     * Publish Draft
     * Clear Draft
     * Create Video From Draft
     * POST
     *
     * @return \Illuminate\Http\Response
    */

    public function publish_draft(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $userId = $user->_id;
            
            $validator = \Validator::make($request->all(),[
                'videoDraftId' =>'required|exists:mongodb.video_drafts,_id' ,
                'title' => 'nullable',
                'description' => 'nullable',                
                'image' => 'file',
                'audioUrl' => 'nullable',
                'tags' => 'nullable|array',
                'categoryIds' => 'nullable|array'
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
            $videoDraftId = $params['videoDraftId'];
            $videoDraft = VideoDraft::find($videoDraftId);
            
            $tags = $videoDraft->tags;
            // dd($tags);
            $videoArr['title'] = isset($params['title'])?$params['title']:$videoDraft->title;
            $videoArr['description'] = isset($params['description'])?$params['description']:$videoDraft->description;
            $videoArr['audioUrl'] = isset($params['audioUrl'])?$params['audioUrl']:$videoDraft->audioUrl;
            $videoArr['tags'] = $tags;
            $videoArr['image'] = $videoDraft->image;
            $videoArr['userId'] = $videoDraft->userId;
            $videoArr['isActive'] = $videoDraft->isActive;
            $videoArr['image'] = $videoDraft->image;
            // dd($tags);

            if ($request->hasFile('image')) {
                if ( !empty($videoDraft->image) ) {
					if (File::exists($videoDraft->image)) {
						File::delete($videoDraft->image);
					}
				}

                $fileImage = $request->file('image');
				$file_name_image= time()."_".$fileImage->getClientOriginalName();
				$locationImage="uploads/videos/";
				$fileImage->move($locationImage,$file_name_image);
				$imagefilename=$locationImage."".$file_name_image;
				$videoArr['image']=$imagefilename;
            }

            $video = Video::create($videoArr);
            $videoId = $video->_id;

            $videoDraftCategory = VideoDraftCategory::where('videoDraftId' , $videoDraftId)->get();
            if(!empty($videoDraftCategory)){
                foreach($videoDraftCategory as $cat){
                    VideoCategory::create([
                        'videoId' => $videoId,
                        'categoryId' => $cat
                    ]);
                }
            }

            VideoDraft::where('_id', $videoDraftId)->delete();
            VideoDraftCategory::where('videoDraftId', $videoDraftId)->delete();

            

            return \Response::json([
                'status' => true,
                'message' => "Draft Published Successfully",
                'data' =>  $params
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
