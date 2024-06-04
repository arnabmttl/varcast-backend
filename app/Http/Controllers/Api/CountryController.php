<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Helper;
use App\Models\Country;

class CountryController extends Controller
{
    //

    public function index(Request $request) : JsonResponse {
        $search = !empty($request->search)?$request->search:'';

        $data = Helper::getCountryCode($search);

        return \Response::json([
            'status' => true,
            'message' => "All countries",
            'data' =>  array(
                'countData' => count($data),
                'listData' => $data
            )
        ], 200);
    }
}
