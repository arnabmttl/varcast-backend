<?php

namespace App\Http\Controllers\Admin\Modules\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Subscribe;
use Session;
use Helper;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }
    public function index(Request $request){
    	$data['contact_list'] = Contact::latest();
    	if(!empty(@$request->all())){
            if(!empty(@$request->keyword)){
                $data['contact_list'] = $data['contact_list']->where(function($query) use ($request){
                    $query->where('name', 'Like', '%' . @$request->keyword .'%')
                    ->orWhere('email', 'Like', '%' . @$request->keyword .'%')
                    ->orWhere('phone', 'Like', '%' . @$request->keyword .'%')
                    ->orWhere('message', 'Like', '%' . @$request->keyword .'%');
                });
            }
        }
        $data['contact_list'] = $data['contact_list']->paginate(10);
        return view('admin.modules.contact.index',@$data);
    }
    public function contactDelete($conIds = null){
        try{
            if(!empty(@$conIds)){
                Contact::whereId(@$conIds)->delete();
                Session::flash('success','Contact deleted successfully.');
            }
            else{
                Session::flash('error','Contact data not found.');
            }
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }
    public function subscribeList(Request $request){
        try{
            $data['subscribe_list'] = Subscribe::latest();
            if(!empty(@$request->all())){
                if(!empty(@$request->keyword)){
                    $data['subscribe_list'] = $data['subscribe_list']->where(function($query) use ($request){
                        $query->where('email', 'Like', '%' . @$request->keyword .'%');
                    });
                }
            }
            $data['subscribe_list'] = $data['subscribe_list']->paginate(10);
            return view('admin.modules.subscribe.index',@$data);
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }

    }
    public function subscribeDelete($conIds = null){
        try{
            if(!empty(@$conIds)){
                Subscribe::whereId(@$conIds)->delete();
                Session::flash('success','Data deleted successfully.');
            }
            else{
                Session::flash('error','Contact data not found.');
            }
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }
    public function sentSubscribeNotification(Request $request){
        try{
            $validated = $request->validate([
                'rowid' => 'required|not_in:-1',
                'subject' => 'required|string',
                'email_content' => 'required|string'
            ]);
            foreach(explode(',', @$request->rowid) as $rowids){
                $checkData = Subscribe::whereId(@$rowids)->first();
                if(!empty(@$checkData)){
                    $mailTo = [@$checkData->email];
                    $mailToName = [@$checkData->email];
                    $mailData = [
                        'subject' => @$request->subject,
                        'to_mail' => @$mailTo,
                        'to_mail_name' => @$mailToName,
                        'short_title' => "",
                        'body' => @$request->email_content,
                    ];
                    Helper::sendMailToUser(@$mailData);
                    Session::flash('success',"Message send successfully.");
                }
                else{
                    Session::flash('error','Data not found.');
                }
            }
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }
}
