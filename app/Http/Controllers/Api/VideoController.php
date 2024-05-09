<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use App\Models\VideoLike;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;

class VideoController extends Controller
{

    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', @$token);
    }


    /**
     * List of all video.
     * GET
     *
     * @return \Illuminate\Http\Response
     */
    public function list(): JsonResponse
    {
        

        $data = (object)[];

        $countData = DB::connection('mongodb')->collection('videos')->count();
        $listData = Video::with('likes')->get();
        return \Response::json([
            'status' => true,
            'message' => "All videos",
            'data' => array(
                'countData' => $countData,
                'listData' => $listData
            )
        ], 200);

    }

    /**
     * Create Video
     * POST
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) : JsonResponse
    {
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
        $params['userId'] = '663a30bb31f889e238081e3a';
        $params['isActive'] = true;
        $params['slug'] = \Str::slug($params['title']);
        // dd($params);
        $data = Video::create($params);
        
        return \Response::json([
            'status' => true,
            'message' => "Video Created",
            'data' =>  $data
        ], 201);


    }

    /**
     * Like / Dislike Video
     * POST
     *
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request): JsonResponse {
        
        $validator = \Validator::make($request->all(),[
            'videoId' =>'required'           
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
        $params['userId'] = '663a30bb31f889e238081e3a';

        $existVideo = Video::where('_id', $params['videoId'])->first();

        if(empty($existVideo)){
            return response()->json([
                "code"=> 400,
                'status' => 'invalid_video',
                'message' => "Invalid video id"
            ],400);
        }
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
        ], 201);

    }

    
}
