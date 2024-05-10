<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Live;
use App\Models\LiveLike;
use App\Models\LiveComment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;

class LiveController extends Controller
{
    //

    public function __construct() {

    }

    /**
     * List of all lives.
     * GET
     *
     * @return \Illuminate\Http\Response
     */

    public function list() : JsonResponse {
        
        $data = (object)[];

        $countData = DB::connection('mongodb')->collection('lives')->count();
        $listData = Live::with('likes','comments')->get();
        return \Response::json([
            'status' => true,
            'message' => "All live lists",
            'data' => array(
                'countData' => $countData,
                'listData' => $listData
            )
        ], 200);

    }

    /**
     * Create Lives
     * POST
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request) : JsonResponse {
        
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
        $params['userId'] = '663a30bb31f889e238081e3a';
        $params['isActive'] = true;
        $params['slug'] = \Str::slug($params['title']);
        // dd($params);
        $data = Live::create($params);
        
        return \Response::json([
            'status' => true,
            'message' => "Live Created",
            'data' =>  $data
        ], 201);


    }

    
    /**
     * Like / Dislike Lives
     * POST
     *
     * @return \Illuminate\Http\Response
     */

    public function like(Request $request): JsonResponse {
              
        $validator = \Validator::make($request->all(),[
            'liveId' =>'required'           
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

        $existLives = Live::where('_id', $params['liveId'])->first();
        if(empty($existLives)){
            return response()->json([
                "code"=> 400,
                'status' => 'validation',
                'message' => "Invalid live id"
            ],400);
        }
        $existLiked = LiveLike::where('liveId', $params['liveId'])->where('userId', $params['userId'])->first();

        $msg = "";
        if(!empty($existLiked)){
            LiveLike::where('_id', $existLiked->_id)->delete();
            $msg = "Disliked";
        } else {
            LiveLike::create($params);
            $msg = "Liked";
        }
        
        return \Response::json([
            'status' => true,
            'message' => $msg,
            'data' =>  (object)[]
        ], 201);

    }

    /**
     * Comment On Lives
     * POST
     *
     * @return \Illuminate\Http\Response
    */

    public function comment(Request $request) : JsonResponse {
        
        $validator = \Validator::make($request->all(),[
            'liveId' =>'required',
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
        $params['userId'] = '663a30bb31f889e238081e3a';

        $existLives = Live::where('_id', $params['liveId'])->first();
        if(empty($existLives)){
            return response()->json([
                "code"=> 400,
                'status' => 'validation',
                'message' => "Invalid live id"
            ],400);
        }
        
        $data = LiveComment::create($params);
        $msg = "Commented successfully";
        
        
        return \Response::json([
            'status' => true,
            'message' => $msg,
            'data' =>  $data
        ], 201);

    }

}
