<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Business;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\HomeContent;
use App\Models\Lead;
use Carbon\Carbon;
use JWTAuth;
use File;

class HomeController extends Controller
{
	public function getCountry(Request $request){
		try{
			$country = Country::where('phonecode', '!=', '0')->orderBy('sortname','asc');
			if(!empty(@$request->all())){
				if(!empty(@$request->keyword)){
					@$country = @$country->where(function($query) use ($request){
						$query->where('name', 'Like', '%' . @$request->keyword .'%')
						->orWhere('sortname', 'Like', '%' . @$request->keyword .'%')
						->orWhere('phonecode', 'Like', '%' . @$request->keyword .'%');
					});
				}
			}
			if(!empty(@$request->page))
			{
				$country = @$country->paginate(10);
			}
			else{
				$country = $country->get();
			}
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data' => @$country,
			],200);
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => $e->getMessage(),
			],403);
		}
	}
	public function getState(Request $request){
		try{
			$state = State::orderBy('name','asc');
			if(!empty(@$request->all())){
				if(!empty(@$request->keyword)){
					@$state = @$state->where(function($query) use ($request){
						$query->where('name', 'Like', '%' . @$request->keyword .'%');
					});
				}
				if (!empty(@$request->country_id)) {
					@$state = @$state->where('country_id',@$request->country_id);
				}
			}
			if(!empty(@$request->page))
			{
				$state = @$state->paginate(10);
			}
			else{
				$state = $state->get();
			}
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data' => @$state,
			],200);
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => $e->getMessage(),
			],403);
		}
	}
	public function getCity(Request $request){
		try{
			$city = City::orderBy('name','asc')->where('name','!=','');
			if(!empty(@$request->all())){
				if(!empty(@$request->keyword)){
					@$city = @$city->where(function($query) use ($request){
						$query->where('name', 'Like', '%' . @$request->keyword .'%');
					});
				}
				if (!empty(@$request->state_id)) {
					@$city = @$city->where('state_id',@$request->state_id);
				}
			}
			if(!empty(@$request->page))
			{
				$city = @$city->paginate(10);
			}
			else{
				$city = $city->get();
			}
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data' => @$city,
			],200);
		}
		catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => $e->getMessage(),
			],403);
		}
	}
}
