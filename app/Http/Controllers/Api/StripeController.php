<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    //
    public function test(Request $request) {

        // $sk = getenv('STRIPE_SECRET_KEY');
        $sk = config('app.stripe_sk_test');
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
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        // curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);
        $result = curl_exec($ch); //execute post
        // echo"<pre>";
        print_r($result);
        $res = json_decode($result);
        // print_r($res);
        // echo $res->url;
        exit;

    }

    public function post(Request $request){
        // $sk = getenv('STRIPE_SECRET_KEY');
        $sk = config('app.stripe_sk_test');
        $url ="https://api.stripe.com/v1/tokens";
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$sk
        );
        
        $ch = curl_init(); //open connection
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $postFields = array(
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 12,
                'exp_year' => 2024,
                'cvc' => '314',
            ],
            'customer' => 'cus_Q7a6ZPVwIi1u00'
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS,
        json_encode ($postFields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 180);
        $result = curl_exec($ch); //execute post
        // echo"<pre>";
        print_r($result);
        
        exit;
    }
}
