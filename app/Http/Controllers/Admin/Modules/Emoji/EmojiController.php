<?php

namespace App\Http\Controllers\Admin\Modules\Emoji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Emoji;
use Carbon\Carbon;
use Session;
use Validator;
use File;
use Illuminate\Validation\Rule;
use MongoDB\BSON\UTCDateTime;

class EmojiController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    public function index(Request $request) {
        $data['emojis'] = Emoji::where('status','!=','D')->orderBy("is_order", "asc");
        if(!empty(@$request->all())){
            if(!empty(@$request->keyword)){
                $data['emojis'] = $data['emojis']->where(function($query) use ($request){
                    $query->where('emoji', 'Like', '%' . @$request->keyword .'%')
                    ->orWhere('usage_coin', 'Like', '%' . @$request->keyword .'%');
                });
            }
            if(!empty(@$request->from_date)){
                $from_date = Carbon::createFromFormat('d/m/Y',@$request->from_date)->format('Y-m-d');
                $data['emojis'] = $data['emojis']->whereDate('created_at','>=',new UTCDateTime(strtotime(@$from_date) * 1000));
            }
            if(!empty(@$request->to_date)){
                $to_date = Carbon::createFromFormat('d/m/Y',@$request->to_date)->format('Y-m-d');
                $data['emojis'] = $data['emojis']->whereDate('created_at','<=',new UTCDateTime(strtotime(@$to_date) * 1000));
            }
            if(!empty(@$request->status)){
                $data['emojis'] = $data['emojis']->where('status',@$request->status);
            }
        }
        $data['emojis'] = $data['emojis']->paginate(10);
        return view('admin.modules.emoji.index',$data);
    }


    public function create() {
        $data['is_order_count'] = Emoji::count();
        return view('admin.modules.emoji.modify',$data);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'emoji' => [
                'required',
                'string',
                Rule::unique('emoji')->where(function($query) use ($request){
                    $query->where('status','!=','D')->where('_id','!=',@$request->rowid);
                })
            ],
            // 'image' => 'required|image',
            'usage_coin' => 'required|numeric'
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
            $new['emoji'] = @$request->emoji;
            $new['usage_coin'] = (int)@$request->usage_coin;
            $new['status'] = 'A';
            $new['is_order'] = @$request->is_order ? (float)@$request->is_order : 0;
            $data = Emoji::updateOrCreate(['_id' => @$request['rowid']], $new);
            if(@$data){
                if(!empty(@$request->rowid)){
                    Session::flash('success','Emoji updated successfully!');
                }
                else{
                    Session::flash('success','Emoji created successfully!');
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
            $data['emoji_data'] = $emoji_data = Emoji::where('_id',@$id)->first();
            if(!empty(@$emoji_data)){
                return view('admin.modules.emoji.modify',$data);
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
            $emoji = Emoji::where('_id',@$id)->first();
            if(!empty(@$emoji)){
                // if (File::exists("storage/banner/" . @$banner->image)) {
                //     File::delete("storage/banner/" . @$banner->image);
                // }
                // @$banner->delete();
                $emoji->update(['status' => 'D']);
                Session::flash('success',"Emoji deleted successfully");
                
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
        $data = Emoji::where('_id',@$id)->first();
        if($data->status=='A') {
            Emoji::where('_id',@$id)->update(['status' => 'I']);
        } else {
            Emoji::where('_id',@$id)->update(['status' => 'A']);
        }
        Session::flash('success',"Emoji status changed successfully");
        return redirect()->back();
    }
}
