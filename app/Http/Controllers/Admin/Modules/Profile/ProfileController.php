<?php

namespace App\Http\Controllers\Admin\Modules\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Admin;
use App\Models\NotiPercentage;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Session;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }
    public function index(){
        return view('admin.modules.profile.edit-profile');
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:199',
            'email' => 'required|string|email|max:199',
            'phone' => 'required|numeric|min:9|max:15',
            'image' => 'nullable|image',
        ]);
        try {
            $admin = Admin::whereId(Auth::guard('admin')->user()->id)->first();
            $data['name'] = @$request->name;
            $data['email'] = @$request->email;
            $data['phone'] = @$request->phone;
            if ($request->hasFile('image')) {
                if ($admin->image) {
                    if (File::exists("storage/app/public/admin_pics/" . @$admin->image)) {
                        File::delete("storage/app/public/admin_pics/" . @$admin->image);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->storeAs("public/admin_pics/", $filename);
                $data['image'] = $filename;
            }
            $admin->update(@$data);
            Session::flash('success',"Profile updated successfully."); 
            return redirect()->back();
        }
        catch(\Exception $e) {
            Session::flash('error',$e->getMessage()); 
            return redirect()->back();
        }
    }
    public function passwordChange(){
        return view('admin.modules.profile.password-change');
    }
    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'nullable|min:8',
            'new_password' => 'nullable|min:8',
            'password_confirmation' => 'nullable|min:8|same:new_password',
        ]);
        try {
            $admin = Admin::whereId(Auth::guard('admin')->user()->id)->first();
            if (!Hash::check(@$request->old_password, @$admin->password)) { 
                Session::flash('error','The current Password does not match.'); 
                return redirect()->back();
            }
            if (@$request->old_password === @$request->new_password ) { 
                Session::flash('error','Please choose a different password, you cannot use the current password.'); 
                return redirect()->back();
            }
            if(!empty(@$request->new_password)){
                $data['password'] = bcrypt(@$request->new_password);
            }
            $admin->update(@$data);
            Session::flash('success',"Password updated successfully."); 
            return redirect()->back();
        }
        catch(\Exception $e) {
            Session::flash('error',$e->getMessage()); 
            return redirect()->back();
        }
    }
}
