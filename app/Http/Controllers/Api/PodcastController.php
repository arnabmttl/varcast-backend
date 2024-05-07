<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Podcast;

class PodcastController extends Controller
{
    //

    public function list(Request $request) : JsonResponse {
        $data = (object)[];
        $countData = DB::connection('mongodb')->collection('podcasts')->count();
        $listData = Podcast::get();
        return \Response::json([
            'status' => true,
            'message' => "All podcast lists",
            'data' => array(
                'countData' => $countData,
                'listData' => $listData
            )
        ], 200);
    }

    public function create(Request $request) : JsonResponse {

        $title = $request->title ?? '';
        $overview = $request->overview ?? '';

        

        $validator = \Validator::make($request->all(),[
            'title' => 'required',
            'overview' => 'required'
        ]);
        if($validator->fails()){
            foreach($validator->errors()->messages() as $key => $value){
                return response()->json(['status' => $value[0]], 400);
            }
        }
        
        $params = $request->except('_token');
        $params['userId'] = '6639fccb4b04e6702ef3b956';
        $params['isActive'] = true;
        
        $podcast = Podcast::create($params);
        // echo $podcast->toJson();
        return \Response::json([
            'status' => true,
            'message' => "Podcast Created",
            'data' =>  $podcast
        ], 201);
    }
}
