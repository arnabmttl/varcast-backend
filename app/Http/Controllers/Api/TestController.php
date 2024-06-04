<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Helper;
use App\Models\Podcast;
use App\Models\PodcastLike;
use App\Models\PodcastComment;

class TestController extends Controller
{
    //

    public function index(Request $request)
    {
        // $sk = getenv('STRIPE_SECRET_KEY');
        $sk = config('app.stripe_sk_test');
        echo $sk;
        die;
        /*+++++++++++++++++++++++++++++++++++++++++++++++++++++*/
        $url = 'https://fcm.googleapis.com/fcm/send';

        // Put your Server Key here
        $apiKey = "server-api-key";

        // Compile headers in one variable
        $headers = array (
            'Authorization:key=' . $apiKey,
            'Content-Type:application/json'
        );

        // Add notification content to a variable for easy reference
        $notifData = [
            'title' => "Test Title",
            'body' => "Test notification body",
            //  "image": "url-to-image",//Optional
            'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
        ];

        $dataPayload = ['to'=> 'My Name', 
        'points'=>80, 
        'other_data' => 'This is extra payload'
        ];

        // Create the api body
        $apiBody = [
            'notification' => $notifData,
            'data' => $dataPayload, //Optional
            'time_to_live' => 600, // optional - In Seconds
            //'to' => '/topics/mytargettopic'
            //'registration_ids' = ID ARRAY
            'to' => 'cc3y906oCS0:APA91bHhifJikCe-6q_5EXTdkAu57Oy1bqkSExZYkBvL6iKCq2hq3nrqKWymoxfTJRnzMSqiUkrWh4uuzzEt3yF5KZTV6tLQPOe9MCepimPDGTkrO8lyDy79O5sv046-etzqCGmKsKT4'
        ];

        // Initialize curl with the prepared headers and body
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, true);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

        // Execute call and save result
        $result = curl_exec($ch);
        print($result);
        // Close curl after call
        curl_close($ch);

        return $result;
    }

    public function upload(Request $request) {
        

        $file = $request->file('file');
        $file_name= time()."_".$file->getClientOriginalName();
        $location="uploads/livevideos/";
        //dd($location);
        $file->move($location,$file_name);
        $filename=$location."".$file_name;
        // $params['image']=$filename;
        dd($file);
        // Category::insert($params);
    }

    public function comments(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}

            $validator = \Validator::make($request->all(),[
                'podcastId' =>'required|exists:mongodb.podcasts,_id'
            ]);
    
            if($validator->fails()){
                foreach($validator->errors()->messages() as $key => $value){
                    return \Response::json([
                        'status' => false,
                        'message' =>  $value[0],
                        'data' => (object)[]
                    ], 400);
                }
            }

            $userId = $user->_id;

            $podcastId = !empty($request->podcastId)?$request->podcastId:'';
            $take = !empty($request->take)?$request->take:15;
            $page = !empty($request->page)?$request->page:0;
            $skip = ($page*$take);

            // $totalData = PodcastComment::where('podcastId',$podcastId)->count();
            // $listData = PodcastComment::with('user:_id,name,email,phone,username')->where('podcastId', $podcastId)->orderBy('_id','desc')->take($take)->skip($skip)->get();

            $data = Helper::getCountryCode();
            
            return \Response::json([
                'status' => true,
                'message' => "All countries",
                'data' =>  array(
                    // 'totalData' => $totalData,
                    'listData' => $data
                )
            ], 200);


        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
    }

}
