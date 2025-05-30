<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Central\Channel;
use App\Models\Variable;
use App\Models\UserChannels;
use App\Models\UserAddon;
use App\Models\CentralUser;
use App\Models\Domain;
use App\Models\Tenant;
use App\Models\UserData;
use Helper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Validator;
use URL;
use Illuminate\Http\Request;

class AuthControllers extends Controller {

    use \TraitsFunc;

    public function login() {
        if(Session::has('user_id')){
            return redirect('/login');
        }
        $data['code'] = 'eg';
        return view('Tenancy.Auth.Views.login')->with('data',(object) $data);
    }

    public function loginByCode() {
        Session::put('check_user_id',\Request::get('user_id'));
        if(\Request::has('user_id')){
            $userObj = User::find(\Request::get('user_id'));
            if($userObj){
                $data['phone'] = $userObj->phone;
                Session::put('check_user_phone',$userObj->phone);
                return view('Tenancy.Auth.Views.checkCode')->with('data',(object) $data);
            }
        }else{
            return view('Tenancy.Auth.Views.checkCode');
        }
    }

    public function doLogin() {
        $input = \Request::all();
        $rules = array(
            'password' => 'required',
            'phone' => 'required',
        );

        $message = array(
            'password.required' => trans('auth.passwordValidation'),
            'phone.required' => trans('auth.phoneValidation'),
        );

        $validate = \Validator::make($input, $rules,$message);

        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first());
        }

        $userObj = User::checkUserBy('phone',$input['phone']);
        if ($userObj == null) {
            return \TraitsFunc::ErrorMessage(trans('auth.invalidUser'));
        }

        $checkPassword = Hash::check($input['password'], $userObj->password);
        if ($checkPassword == null) {
            return \TraitsFunc::ErrorMessage(trans('auth.invalidPassword'));
        }

        // Send Code Here
        $code = rand(1000,10000);
        $userObj->code = $code;
        $userObj->save();

        if($userObj->two_auth == 1){
            $channelObj = \DB::connection('main')->table('channels')->where('deleted_by',null)->orderBy('id','ASC')->first();
            $whatsLoopObj =  new \OfficialHelper($channelObj->instanceId,$channelObj->instanceToken);
            $data['body'] = 'كود التحقق الخاص بك هو : '.$code;
            $data['phone'] = str_replace('+','',$input['phone']);
            $test = $whatsLoopObj->sendMessage($data);
            $result = $test->json();
            if($result['status']['status'] != 1){
                return \TraitsFunc::ErrorMessage(trans('auth.codeProblem'));
            }
            \Session::put('check_user_id',$userObj->id);
            return \TraitsFunc::SuccessResponse(trans('auth.codeSuccess'));
        }else{
            User::setSessions($userObj);
            Session::flash('success', trans('auth.welcome') . ucwords($userObj->name));
            $statusObj['data'] = \URL::to('/dashboard');
            $statusObj['status'] = \TraitsFunc::LoginResponse(trans('auth.welcome') . ucwords($userObj->name));
            return \Response::json((object) $statusObj);
        }
    }

    public function checkByCode(){
        $input = \Request::all();
        $code = $input['code'];
        $user_id = Session::get('check_user_phone');
        $userObj = User::NotDeleted()->where('phone',$user_id)->first();
        if($code != $userObj->code && $code != $userObj->pin_code){
            Session::flash('error',trans('auth.codeError'));
            return redirect()->back()->withInput(); 
        }
        User::setSessions($userObj);
        $this->genNewPinCode($userObj->id);
        Session::flash('success', trans('auth.welcome') . ucwords($userObj->name));
        return redirect()->to('/dashboard');
    }

    public function checkLoginCode(){
        $input = \Request::all();
        $code = $input['code'];
        $user_id = Session::get('check_user_id');
        $userObj = User::getOne($user_id);
        if($code != $userObj->code && $code != $userObj->pin_code){
            return \TraitsFunc::ErrorMessage(trans('auth.codeError'));
        }
        User::setSessions($userObj);
        Session::flash('success', trans('auth.welcome') . ucwords($userObj->name));
        $statusObj['data'] = \URL::to('/dashboard');
        $statusObj['status'] = \TraitsFunc::LoginResponse(trans('auth.welcome') . ucwords($userObj->name));
        return \Response::json((object) $statusObj);
    }

    public function genNewPinCode($user_id){
        $newCode = rand(1,10000);
        $userObj = User::getOne($user_id);
        $userObj->pin_code = $newCode;
        $userObj->save();

        $userObj = CentralUser::getOne($user_id);
        $userObj->pin_code = $newCode;
        $userObj->save();
        return $newCode;
    }

    public function logout() {
        $lang = Session::get('locale');
        session()->flush();
        $lang = Session::put('locale',$lang);
        Session::flash('success', trans('auth.seeYou'));
        return redirect()->route("login");
    }

    public function getResetPassword(){
        if(Session::has('user_id')){
            return redirect('/dashboard');
        }
        $data['code'] = \Helper::getCountryCode() ? \Helper::getCountryCode()->countryCode : 'sa';
        return view('Tenancy.Auth.Views.resetPassword')->with('data',(object) $data);
    }

    public function resetPassword(){
        $input = \Request::all();
        $rules = [
            'phone' => 'required',
        ];

        $message = [
            'phone.required' => trans('auth.phoneValidation'),
        ];

        $validate = Validator::make($input, $rules, $message);

        if($validate->fails()){
            Session::flash('error',$validate->messages()->first());
            return redirect()->back()->withInput(); 
        }

        $phone = $input['phone'];
        $userObj = User::checkUserBy('phone',$phone);

        if ($userObj == null) {
            Session::flash('error',trans('auth.invalidUser'));
            return redirect()->back()->withInput(); 
        }

        // Send Code Here
        $code = rand(1000,10000);
        $userObj->code = $code;
        $userObj->save();

        $channelObj = \DB::connection('main')->table('channels')->where('deleted_by',null)->orderBy('id','ASC')->first();
        $whatsLoopObj =  new \OfficialHelper($channelObj->id,$channelObj->token);
        $data['body'] = 'كود التحقق الخاص بك هو : '.$code;
        $data['phone'] = str_replace('+','',$input['phone']);
        $test = $whatsLoopObj->sendMessage($data);
        $result = $test->json();
        if($result['status']['status'] != 1){
            Session::flash('error',trans('auth.codeProblem'));
            return redirect()->back()->withInput(); 
        }

        Session::put('check_user_id',$userObj->id);
        Session::flash('success',trans('auth.codeSuccess'));
        return view('Tenancy.Auth.Views.checkCode')->with('data',(object) $data);
    }

    public function checkResetPassword(){
        $input = \Request::all();
        $code = $input['code'];
        $user_id = Session::get('check_user_id');
        $userObj = User::getOne($user_id);
        if($code != $userObj->code){
            Session::flash('error',trans('auth.codeError'));
            return redirect()->back()->withInput(); 
        }

        Session::flash('success', trans('auth.validated'));
        return redirect()->to('/changePassword');
    }

    public function changePassword() {
        if(!Session::has('check_user_id')){
            return redirect('/getResetPassword');
        }
        return view('Tenancy.Auth.Views.changePassword');
    }

    public function completeReset() {
        $input = \Request::all();
        $rules = [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];

        $message = [
            'password.required' => trans('auth.passwordValidation'),
            'password.confirmed' => trans('auth.passwordValidation2'),
            'password_confirmation.required' => trans('auth.passwordValidation3'),
        ];

        $validate = Validator::make($input, $rules, $message);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return back()->withInput();
        }

        $password = $input['password'];
        $user_id = Session::get('check_user_id');


        $userObj = User::NotDeleted()->find($user_id);
        if($userObj == null){
            Session::flash('error', trans('auth.invalidUser'));
            return back()->withInput();
        }

        $userObj->password = Hash::make($password);
        $userObj->save();
        
        CentralUser::where('id',$user_id)->update(['password'=>Hash::make($password)]);

        Session::forget('check_user_id');
        User::setSessions($userObj);

        Session::flash('success', trans('auth.passwordChanged'));
        return redirect('/dashboard');
    }

}
