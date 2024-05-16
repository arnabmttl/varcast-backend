<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    //
    public function test(Request $request) {

        // dd(getenv('STRIPE_PUBLISHABLE_KEY'));

        $sk = getenv('STRIPE_SECRET_KEY');
        $url ="https://api.stripe.com/v1/charges";
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer '.$sk
        );
        
        $ch = curl_init(); //open connection
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 180);
        $result = curl_exec($ch); //execute post
        // echo"<pre>";
        print_r($result);
        $res = json_decode($result);
        print_r($res);
        echo $res->url;
        exit;

        // return response()->json($result,  $headers);

        

    }

    public function post(Request $request){
        
    }
}
