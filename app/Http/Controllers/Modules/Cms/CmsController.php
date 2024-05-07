<?php

namespace App\Http\Controllers\Modules\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;

class CmsController extends Controller
{
    public function aboutUs() {
        $data['about'] = Content::where('type','about')->first();
        return view('modules.cms.about',$data);
    }
}
