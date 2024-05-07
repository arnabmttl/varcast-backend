<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\ScorecardContent;
use App\Models\Contact;
use Session;
use Validator;
use Helper;
class ContentController extends Controller
{
	public function getContent(Request $request){
		try{
			if(!empty(@$request->page)){
				$content = Content::where('type',@$request->page)->get();
			}
			else{
				$content = Content::all();
			}
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => 'fetch successfully.',
				'data' => @$content,
				'content_image_path' => url('storage/app/public/content/'),
			],200);
		}
		catch(\Exception $e)
		{
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => 'Sorry a problem has occurred.',
				'exception' => $e->getMessage(),
			],403);
		}
	}
	public function businessScoreCardContent(Request $request){
		try{
			$content = ScorecardContent::select('banner_image','banner_title','banner_short_description','banner_form_title','banner_form_description')->first();
			if(empty(@$content)){
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => 'Data not found.',
				],200);
			}
			
			return response()->json([
				"code"=> 200,
				'status' => 'success',
				'message' => 'fetch successfully.',
				'data' => @$content,
			],200);
		}
		catch(\Exception $e)
		{
			return response()->json([
				"code"=> 403,
				'status' => 'error',
				'message' => 'Sorry a problem has occurred.',
				'exception' => $e->getMessage(),
			],403);
		}
	}
	public function contactUsFormstore(Request $request) {
		try {
			$validator = Validator::make(@$request->all(), [
				'name' => 'required|string|max:255',
				'email' => 'required|email|max:199',
				'phone' => 'required|digits_between:9,15',
				'message' => 'required|max:500',
			]);
			if ($validator->fails()) {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => @$validator->errors()->first()
				],200);
			}

			$new['name'] = $request->name;
			$new['email'] = $request->email;
			$new['phone'] = $request->phone;
			$new['message'] = nl2br($request->message);
			$a = Contact::create($new);
			if ($a) {
                // send mail to user
				$mailTo = [@$request->email];
				$mailToName = [@$request->name];
				$mailData = [
					'subject' => "InfoTree Contact Us",
					'to_mail' => @$mailTo,
					'to_mail_name' => @$mailToName,
					'short_title' => "Contact message sent successfully.",
					'body' => 'Message - '.$request->message,
				];
				Helper::sendMailToUser(@$mailData);
				return response()->json([
					"code"=> 200,
					'status' => 'success',
					'message' => 'Message sent successfully.',
				],200);
			} else {
				return response()->json([
					"code"=> 200,
					'status' => 'error',
					'message' => 'Something wents to wrong. Please try again later.',
				], 200);
			}
		} catch(\Exception $e) {
			return response()->json([
				"code"=> 403,
				'status' => 'token_expire',
				'message' => $e->getMessage(),
			],403);
		}
	}
}
