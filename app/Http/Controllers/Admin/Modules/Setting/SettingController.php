<?php

namespace App\Http\Controllers\Admin\Modules\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Str;
use Session;
use Carbon\Carbon;
use Validator;
class SettingController extends Controller
{
	public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }
    public function index(){
    	$data['setting'] = Setting::first();
    	return view('admin.modules.setting.index',@$data);
    }
    public function store(Request $request){
    	$validator = Validator::make($request->all(), [
            'email'  => 'required|email|max:199',
            'phone' => 'required|numeric|digits_between:10,12',
            'mail_from_address' => 'nullable|email|max:199',
        ]);
        try {
        	$setdata = Setting::first();
            $data['email'] = @$request->email;
            $data['phone'] = @$request->phone;
            $data['address'] = @$request->address;
            $data['mail_host'] = @$request->mail_host;
            $data['mail_port'] = @$request->mail_port;
            $data['mail_username'] = @$request->mail_username;
            $data['mail_password'] = @$request->mail_password;
            $data['mail_encryption'] = @$request->mail_encryption;
            $data['mail_from_address'] = @$request->mail_from_address;
            $data['mail_from_name'] = @$request->mail_from_name;
            $data['push_notification'] = @$request->push_notification ? 'Y' : 'N';
            $data['per_coin_price'] = @$request->per_coin_price ? @$request->per_coin_price : 1;
            if(!empty(@$setdata)){
            	$setdata->update(@$data);
            }
            else{
            	Setting::create(@$data);
            }
            Session::flash('success',"Setting updated successfully."); 
            return redirect()->back();
        }
        catch(\Exception $e) {
            Session::flash('error',$e->getMessage()); 
            return redirect()->back();
        }
    }
}
