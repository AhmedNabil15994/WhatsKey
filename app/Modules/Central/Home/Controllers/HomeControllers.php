<?php namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Models\Slider;
use App\Models\FAQ;
use App\Models\Membership;

class HomeControllers extends Controller {

    use \TraitsFunc;

    protected function validateObject($input){
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10',//|regex:/(01)[0-9]{9}/',
            'message' => 'required',
            'title' => 'required',
        ];

        $message = [
            'name.required' => "يرجي ادخال الاسم بالكامل",
            'email.required' => "يرجي ادخال البريد الالكتروني",
            'email.email' => "يرجي ادخال بريد الكتروني متاح",
            'message.required' => "يرجي ادخال تفاصيل الرسالة",
            'title.required' => "يرجي ادخال عنوان الرسالة",
            'phone.required' => "يرجي ادخال رقم الجوال",
            'phone.min' => "رقم الجوال يجب ان يكون 10 خانات",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $data['sliders'] = Slider::dataList(1,1)['data'];
        $data['memberships'] = Membership::dataList(1)['data'];
        return view('Central.Home.Views.index')->with('data', (object) $data);
    }

    public function FAQ() {
        $data['faq'] = FAQ::dataList(1)['data'];
        return view('Central.Home.Views.faq')->with('data', (object) $data);
    }

    public function contactUs() {
        return view('Central.Home.Views.contactUs');
    }

    public function postContactUs() {
        $input = \Request::all();

        $validate = $this->validateObject($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        $ip_address = \Request::ip();

        $faqObj = ContactUs::NotDeleted()->where('ip_address',$ip_address)->where('reply',null)->whereDate('created_at',date('Y-m-d'))->first();
        if($faqObj != null){
            \Session::flash('error', 'لقد تم ارسال الرسالة مسبقا');
            return redirect()->back()->withInput();
        }

        $menuObj = new ContactUs;
        $menuObj->name = $input['name'];
        $menuObj->email = $input['email'];
        $menuObj->phone = $input['phone'];
        $menuObj->address = $input['title'];
        $menuObj->message = $input['message'];
        $menuObj->ip_address = $ip_address;
        $menuObj->reply = null;
        $menuObj->status = 1;
        $menuObj->created_at = DATE_TIME;
        $menuObj->save();

        \Session::flash('success', 'تنبيه! تم الارسال بنجاح');
        return redirect()->back();
    }

    public function whoUs() {
        return view('Central.Home.Views.whoUs');
    }
    
    public function privacy() {
        return view('Central.Home.Views.privacy');
    }

    public function explaination() {
        return view('Central.Home.Views.explaination');
    }

}
