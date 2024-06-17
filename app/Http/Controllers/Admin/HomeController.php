<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Faq;
use App\Models\Subscribe;
use App\Models\Testimonial;
use App\Models\Podcast;
use App\Models\Video;
use App\Models\VideoView;
use App\Models\LiveView;
use App\Models\PodcastView;
use App\Models\Live;
use App\Helper\Helper;
use Illuminate\Support\Facades\DB;

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
        $data['total_category'] = Category::count();
        $data['total_banner'] = Banner::count();
        $data['total_faq'] = Faq::count();
        $data['total_subscribe'] = Subscribe::count();
        $data['total_testimonial'] = Testimonial::count();
        $data['total_podcast'] = Podcast::count();
        $data['total_video'] = Video::count();
        $data['total_live'] = Live::count();

        /*$video_views = VideoView::query();
        $video_views = $video_views->groupBy('videoId')->get()->toArray();     
        $video_views = array_values($video_views);
        foreach($video_views as $key => $video){
            $views = VideoView::where('videoId', $video['videoId'])->count();
            $video_views[$key]['views'] = $views;
            // $videoData = Helper::getSingleCollectionData('videos', $video['videoId']);
            // // dd($videoData);
            // $videoName = $videoData['title'];
            // // $video_views[$key]['videoName'] = $videoName;
        }

        $podcast_views = PodcastView::query();
        $podcast_views = $podcast_views->groupBy('podcastId')->get()->toArray();  
        $podcast_views = array_values($podcast_views);
        foreach($podcast_views as $key => $podcast){
            $views = PodcastView::where('podcastId', $podcast['podcastId'])->count();
            $podcast_views[$key]['views'] = $views;
        }

        $live_views = LiveView::query();
        $live_views = $live_views->groupBy('liveId')->get()->toArray();
        $live_views = array_values($live_views);
        foreach($live_views as $key => $live){
            $views = LiveView::where('liveId', $live['liveId'])->count();
            $live_views[$key]['views'] = $views;
        }  
        
        usort($video_views, function($a,$b){
            if($a['views'] < $b['views']){
                return 1;
            }
        });
        usort($podcast_views, function($a,$b){
            if($a['views'] < $b['views']){
                return 1;
            }
        });
        usort($live_views, function($a,$b){
            if($a['views'] < $b['views']){
                return 1;
            }
        });*/

        return view('admin.modules.dashboard.dashboard', $data);
    }
}
