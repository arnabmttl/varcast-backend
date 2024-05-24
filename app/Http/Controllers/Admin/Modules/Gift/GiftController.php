<?php

namespace App\Http\Controllers\Admin\Modules\Gift;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Gift;
use Carbon\Carbon;
use Session;
use Validator;
use File;
use Illuminate\Validation\Rule;
use App\Interfaces\CoinPriceInterface;

class GiftController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    public function index(Request $request) {
        $keyword = !empty($request->keyword)?$request->keyword:'';
        $limit = 10;
        $data = Gift::orderBy('_id','desc')->where('status','!=','D');
        if(!empty($keyword)){
            $data = $data->where(function($query) use ($keyword){
                $query->where('name', 'Like', '%' . $keyword .'%');
            });
        }
        $data =  $data->paginate($limit);
        // dd($data);
        return view('admin.modules.gift.index',$data);
    }

    public function create() {
        return view('admin.modules.gift.modify');
    }


    public function store(Request $request) {
        // dd($request->all());
        $validated = $request->validate([
            'gift_name' => [
                'required',
                'string',
                'max:199',
                Rule::unique('mongodb.gifts')->where(function($query) use ($request){
                    $query->where('status','!=','D')->where('_id','!=',$request->rowid);
                })
            ],  
            // 'icon_image' => 'required_unless:rowid,nullable',
            'coin_value' => 'required|numeric'
        ]);
        try {
            // $data = $this->coinPriceRepository->createCoinPrice(@$request->all());
            $params = $request->except('_token');            
            $params['status'] = 'A';
            $upload_image=$request->file('image');

            if(!empty($request['rowid'])){
                $gift = Gift::find($request['rowid']);
                $icon_image = $gift->icon_image;
                $params['icon_image'] = $icon_image;
                if(!empty($upload_image)){
                    $image_name= time()."_".$upload_image->getClientOriginalName();
                    $location="uploads/gifts/";
                    //dd($location);
                    $upload_image->move($location,$image_name);
                    $filename=$location."".$image_name;
                    $params['icon_image']=$filename;
                    //dd($upload_image);
                }
            } else {
                $params['icon_image'] = null;
                if(!empty($upload_image)){
                    $image_name= time()."_".$upload_image->getClientOriginalName();
                    $location="uploads/gifts/";
                    //dd($location);
                    $upload_image->move($location,$image_name);
                    $filename=$location."".$image_name;
                    $params['icon_image']=$filename;
                    unset($params['image']);
                    // dd($params);
                }
            }

            $data = Gift::updateOrCreate(['_id' => @$request['rowid']], $params);
            
            if($data){
                if(!empty($request->rowid)){
                    Session::flash('success','Gift updated successfully!');
                }
                else{
                    Session::flash('success','Gift created successfully!');
                }
            } else {
                Session::flash('error','Sorry a problem has occurred.');
            }  
        } catch(\Exception $e) {
            Session::flash('error',@$e->getMessage()); 
        }
        return redirect()->route('admin.gift.index');
    }

    public function edit($id=null) {
        // dd($id);
        try{
            $data = Gift::where('_id', $id)->where('status','!=','D')->first();
            // dd($data->gift_name);
            // $data['coin_price_data'] = $coin_price_data = $this->coinPriceRepository->getCoinPriceId(@$id);
            if(!empty($data)){
                return view('admin.modules.gift.modify', compact('data','id'));
            } else {
                Session::flash('error','Sorry a problem has occurred.');
            }
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }

    public function status($id) {
        try{
            $this->coinPriceRepository->statusCoinPrice(@$id);
            Session::flash('success',"Coin price status change successfully");
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }


}
