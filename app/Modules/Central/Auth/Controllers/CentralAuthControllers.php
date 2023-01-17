<?php namespace App\Http\Controllers;

use Validator;
use URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\CentralUser;
use App\Models\CentralChannel;
use App\Models\CentralVariable;
use App\Models\CentralWebActions;
use App\Models\Domain;
use App\Models\Variable;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserData;
use App\Models\ClientsRequests;
use App\Models\NotificationTemplate;
use App\Models\OAuthData;
use App\Models\StatusCategory;
use App\Models\Changelog;
use App\Models\CentralCategory;
use App\Models\Addons;
use App\Models\BankAccount;
use App\Models\BankTransfer;
use App\Models\Coupon;
use App\Models\ExtraQuota;
use App\Models\Invoice;
use App\Models\Membership;
use App\Models\ModTemplate;
use App\Models\UserAddon;
use App\Models\UserChannels;
use App\Models\UserExtraQuota;
use App\Jobs\NewClient;

use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

class CentralAuthControllers extends Controller {

    use \TraitsFunc;
    
    public function __construct(){
        Session::put('central',1);
    }

    public function login() {
        if(Session::has('user_id') && !Session::has('t_reset')){
            return redirect('/dashboard');
        } 
        
        if(Session::has('t_user_id') && !Session::has('t_reset')){
            $userId = Session::get('t_user_id');
            $tenantPhone = '';
            if(Session::has('t_phone')){
                $tenantPhone = Session::get('t_phone');
            }
            
            if($tenantPhone != ''){
                $rootId = Session::has('t_root') && Session::get('t_root') != $userId  ? 0 : 1;            
                $userObj = CentralUser::find($userId);
                $domainObj = \DB::connection('main')->table('tenant_users')->where('global_user_id',$userObj->global_id)->first();
                $tenant = Tenant::find($domainObj->tenant_id);
                tenancy()->initialize($tenant->id);
                $dataObj = User::where('id',$userId)->first();
                tenancy()->end();
                if(isset($dataObj)){
                    $token = tenancy()->impersonate($tenant,$userId,'/dashboard');
                    return redirect(tenant_route($dataObj->domain  . '.' . request()->getHttpHost(), 'impersonate',[
                        'token' => $token
                    ]));
                }   
            }
        }
        
        $data['code'] = \Helper::getCountryCode() ? \Helper::getCountryCode()->countryCode : 'sa';
        return view('Central.Auth.Views.login')->with('data',(object) $data);
    }

    public function register() {
        if(Session::has('user_id') && !Session::has('t_reset')){
            return redirect('/dashboard');
        }elseif(!Session::has('checked_user_phone')){
            return redirect('/checkAvailability');
        }
        $data['code'] = \Helper::getCountryCode() ? \Helper::getCountryCode()->countryCode : 'sa';
        return view('Central.Auth.Views.register')->with('data',(object) $data);
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
            Session::flash('error',$validate->messages()->first());
            return redirect()->back()->withInput(); 
        }

        $userObj = CentralUser::checkUserBy('phone',$input['phone']);
        $oneSignal = Session::has('one_signal') && Session::get('one_signal') != null ? Session::get('one_signal') : null; 
        if ($userObj == null) {
            Session::flash('error',trans('auth.invalidUser'));
            return redirect()->back()->withInput(); 
        }
        
        $checkPassword = Hash::check($input['password'], $userObj->password);
        if ($checkPassword == null) {
            $statusObj['data'] = \URL::to('/resetPassword');
            $statusObj['status'] = \TraitsFunc::LoginResponse(trans('auth.invalidPassword'));
            return \Response::json((object) $statusObj);
        }

        if($userObj->group_id == 0){
            $userObj = CentralUser::getData($userObj);
            $domainObj = Domain::where('domain',$userObj->domain)->first();
            $tenant = Tenant::find($domainObj->tenant_id);
            tenancy()->initialize($tenant->id);
            if($oneSignal != null){
                $varObj = new Variable;
                $varObj->var_key = 'ONESIGNALPLAYERID_'.str_replace('+','',$userObj->phone);
                $varObj->var_value = '{"'.$oneSignal.'":"'.$oneSignal.'"}';
                $varObj->save();
            }
            tenancy()->end();
            $token = tenancy()->impersonate($tenant,$userObj->id,'/dashboard');
            
            Session::put('check_user_id',$userObj->id);
            Session::put('t_user_id',$userObj->id);
            Session::put('t_phone',$userObj->phone);
            Session::put('t_root',$userObj->group_id == 1 ? 1 : 0);
            $statusObj['data'] = tenant_route($tenant->domains()->first()->domain  . '.' . request()->getHttpHost(), 'impersonate',[
                'token' => $token
            ]);
            $statusObj['status'] = \TraitsFunc::LoginResponse(trans('auth.welcome') . ucwords($userObj->name));
            return \Response::json((object) $statusObj);
        }

