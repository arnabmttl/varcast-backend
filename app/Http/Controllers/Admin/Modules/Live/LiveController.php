<?php

namespace App\Http\Controllers\Admin\Modules\Live;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Live;
use Carbon\Carbon;
use Session;
use Validator;
use File;

class LiveController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    public function index()
    {
        $paginate = 20;
        $data = Live::orderBy('_id', 'desc')->paginate($paginate);
        // dd($data);
        return view('admin.modules.live.index', compact('data','paginate'));
    }
}
