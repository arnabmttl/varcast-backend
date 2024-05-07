<?php

namespace App\Http\Controllers\Api\YourLibrary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\YourLibrary;

class YourLibraryController extends Controller
{
	public function __construct(Request $request)
	{
		$token = $request->header('x-access-token');
		$request->headers->set('Authorization', @$token);
	}
	public function FunctionName($value='')
	{
		try {
			if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @trans('error.not_found'),
				], 200);
			}
			
			$validator = Validator::make($request->all(), [
				'name'  => 'required|string|max:199',
				'type'  => 'required|string|in:playlist,podcasts,artists',
				'thumbnail_image' => 'nullable|image',
				'about' => 'nullable|string',
			]);
			if ($validator->fails()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @$validator->errors()->first()
				],200);
			}
			if(empty(@$request->all())){
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => 'Please sent any object value to update profile.',
				],200);
			}
			if (!empty(@$user)) {
				if(@$user->status == 'I')
				{
					return response()->json([
						"code"=> 200,
						'status' => 'inactive_account',
						'message' => 'Your account is inactive by admin.'
					],200);
				}
				
				
				$data = [];
				if(!empty(@$request->name)){
					$data['name'] = @$request->name;
				}
				if(!empty(@$request->email)){
					$data['email'] = @$request->email;
				}
				if ($request->hasFile('thumbnail_image')) {
					if(!empty(@$request->_id)){
						$library = YourLibrary::where('_id',@$request->_id)->first();
						if (@$library->thumbnail_image) {
							if (File::exists("storage/library/" . @$library->thumbnail_image)) {
								File::delete("storage/library/" . @$library->thumbnail_image);
							}
						}
					}
					$file      = $request->file('thumbnail_image');
					$time      = Carbon::now();
					$extension = $file->getClientOriginalExtension();
					$filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
					$file->storeAs('public/library', @$filename);
					$data['thumbnail_image'] = $filename;
				}
				
				return response()->json([
					"code"=> 200,
					'status' => 'success',
					'message' => "Profile updated successfully.",
					'data' => @$user_details,
				],200);
			}
			else{
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => 'Data not found.',
				], 200);
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
