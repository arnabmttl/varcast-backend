<?php

namespace App\Http\Controllers\Admin\Modules\Video;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Video;
use Carbon\Carbon;
use Session;
use Validator;
use File;

class VideoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    public function index()
    {
        $paginate = 20;
        $data = Video::select('*')->paginate($paginate);
        // dd($data);
        return view('admin.modules.video.index', compact('data'));
    }
}
