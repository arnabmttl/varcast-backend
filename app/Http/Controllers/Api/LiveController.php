<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Live;

class LiveController extends Controller
{
    //

    public function __construct() {

    }

    public function list() : JsonResponse {
        
        $data = (object)[];

        $countData = DB::connection('mongodb')->collection('lives')->count();
        $listData = Live::get();
        return \Response::json([
            'status' => true,
            'message' => "All live lists",
            'data' => array(
                'countData' => $countData,
                'listData' => $listData
            )
        ], 200);

        return \Response::json([
            'status' => true,
            'message' => "All live data",
            'data' => $data
        ]);
    }

    public function create(Request $request) : JsonResponse {
        
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
        $data = Live::create($params);
        
        return \Response::json([
            'status' => true,
            'message' => "Live Created",
            'data' =>  $data
        ], 201);


    }
}
