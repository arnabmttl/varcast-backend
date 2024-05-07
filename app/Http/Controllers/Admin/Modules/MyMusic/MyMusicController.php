<?php

namespace App\Http\Controllers\Admin\Modules\MyMusic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\MyMusic;
use Carbon\Carbon;
use Session;
use Validator;
use File;
use Illuminate\Validation\Rule;
use MongoDB\BSON\UTCDateTime;

class MyMusicController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    public function index(Request $request) {
        $data['my_musics'] = MyMusic::where('status','!=','D')->orderBy("is_order", "asc");
        if(!empty(@$request->all())){
            if(!empty(@$request->keyword)){
                $data['my_musics'] = $data['my_musics']->where(function($query) use ($request){
                    $query->where('name', 'Like', '%' . @$request->keyword .'%')
                    ->orWhere('slug', 'Like', '%' . @$request->keyword .'%')
                    ->orWhere('author', 'Like', '%' . @$request->keyword .'%')
                    ->orWhere('duration', 'Like', '%' . @$request->keyword .'%');
                });
            }
            if(!empty(@$request->from_date)){
                $from_date = Carbon::createFromFormat('d/m/Y',@$request->from_date)->format('Y-m-d');
                $data['my_musics'] = $data['my_musics']->whereDate('created_at','>=',new UTCDateTime(strtotime(@$from_date) * 1000));
            }
            if(!empty(@$request->to_date)){
                $to_date = Carbon::createFromFormat('d/m/Y',@$request->to_date)->format('Y-m-d');
                $data['my_musics'] = $data['my_musics']->whereDate('created_at','<=',new UTCDateTime(strtotime(@$to_date) * 1000));
            }
            if(!empty(@$request->status)){
                $data['my_musics'] = $data['my_musics']->where('status',@$request->status);
            }
        }
        $data['my_musics'] = $data['my_musics']->paginate(10);
        return view('admin.modules.my_music.index',$data);
    }


    public function create() {
        $data['is_order_count'] = MyMusic::where('status','!=','D')->count();
        return view('admin.modules.my_music.modify',$data);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            // 'emoji' => [
            //     'required',
            //     'string',
            //     Rule::unique('emoji')->where(function($query) use ($request){
            //         $query->where('status','!=','D')->where('_id','!=',@$request->rowid);
            //     })
            // ],
            // 'image' => 'required|image',
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'thumbnail_image' => 'required_if:rowid,=,null|image',
            'file' => 'required_if:rowid,=,null|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav',

        ]);
        // dd($request->all());
        try {
            if (@$request->hasFile('thumbnail_image')) {
                if(!empty(@$request->rowid)){
                    $my_music_thumb =  MyMusic::where('_id',@$request->rowid)->first();
                    if (File::exists("storage/my_music/thumbnail/" . @$my_music_thumb->thumbnail_image)) {
                        File::delete("storage/my_music/thumbnail/" . @$my_music_thumb->thumbnail_image);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('thumbnail_image');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->storeAs('public/my_music/thumbnail', @$filename);

                $new['thumbnail_image'] = @$filename;
            }
            if (@$request->hasFile('file')) {
                if(!empty(@$request->rowid)){
                    $my_music_file =  MyMusic::where('_id',@$request->rowid)->first();
                    if (File::exists("storage/my_music/file/" . @$my_music_file->file)) {
                        File::delete("storage/my_music/file/" . @$my_music_file->file);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->storeAs('public/my_music/file', @$filename);
                $new['file'] = @$filename;
                $new['duration'] = @$this->calculateFileSize(@$file);
            }
            $new['name'] = @$request->name;
            $new['slug'] = Str::slug(@$request->name);
            $new['author'] = @$request->author;
            $new['status'] = 'A';
            $new['is_order'] = @$request->is_order ? (float)@$request->is_order : 0;
            $data = MyMusic::updateOrCreate(['_id' => @$request['rowid']], $new);
            if(@$data){
                if(!empty(@$request->rowid)){
                    Session::flash('success','My music updated successfully!');
                }
                else{
                    Session::flash('success','My music created successfully!');
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
            $data['my_music_data'] = $my_music_data = MyMusic::where('_id',@$id)->first();
            if(!empty(@$my_music_data)){
                return view('admin.modules.my_music.modify',$data);
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
            $myMusic = MyMusic::where('_id',@$id)->first();
            if(!empty(@$myMusic)){
                // if (File::exists("storage/banner/" . @$banner->image)) {
                //     File::delete("storage/banner/" . @$banner->image);
                // }
                // @$banner->delete();
                $myMusic->update(['status' => 'D']);
                Session::flash('success',"My music deleted successfully");
                
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
        $data = MyMusic::where('_id',@$id)->first();
        if($data->status=='A') {
            MyMusic::where('_id',@$id)->update(['status' => 'I']);
        } else {
            MyMusic::where('_id',@$id)->update(['status' => 'A']);
        }
        Session::flash('success',"My music status changed successfully");
        return redirect()->back();
    }

    public function calculateFileSize($file){
        $ratio = 32000; 
        if (!$file) {
            exit("Verify file name and it's path");
        }
        $file_size = filesize($file);
        if (!$file_size)
            exit("Verify file, something wrong with your file");
        $duration = ($file_size / $ratio);
        $minutes = floor($duration / 60);
        $seconds = $duration - ($minutes * 60);
        $seconds = round($seconds);
        return  "$minutes:$seconds minutes";

    }
}
