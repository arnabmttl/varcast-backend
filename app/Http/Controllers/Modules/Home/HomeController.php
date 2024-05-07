<?php

namespace App\Http\Controllers\Modules\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Banner;
use App\Models\User;
use App\Models\Business;
use App\Models\City;
use App\Models\Subscribe;
use App\Models\HomeContent;
use App\Models\Testimonial;
use Validator;
use Helper;
use File;

class HomeController extends Controller
{
    /**
     * for home page data
     */
    public function index() {
        
        return view('modules.home.home');
    }
    public function download() {
        return view('modules.cms.download');
    }
}
