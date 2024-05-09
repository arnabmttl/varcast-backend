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
        $params['userId'] = '663a30bb31f889e238081e3a';
        $params['slug'] = \Str::slug($params['title']);
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
