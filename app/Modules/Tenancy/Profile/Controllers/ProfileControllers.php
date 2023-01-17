<?php namespace App\Http\Controllers;

use App\Models\Addons;
use App\Models\UserAddon;
use App\Models\CentralUser;
use App\Models\ExtraQuota;
use App\Models\UserExtraQuota;
use App\Models\Membership;
use App\Models\PaymentInfo;
use App\Models\User;
use App\Models\Variable;
use App\Models\UserChannels;
use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\BankTransfer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Storage;
use Validator;
use App\Jobs\NewClient;

class ProfileControllers extends Controller
{

    use \TraitsFunc;

    public function personalInfo()
    {
        $userObj = User::authenticatedUser();
        $data['designElems']['mainData'] = [
            'title' => trans('main.account_setting'),
            'icon' => 'fa fa-user',
        ];
        $data['data'] = $userObj;
        $data['paymentInfo'] = $userObj->paymentInfo;
        return view('Tenancy.Profile.Views.personalInfo')->with('data', (object) $data);
    }

    public function updatePersonalInfo()
    {
        $input = \Request::all();
        $mainUserObj = User::getOne(USER_ID);
        $dataObj = User::getData($mainUserObj);
        $domainObj = \DB::connection('main')->table('domains')->where('domain', $dataObj->domain)->first();

        $oldDomainValue = $mainUserObj->domain;

        if (isset($input['email']) && !empty($input['email'])) {
            $userObj = User::checkUserBy('email', $input['email'], USER_ID);
            $oldEmail = null;
            if ($userObj) {
                if ($userObj->deleted_by != null) {
                    $oldEmail = $userObj->email;
                    User::where('email', $input['email'])->where('deleted_by', '!=', null)->delete();
                } else {
                    Session::flash('error', trans('main.emailError'));
                    return redirect()->back()->withInput();
                }
            }
            $mainUserObj->email = $input['email'];

            if ($mainUserObj->group_id == 1) {
                CentralUser::where('id', User::first()->id)->update(['email' => $input['email']]);
            }
        }

        if (isset($input['phone']) && !empty($input['phone'])) {
            $userObj = User::checkUserBy('phone', $input['phone'], USER_ID);
            $oldPhone = null;
            if ($userObj) {
                if ($userObj->deleted_by != null) {
                    $oldPhone = $userObj->phone;
                    User::where('phone', $input['phone'])->where('deleted_by', '!=', null)->delete();
                } else {
                    Session::flash('error', trans('main.phoneError'));
                    return redirect()->back()->withInput();
                }
            }
            $mainUserObj->phone = $input['phone'];

            \DB::connection('main')->table('tenants')->where('id', $domainObj->tenant_id)->update([
                'phone' => $input['phone'],
            ]);
            if ($mainUserObj->group_id == 1) {
                CentralUser::where('id', User::first()->id)->update(['phone' => $input['phone']]);
            }
        }

        if (isset($input['two_auth']) && !empty($input['two_auth'])) {
            $mainUserObj->two_auth = $input['two_auth'];
        }

        if (isset($input['emergency_number']) && !empty($input['emergency_number'])) {
            $mainUserObj->emergency_number = $input['emergency_number'];
        }

        if (isset($input['domain']) && !empty($input['domain']) && $oldDomainValue != $input['domain']) {

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
            if ($checkDomainObj && $checkDomainObj->domain != $dataObj->domain) {
                Session::flash('error', trans('main.domainValidate2'));
                return redirect()->back()->withInput();
            }

            $mainUserObj->domain = $input['domain'];
            \DB::connection('main')->table('domains')->where('tenant_id', $domainObj->tenant_id)->limit(1)->update([
                'domain' => $input['domain'],
            ]);
            // // Update User With Settings For Whatsapp Based On His Domain
            $mainWhatsLoopObj = new \OfficialHelper();
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
        }

        if (isset($input['company']) && !empty($input['company'])) {
            $mainUserObj->company = $input['company'];
            CentralUser::where('id', User::first()->id)->update(['company' => $input['company']]);
        }

        if (isset($input['name']) && !empty($input['name'])) {
            $mainUserObj->name = $input['name'];
            if ($mainUserObj->group_id == 1) {
                CentralUser::where('id', User::first()->id)->update(['name' => $input['name']]);
            }
            \DB::connection('main')->table('tenants')->where('id', $domainObj->tenant_id)->update([
                'title' => $input['name'],
            ]);
        }

        $mainUserObj->save();

        $photos_name = Session::get('photos');
        if ($photos_name) {
            $photos = Storage::files($photos_name);
            if (count($photos) > 0) {
                $images = self::addImage($photos[0], $mainUserObj->id);
                if ($images == false) {
                    Session::flash('error', trans('main.uploadProb'));
                    return redirect()->back()->withInput();
                }
                $mainUserObj->image = $images;
                $mainUserObj->save();
            }
        }

        if ($input['domain'] != $oldDomainValue) {
            return redirect()->to(config('tenancy.protocol') . $input['domain'] . '.' . config('tenancy.central_domains')[0] . '/login');
        }

        Session::forget('photos');
        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function postChangePassword()
    {
        $input = \Request::all();
        $rules = [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];

        $message = [
            'password.required' => trans('auth.passwordValidation'),
            'password.confirmed' => trans('auth.passwordValidation2'),
            'password_confirmation.required' => trans('auth.passwordValidation3'),
        ];

        $validate = Validator::make($input, $rules, $message);
        if ($validate->fails()) {
            Session::flash('error', $validate->messages()->first());
            return back()->withInput();
        }

        $password = $input['password'];
        $userObj = User::NotDeleted()->find(USER_ID);
        if ($userObj == null) {
            Session::flash('error', trans('auth.invalidUser'));
            return back()->withInput();
        }
        $userObj->password = Hash::make($password);
        $userObj->save();

        if ($userObj->group_id == 1) {
            CentralUser::where('id', $userObj->id)->update(['password' => Hash::make($password)]);
        }

        Session::flash('success', trans('auth.passwordChanged'));
        return \Redirect::back()->withInput();
    }

    public function postPaymentInfo(Request $request)
    {
        $input = \Request::all();
        $rules = [
            'address' => 'required',
        ];

        $message = [
            'address.required' => trans('main.addressValidation'),
        ];

        $validate = Validator::make($input, $rules, $message);
        if ($validate->fails() && !$request->ajax()) {
            Session::flash('error', $validate->messages()->first());
            return back()->withInput();
        }
        $userObj = User::authenticatedUser();
        if ($userObj->paymentInfo) {
            $paymentInfoObj = $userObj->paymentInfo;
            $paymentInfoObj->updated_at = DATE_TIME;
            $paymentInfoObj->updated_by = USER_ID;
            $type = 2;
        } else {
            $paymentInfoObj = new PaymentInfo();
            $paymentInfoObj->created_at = DATE_TIME;
            $paymentInfoObj->created_by = USER_ID;
            $type = 1;
        }

        $paymentInfoObj->user_id = USER_ID;
        $paymentInfoObj->address = $input['address'];
        $paymentInfoObj->address2 = $input['address2'];
        $paymentInfoObj->city = $input['city'];
        if(isset($input['payment_method'])){
            $paymentInfoObj->payment_method = $input['payment_method'];
        }
        if(isset($input['currency'])){
            $paymentInfoObj->currency = $input['currency'];
        }
        $paymentInfoObj->region = $input['region'];
        $paymentInfoObj->country = $input['country'];
        $paymentInfoObj->postal_code = $input['postal_code'];
        $paymentInfoObj->tax_id = $input['tax_id'];
        $paymentInfoObj->save();

        if($request->ajax()){
            return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
        }
        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function uploadImage(Request $request, $id = false)
    {
        $rand = rand() . date("YmdhisA");
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            Storage::put($rand, $files);
            Session::put('photos', $rand);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images, $nextID = false)
    {
        $fileName = \ImagesHelper::UploadFile('users', $images, $nextID);
        if ($fileName == false) {
            return false;
        }
        return $fileName;
    }

    public function deleteImage()
    {
        $id = (int) USER_ID;
        $input = \Request::all();

        $menuObj = User::find($id);
        if ($menuObj == null) {
            return \TraitsFunc::ErrorMessage(trans('main.userNotFound'));
        }

        \ImagesHelper::deleteDirectory(public_path('/') . '/uploads/users/' . $id . '/' . $menuObj->image);
        $menuObj->image = '';
        $menuObj->save();
        return \TraitsFunc::SuccessResponse(trans('main.imgDeleted'));
    }

    public function memberships()
    {   
        $memberships = Membership::NotDeleted()->where('id','!=',Session::get('membership'));
        $data['memberships'] = Membership::generateObj($memberships)['data'];
        return view('Tenancy.Profile.Views.memberships')->with('data', (object) $data);
    }

    public function updateMembership(){
        $input = \Request::all();
        if((!isset($input['membership']) || empty($input['membership'])) || (!isset($input['duration']) || empty($input['duration']))){
            Session::flash('error', trans('main.membershipValidate'));
            return back()->withInput();
        }

        $membership_id = (int) $input['membership'];
        $duration = (int) $input['duration'];
        $membershipObj = Membership::getOne($membership_id);
        $channelObj = UserChannels::NotDeleted()->first();
        $oldDuration = User::NotDeleted()->first()->duration_type;
        if(!$membershipObj || !$channelObj){
            Session::flash('error', trans('main.membershipValidate'));
            return back()->withInput();
        }

        $userObj = User::authenticatedUser();
        $oldMembership = Membership::getOne(User::NotDeleted()->first()->membership_id);
        $datediff = strtotime($channelObj->end_date) - strtotime(date('Y-m-d H:i:s'));
        $daysLeft = (int) round($datediff / (60 * 60 * 24));
        $newPriceAfterVat = $duration == 1 ? $membershipObj->monthly_after_vat : $membershipObj->annual_after_vat;

        if ($oldDuration == 1) {
            $usedCost = ($oldMembership->monthly_after_vat / 30);
        } else if ($oldDuration == 2) {
            $usedCost = ($oldMembership->annual_after_vat / 365);
        }

        $userCredits = round($daysLeft * $usedCost, 2);
        if($userCredits > $newPriceAfterVat){
            Session::flash('error', trans('main.membershipValidate'));
            return back()->withInput();
        }
        \Session::put('userCredits',$userCredits);

        $data['userCredits'] = $userCredits;
        $data['paymentInfo'] = $userObj->paymentInfo;
        $data['items'][] = [
            'id' => $membership_id,
            'type' => 'membership',
            'duration_type' => $duration,
            'title' => Membership::getData($membershipObj)->title,
            'start_date' => $channelObj->start_date,
            'end_date' => $duration == 1 ? date('Y-m-d',strtotime('+1 month',strtotime($channelObj->start_date))) : date('Y-m-d',strtotime('+1 year',strtotime($channelObj->start_date))),
            'price' => $duration == 1 ? $membershipObj->monthly_after_vat : $membershipObj->annual_after_vat,
            'quantity' => 1,
        ];

        $subscriptionHelperData = [
            'user_id' => ROOT_ID,
            'tenant_id' => TENANT_ID,
            'global_id' => GLOBAL_ID,
            'cartData' => $data['items'],
            'type' => 'Change',
            'transaction_id' => null,
            'payment_gateaway' => null,
            'user_credits' => $userCredits,
            'coupon_code' => null,
        ];

        Variable::where('var_key','inv_status')->firstOrCreate(['var_key'=>'inv_status','var_value'=>'Change']);
        $subscriptionHelperObj = new \SubscriptionHelper;
        $data['invoice_id'] = $subscriptionHelperObj->setInvoice($subscriptionHelperData);
        return view('Tenancy.Profile.Views.cart')->with('data', (object) $data);
    }

    public function addCoupon(){
        $input = \Request::all();

        $availableCoupons = Coupon::availableCoupons();
        $availableCoupons = reset($availableCoupons);        
        $coupon = $input['coupon'];
        if($coupon != null){
            if(count($availableCoupons) > 0 && !in_array($coupon, $availableCoupons)){
                return \TraitsFunc::ErrorMessage('هذا الكود ('.$coupon.') غير متاح حاليا');
            }

            if(in_array($coupon, $availableCoupons)){
                $couponObj = Coupon::NotDeleted()->where('code',$coupon)->where('status',1)->first();
                if($couponObj){

                    $invoiceObj = Invoice::NotDeleted()->where('client_id',USER_ID)->where('status',0)->orderBy('id','DESC')->first();
                    if($invoiceObj){
                        $invoiceObj->total = $invoiceObj->total - ( $couponObj->discount_type == 1 ? $couponObj->discount_value : round($invoiceObj->total * $couponObj->discount_value / 100 ,2) );
                        $invoiceObj->coupon_code = $coupon;
                        $invoiceObj->discount_type = $couponObj->discount_type;
                        $invoiceObj->discount_value = $couponObj->discount_value;
                        $invoiceObj->save();
                    }

                    $statusObj['data'] = Coupon::getData($couponObj);
                    $statusObj['status'] = \TraitsFunc::SuccessMessage(trans('main.addSuccess'));
                    return \Response::json((object) $statusObj);
                }
            }
        }
    }

    public function checkout(Request $request){
        if ($request->hasFile('transfer_image')) {
            $centralUser = CentralUser::getOne(ROOT_ID);
            $files = $request->file('transfer_image');

            $bankTransferObj = BankTransfer::NotDeleted()->where('user_id',USER_ID)->where('status',1)->first();
            $invoiceObj = Invoice::find($request->invoice_id);
            if(!$bankTransferObj){
                $bankTransferObj = new BankTransfer;
                $bankTransferObj->user_id = USER_ID;
                $bankTransferObj->tenant_id = TENANT_ID;
                $bankTransferObj->global_id = GLOBAL_ID;
                $bankTransferObj->invoice_id = $invoiceObj->id;
                $bankTransferObj->domain = DOMAIN;
                $bankTransferObj->order_no = rand(1,100000);
                $bankTransferObj->status = 1;
                $bankTransferObj->sort = BankTransfer::newSortIndex();
                $bankTransferObj->created_at = DATE_TIME;
                $bankTransferObj->created_by = USER_ID;
            }
            $bankTransferObj->total = $invoiceObj->total;
            $bankTransferObj->save();

            $fileName = \ImagesHelper::uploadFileFromRequest('bank_transfers', $files,$bankTransferObj->id);
            if($fileName == false){
                return false;
            }

            $bankTransferObj->image = $fileName;
            $bankTransferObj->save();

            Variable::where('var_key','cartObj')->firstOrCreate(['var_key'=>'cartObj','var_value'=>json_encode(unserialize($invoiceObj->items))]);

            Session::flash('success',trans('main.transferSuccess'));
            return redirect()->to('/dashboard');
        }       
    }

    public function activate()
    {
        $input = \Request::all();
        if(!isset($input['invoice_id']) || empty($input['invoice_id'])){
            return back()->withInput();
        }

        $invoiceObj = Invoice::NotDeleted()->where('client_id',ROOT_ID)->where('id',(int) $input['invoice_id'])->first();
        if(!$invoiceObj){
            return back()->withInput();
        }

        tenancy()->initialize(TENANT_ID);
        $type = Variable::getVar('inv_status');
        tenancy()->end(TENANT_ID);

        $data = [
            'user_id' => ROOT_ID,
            'tenant_id' => TENANT_ID,
            'global_id' => GLOBAL_ID,
            'cartData' => json_decode(json_encode(unserialize($invoiceObj->items)), true), 
            'type' => $type,
            'transaction_id' => isset($input['transaction_id']) && !empty($input['transaction_id']) ? $input['transaction_id'] : rand(1,1000000),
            'payment_gateaway' => isset($input['payment_gateaway']) && !empty($input['payment_gateaway']) ? $input['payment_gateaway'] : 'Noon',
            'invoice_id' => $invoiceObj->id,
        ];        

        try {
            dispatch(new NewClient($data))->onConnection('database');
            // dispatch(new NewClient($data))->onConnection('cjobs');
        } catch (Exception $e) {
            
        }
        return redirect()->to('/dashboard');
    }

    public function extraQuotas()
    {
        $userObj = User::authenticatedUser();
        $data['designElems']['mainData'] = [
            'title' => trans('main.extraQuotas'),
            'icon' => 'fas fa-star',
        ];
        $data['data'] = $userObj;
        $data['extraQuotas'] = ExtraQuota::dataList()['data'];
        $userQuotas = UserExtraQuota::getForUser($userObj->global_id);
        $data['userQuotas'] = reset($userQuotas[0]);
        $data['userQuotas2'] = array_unique($data['userQuotas']);
        // dd($data['userQuotas2']);
        return view('Tenancy.Profile.Views.extraQuotas')->with('data', (object) $data);
    }

    public function postExtraQuotas($extraQuota_id)
    {
        $input = \Request::all();
        $extraQuota_id = (int) $extraQuota_id;
        $userObj = User::getOne(USER_ID);
        $extraQuotaObj = ExtraQuota::getOne($extraQuota_id);
        if (!$extraQuotaObj) {
            return redirect('404');
        }
        $userExtraQuotaObj = UserExtraQuota::NotDeleted()->where('user_id', USER_ID)->where('extra_quota_id', $extraQuota_id)->first();
        if (!$userExtraQuotaObj) {
            $userExtraQuotaObj = new UserExtraQuota;
        }
        $userExtraQuotaObj->user_id = USER_ID;
        $userExtraQuotaObj->extra_quota_id = $extraQuota_id;
        $userExtraQuotaObj->tenant_id = \DB::connection('main')->table('tenant_users')->where('global_user_id', $userObj->global_id)->first()->tenant_id;
        $userExtraQuotaObj->status = 1;
        $userExtraQuotaObj->global_user_id = $mainUser->global_id;
        $userExtraQuotaObj->duration_type = 1;
        $userExtraQuotaObj->start_date = date('Y-m-d');
        $userExtraQuotaObj->end_date = date('Y-m-d', strtotime("+1 month", strtotime(date('Y-m-d'))));
        $userExtraQuotaObj->created_by = USER_ID;
        $userExtraQuotaObj->created_at = DATE_TIME;
        $userExtraQuotaObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function addons()
    {
        $userObj = User::authenticatedUser();
        $data['designElems']['mainData'] = [
            'title' => trans('main.addons'),
            'icon' => 'fas fa-star',
        ];
        $mainUser = User::first();
        $data['data'] = $userObj;
        $data['addons'] = Addons::dataList()['data'];
        $data['userAddons'] = $mainUser->addons != null ? unserialize($mainUser->addons) : [];
        $data['userAddons2'] = UserAddon::getAllDataForUser($mainUser->id);
        return view('Tenancy.Profile.Views.addons')->with('data', (object) $data);
    }

    public function postAddons($addon_id)
    {
        $input = \Request::all();
        $addon_id = (int) $addon_id;
        $userObj = User::getOne(USER_ID);
        $extraQuotaObj = Addons::getOne($addon_id);
        if (!$extraQuotaObj) {
            return redirect('404');
        }

        $tryFlag = 0;
        $userExtraQuotaObj = UserAddon::NotDeleted()->where('user_id', USER_ID)->where('addon_id', $addon_id)->first();
        if (!$userExtraQuotaObj) {
            $userExtraQuotaObj = new UserAddon;
            $start_date = date('Y-m-d');
            $tryFlag = 1;
        } else {
            $start_date = $userExtraQuotaObj->start_date;
        }

        $userExtraQuotaObj->user_id = USER_ID;
        $userExtraQuotaObj->addon_id = $addon_id;
        $userExtraQuotaObj->status = 1;
        $userExtraQuotaObj->tenant_id = \DB::connection('main')->table('tenant_users')->where('global_user_id', $userObj->global_id)->first()->tenant_id;
        $userExtraQuotaObj->global_user_id = $userObj->global_id;
        $userExtraQuotaObj->duration_type = isset($input['addons'][$addon_id][2]) ? 2 : 1;
        $userExtraQuotaObj->start_date = $start_date;
        $userExtraQuotaObj->end_date = date('Y-m-d', strtotime("+1 " . ($userExtraQuotaObj->duration_type == 1 ? 'month' : 'year'), strtotime($start_date)));
        $userExtraQuotaObj->created_by = USER_ID;
        $userExtraQuotaObj->created_at = DATE_TIME;
        $userExtraQuotaObj->save();

        if ($tryFlag) {
            $oldAddons = $userObj->addons != null ? unserialize($userObj->addons) : [];
            $oldAddons[] = $addon_id;
            $userObj->addons = serialize($oldAddons);
            $userObj->save();
        }

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function getDiffs($end_date, $oldDuration, $monthly_after_vat, $annual_after_vat)
    {
        $nextStartMonth = date('Y-m-d', strtotime('first day of +1 month', strtotime($end_date)));

        $endDate = strtotime($end_date);
        $datediff = strtotime($nextStartMonth) - $endDate;
        $daysLeft = (int) round($datediff / (60 * 60 * 24));

        $newPriceAfterVat = $monthly_after_vat;

        if ($oldDuration == 1) {
            $usedCost = ($monthly_after_vat / 30);
        } else if ($oldDuration == 2) {
            $usedCost = ($annual_after_vat / 365);
            $newPriceAfterVat = $annual_after_vat;
        }
        $membershipMustPaid = round($daysLeft * $usedCost, 2);
        return [
            'mustPaid' => $membershipMustPaid,
            'daysLeft' => $daysLeft,
            'nextStartMonth' => $nextStartMonth,
        ];
    }

    public function pushInvoice2()
    {
        $input = \Request::all();
        $data['data'] = json_decode($input['data']);
        $data['status'] = json_decode($input['status']);
        // dd($data);
        if ($data['status']->status == 1) {
            return $this->activate($data['data']->transaction_id, $data['data']->paymentGateaway);
        } else {
            $userObj = User::first();
            User::setSessions($userObj);
            \Session::flash('error', $data['status']->message);
            return redirect()->to('/paymentError')->withInput();
        }
    }

    
}
