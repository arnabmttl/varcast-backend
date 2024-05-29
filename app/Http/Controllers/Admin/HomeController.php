<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contact;
use App\Models\Banner;
use App\Models\Faq;
use App\Models\Subscribe;
use App\Models\Testimonial;
use App\Models\Podcast;
use App\Models\Video;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    /**
     * Show the Admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $data['total_user'] = User::where('status','!=','D')->count();
        $data['total_contact'] = Contact::count();
        $data['total_banner'] = Banner::count();
        $data['total_faq'] = Faq::count();
        $data['total_subscribe'] = Subscribe::count();
        $data['total_testimonial'] = Testimonial::count();
        $data['total_podcast'] = Podcast::count();
        $data['total_video'] = Video::count();
        return view('admin.modules.dashboard.dashboard',@$data);
        // return view('admin.home');
    }
}
