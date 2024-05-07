<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserShort;
use Storage;
use JWTAuth;
use Validator;
use Str;
use File;
use Carbon\Carbon;
class UserShortsController extends Controller
{
    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', @$token);
    }
    public function createShorts(Request $request){
    	try {
    		if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.not_found'),
				], 200);
			}
			$validator = Validator::make($request->all(), [
				'taging' => 'nullable|array',
				'category' => 'required|array',
	            'title' => 'required|string|max:199',
	            'description' => 'nullable|string|max:1000',
	            'thumbnail_image' => 'nullable|image',
	            'video' => 'required_without:_id|mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv',
	            'status' => 'required|in:A,DR'
			]);
			
			if ($validator->fails()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @$validator->errors()->first()
				],200);
			}
			$data['user_id'] = @$user->_id;
			$data['title'] = @$request->title;
			$data['description'] = @$request->description;
			$data['category'] = @$request->category;
			$data['status'] = @$request->status;
			if(!empty(@$request->taging)){
				$data['taging'] = @$request->taging;
			}
			$shortsData = null;
			if(!empty(@$request->_id)){
				$shortsData = UserShort::where('_id',@$request->_id)->first();
			}
			if ($request->hasFile('thumbnail_image')) {
				if(!empty(@$request->_id)){
					if (!empty(@$shortsData)) {
						if (File::exists("storage/app/public/shorts/image/" . @$shortsData->thumbnail_image)) {
							File::delete("storage/app/public/shorts/image/" . @$shortsData->thumbnail_image);
						}
					}
				}
				$file      = $request->file('thumbnail_image');
				$time      = Carbon::now();
				$extension = $file->getClientOriginalExtension();
				$filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
				$file->storeAs('public/shorts/image', @$filename);
				$data['thumbnail_image'] = $filename;
			}
			if ($request->hasFile('video')) {
				if(!empty(@$request->_id)){
					if (!empty(@$shortsData)) {
						if (File::exists("storage/app/public/shorts/video/" . @$shortsData->video)) {
							File::delete("storage/app/public/shorts/video/" . @$shortsData->video);
						}
					}
				}
				$file      = $request->file('video');
				$time      = Carbon::now();
				$extension = $file->getClientOriginalExtension();
				$filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
				$file->storeAs('public/shorts/video', @$filename);
				$data['video'] = $filename;
			}
			$save = UserShort::updateOrCreate(['_id' => @$request['_id']], $data);
			if(!empty($save))
			{
				$upSlug =  Str::slug(@$request->title).'-'.@$save->_id;
				@$save->update(['slug' => @$upSlug]);
				return response()->json([
					"code"=> 200,
					'status' => 'success',
					'message' => @trans('success.shorts_insert'),
					'data' => @$save,
				],200);
			}
			else{
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.problem'),
					'data' => @$save,
				],200);
			}
    	}
    	catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'token_expire',
				'message' => $e->getMessage(),
			],403);
		}
    }
    public function listShorts(Request $request){
    	try {
    		if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.not_found'),
				], 200);
			}

    		$shorts = UserShort::latest()->where(['user_id' => @$user->_id])->where('status','!=','D');
		
			if(!empty(@$request->keyword)){
				$shorts = $shorts->where(function($query) use ($request){
					$query->where('title', 'Like', '%' . @$request->keyword .'%')
					->orWhere('slug', 'Like', '%' . @$request->keyword .'%')
					->orWhere('description', 'Like', '%' . @$request->keyword .'%');
				});
			}
			if(!empty(@$request->status)){
				$shorts = $shorts->where('status',@$request->status);
			}
			
			if(!empty(@$request->page))
			{
				$limit = @$request->limit ? @$request->limit : 10;
				$shorts = $shorts->paginate(@$limit);
			}
			else{
				$shorts = $shorts->get();
			}
			
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data' => @$shorts,
			],200);
    	}
    	catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'token_expire',
				'message' => $e->getMessage(),
			],403);
		}
    }
    public function shortstatuschange(Request $request){
    	try {
    		if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.not_found'),
				], 200);
			}
    		$validator = Validator::make($request->all(), [
				'_id' => 'required|string',
				'status' => 'required|in:A,I,D,DR',
			]);
			
			if ($validator->fails()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @$validator->errors()->first()
				],200);
			}
    		$shorts = UserShort::where(['user_id' => @$user->_id, '_id' => @$request->_id])->first();
    		if(@$shorts){
    			@$shorts->update(['status'=>@$request->status]);
    			return response()->json([
					"code"=> 200,
					'status' => 'success',
					'message' => @trans('success.shorts_status'),
					'data' => @$shorts,
				],200);
    		}
    		else{
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.problem'),
					'data' => @$save,
				],200);
			}
    	}
    	catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'token_expire',
				'message' => $e->getMessage(),
			],403);
		}
    }
}
