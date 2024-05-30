<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use App\Models\Podcast;
use App\Models\PodcastLike;
use App\Models\PodcastComment;
use Helper;

class NotificationController extends Controller
{
    //

    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }

    /**
     * List of user notifications
     * GET
     *
     * @return \Illuminate\Http\Response
     */


    public function index(Request $request) : JsonResponse {
        
    }
}
