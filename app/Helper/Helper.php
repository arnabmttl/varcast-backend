<?php

namespace App\Helper;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Content;
use App\Models\Setting;
use App\Models\UserToDeviceToken;
use App\Models\Activity;
use App\Models\UserCoin;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Mail\commonMail;
use Mail;

class Helper
{
    public static function sendMailToUser($mailData = []){
        try{
            foreach($mailData['to_mail'] as $key => $value){
                $mail_data = [
                    'subject' => $mailData['subject'],
                    'to_mail' => @$value,
                    'mail_title' => @$mailData['mail_title'],
                    'to_mail_name' => $mailData['to_mail_name'][@$key],
                    'short_title' => @$mailData['short_title'],
                    'mail_type' => @$mailData['mail_type'],
                    'otp' => @$mailData['otp'],
                    'body' => $mailData['body'],
                ];
                Mail::send(new commonMail(@$mail_data));
            }
        }
        catch(\Exception $e){
            return response()->json([
                'data' => [
                    "status"  => 'catch_error',
                    "message" => $e->getMessage(),
                ],
            ], 200);
        }
    }

    // get country code
    public static function getCountryCode($search=''){
        try{
            $country = Country::orderBy('phonecode','asc');
            
            if(!empty($search)){
                $country = $country->where('name', 'LIKE', '%'.$search.'%')->orWhere('phonecode', 'LIKE','%'.$search);
            }
            
            $country = $country->get();
            return $country;
        }
        catch(\Exception $e){
            return response()->json([
                'data' => [
                    "status"  => 'catch_error',
                    "message" => $e->getMessage(),
                ],
            ], 200);
        }
    }
    public static function getAboutContent(){
        try{
            $aboutContent = Content::where('type','about')->first();
            return $aboutContent;
        }
        catch(\Exception $e){
            return response()->json([
                'data' => [
                    "status"  => 'catch_error',
                    "message" => $e->getMessage(),
                ],
            ], 200);
        }
    }
    public static function getTermsContent(){
        try{
            $termsContent = Content::where('type','terms')->first();
            return $termsContent;
        }
        catch(\Exception $e){
            return response()->json([
                'data' => [
                    "status"  => 'catch_error',
                    "message" => $e->getMessage(),
                ],
            ], 200);
        }
    }
    public static function getPrivacyContent(){
        try{
            $privacyContent = Content::where('type','privacy')->first();
            return $privacyContent;
        }
        catch(\Exception $e){
            return response()->json([
                'data' => [
                    "status"  => 'catch_error',
                    "message" => $e->getMessage(),
                ],
            ], 200);
        }
    }
    public static function getContactUsContent(){
        try{
            $setting = Setting::first();
            return $setting;
        }
        catch(\Exception $e){
            return response()->json([
                'data' => [
                    "status"  => 'catch_error',
                    "message" => $e->getMessage(),
                ],
            ], 200);
        }
    }
    public static function sendNotification($notiData)
    {
        // $firebaseToken = User::whereNotNull('device_token')->whereId(1)->pluck('device_token')->toArray();

        // $SERVER_API_KEY = env('FCM_SERVER_KEY');

        // $data = [
        //     "registration_ids" => @$notiData['diviceToken'],
        //     "notification" => [
        //         "title" => @$notiData['title'],
        //         "body" => @$notiData['body'],  
        //     ]
        // ];
        // $dataString = json_encode($data);

        // $headers = [
        //     'Authorization: key=' . $SERVER_API_KEY,
        //     'Content-Type: application/json',
        // ];

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        // $response = curl_exec($ch);
        // dd($response);
        $SERVER_API_KEY = env('FCM_SERVER_KEY');
        $content = array(
            "en" => @$notiData['body']
        );
        $fields = array(
            'app_id' => '03defe23-7795-4b8a-9f10-032becd4806e',
            'include_player_ids' => @$notiData['diviceToken'],
            // 'web_url'=> MANAGER_URL.'notifications',
            'app_url'=>'',
            'large_icon' => 'https://dev7.ivantechnology.in/infotreeit/public/customer/images/logo.png',
            'contents' => $content,
            // "is_android" => true,
            // "is_ios" => true,
            // 'included_segments' => array('All'),
        );
        // dd(@$notiData['diviceToken']);
        $fields = json_encode($fields);
        // dd($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic YWExZjc5MDItMDcyZC00NjMyLTg3MjYtYTNlZGI1Y2QxODM2'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    

        $response = curl_exec($ch);
        curl_close($ch);
        // dd(@$response);

    }
    public static function storeDeviceTokenUserWise($userId, $deviceToken){
        try{
            $checkExistData = UserToDeviceToken::where('user_id',@$userId)->where('device_token',@$deviceToken)->first();
            if(empty(@$checkExistData)){
                UserToDeviceToken::create([
                    'user_id' => @$userId,
                    'device_token' => @$deviceToken
                ]);
            }

        }
        catch(\Exception $e){
            return response()->json([
                'data' => [
                    "status"  => 'catch_error',
                    "message" => $e->getMessage(),
                ],
            ], 200);
        }
    }

    public static function addActivity($userId,$type,$message){
        Activity::create(
            [
                'userId' => $userId,
                'type' => $type,
                'message' => $message
            ]
        );
    }

    public static function addNotification($userId,$type,$message){
        Notification::create(
            [
                'userId' => $userId,
                'type' => $type,
                'message' => $message
            ]
        );
    }

}
