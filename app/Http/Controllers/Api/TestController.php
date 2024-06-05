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
        // $sk = config('app.stripe_sk_test');
        // echo $sk;
        // die;
        /*+++++++++++++++++++++++++++++++++++++++++++++++++++++*/
        $message = "Hi there! It is a Test Push Notification";
        $title = "Test FCM";
        $msg = urlencode($message);
        $datapayload = array(
            'title' => 'Test Podcast One ',
            'overview' => 'Test Podcast One'
        );
        $to = ""; ## To User Device Id
        $data = array(
            'title'=>$title,
            'sound' => "default",
            'msg'=>$msg,
            'data'=>$datapayload,
            'body'=>$message,
            'color' => "#79bc64"
        );
        // if($img){
        //     $data["image"] = $img;
        //     $data["style"] = "picture";
        //     $data["picture"] = $img;
        // }
        $fields = array(
            'to'=>$to,
            'notification'=>$data,
            'data'=>$datapayload,
            "priority" => "high",
        );
        $headers = array(
            'Authorization: key=GOOGLE_API_KEY',
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close( $ch );

        print_r($result);
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
