<?php

namespace App\Http\Controllers\Admin\Modules\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;
use Response;
use Validator;
use Hash;
use File;
use Str;
use DB;
use MongoDB\BSON\UTCDateTime;
class CustomerController extends Controller
{
	public function __construct()
	{
		$this->middleware('admin.auth:admin');
	}
	public function index(Request $request){
		$data['all_users'] = User::where('status','!=','D')->latest();
		if(!empty(@$request->all())){
			if(!empty(@$request->keyword)){
				$data['all_users'] = $data['all_users']->where(function($query) use ($request){
					$query->where('name', 'Like', '%' . @$request->keyword .'%')
					->orWhere('email', 'Like', '%' . @$request->keyword .'%')
					->orWhere('phone', 'Like', '%' . @$request->keyword .'%');
                    // ->orWhere('address', 'Like', '%' . @$request->keyword .'%');
				});
			}

			if(!empty(@$request->from_date)){
				$from_date = Carbon::createFromFormat('d/m/Y',@$request->from_date)->format('Y-m-d');
				$data['all_users'] = $data['all_users']->whereDate('created_at','>=',new UTCDateTime(strtotime(@$from_date) * 1000));
			}
			if(!empty(@$request->to_date)){
				$to_date = Carbon::createFromFormat('d/m/Y',@$request->to_date)->format('Y-m-d');
				$data['all_users'] = $data['all_users']->whereDate('created_at','<=',new UTCDateTime(strtotime(@$to_date) * 1000));
			}
			if(!empty(@$request->email_verify)){
				$data['all_users'] = $data['all_users']->where('is_email_verify',@$request->email_verify);
			}
			if(!empty(@$request->phone_verify)){
				$data['all_users'] = $data['all_users']->where('is_phone_verify',@$request->phone_verify);
			}
			if(!empty(@$request->status)){
				$data['all_users'] = $data['all_users']->where('status',@$request->status);
			}
		}
		$data['all_users'] = $data['all_users']->paginate(10);
		return view('admin.modules.customer.index',@$data);
	}
	public function userStatusChange($id=null, $status=null){
		try{
			if(!empty(@$id) && !empty($status)){
				$userData = User::where('_id',@$id)->where('status','!=','D')->first();
				if(!empty(@$userData)){
					if(@$status == 'A' || @$status == 'I'){
						@$userData->update(['status' => @$status]);
						Session::flash('success','Customer status update successfully.');
					}
					else{
						Session::flash('error','Customer status not update successfully.');
					}
				}
				else{
					Session::flash('error','Customer data not found.');
				}
			}else{
				Session::flash('error','Sorry a problem has occurred.');
			}
		}
		catch(\Exception $e) {
			Session::flash('error',@$e->getMessage());
		}
		return redirect()->back();
	}
	public function userApprove($id=null){
		try{
			if(!empty(@$id)){
				$userData = User::where('_id',@$id)->where('status','!=','D')->first();
				if(!empty(@$userData)){
					@$userData->update(['is_approved' => 'Y']);
					Session::flash('success','Customer approved successfully.');
				}
				else{
					Session::flash('error','Customer data not found.');
				}
			}else{
				Session::flash('error','Sorry a problem has occurred.');
			}
		}
		catch(\Exception $e) {
			Session::flash('error',@$e->getMessage());
		}
		return redirect()->back();
	}
	public function customerDetails($userIds=null){
		try{
			if(!empty(@$userIds)){
				$userData = User::where('_id',@$userIds)->where('status','!=','D')->first();
				if(!empty(@$userData)){
					@$data['user_details'] = @$userData;
					return view('admin.modules.customer.view-customer',@$data);
				}
				else{
					Session::flash('error','Customer data not found.');
				}
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
	public function customerDelete($userIds=null){
		try{
			if(!empty(@$userIds)){
				$userData = User::where('_id',@$userIds)->where('status','!=','D')->first();
				if(!empty(@$userData)){
					@$userData->update(['status' => 'D']);
					Session::flash('success','Customer deleted successfully.');
				}
				else{
					Session::flash('error','Customer data not found.');
				}
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
	public function user_add($ids = null){
		try{
			if($ids){
				$data['user_data'] = User::where('_id',@$ids)->where('status','!=','D')->first();
				if(empty(@$data['user_data'])){
					Session::flash('error','Something went wrong. please try again');
					return redirect()->back();
				}
				return view('admin.modules.customer.modify',@$data);
			}
			return view('admin.modules.customer.modify');
		}
		catch(\Exception $e) {
			Session::flash('error',@$e->getMessage());
			return redirect()->back();
		}
	}
	public function storeUsers(Request $request,$type=null) {
		try {
			$validator = Validator::make($request->all(),[
				'name' => 'required|string|max:100',
				'email' =>  [
					'required','string','email','max:199',
					Rule::unique('users','email')->where(function($query) use ($request){
						@$query->where('status','!=','D')->where('_id','!=',@$request->rowid);
					}),
				],
				'phone' => [
					'nullable','string','numeric','digits_between:9,15',
					Rule::unique('users')->where(function($query) use ($request){
						@$query->where('status','!=','D')->where('_id','!=',@$request->rowid);
					})
				],
				'image' => 'required_without:rowid|image',
				'password' => 'required_without:rowid|confirmed',
				'password_confirmation' => 'required_without:rowid|same:password',
			]);
			if (@$validator->fails()) {
				return redirect()->back()->withErrors(@$validator)->withInput(@$request->all());
			}
			$new['name'] = @$request->name;
			// $new['type'] = @$type ? @$type : 'C';
			$new['email'] = @$request->email;
			$new['phone'] = @$request->phone;
			$new['description'] = @$request->description;
			if(empty(@$request->rowid)){
				$new['status'] = 'A';
			}
			if(!empty(@$request->password)){
				$new['password'] = Hash::make(@$request->password);
			}
			if ($request->hasFile('image')) {
				if(!empty(@$request->rowid))
				{
					$imageData = User::where('_id',@$request->rowid)->first();
					if (@$imageData->image) {
						if (File::exists("storage/profile_pics/" . @$imageData->image)) {
							File::delete("storage/profile_pics/" . @$imageData->image);
						}
					}
				}
				$time      = Carbon::now();
				$file      = @$request->file('image');
				$extension = @$file->getClientOriginalExtension();
				$filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
				$file->storeAs("public/profile_pics/", $filename);
				$new['image'] = $filename;
			}
			$save = User::updateOrCreate(['_id' => @$request->rowid ],@$new);
			if(!empty(@$request->rowid))
			{
				Session::flash('success',"User updated successfully..!");
			}
			else{
				Session::flash('success',"User created successfully..!");
			}
			return redirect()->back();

		} catch(\Exception $e) {
			Session::flash('error',@$e->getMessage());
			return redirect()->back();
		}
	}

}
