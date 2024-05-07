<?php

namespace App\Http\Controllers\Modules\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
Use Session;
Use Validator;
use Helper;

class ContactController extends Controller
{
    /**
     * for contact us page
     */
    public function index() {
        // $data['about'] = Contact::where('type','about')->first();
        // dd($data);
        return view('modules.contact.contact');
    }

            
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:199',
            'message' => 'required|max:500',
            'phone' => 'required|digits_between:9,15',
        ]);
        // dd($request->all());

        try {
            $new['name'] = $request->name;
            $new['email'] = $request->email;
            $new['phone'] = $request->phone;
            $new['message'] = nl2br($request->message);
            $a = Contact::create($new);
            if ($a) {
                // send mail to user
                $mailTo = [@$request->email];
                $mailToName = [@$request->name];
                $mailData = [
                    'subject' => "VARCAST Contact Us",
                    'to_mail' => @$mailTo,
                    'to_mail_name' => @$mailToName,
                    'short_title' => "Contact message sent successfully.",
                    'body' => 'Message - '.$request->message,
                ];
                Helper::sendMailToUser(@$mailData);
                Session::flash('success',"Message sent successfully.");
                return redirect()->back();
            } else {
                Session::flash('error',"Sometings wents to wrong. Please try again later.");
                return redirect()->back();
            }
        } catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
    }
}
