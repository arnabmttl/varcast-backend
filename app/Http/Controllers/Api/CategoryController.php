<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
	public function getCetegory(Request $request){
		try{
			$category = Category::orderBy('name','asc')->where(['parent_id' => 0,'status'=>'A']);
		
			if(!empty(@$request->keyword)){
				$category = $category->where(function($query) use ($request){
					$query->where('name', 'Like', '%' . @$request->keyword .'%')
					->orWhere('slug', 'Like', '%' . @$request->keyword .'%')
					->orWhere('description', 'Like', '%' . @$request->keyword .'%');
				});
			}
			
			if(!empty(@$request->page))
			{
				$category = $category->paginate(10);
			}
			else{
				$category = $category->get();
			}
			
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => @trans('success.fetch'),
				'data' => @$category,
				'category_image_path' => url('storage/app/public/category/'),
			],200);
		}
		catch(\Exception $e)
		{
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => @trans('error.problem'),
				'exception' => $e->getMessage(),
			],403);
		}
	}
}
