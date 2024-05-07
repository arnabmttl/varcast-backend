<?php

namespace App\Http\Controllers\Admin\Modules\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Testimonial;
use Carbon\Carbon;
use Session;
use Validator;
use File;
use Illuminate\Support\Str;

class CmsController extends Controller
{
	public function __construct()
	{
		$this->middleware('admin.auth:admin');
	}
	public function index($page=null){
		try{
			if(@$page == 'about' || @$page == 'terms' || @$page == 'privacy'){
				$data['content'] = Content::where('type',@$page)->first();
				$data['page'] = @$page;
				return view('admin.modules.content.content',@$data);
			}
			else{
				Session::flash('error','Sorry a problem has occurred.');
			}
		}
		catch(\Exception $e) {
			Session::flash('error',@$e->getMessage());
		}
		return redirect()->back();
	}
	public function store(Request $request){
		$validated = $request->validate([
			'page_name' => 'required|string',
			'content' => 'required|string',
			'page' => 'required|in:about,terms,privacy',
		]);
		try {
			$content = Content::where('type',@$request->page)->first();
			if ($request->hasFile('image')) {
				if (@$content->image) {
					if (File::exists("storage/content/" . @$content->image)) {
						File::delete("storage/content/" . @$content->image);
					}
				}
				$time      = Carbon::now();
				$file      = $request->file('image');
				$extension = $file->getClientOriginalExtension();
				$filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
				$file->storeAs("public/content/", $filename);
				$data['image'] = $filename;
			}
			$data['name'] = $request->page_name;
			$data['content'] = $request->content;
			$data['type'] = $request->page;
			if(!empty(@$content)){
				@$content->update(@$data);
			}
			else{
				Content::create(@$data);
			}
			Session::flash('success','Content updated successfully.');
		}
		catch(\Exception $e) {
			Session::flash('error',$e->getMessage());
		}
		return redirect()->back();
	}
	
	public function testimonialPage(Request $request) {
		$data['teastmonial_list'] = Testimonial::latest();
		if(!empty(@$request->all())){
			if(!empty(@$request->keyword)){
				$data['teastmonial_list'] = $data['teastmonial_list']->where(function($query) use ($request){
					$query->where('name', 'Like', '%' . @$request->keyword .'%')
					->orWhere('designation', 'Like', '%' . @$request->keyword .'%')
					->orWhere('message', 'Like', '%' . @$request->keyword .'%');
				});
			}
			if(!empty(@$request->status)){
				$data['teastmonial_list'] = $data['teastmonial_list']->where('status',@$request->status);
			}
		}
		$data['teastmonial_list'] = $data['teastmonial_list']->paginate(10);
		return view('admin.modules.testimonial.index',$data);
	}
	public function testimonialCreatePage() {
		return view('admin.modules.testimonial.modify');
	}
	public function testimonialStore(Request $request) {
		try {
			$validated = $request->validate([
				'name' => 'required|string',
				'designation' => 'required|string',
				'message' => 'required|string'
			]);
			$new['name'] = @$request->name;
			$new['designation'] = @$request->designation ? nl2br(@$request->designation): null;
			$new['message'] = @$request->message ? nl2br(@$request->message): null;
			$data = Testimonial::updateOrCreate(['id' => @$request['rowid']], $new);
			if(@$data){
				if(!empty(@$request->rowid)){
					Session::flash('success','Testimonial updated successfully!');
				}
				else{
					Session::flash('success','Testimonial created successfully!');
				}
			} else {
				Session::flash('error','Sorry a problem has occurred.');
			}
		} catch(\Exception $e) {
			Session::flash('error',$e->getMessage());
		}
		return redirect()->route('admin.testimonial.page');
	}
	public function testimonialStatus($id=null) {
		try{
			$data = Testimonial::find(@$id);
			if($data->status=='A') {
				Testimonial::whereId(@$id)->update(['status' => 'I']);
			} else {
				Testimonial::whereId(@$id)->update(['status' => 'A']);
			}
			Session::flash('success',"Testimonial status changed successfully.");
		}
		catch(\Exception $e) {
			Session::flash('error',@$e->getMessage());
		}
		return redirect()->back();
	}
	public function testimonialDelete($id=null) {
		try{
			$testimonial = Testimonial::whereId(@$id)->first();
			if(!empty(@$testimonial)){
				$testimonial->delete();
				Session::flash('success',"Testimonial deleted successfully");
			}else{
				Session::flash('error','Sorry a problem has occurred.');
			}
		}
		catch(\Exception $e) {
			Session::flash('error',@$e->getMessage());
		}
		return redirect()->back();
	}
	public function testimonialEdit($id=null) {
        try{
            $data['testimonial_data'] = $testimonialData = Testimonial::whereId(@$id)->first();
            if(!empty(@$testimonialData)){
                return view('admin.modules.testimonial.modify',$data);
            } else {
                Session::flash('error','Sorry a problem has occurred.');
            }
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }
}