        // Send Code Here
        $code = rand(1000,10000);
        $userObj->code = $code;
        $userObj->save();


        if($userObj->two_auth == 1){
            $channelObj = \DB::connection('main')->table('channels')->where('deleted_by',null)->orderBy('id','ASC')->first();
            $whatsLoopObj =  new \OfficialHelper($channelObj->id,$channelObj->token);
            $data['body'] = 'كود التحقق الخاص بك هو : '.$code;
            $notificationTemplateObj = NotificationTemplate::getOne(1,'phoneConfirmation');
            if($notificationTemplateObj){
                $data['body'] = str_replace('{CODE}',$code,$notificationTemplateObj->content_ar);
            }
            $data['phone'] = str_replace('+','',$input['phone']);
            $test = $whatsLoopObj->sendMessage($data);
            $result = $test->json();
            if($result['status']['status'] != 1){
                Session::flash('error',trans('auth.codeProblem'));
                return redirect()->back()->withInput(); 
            }

            \Session::put('check_user_id',$userObj->id);
            return \TraitsFunc::SuccessResponse(trans('auth.codeSuccess'));
        }else{
            $isAdmin = in_array($userObj->group_id, [1,]);
            session(['group_id' => $userObj->group_id]);
            session(['user_id' => $userObj->id]);
            session(['email' => $userObj->email]);
            session(['name' => $userObj->name]);
            session(['is_admin' => $isAdmin]);
            session(['group_name' => $userObj->Group->name_ar]);
            $channels = CentralUser::getData($userObj)->channels;
            if(!empty($channels)){
                session(['channel' => $channels[0]->id]);
            }
            if($isAdmin){
                session(['central' => 1]);
            }

            Session::flash('success', trans('auth.welcome') . ucwords($userObj->name));
            $statusObj['data'] = \URL::to('/dashboard');
            $statusObj['status'] = \TraitsFunc::LoginResponse(trans('auth.welcome') . ucwords($userObj->name));
            return \Response::json((object) $statusObj);
        }
    }

    public function checkByCode(){
        $input = \Request::all();
        $code = $input['code'];
        $user_id = Session::get('check_user_id');
        $userObj = CentralUser::getOne($user_id);
        if($code != $userObj->code){
            Session::flash('error',trans('auth.codeError'));
            return redirect()->back()->withInput(); 
        }
        $isAdmin = in_array($userObj->group_id, [1,]);
        session(['group_id' => $userObj->group_id]);
        session(['user_id' => $userObj->id]);
        session(['email' => $userObj->email]);
        session(['name' => $userObj->name]);
        session(['is_admin' => $isAdmin]);
        session(['group_name' => $userObj->Group->name_ar]);
        $channels = CentralUser::getData($userObj)->channels;
        if(!empty($channels)){
            session(['channel' => $channels[0]->id]);
        }

        Session::flash('success', trans('auth.welcome') . $userObj->name_ar);
        return \TraitsFunc::SuccessResponse(trans('auth.welcome') . $userObj->name_ar);
    }

    public function logout() {
        $lang = Session::get('locale');
        session()->flush();
        $lang = Session::put('locale',$lang);
        Session::flash('success', trans('auth.seeYou'));
        return redirect()->to('/login');
    }

    public function getResetPassword(){
        if(Session::has('user_id') && !Session::has('t_reset')){
            return redirect('/dashboard');
        }
        $data['code'] = \Helper::getCountryCode() ? \Helper::getCountryCode()->countryCode : 'sa';
        return view('Central.Auth.Views.resetPassword')->with('data',(object) $data);
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
        
        Session::put('t_reset',1);
        $dataArr['code'] = \Helper::getCountryCode() ? \Helper::getCountryCode()->countryCode : 'sa';
        $dataArr['phone'] = $input['phone'];

        $phone = $input['phone'];
        $userObj = CentralUser::checkUserBy('phone',$phone);

        if ($userObj == null) {
            Session::flash('error',trans('auth.invalidUser'));
            return redirect()->back()->withInput(); 
        }

        // Send Code Here
        $code = rand(1000,10000);
        $userObj->code = $code;
        $userObj->save();
        
        if($userObj->group_id == 0){
            Session::put('t_user_id',$userObj->id);
            Session::put('t_phone',$userObj->phone);
            Session::put('t_root',1);
        }

        $channelObj = \DB::connection('main')->table('channels')->where('deleted_by',null)->orderBy('id','ASC')->first();
        $whatsLoopObj =  new \OfficialHelper($channelObj->id,$channelObj->token);
        $data['body'] = 'كود التحقق الخاص بك هو : '.$code;
        $notificationTemplateObj = NotificationTemplate::getOne(1,'phoneConfirmation');
        if($notificationTemplateObj){
            $data['body'] = str_replace('{CODE}',$code,$notificationTemplateObj->content_ar);
        }
        $data['phone'] = str_replace('+','',$input['phone']);
        $test = $whatsLoopObj->sendMessage($data);
        $result = $test->json();
        if($result['status']['status'] != 1){
            Session::flash('error',trans('auth.codeProblem'));
            return redirect()->back()->withInput(); 
        }

        Session::put('check_user_id',$userObj->id);
        Session::flash('success',trans('auth.codeSuccess'));
        return view('Central.Auth.Views.checkCode')->with('data',(object) $dataArr);
    }

    public function checkResetPassword(){
        $input = \Request::all();
        $code = $input['code'];
        $user_id = Session::get('check_user_id');
        $userObj = User::where('phone',$user_id)->first();
        if($userObj){
            if($code != $userObj->code){
                Session::flash('error',trans('auth.codeError'));
                return redirect()->back()->withInput(); 
            }
        }else{
            $userObj = CentralUser::getOne($user_id);
            if($code != $userObj->code){
                Session::flash('error',trans('auth.codeError'));
                return redirect()->back()->withInput(); 
            }

        }

        Session::flash('success', trans('auth.validated'));
        return redirect()->to('/changePassword');
    }

    public function changePassword() {
        if(!Session::has('check_user_id')){
            return redirect('/resetPassword');
        }
        return view('Central.Auth.Views.changePassword');
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

        if(Session::has('t_user_id')){
            $userId = Session::get('t_user_id');
            $tenantPhone = '';
            if(Session::has('t_phone')){
                $tenantPhone = Session::get('t_phone');
            }
            
            Session::forget('t_reset');
            if($tenantPhone != ''){
                $rootId = Session::has('t_root') && Session::get('t_root') != $userId  ? 0 : 1;
        
                $userObj = CentralUser::find($userId);
                if($userObj->group_id == 0){
                    $userObj->password = Hash::make($password);
                    $userObj->save();

                    $tenant = Tenant::where('phone',$userObj->phone)->first();
                    tenancy()->initialize($tenant->id);
                    User::where('id',$user_id)->update(['password'=>Hash::make($password)]);
                    tenancy()->end();
                    $usdObj = User::where('phone',$userObj->phone)->first();
                    if($usdObj){
                        $usdObj->update(['password'=>Hash::make($password)]);
                    }
                    $token = tenancy()->impersonate($tenant,$user_id,'/dashboard');

                    return redirect(tenant_route($tenant->domains()->first()->domain  . '.' . request()->getHttpHost(), 'impersonate',[
                        'token' => $token
                    ]));
                }
            }
        }else{
            $userObj = CentralUser::find($user_id);
            $userObj->password = Hash::make($password);
            $userObj->save();
            Session::forget('check_user_id');

            $isAdmin = in_array($userObj->group_id, [1,]);
            session(['group_id' => $userObj->group_id]);
            session(['user_id' => $userObj->id]);
            session(['email' => $userObj->email]);
            session(['name' => $userObj->name]);
            session(['is_admin' => $isAdmin]);
            session(['group_name' => $userObj->Group->name_ar]);
            $channels = CentralUser::getData($userObj)->channels;
            if(!empty($channels)){
                session(['channel' => $channels[0]->id]);
            }
            if($isAdmin){
                session(['central' => 1]);
            }

            Session::flash('success', trans('auth.passwordChanged'));
            return redirect('/dashboard');
        }
    }

    public function checkAvailability(){
        $input = \Request::all();
        if(isset($input['membership']) && !empty($input['membership'])){
            Session::put('package_id',(int)$input['membership']);
            Session::put('package_duration',(int)$input['duration']);
            return redirect()->to('/checkAvailability');
        }
        $data['code'] = \Helper::getCountryCode() ? \Helper::getCountryCode()->countryCode : 'sa';
        return view('Central.Auth.Views.checkAvailability')->with('data',(object) $data);
    }

    public function postCheckAvailability(Request $request){
        $input = \Request::all();
        $userObj = CentralUser::checkUserBy('phone',$input['phone']);
        if($userObj){
            Session::flash('error', trans('main.phoneError'));
            return redirect()->back()->withInput();
        }
        
        $dataArr['code'] = \Helper::getCountryCode() ? \Helper::getCountryCode()->countryCode : 'sa';
        $dataArr['phone'] = $input['phone'];
        
        $clientRequestObj = ClientsRequests::getOne($input['phone']);
        if($clientRequestObj){
            $clientRequestObj->delete();
        }
        
        $channelObj = \DB::connection('main')->table('channels')->where('deleted_by',null)->orderBy('instanceId','ASC')->first();
        $whatsLoopObj =  new \OfficialHelper($channelObj->instanceId,$channelObj->instanceToken);
        
        $code = rand(1000,10000);
        $notificationTemplateObj = NotificationTemplate::getOne(1,'phoneConfirmation');
        $data['body'] = 'كود التحقق الخاص بك هو : '.$code;
        if($notificationTemplateObj){
            $data['body'] = str_replace('{CODE}',$code,$notificationTemplateObj->content_ar);
        }
        $data['phone'] = str_replace('+','',$input['phone']);
        $test = $whatsLoopObj->sendMessage($data);
        $result = $test->json();
        
        if($result['status']['status'] != 1){
            Session::flash('error', trans('auth.codeProblem'));
            return redirect()->back()->withInput();
        }

        $clientRequestObj = new ClientsRequests();
        $clientRequestObj->phone = $input['phone'];
        $clientRequestObj->code = $code;
        $clientRequestObj->ip_address = $request->ip();
        $clientRequestObj->created_at = DATE_TIME;
        $clientRequestObj->save();
        return view('Central.Auth.Views.checkCode')->with('data',(object) $dataArr);
    }

    public function checkAvailabilityCode(){
        $input = \Request::all();
        $clientRequestObj = ClientsRequests::getOne($input['phone']);
        $dataArr['phone'] = $input['phone'];
        $dataArr['code'] = \Helper::getCountryCode() ? \Helper::getCountryCode()->countryCode : 'sa';
        if(!$clientRequestObj){
            Session::flash('error', trans('main.userNotFound'));
            return view('Central.Auth.Views.checkCode')->with('data',(object) $dataArr);
        }

        if($clientRequestObj->code != $input['code']){
            Session::flash('error', trans('auth.codeError'));
            return view('Central.Auth.Views.checkCode')->with('data',(object) $dataArr);
        }

        Session::put('checked_user_phone',$input['phone']);
        return redirect()->to('/register');
    }

    protected function validateInsertObject($input){
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'company' => 'required',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'email' => 'required|email',
            'domain' => 'required|regex:/^([a-zA-Z0-9][a-zA-Z0-9-_])*[a-zA-Z0-9]*[a-zA-Z0-9-_]*[[a-zA-Z0-9]$/',
        ];

        $message = [
            'name.required' => trans('main.nameValidate'),
            'comapny.required' => trans('main.nameValidate'),
            'phone.required' => trans('main.phoneValidate'),
            'password.required' => trans('main.passwordValidate'),
            'password.min' => trans('main.passwordValidate2'),
            'password.confirmed' => trans('auth.passwordValidation2'),
            'password_confirmation.required' => trans('auth.passwordValidation3'),
            'email.required' => trans('main.emailValidate'),
            'email.email' => trans('main.emailValidate'),
            'domain.required' => trans('main.domainValidate'),
            'domain.regex' => trans('main.domain2Validate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function postRegister(){
        $input = \Request::all();
        $input['phone'] = Session::get('checked_user_phone');
        $names = explode(' ',$input['name']);
        if(count($names) < 2){
            Session::flash('error', trans('main.name2Validate'));
            return redirect()->back()->withInput();
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
            
        $domainObj = Domain::getOneByDomain($input['domain']);
        if($domainObj){
            Session::flash('error', trans('main.domainValidate2'));
            return redirect()->back()->withInput();
        }

        $userObj = CentralUser::checkUserBy('email',$input['email']);
        if($userObj){
            Session::flash('error', trans('main.emailError'));
            return redirect()->back()->withInput();
        }

        $userObj = CentralUser::checkUserBy('phone',$input['phone']);
        if($userObj){
            Session::flash('error', trans('main.phoneError'));
            return redirect()->back()->withInput();
        }

        $package_id = Session::get('package_id');
        $package_duration = Session::get('package_duration');
        $membershipObj = Membership::getData(Membership::getOne($package_id));
        if(!$membershipObj){
            Session::flash('error', trans('main.membershipValidate'));
            return redirect()->back()->withInput();
        }

        $tenant = Tenant::create([
            'phone' => $input['phone'],
            'title' => $input['name'],
            'description' => '',
        ]);
        
        $tenant->domains()->create([
            'domain' => $input['domain'],
        ]);

        $centralUser = CentralUser::create([
            'global_id' => (string) Str::orderedUuid(),
            'name' => $input['name'],
            'phone' => $input['phone'],
            'email' => $input['email'],
            'company' => $input['company'],
            'password' => Hash::make($input['password']),
            'notifications' => 0,
            'setting_pushed' => 0,
            'offers' => 0,
            'group_id' => 0,
            'is_active' => 1,
            'is_approved' => 1,
            'status' => 1,
            'two_auth' => 0,
            'is_old' => 0,
            'is_synced' => 0,
            'isBA' => 1,
        ]);

        \DB::connection('main')->table('tenant_users')->insert([
            'tenant_id' => $tenant->id,
            'global_user_id' => $centralUser->global_id,
        ]);
        
        $user = $tenant->run(function() use(&$centralUser,$input){
            $userObj = User::create([
                'id' => $centralUser->id,
                'global_id' => $centralUser->global_id,
                'name' => $input['name'],
                'phone' => $input['phone'],
                'email' => $input['email'],
                'company' => $input['company'],
                'group_id' => 1,
                'status' => 1,
                'domain' => $input['domain'],
                'is_old' => $centralUser->is_old,
                'is_synced' => $centralUser->is_synced,
                'two_auth' => 0,
                'sort' => 1,
                'setting_pushed' => 0,
                'password' => Hash::make($input['password']),
                'is_active' => 1,
                'is_approved' => 1,
                'notifications' => 0,
                'offers' => 0,
            ]);
            return $userObj;
        });

        // Session::flash('success', trans('main.addSuccess'));
        // $token = tenancy()->impersonate($tenant,$user->id,'/');

        // Activate Account Here
        $start_date = date('Y-m-d');
        $cartData = [
            [
                'id' => $package_id,
                'type' => 'membership',
                'title' => $membershipObj->{'title_' . LANGUAGE_PREF},
                'duration_type' => $package_duration,
                'start_date' => $start_date,
                'end_date' => $package_duration == 1 ? date('Y-m-d', strtotime('+1 month', strtotime($start_date))) : date('Y-m-d', strtotime('+1 year', strtotime($start_date))),
                'price' => $package_duration == 1 ? $membershipObj->monthly_after_vat : $membershipObj->annual_after_vat,
                'quantity' => 1,
            ]
        ];
        $total = $cartData[0]['price'];

        if (true) {
            $data = [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'global_id' => $centralUser->global_id,
                'cartData' => $cartData,
                'type' => 'New',
                'transaction_id' => rand(1,100000),
                'payment_gateaway' => 'EPayment',
            ];

            try {
                dispatch(new NewClient($data))->onConnection('database');
            } catch (Exception $e) {}

            tenancy()->initialize($tenant->id);
            User::setSessions($user);
            Variable::where('var_key','hasJob')->firstOrCreate(['var_key'=>'hasJob','var_value'=>1]);
            tenancy()->end();

            Session::put('check_user_id',$user->id);

            $token = tenancy()->impersonate($tenant,$user->id,'/dashboard');
            return redirect(tenant_route($tenant->domains()->first()->domain  . '.' . request()->getHttpHost(), 'impersonate',[
                'token' => $token
            ]));   
        } else {
            tenancy()->initialize($tenant->id);
            User::setSessions($user);
            tenancy()->end();

            \Session::flash('error', $data['status']->message);
            return redirect()->to('/paymentError')->withInput();
        }
    }

    public function changeLang(Request $request){
        if($request->ajax()){
            if(!Session::has('locale')){
                Session::put('locale', $request->locale);
            }else{
                Session::forget('locale');
                Session::put('locale', $request->locale);
            }
            return Redirect::back();
        }
    }

    public function paymentError()
    {
        return view('Tenancy.Dashboard.Views.V5.paymentError');
    }

    public function completeJob()
    {
        $checkHasJob = Session::has('hasJob') ? 1 : 0;
        Session::forget('hasJob');
        Session::forget('userCredits');
        Session::forget('package_id');

        $userObj = User::first();
        Session::flush();

        User::setSessions($userObj);
        if ($checkHasJob) {
            return redirect()->to('/QR');
        } else {
            return redirect()->to('/dashboard');
        }
    }
}
