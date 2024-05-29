<?php

namespace App\Http\Controllers\Admin\Modules\Podcast;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Podcast;
use Carbon\Carbon;
use Session;
use Validator;
use File;


class PodcastController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    public function index()
    {
        $paginate = 20;
        $data = Podcast::select('*')->paginate($paginate);
        // dd($data);
        return view('admin.modules.podcast.index', compact('data'));
    }


}
