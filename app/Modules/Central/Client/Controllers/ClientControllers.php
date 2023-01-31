<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CentralUser;
use App\Models\Domain;
use App\Models\Membership;
use App\Models\Tenant;
use App\Models\PaymentInfo;
use App\Jobs\NewClient;
use App\Models\CentralChannel;
use App\Models\Addons;
use App\Models\ChatMessage;
use App\Models\UserAddon;
use App\Models\CentralTicket;
use App\Models\Invoice;
use App\Models\ChatDialog;
use App\Models\Contact;
use App\Models\UserStatus;
use App\Models\UserChannels;
use App\Models\ExtraQuota;
use App\Models\UserExtraQuota;
use App\Models\Variable;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Jobs\TransferDays;

use DataTables;
use Storage;

use App\Jobs\SyncMessagesJob;
use App\Jobs\SyncDialogsJob;
use App\Jobs\ReadChatsJob;


class ClientControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.clients'),
            'url' => 'clients',
            'name' => 'clients',
            'nameOne' => 'client',
            'modelName' => 'CentralUser',
            'icon' => 'fa fa-users',
            'sortName' => 'name',
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'name' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '1',
                'label' => trans('main.name'),
            ],
            'email' => [
                'type' => 'email',
                'class' => 'form-control m-input',
                'index' => '2',
                'label' => trans('main.email'),
            ],
            'phone' => [
                'type' => 'number',
                'class' => 'form-control m-input',
                'index' => '3',
                'label' => trans('main.phone'),
            ],
            'domain' => [
                'type' => 'text',
                'class' => 'form-control',
                'index' => '4',
                'label' => trans('main.domain'),
            ],
        ];

        $data['tableData'] = [
            'id' => [
                'label' => trans('main.id'),
                'type' => '',
                'className' => '',
                'data-col' => '',
                'anchor-class' => '',
            ],
            'name' => [
                'label' => trans('main.name'),
                'type' => '',
                'className' => '',
                'data-col' => 'name',
                'anchor-class' => '',
            ],
            'email' => [
                'label' => trans('main.email'),
                'type' => '',
                'className' => '',
                'data-col' => 'email',
                'anchor-class' => '',
            ],
            'phone' => [
                'label' => trans('main.phone'),
                'type' => '',
                'className' => '',
                'data-col' => 'phone',
                'anchor-class' => '',
            ],
            'domain' => [
                'label' => trans('main.domain'),
                'type' => '',
                'className' => '',
                'data-col' => 'domain',
                'anchor-class' => '',
            ],
            'channelCodes' => [
                'label' => trans('main.channel'),
                'type' => '',
                'className' => ' ',
                'data-col' => 'channels',
                'anchor-class' => '',
            ],
            'leftDays' => [
                'label' => trans('main.leftDays'),
                'type' => '',
                'className' => '',
                'data-col' => 'leftDays',
                'anchor-class' => '',
            ],
            'actions' => [
                'label' => trans('main.actions'),
                'type' => '',
                'className' => '',
                'data-col' => '',
                'anchor-class' => '',
            ],
        ];
        return $data;
    }

    protected function validateInsertObject($input){
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'password' => 'required|min:6',
            'email' => 'required',
            'domain' => 'required',
            'membership_id' => 'required',
            'duration_type' => 'required',
        ];

        $message = [
            'name.required' => trans('main.nameValidate'),
            'phone.required' => trans('main.phoneValidate'),
            'password.required' => trans('main.passwordValidate'),
            'password.min' => trans('main.passwordValidate2'),
            'email.required' => trans('main.emailValidate'),
            'domain.required' => trans('main.domainValidate'),
            'membership_id.required' => trans('main.membershipValidate'),
            'duration_type.required' => trans('main.durationTypeValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = CentralUser::dataList('domains');
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.Client.Views.index')->with('data', (object) $data);
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.clients') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['memberships'] = Membership::dataList(1)['data'];
        return view('Central.Client.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
            
        $domainObj = Domain::getOneByDomain('domain',$input['domain']);
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

        $membershipObj = Membership::getData(Membership::getOne($input['membership_id']));
        if(!$membershipObj){
            Session::flash('error', trans('main.membershipValidate'));
            return redirect()->back()->withInput();
        }

        $duration = strtotime('+1 month');
        if($input['duration_type'] == 2){
            $duration = strtotime('+1 year');
        }else if($input['duration_type'] == 3){
            $duration = strtotime('+3 days');
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
            'notifications' => isset($input['notifications']) && !empty($input['notifications']) && $input['notifications'] == 'on' ? 1 : 0,
            'offers' => isset($input['offers']) && !empty($input['offers']) && $input['offers'] == 'on' ? 1 : 0,
            'group_id' => 0,
            'setting_pushed' => 0,
            'pin_code' => $input['pin_code'],
            'emergency_number' => $input['emergency_number'],
            'two_auth' => $input['two_auth'],
            'is_active' => $input['status'],
            'is_approved' => $input['status'],
            'status' => $input['status'],
            'is_old' => 0,
            'is_synced' => 0,
            'isBA' => 1,
            'duration_type' => isset($input['duration_type']) && !empty($input['duration_type']) && $input['duration_type'] == 3 ? 1 : $input['duration_type'],
            'membership_id' => $input['membership_id'],
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
                'duration_type' => isset($input['duration_type']) && !empty($input['duration_type']) && $input['duration_type'] == 3 ? 1 : $input['duration_type'],
                'group_id' => 1,
                'status' => $input['status'],
                'domain' => $input['domain'],
                'sort' => 1,
                'password' => Hash::make($input['password']),
                'is_active' => $input['status'],
                'is_approved' => $input['status'],
                'notifications' => isset($input['notifications']) && !empty($input['notifications']) && $input['notifications'] == 'on' ? 1:0,
                'offers' => isset($input['offers']) && !empty($input['offers']) && $input['offers'] == 'on' ? 1 : 0,
                'company' => $input['company'],
                'pin_code' => $input['pin_code'],
                'emergency_number' => $input['emergency_number'],
                'two_auth' => $input['two_auth'],
                'membership_id' => $input['membership_id'],
            ]);

            $paymentInfoObj = new PaymentInfo;
            $paymentInfoObj->user_id = $userObj->id;
            $paymentInfoObj->address = $input['address'];
            $paymentInfoObj->address2 = $input['address2'];
            $paymentInfoObj->city = $input['city'];
            $paymentInfoObj->country = $input['country'];
            $paymentInfoObj->region = $input['region'];
            $paymentInfoObj->postal_code = $input['postal_code'];
            $paymentInfoObj->tax_id = $input['tax_id'];
            $paymentInfoObj->payment_method = $input['payment_method'];
            $paymentInfoObj->currency = $input['currency'];
            $paymentInfoObj->created_at = DATE_TIME;
            $paymentInfoObj->created_by = USER_ID;
            $paymentInfoObj->save();

            return $userObj;
        });

        $start_date = date('Y-m-d');
        $package_duration = $input['duration_type'];
        $cartData = [
            [
                'id' => $input['membership_id'],
                'type' => 'membership',
                'title' => $membershipObj->{'title_' . LANGUAGE_PREF},
                'duration_type' => $package_duration,
                'start_date' => $start_date,
                'end_date' => $package_duration == 1 ? date('Y-m-d', strtotime('+1 month', strtotime($start_date))) : ($package_duration == 2 ?  date('Y-m-d', strtotime('+1 year', strtotime($start_date))) :  date('Y-m-d', strtotime('+3 days', strtotime($start_date)))),
                'price' => $package_duration == 1 ? $membershipObj->monthly_after_vat : $membershipObj->annual_after_vat,
                'quantity' => 1,
            ]
        ];
        $total = $cartData[0]['price'];
        $data = [
            'user_id' => $centralUser->id,
            'tenant_id' => $tenant->id,
            'global_id' => $centralUser->global_id,
            'cartData' => $cartData,
            'type' => 'New',
            'transaction_id' => rand(1,100000),
            'payment_gateaway' => 'EPayment',
        ];

        try {
            dispatch(new NewClient($data))->onConnection('syncdata');
        } catch (Exception $e) {}

        tenancy()->initialize($tenant->id);
        Variable::where('var_key','hasJob')->firstOrCreate(['var_key'=>'hasJob','var_value'=>1]);
        tenancy()->end();

            
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function view($id) {
        $id = (int) $id;

        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }
        
        $data['data'] = CentralUser::getData($userObj);
        if($data['data']->domain == ''){
            return redirect()->back();
        }
        
        $domainObj = Domain::where('domain',$data['data']->domain)->first();
        tenancy()->initialize($domainObj->tenant_id);
        $data['paymentInfo'] = PaymentInfo::where('user_id',$id)->first();
        $channelObj = UserChannels::first();
        if($channelObj){
            $whatsLoopObj = new \OfficialHelper($channelObj->instanceId,$channelObj->instanceToken);
            $updateResult = $whatsLoopObj->me();
            $result = $updateResult->json();
        }
        $lastStatus = UserStatus::orderBy('id','DESC')->first();

        $data['client'] = $userObj;
        $data['me'] =  isset($result) && isset($result['data']) && isset($result['data']['me']) ? (object) $result['data']['me'] : [];
        $settingsArr = isset($result) && isset($result['data']) && isset($result['data']['channelSetting']) ? (object) $result['data']['channelSetting'] : [];
        $data['status'] = $lastStatus ? UserStatus::getData($lastStatus) : [];
        $data['allMessages'] = ChatMessage::count();
        $data['sentMessages'] = ChatMessage::where('fromMe',1)->count();
        $data['incomingMessages'] = $data['allMessages'] - $data['sentMessages'];
        $data['contactsCount'] = Contact::NotDeleted()->count();
        $data['channel'] = $channelObj ? UserChannels::getData($channelObj) : [];
        tenancy()->end($domainObj->tenant_id);
        
        // // Update User With Settings For Whatsapp Based On His Domain
        $myData = [
            'sendDelay' => '0',
            'webhooks' => [
                'messageNotifications' => str_replace('://', '://'.$data['data']->domain.'.', config('app.BASE_URL')).'/services/webhooks/messages-webhook',
                'ackNotifications' => str_replace('://', '://'.$data['data']->domain.'.', config('app.BASE_URL')).'/services/webhooks/acks-webhook',
                'chatNotifications' => str_replace('://', '://'.$data['data']->domain.'.', config('app.BASE_URL')).'/services/webhooks/chats-webhook',
                'businessNotifications' => str_replace('://', '://'.$data['data']->domain.'.', config('app.BASE_URL')).'/services/webhooks/business-webhook',
            ],
            'ignoreOldMessages' => 1,
        ];
        if($channelObj){
            $channelObj = CentralChannel::where('instanceId',$channelObj->id)->first();
            if($channelObj){
                $mainWhatsLoopObj = new \OfficialHelper($channelObj->id,$channelObj->token);
                if($userObj->setting_pushed == 0){
                    $updateResult = $mainWhatsLoopObj->updateChannelSetting($myData);
                    $result = $updateResult->json();
                    $userObj->setting_pushed = 1;
                    $userObj->save();
                }
                $settingsArr = $myData;
            }
        }

        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.view') . ' '.trans('main.clients') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';

        $data['memberships'] = Membership::dataList(1)['data'];
        $data['addons'] = Addons::dataList(1)['data'];
        $data['extraQuotas'] = ExtraQuota::dataList(1)['data'];

        $data['tickets'] = CentralTicket::dataList(null,$id)['data'];
        $data['invoices'] = Invoice::dataList(null,$id)['data']; 
        $data['userAddons'] = UserAddon::NotDeleted()->where('user_id',$id)->pluck('addon_id');
        $data['userAddons'] = reset($data['userAddons']);
        $data['settings'] = isset($settingsArr) ? (object)$settingsArr : (object)$myData;
        $data['channelSettings'] = $data['settings'];
        if($data['data']->membership_id){
            $data['membership'] = Membership::getData(Membership::getOne($data['data']->membership_id));
            $data['addonsData'] = UserAddon::dataList(null,$id)['data'];
            $data['extraQuotasData'] = UserExtraQuota::dataList($id)['data'];
        }
        return view('Central.Client.Views.view')->with('data', (object) $data);      
    }

    public function invLogin($id){
        $id = (int) $id;

        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }
        $userObj = CentralUser::getData($userObj);
        $domainObj = Domain::where('domain',$userObj->domain)->first();
        $tenant = Tenant::find($domainObj->tenant_id);
        $token = tenancy()->impersonate($tenant,$id,'/dashboard');
        Session::put('check_user_id',$id);
        return redirect(tenant_route($tenant->domains()->first()->domain  . '.' . request()->getHttpHost(), 'impersonate',[
            'token' => $token
        ]));
    }

    public function transferDays($id){
        $id = (int) $id;
        $input = \Request::all();

        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }
        $data['data'] = CentralUser::getData($userObj);
        $centralChannel = CentralChannel::NotDeleted()->orderBy('id','ASC')->first();
        $domainObj = Domain::where('domain',$data['data']->domain)->first();
        tenancy()->initialize($domainObj->tenant_id);
        $channelObj = UserChannels::first();
        tenancy()->end($domainObj->tenant_id);

        $mainWhatsLoopObj = new \OfficialHelper($centralChannel->id,$centralChannel->token);
        $transferDaysData = [
            'receiver' => $channelObj->id,
            'days' => $input['days'],
            'sender' => $centralChannel->id,
        ];
        $updateResult = $mainWhatsLoopObj->transferDays($transferDaysData);

        \Session::flash('success', trans('main.editSuccess'));
        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }

    public function pinCodeLogin($id){
        $id = (int) $id;

        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }
        $userObj = CentralUser::getData($userObj);
        $domainObj = Domain::where('domain',$userObj->domain)->first();
        $tenant = Tenant::find($domainObj->tenant_id);
        $token = tenancy()->impersonate($tenant,$id,'/dashboard');
        return redirect(tenant_route($tenant->domains()->first()->domain  . '.' . request()->getHttpHost(), 'loginByCode',[
            'code' => $userObj->pin_code,
            'user_id' => $userObj->id,
        ]));
    }

    public function compensation($id){
        $id = (int) $id;
        $input = \Request::all();

        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data = $input['data'];
        foreach ($data as $key => $value) {
            if($value['type'] == 1){
                CentralChannel::where('global_user_id',$userObj->global_id)->update([
                    'start_date' => date('Y-m-d',strtotime($value['start_date'])),
                    'end_date' => date('Y-m-d',strtotime($value['end_date'])),
                ]);

                $user = CentralUser::getData($userObj);
                $domainObj = Domain::where('domain',$user->domain)->first();
                $tenant = Tenant::find($domainObj->tenant_id);
                tenancy()->initialize($tenant);
                $channelObj = UserChannels::first();
                $channelObj->start_date = date('Y-m-d',strtotime($value['start_date']));
                $channelObj->end_date = date('Y-m-d',strtotime($value['end_date']));
                $channelObj->save();
                tenancy()->end($tenant);
            }elseif($value['type'] == 2){
                UserAddon::where('user_id',$id)->where('addon_id',$value['id'])->update([
                    'start_date' => date('Y-m-d',strtotime($value['start_date'])),
                    'end_date' => date('Y-m-d',strtotime($value['end_date'])),
                ]);
            }elseif($value['type'] == 3){
                UserExtraQuota::where('user_id',$id)->where('extra_quota_id',$value['id'])->update([
                    'start_date' => date('Y-m-d',strtotime($value['start_date'])),
                    'end_date' => date('Y-m-d',strtotime($value['end_date'])),
                ]);
            }
        }        
        
        \Session::flash('success', trans('main.editSuccess'));
        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
    
    public function updatePersonalInfo($id){
        $id = (int) $id;
        $input = \Request::all();
        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $centralUserObj = CentralUser::getData($userObj);
        unset($input['_token']);
        $domainObj = Domain::where('domain',$centralUserObj->domain)->first();

        tenancy()->initialize($domainObj->tenant_id);
        $channelObj = UserChannels::first();
        tenancy()->end($domainObj->tenant_id);

        $updates = [];
        if (isset($input['email']) && !empty($input['email']) && $input['email'] != $centralUserObj->email) {
            $userObj = CentralUser::checkUserBy('email', $input['email'], $id);
            if ($userObj) {
                Session::flash('error', trans('main.emailError'));
                return redirect()->back()->withInput();
            }
            $userObj->email = $input['email'];
            $userObj->save();
            $updates['email'] = $input['email'];
        }

        if (isset($input['phone']) && !empty($input['phone']) && $input['phone'] != $centralUserObj->phone) {
            $userObj = User::checkUserBy('phone', $input['phone'], $id);
            if ($userObj) {
                Session::flash('error', trans('main.phoneError'));
                return redirect()->back()->withInput();
            }
            $userObj->phone = $input['phone'];
            $userObj->save();

            \DB::connection('main')->table('tenants')->where('id', $domainObj->tenant_id)->update([
                'phone' => $input['phone'],
            ]);
            $updates['phone'] = $input['phone'];
        }

        if (isset($input['domain']) && !empty($input['domain']) && $centralUserObj->domain != $input['domain']) {
            $rules = [
                'domain' => 'regex:/^([a-zA-Z0-9][a-zA-Z0-9-_])*[a-zA-Z0-9]*[a-zA-Z0-9-_]*[[a-zA-Z0-9]$/',
            ];
            $message = [
                'domain.regex' => trans('main.domain2Validate'),
            ];

            $validate = \Validator::make($input, $rules, $message);
            if ($validate->fails()) {
                Session::flash('error', $validate->messages()->first());
                return redirect()->back()->withInput();
            }

            $checkDomainObj = \DB::connection('main')->table('domains')->where('domain', $input['domain'])->first();
            if ($checkDomainObj && $checkDomainObj->domain != $centralUserObj->domain) {
                Session::flash('error', trans('main.domainValidate2'));
                return redirect()->back()->withInput();
            }

            \DB::connection('main')->table('domains')->where('tenant_id', $domainObj->tenant_id)->limit(1)->update([
                'domain' => $input['domain'],
            ]);

            // Update User With Settings For Whatsapp Based On His Domain
            if($channelObj){
                $channelObj = CentralChannel::where('instanceId',$channelObj->id)->first();
                if($channelObj){
                    $mainWhatsLoopObj = new \OfficialHelper($channelObj->id,$channelObj->token);
                    $myData = [
                        'sendDelay' => '0',
                        'webhooks' => [
                            'messageNotifications' => str_replace('://', '://'.$input['domain'].'.', config('app.BASE_URL')).'/services/webhooks/messages-webhook',
                            'ackNotifications' => str_replace('://', '://'.$input['domain'].'.', config('app.BASE_URL')).'/services/webhooks/acks-webhook',
                            'chatNotifications' => str_replace('://', '://'.$input['domain'].'.', config('app.BASE_URL')).'/services/webhooks/chats-webhook',
                            'businessNotifications' => str_replace('://', '://'.$input['domain'].'.', config('app.BASE_URL')).'/services/webhooks/business-webhook',
                        ],
                        'ignoreOldMessages' => 1,
                    ];
                    $updateResult = $mainWhatsLoopObj->updateChannelSetting($myData);
                    $result = $updateResult->json();
                    $updates['domain'] = $input['domain'] ;
                }
            }
        }

        if (isset($input['company']) && !empty($input['company'])) {
            $userObj->company = $input['company'];
            $userObj->save();
            $updates['company'] = $input['company'];
        }

        if (isset($input['name']) && !empty($input['name'])) {
            $userObj->name = $input['name'];
            $userObj->save();
            
            \DB::connection('main')->table('tenants')->where('id', $domainObj->tenant_id)->update([
                'title' => $input['name'],
            ]);
            $updates['name'] = $input['name'];
        }

        if (isset($input['password']) && !empty($input['password'])) {
            $userObj->password = Hash::make($input['password']);
            $userObj->save();
            $updates['password'] = Hash::make($input['password']);
        }
        
        tenancy()->initialize($domainObj->tenant_id);
        $tenantUser = User::where('id',$id)->update($updates);
        tenancy()->end($domainObj->tenant_id);

        \Session::flash('success', trans('main.editSuccess'));
        return back()->withInput();
    }

    public function updateSubscription($id){
        $id = (int) $id;
        $input = \Request::all();
        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $centralUserObj = CentralUser::getData($userObj);
        unset($input['_token']);

        $duration_type = isset($input['duration_type']) && !empty($input['duration_type']) && $input['duration_type'] == 3 ? 1 : $input['duration_type'];
        $membership_id = $input['membership_id'];
        $membershipObj = Membership::getData(Membership::getOne($membership_id));
        if(!$membershipObj){
            Session::flash('error', trans('main.membershipValidate'));
            return redirect()->back()->withInput();
        }
        $domainObj = Domain::where('domain',$centralUserObj->domain)->first();

        CentralChannel::where('tenant_id',$domainObj->tenant_id)->update(['start_date'=>$input['start_date'],'end_date'=>$input['end_date']]);

        $userObj->membership_id = $membership_id;
        $userObj->duration_type = $duration_type;
        $userObj->save();

        tenancy()->initialize($domainObj->tenant_id);
        UserChannels::first()->update(['start_date'=>$input['start_date'],'end_date'=>$input['end_date']]);
        User::where('id','!=',0)->update(['membership_id'=>$membership_id,'duration_type'=>$duration_type]);
        tenancy()->end($domainObj->tenant_id);

        \Session::flash('success', trans('main.editSuccess'));
        return back()->withInput();
    }

    public function deleteAddon($id,$type,$type_id){
        $id = (int) $id;
        $type = (int) $type;
        $type_id = (int) $type_id;
        if(!in_array($type, [1,2])){
            return Redirect('404');
        }

        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        if($type == 1){
            UserAddon::where('user_id',$id)->where('addon_id',$type_id)->delete();
        }else if($type == 2){
            UserExtraQuota::where('user_id',$id)->where('extra_quota_id',$type_id)->delete();
        }

        \Session::flash('success', trans('main.editSuccess'));
        return back()->withInput();
    }

    public function enableAddon($id,$type,$type_id){
        $id = (int) $id;
        $type = (int) $type;
        $type_id = (int) $type_id;
        if(!in_array($type, [1,2])){
            return Redirect('404');
        }

        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        if($type == 1){
            UserAddon::where('user_id',$id)->where('addon_id',$type_id)->update(['status'=>1]);
        }else if($type == 2){
            UserExtraQuota::where('user_id',$id)->where('extra_quota_id',$type_id)->update(['status'=>1]);
        }

        \Session::flash('success', trans('main.editSuccess'));
        return back()->withInput();
    }

    public function disableAddon($id,$type,$type_id){
        $id = (int) $id;
        $type = (int) $type;
        $type_id = (int) $type_id;
        if(!in_array($type, [1,2])){
            return Redirect('404');
        }

        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        if($type == 1){
            UserAddon::where('user_id',$id)->where('addon_id',$type_id)->update(['status'=>0]);
        }else if($type == 2){
            UserExtraQuota::where('user_id',$id)->where('extra_quota_id',$type_id)->update(['status'=>0]);
        }

        \Session::flash('success', trans('main.editSuccess'));
        return back()->withInput();
    }

    public function updateUserAddons($id){
        $id = (int) $id;
        $input = \Request::all();
        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        if(!isset($input['addon_id']) || empty($input['addon_id'])){
            return \TraitsFunc::ErrorMessage(trans('main.addonValidate'));
        }

        if(!isset($input['status']) || empty($input['status'])){
            return \TraitsFunc::ErrorMessage(trans('main.statusValidate'));
        }

        if(!isset($input['start_date']) || empty($input['start_date'])){
            return \TraitsFunc::ErrorMessage(trans('main.start_dateValidate'));
        }

        if(!isset($input['end_date']) || empty($input['end_date'])){
            return \TraitsFunc::ErrorMessage(trans('main.end_dateValidate'));
        }

        if(!isset($input['duration_type']) || empty($input['duration_type'])){
            return \TraitsFunc::ErrorMessage(trans('main.durationTypeValidate'));
        }

        $input['status'] = $input['status'] == 3 ? 0 : $input['status'];
        $input['duration_type'] = $input['duration_type'] == 3 ? 1 : $input['duration_type'];

        $centralUserObj = CentralUser::getData($userObj);
        $domainObj = Domain::where('domain',$centralUserObj->domain)->first();
        if(isset($input['item_id']) && !empty($input['item_id'])){
            $dataObj = UserAddon::find($input['item_id']);
        }else{
            $dataObj = new UserAddon;
        }
        $dataObj->addon_id = $input['addon_id'];
        $dataObj->tenant_id = $domainObj->tenant_id;
        $dataObj->global_user_id = $userObj->global_id;
        $dataObj->status = $input['status'];
        $dataObj->start_date = $input['start_date'];
        $dataObj->end_date = $input['end_date'];
        $dataObj->duration_type = $input['duration_type'];
        $dataObj->setting_pushed = 0;
        $dataObj->user_id = $id;
        $dataObj->save();
        
        \Session::flash('success', trans('main.editSuccess'));
        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
    
    public function updateUserExtraQuotas($id){
        $id = (int) $id;
        $input = \Request::all();
        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        if(!isset($input['extra_quota_id']) || empty($input['extra_quota_id'])){
            return \TraitsFunc::ErrorMessage(trans('main.extraQuotaValidate'));
        }

        if(!isset($input['status']) || empty($input['status'])){
            return \TraitsFunc::ErrorMessage(trans('main.statusValidate'));
        }

        if(!isset($input['start_date']) || empty($input['start_date'])){
            return \TraitsFunc::ErrorMessage(trans('main.start_dateValidate'));
        }

        if(!isset($input['end_date']) || empty($input['end_date'])){
            return \TraitsFunc::ErrorMessage(trans('main.end_dateValidate'));
        }

        if(!isset($input['duration_type']) || empty($input['duration_type'])){
            return \TraitsFunc::ErrorMessage(trans('main.durationTypeValidate'));
        }

        $input['status'] = $input['status'] == 3 ? 0 : $input['status'];
        $input['duration_type'] = $input['duration_type'] == 3 ? 1 : $input['duration_type'];
        
        $centralUserObj = CentralUser::getData($userObj);
        $domainObj = Domain::where('domain',$centralUserObj->domain)->first();
        if(isset($input['item_id']) && !empty($input['item_id'])){
            $dataObj = UserExtraQuota::find($input['item_id']);
        }else{
            $dataObj = new UserExtraQuota;
        }
        $dataObj->extra_quota_id = $input['extra_quota_id'];
        $dataObj->tenant_id = $domainObj->tenant_id;
        $dataObj->global_user_id = $userObj->global_id;
        $dataObj->status = $input['status'];
        $dataObj->start_date = $input['start_date'];
        $dataObj->end_date = $input['end_date'];
        $dataObj->duration_type = $input['duration_type'];
        $dataObj->user_id = $id;
        $dataObj->save();
        
        \Session::flash('success', trans('main.editSuccess'));
        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }

    public function updatePaymentInfo($id){
        $id = (int) $id;
        $input = \Request::all();
        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = CentralUser::getData($userObj);
        unset($input['_token']);
        $input['user_id']= $id;
        $domainObj = Domain::where('domain',$data['data']->domain)->first();
        tenancy()->initialize($domainObj->tenant_id);
        $paymentObj = PaymentInfo::where('user_id',$id);
        if($paymentObj->first()){
            $paymentObj->update($input);
        }else{
            PaymentInfo::create($input);
        }
        tenancy()->end($domainObj->tenant_id);

        \Session::flash('success', trans('main.editSuccess'));
        return back()->withInput();
    }

    public function updateSettings($id){
        $id = (int) $id;
        $input = \Request::all();
        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }
        $data['data'] = CentralUser::getData($userObj);
        if(isset($input['notifications']) && !empty($input['notifications'])){
            $input['notifications'] = $input['notifications'] == 'on' ? 1 : 0;
        }
        if(isset($input['offers']) && !empty($input['offers'])){
            $input['offers'] = $input['offers'] == 'on' ? 1 : 0;
        }
        unset($input['_token']);
        unset($input['pin_code']);
        unset($input['emergency_tel']);

        $domainObj = Domain::where('domain',$data['data']->domain)->first();
        tenancy()->initialize($domainObj->tenant_id);
        $channelObj = User::where('id',$id)->update($input);
        tenancy()->end($domainObj->tenant_id);

        CentralUser::where('id',$id)->update($input);
        
        \Session::flash('success', trans('main.editSuccess'));
        return back()->withInput();
    }

    public function updateChannelSettings($id){
        $id = (int) $id;
        $input = \Request::all();
        unset($input['_token']);
        $myArr = [];
        foreach ($input as $key => $value) {
            if($value != null){
                $myArr[$key] = $value;
            }
        }
        $newData['webhooks'] = $myArr;
        $newData['sendDelay'] = 0;
        $newData['ignoreOldMessages'] = 1;

        $userObj = CentralUser::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }
        $data['data'] = CentralUser::getData($userObj);

        $domainObj = Domain::where('domain',$data['data']->domain)->first();
        tenancy()->initialize($domainObj->tenant_id);
        $channelObj = UserChannels::first();
        tenancy()->end($domainObj->tenant_id);

        $channelObj = $channelObj != null ? CentralChannel::where('instanceId',$channelObj->id)->first() : [];
        if($channelObj && $channelObj->instanceId != null){
            $mainWhatsLoopObj = new \OfficialHelper($channelObj->id,$channelObj->token);
            $settings = $mainWhatsLoopObj->updateChannelSetting($newData);       
            $result = $settings->json();
            if($result['status']['status'] != 1){
                \Session::flash('error', $result['status']['message']);
                return back()->withInput();
            }
        }
        \Session::flash('success', trans('main.editSuccess'));
        return back()->withInput();
    }
    
    public function delete($id) {
        $id = (int) $id;
        $dataObj = CentralUser::getOne($id);
        \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id);
        return \Helper::globalDelete($dataObj);
    }

    public function transferDay(){
        \Artisan::call('transfer:days');
        \Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
    }

    public function pushChannelSetting(){
        \Artisan::call('push:channelSetting');
        \Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
    }

    public function setInvoices(){
        \Artisan::call('set:invoices');
        \Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
    }
}
