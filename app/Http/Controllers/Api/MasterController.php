<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\CoinPrice;
use App\Models\Emoji;
use App\Models\MyMusic;
class MasterController extends Controller
{
	public function allTags(Request $request){
		try{
			$result = array();
			$limit = !empty(@$request->limit) ? @$request->limit : 10;
			$allTags = Tag::where('status','A')->orderBy('is_order','asc');
			if(!empty(@$request->keyword)){
				$allTags = $allTags->where(function($query) use ($request){
					$query->where('name', 'Like', '%' . @$request->keyword .'%')
					->orWhere('slug', 'Like', '%' . @$request->keyword .'%');
				});
			}
			if(!empty(@$request->page))
			{
				$allTags = $allTags->paginate(@$limit);
			}
			else{
				$allTags = $allTags->get();
			}
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data'=> @$allTags
			],200);
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => @trans('error.problem'),
				'exception' => $e->getMessage(),
			],403);
		}
	}
	public function coinPlan(Request $request){
		try{
			$result = array();
			$limit = !empty(@$request->limit) ? @$request->limit : 10;
			$allCoinPlan = CoinPrice::where('status','A')->orderBy('plan_name','asc');
			if(!empty(@$request->keyword)){
				$allCoinPlan = $allCoinPlan->where(function($query) use ($request){
					$query->where('plan_name', 'Like', '%' . @$request->keyword .'%');
				});
			}
			if(@$request->_id){
				$allCoinPlan = $allCoinPlan->where("_id",@$request->_id)->first();
			}
			else{
				if(!empty(@$request->page))
				{
					$allCoinPlan = $allCoinPlan->paginate(@$limit);
				}
				else{
					$allCoinPlan = $allCoinPlan->get();
				}
			}
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data'=> $allCoinPlan
			],200);
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => @trans('error.problem'),
				'exception' => $e->getMessage(),
			],403);
		}
	}
	public function allEmoji(Request $request){
		try{
			$result = array();
			$limit = !empty(@$request->limit) ? @$request->limit : 10;
			$allEmoji = Emoji::where('status','A')->orderBy('is_order','asc');
			if(!empty(@$request->keyword)){
				$allEmoji = $allEmoji->where(function($query) use ($request){
					$query->where('usage_coin', (int) @$request->keyword);
				});
			}
			if(!empty(@$request->page))
			{
				$allEmoji = $allEmoji->paginate(@$limit);
			}
			else{
				$allEmoji = $allEmoji->get();
			}
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data'=> @$allEmoji
			],200);
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => @trans('error.problem'),
				'exception' => $e->getMessage(),
			],403);
		}
	}
	public function allMusic(Request $request){
		try{
			$result = array();
			$limit = !empty(@$request->limit) ? @$request->limit : 10;
			$allMusic = MyMusic::where('status','A')->orderBy('is_order','asc');
			if(!empty(@$request->keyword)){
				$allMusic = $allMusic->where(function($query) use ($request){
					$query->where('name', 'Like', '%' . @$request->keyword .'%')
					->orWhere('slug', 'Like', '%' . @$request->keyword .'%')
					->orWhere('author', 'Like', '%' . @$request->keyword .'%');
				});
			}
			if(@$request->_id){
				$allMusic = $allMusic->where("_id",@$request->_id)->first();
			}
			else{
				if(!empty(@$request->page))
				{
					$allMusic = $allMusic->paginate(@$limit);
				}
				else{
					$allMusic = $allMusic->get();
				}
			}
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data'=> @$allMusic
			],200);
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => @trans('error.problem'),
				'exception' => $e->getMessage(),
			],403);
		}
	}
}
