<?php

namespace App\Http\Controllers\Admin\Modules\Tag;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Tag;
use Carbon\Carbon;
use Session;
use Validator;
use File;
use Illuminate\Validation\Rule;
use MongoDB\BSON\UTCDateTime;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    public function index(Request $request) {
        $data['tags'] = Tag::where('status','!=','D')->orderBy("is_order", "asc");
        if(!empty(@$request->all())){
            if(!empty(@$request->keyword)){
                $data['tags'] = $data['tags']->where(function($query) use ($request){
                    $query->where('name', 'Like', '%' . @$request->keyword .'%')
                    ->orWhere('slug', 'Like', '%' . @$request->keyword .'%');
                });
            }
            if(!empty(@$request->from_date)){
                $from_date = Carbon::createFromFormat('d/m/Y',@$request->from_date)->format('Y-m-d');
                $data['tags'] = $data['tags']->whereDate('created_at','>=',new UTCDateTime(strtotime(@$from_date) * 1000));
            }
            if(!empty(@$request->to_date)){
                $to_date = Carbon::createFromFormat('d/m/Y',@$request->to_date)->format('Y-m-d');
                $data['tags'] = $data['tags']->whereDate('created_at','<=',new UTCDateTime(strtotime(@$to_date) * 1000));
            }
            if(!empty(@$request->status)){
                $data['tags'] = $data['tags']->where('status',@$request->status);
            }
        }
        $data['tags'] = $data['tags']->paginate(10);
        return view('admin.modules.tag.index',$data);
    }


    public function create() {
        $data['is_order_count'] = Tag::count();
        return view('admin.modules.tag.modify',$data);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('tags')->where(function($query) use ($request){
                    $query->where('status','!=','D')->where('_id','!=',@$request->rowid);
                })
            ],
        ]);
        // dd($request->all());
        try {
            // if (@$request->hasFile('image')) {
            //     if(!empty(@$request->rowid)){
            //         $banner =  Banner::where('_id',@$request->rowid)->first();
            //         if (File::exists("storage/banner/" . @$banner->image)) {
            //             File::delete("storage/banner/" . @$banner->image);
            //         }
            //     }
            //     $time      = Carbon::now();
            //     $file      = $request->file('image');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            //     $file->storeAs('public/banner', @$filename);

            //     $new['image'] = @$filename;
            // }
            $new['name'] = @$request->name;
            $new['slug'] = Str::slug(@$request->name);
            $new['status'] = 'A';
            $new['is_order'] = @$request->is_order ? (float)@$request->is_order : 0;
            $data = Tag::updateOrCreate(['_id' => @$request['rowid']], $new);
            if(@$data){
                if(!empty(@$request->rowid)){
                    Session::flash('success','Tag updated successfully!');
                }
                else{
                    Session::flash('success','Tag created successfully!');
                }
            } else {
                Session::flash('error','Sorry a problem has occurred.');
            }
        } catch(\Exception $e) {
            Session::flash('error',$e->getMessage());
        }
        return redirect()->back();
    }


    public function edit($id=null) {
        try{
            $data['tag_data'] = $tag_data = Tag::where('_id',@$id)->first();
            if(!empty(@$tag_data)){
                return view('admin.modules.tag.modify',$data);
            } else {
                Session::flash('error','Sorry a problem has occurred.');
            }
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }

    public function delete($id) {
        try{
            $tag = Tag::where('_id',@$id)->first();
            if(!empty(@$tag)){
                // if (File::exists("storage/banner/" . @$banner->image)) {
                //     File::delete("storage/banner/" . @$banner->image);
                // }
                // @$banner->delete();
                $tag->update(['status' => 'D']);
                Session::flash('success',"Tag deleted successfully");
                
            }else{
                Session::flash('error','Sorry a problem has occurred.');
            }
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }

    public function status($id=null) {
        $data = Tag::where('_id',@$id)->first();
        if($data->status=='A') {
            Tag::where('_id',@$id)->update(['status' => 'I']);
        } else {
            Tag::where('_id',@$id)->update(['status' => 'A']);
        }
        Session::flash('success',"Tag status changed successfully");
        return redirect()->back();
    }
}
