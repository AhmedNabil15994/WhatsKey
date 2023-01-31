<?php namespace App\Http\Controllers;
use App\Models\Addons;
use App\Models\UserAddon;
use App\Models\CentralUser;
use App\Models\ExtraQuota;
use App\Models\UserExtraQuota;
use App\Models\CentralChannel;
use App\Models\Membership;
use App\Models\PaymentInfo;
use App\Models\User;
use App\Models\Variable;
use App\Models\UserChannels;
use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\BankTransfer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Jobs\NewClient;

class SubscriptionControllers extends Controller
{

    use \TraitsFunc;

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
        if(\Session::has('invoice_id')){
            $userCredits = 0;
        }
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

            $bankTransferObj = BankTransfer::NotDeleted()->where('user_id',ROOT_ID)->where('status',1)->first();
            $invoiceObj = Invoice::find($request->invoice_id);
            if(!$bankTransferObj){
                $bankTransferObj = new BankTransfer;
                $bankTransferObj->user_id = ROOT_ID;
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
            dispatch(new NewClient($data))->onConnection('syncdata');
        } catch (Exception $e) {
            
        }
        return redirect()->to('/dashboard');
    }

    public function addons()
    {   
        $userAddons = array_unique(array_merge(Session::get('membershipAddonsID'),Session::get('addonsID')));
        $data['addons'] = Addons::dataList(null,null,$userAddons)['data'];
        return view('Tenancy.Profile.Views.addons')->with('data', (object) $data);
    }

    public function postAddons()
    {
        $input = \Request::all();
        if(!isset($input['addonData']) || empty($input['addonData'])){
            Session::flash('error', trans('main.addonsValidate'));
            return back()->withInput();
        }
        $addonData = json_decode($input['addonData']);
        $items = [];
        foreach ($addonData as $key => $value) {
            $addon_id = (int) $value->addon_id;
            $addonObj = Addons::getOne($addon_id);
            if (!$addonObj) {
                return redirect('404');
            }
            $items[] = [
                'id' => $addon_id,
                'type' => 'addon',
                'duration_type' => $value->duration,
                'title' => Addons::getData($addonObj)->title,
                'start_date' => date('Y-m-d'),
                'end_date' => $value->duration == 1 ? date('Y-m-d',strtotime('+1 month',strtotime(date('Y-m-d')))) : date('Y-m-d',strtotime('+1 year',strtotime(date('Y-m-d')))),
                'price' => $value->duration == 1 ? $addonObj->monthly_after_vat : $addonObj->annual_after_vat,
                'quantity' => 1,
            ];
        }
            
        $userObj = User::getOne(ROOT_ID);
        $data['userCredits'] = 0;
        $data['paymentInfo'] = $userObj->paymentInfo;
        $data['items'] = $items;

        $subscriptionHelperData = [
            'user_id' => ROOT_ID,
            'tenant_id' => TENANT_ID,
            'global_id' => GLOBAL_ID,
            'cartData' => $data['items'],
            'type' => 'Change',
            'transaction_id' => null,
            'payment_gateaway' => null,
            'user_credits' => 0,
            'coupon_code' => null,
        ];

        Variable::where('var_key','inv_status')->firstOrCreate(['var_key'=>'inv_status','var_value'=>'Change']);
        $subscriptionHelperObj = new \SubscriptionHelper;
        $data['invoice_id'] = $subscriptionHelperObj->setInvoice($subscriptionHelperData);
        return view('Tenancy.Profile.Views.cart')->with('data', (object) $data);
    }

    public function extraQuotas()
    {
        $data['extraQuotas'] = ExtraQuota::dataList()['data'];
        return view('Tenancy.Profile.Views.extraQuotas')->with('data', (object) $data);
    }

    public function postExtraQuotas()
    {
        $input = \Request::all();
        if(!isset($input['addonData']) || empty($input['addonData'])){
            Session::flash('error', trans('main.addonsValidate'));
            return back()->withInput();
        }
        $addonData = json_decode($input['addonData']);
        $items = [];
        foreach ($addonData as $key => $value) {
            $addon_id = (int) $value->addon_id;
            $addonObj = ExtraQuota::getOne($addon_id);
            if (!$addonObj) {
                return redirect('404');
            }
            $items[] = [
                'id' => $addon_id,
                'type' => 'extra_quota',
                'duration_type' => $value->duration,
                'title' => ExtraQuota::getData($addonObj)->title,
                'start_date' => date('Y-m-d'),
                'end_date' => $value->duration == 1 ? date('Y-m-d',strtotime('+1 month',strtotime(date('Y-m-d')))) : date('Y-m-d',strtotime('+1 year',strtotime(date('Y-m-d')))),
                'price' => $value->duration == 1 ? $addonObj->monthly_after_vat : $addonObj->annual_after_vat,
                'quantity' => 1,
            ];
        }
            
        $userObj = User::authenticatedUser();
        $data['userCredits'] = 0;
        $data['paymentInfo'] = $userObj->paymentInfo;
        $data['items'] = $items;

        $subscriptionHelperData = [
            'user_id' => ROOT_ID,
            'tenant_id' => TENANT_ID,
            'global_id' => GLOBAL_ID,
            'cartData' => $data['items'],
            'type' => 'Change',
            'transaction_id' => null,
            'payment_gateaway' => null,
            'user_credits' => 0,
            'coupon_code' => null,
        ];

        Variable::where('var_key','inv_status')->firstOrCreate(['var_key'=>'inv_status','var_value'=>'Change']);
        $subscriptionHelperObj = new \SubscriptionHelper;
        $data['invoice_id'] = $subscriptionHelperObj->setInvoice($subscriptionHelperData);
        return view('Tenancy.Profile.Views.cart')->with('data', (object) $data);
    }

    public function updateAddonStatus($addon_id, $status)
    {
        $status = (int) $status;
        $addon_id = (int) $addon_id;
        if (!in_array($status, [1 , 3,  4 , 5])) {
            return redirect('404');
        }

        $userAddonObj = UserAddon::getOne($addon_id);
        if (!$userAddonObj || $userAddonObj->user_id != USER_ID) {
            return redirect('404');
        }

        $userObj = User::getOne(ROOT_ID);
        if ($status == 5) {
            $userAddonObj->deleted_by = USER_ID;
            $userAddonObj->deleted_at = DATE_TIME;
            $userAddonObj->save();
        } elseif ($status == 3) {
            $items = [
                [
                    'id' => $userAddonObj->addon_id,
                    'type' => 'addon',
                    'duration_type' => $userAddonObj->duration_type,
                    'title' => Addons::getData($userAddonObj->Addon)->title,
                    'start_date' => date('Y-m-d'),
                    'end_date' => $userAddonObj->duration_type == 1 ? date('Y-m-d',strtotime('+1 month',strtotime(date('Y-m-d')))) : date('Y-m-d',strtotime('+1 year',strtotime(date('Y-m-d')))),
                    'price' => $userAddonObj->duration_type == 1 ? $userAddonObj->Addon->monthly_after_vat : $userAddonObj->Addon->annual_after_vat,
                    'quantity' => 1,
                ]
            ];
            $data['userCredits'] = 0;
            $data['paymentInfo'] = $userObj->paymentInfo;
            $data['items'] = $items;

            $subscriptionHelperData = [
                'user_id' => ROOT_ID,
                'tenant_id' => TENANT_ID,
                'global_id' => GLOBAL_ID,
                'cartData' => $items,
                'type' => 'Renew',
                'transaction_id' => null,
                'payment_gateaway' => null,
                'user_credits' => 0,
                'coupon_code' => null,
            ];

            Variable::where('var_key','inv_status')->firstOrCreate(['var_key'=>'inv_status','var_value'=>'Renew']);
            $subscriptionHelperObj = new \SubscriptionHelper;
            $data['invoice_id'] = $subscriptionHelperObj->setInvoice($subscriptionHelperData);
            return view('Tenancy.Profile.Views.cart')->with('data', (object) $data);
        } elseif ($status == 4) {
            $userAddonObj->status = 0;
            $userAddonObj->save();
        } elseif ($status == 1) {
            $userAddonObj->status = 1;
            $userAddonObj->save();
        }

        User::setSessions(User::getOne(USER_ID));
        Session::flash('success', trans('main.editSuccess'));
        return redirect()->back();
    }

    public function updateExtraQuotaStatus($extra_quota_id, $status)
    {
        $status = (int) $status;
        $extra_quota_id = (int) $extra_quota_id;
        if (!in_array($status, [1 , 3,  4 , 5])) {
            return redirect('404');
        }

        $userExtraQuotaObj = UserExtraQuota::getOne($extra_quota_id);
        if (!$userExtraQuotaObj || $userExtraQuotaObj->user_id != ROOT_ID) {
            return redirect('404');
        }

        $userObj = User::getOne($userExtraQuotaObj->user_id);
        if ($status == 5) {
            $userExtraQuotaObj->deleted_by = USER_ID;
            $userExtraQuotaObj->deleted_at = DATE_TIME;
            $userExtraQuotaObj->save();
        } elseif ($status == 3) {
            $items = [
                [
                    'id' => $userExtraQuotaObj->extra_quota_id,
                    'type' => 'extra_quota',
                    'duration_type' => $userExtraQuotaObj->duration_type,
                    'title' => ExtraQuota::getData($userExtraQuotaObj->ExtraQuota)->title,
                    'start_date' => date('Y-m-d'),
                    'end_date' => $userExtraQuotaObj->duration_type == 1 ? date('Y-m-d',strtotime('+1 month',strtotime(date('Y-m-d')))) : date('Y-m-d',strtotime('+1 year',strtotime(date('Y-m-d')))),
                    'price' => $userExtraQuotaObj->duration_type == 1 ? $userExtraQuotaObj->ExtraQuota->monthly_after_vat : $userExtraQuotaObj->ExtraQuota->annual_after_vat,
                    'quantity' => 1,
                ]
            ];
            $data['userCredits'] = 0;
            $data['paymentInfo'] = $userObj->paymentInfo;
            $data['items'] = $items;

            $subscriptionHelperData = [
                'user_id' => ROOT_ID,
                'tenant_id' => TENANT_ID,
                'global_id' => GLOBAL_ID,
                'cartData' => $items,
                'type' => 'Renew',
                'transaction_id' => null,
                'payment_gateaway' => null,
                'user_credits' => 0,
                'coupon_code' => null,
            ];

            Variable::where('var_key','inv_status')->firstOrCreate(['var_key'=>'inv_status','var_value'=>'Renew']);
            $subscriptionHelperObj = new \SubscriptionHelper;
            $data['invoice_id'] = $subscriptionHelperObj->setInvoice($subscriptionHelperData);
            return view('Tenancy.Profile.Views.cart')->with('data', (object) $data);
        }elseif ($status == 4) {
            $userExtraQuotaObj->status = 0;
            $userExtraQuotaObj->save();
        } elseif ($status == 1) {
            $userExtraQuotaObj->status = 1;
            $userExtraQuotaObj->save();
        }

        User::setSessions(User::getOne(USER_ID));
        Session::flash('success', trans('main.editSuccess'));
        return redirect()->back();
    }

    public function transferPayment(){
        $channelObj = Session::get('channel') != null ? CentralChannel::getData(CentralChannel::getOne(Session::get('channel'))) : null;
        $mainUser = User::first();
        if(!$channelObj || !$mainUser){
            return redirect('404');
        }

        $nextStartMonth = date('Y-m-d', strtotime('first day of +1 month', strtotime($channelObj->end_date)));
        $endDate = strtotime($channelObj->end_date);
        $datediff = strtotime($nextStartMonth) - $endDate;
        $daysLeft = (int) round($datediff / (60 * 60 * 24));

        $membershipObj = Membership::find($mainUser->membership_id);

        if($mainUser->duration_type == 2){
            $price = $membershipObj->annual_after_vat;
            $usedCost = ($price / 365);
        }else{
            $price = $membershipObj->monthly_after_vat;
            $usedCost = ($price / 30);
        }

        $membershipMustPaid = round($daysLeft * $usedCost, 2);

        $data['userCredits'] = round( $price - $membershipMustPaid ,2);
        $data['paymentInfo'] = $mainUser->paymentInfo;

        $item = [
            'id' => $membershipObj->id,
            'type' => 'membership',
            'duration_type' => $mainUser->duration_type,
            'title' => Membership::getData($membershipObj)->title,
            'start_date' => $channelObj->start_date,
            'end_date' => $nextStartMonth,
            'price' => $mainUser->duration_type == 1 ? $membershipObj->monthly_after_vat : $membershipObj->annual_after_vat,
            'quantity' => 1,
        ];

        $newItem = $item;
        $newItem['start_date'] = $channelObj->end_date;
        $data['items'][] = $newItem;

        $subscriptionHelperData = [
            'user_id' => ROOT_ID,
            'tenant_id' => TENANT_ID,
            'global_id' => GLOBAL_ID,
            'cartData' => [$item],
            'type' => 'Change',
            'transaction_id' => null,
            'payment_gateaway' => null,
            'user_credits' => $data['userCredits'],
            'coupon_code' => null,
            'due_date' => $channelObj->end_date,
        ];

        Variable::where('var_key','inv_status')->firstOrCreate(['var_key'=>'inv_status','var_value'=>'Change']);
        $subscriptionHelperObj = new \SubscriptionHelper;
        $data['invoice_id'] = $subscriptionHelperObj->setInvoice($subscriptionHelperData);
        return view('Tenancy.Profile.Views.cart')->with('data', (object) $data);
    }

    public function disableAddonAutoInvoice(){
        $varObj = Variable::where('var_key','disableAddonAutoInvoice')->first();
        if(!$varObj){
            Variable::create(['var_key'=>'disableAddonAutoInvoice','var_value'=>1]);
        }else{
            $varObj->delete();
        }
        Session::flash('success', trans('main.editSuccess'));
        return redirect()->back();
    }

    public function disableExtraQuotaAutoInvoice(){
        $varObj = Variable::where('var_key','disableExtraQuotaAutoInvoice')->first();
        if(!$varObj){
            Variable::create(['var_key'=>'disableExtraQuotaAutoInvoice','var_value'=>1]);
        }else{
            $varObj->delete();
        }
        Session::flash('success', trans('main.editSuccess'));
        return redirect()->back();
    }

    public function paymentError()
    {
        return view('Tenancy.Dashboard.Views.V5.paymentError');
    }
    
    public function pushInvoice()
    {
        $input = \Request::all();
        $data['data'] = json_decode($input['data']);
        $data['status'] = json_decode($input['status']);
        if ($data['status']->status == 1) {
            //paytabs
            return $this->activate($data['data']->tran_ref, $data['data']->paymentGateaway);
            //noon
            //return $this->activate($data['data']->transaction_id, $data['data']->paymentGateaway);
        } else {
            $userObj = User::first();
            User::setSessions($userObj);
            \Session::flash('error', $data['status']->message);
            return redirect()->to('/paymentError')->withInput();
        }
    }

    
    public function completeOrder()
    {
        $input = \Request::all();
        if (!IS_ADMIN) {
            return redirect()->to('/dashboard');
        }

        $userObj = User::first();
        $centralUser = CentralUser::getOne($userObj->id);

        if (isset($input['name']) && !empty($input['name'])) {

            $names = explode(' ', $input['name']);
            // if(count($names) < 2){
            //     Session::flash('error', trans('main.name2Validate'));
            //     return redirect()->back()->withInput();
            // }

            $userObj->name = $input['name'];
            $userObj->save();

            $centralUser->name = $input['name'];
            $centralUser->save();
        }

        if (isset($input['company_name']) && !empty($input['company_name'])) {
            $userObj->company = $input['company_name'];
            $userObj->save();

            $centralUser->company = $input['company_name'];
            $centralUser->save();
        }

        $cartData = $input['data'];

        $this->calcData($input['totals'], $cartData, $userObj);

        $url = \URL::to('/pushInvoice');
        // if(isset($input['dataType']) && $input['dataType'] > 1){
        //     $url = \URL::to('/pushInvoice2');
        //     if($input['dataType'] == 2){
        //         $nextStartMonth = date('Y-m-d',strtotime('first day of +1 month',strtotime(date('Y-m-d'))));

        //         Variable::where('var_key','endDate')->firstOrCreate([
        //             'var_key' => 'endDate',
        //             'var_value' => $nextStartMonth,
        //         ]);
        //     }else{
        //         $nextStartMonth = date('Y-m-d',strtotime('+1 month',strtotime(date('Y-m-d'))));

        //         Variable::where('var_key','endDate')->firstOrCreate([
        //             'var_key' => 'endDate',
        //             'var_value' => $nextStartMonth,
        //         ]);
        //     }
        //     Variable::where('var_key','start_date')->firstOrCreate([
        //         'var_key' => 'start_date',
        //         'var_value' => date('Y-m-d'),
        //     ]);
        // }

        if ($input['payType'] == 2) { // Noon Integration
            // paytabs - noon
            $urlSecondSegment = '/paytabs';
            $noonData = [
                'returnURL' => $url,
                'cart_id' => 'whatsloop-' . rand(1, 100000),
                'cart_amount' => json_decode($input['totals'])[3],
                'cart_description' => 'New Membership',
                'paypage_lang' => LANGUAGE_PREF,
                'description' => 'WhatsLoop Membership For User ' . $userObj->id,
            ];

            $paymentObj = new \PaymentHelper();
            $resultData = $paymentObj->initNoon($noonData);

            $result = $paymentObj->hostedPayment($resultData['dataArr'], $urlSecondSegment, $resultData['extraHeaders']);
            $result = json_decode($result);
            // dd($result);
            // paytabs
            if (($result->data) && $result->data->redirect_url) {
                return redirect()->away($result->data->redirect_url);
            }
            // noon
            // if(($result->data) && $result->data->result->redirect_url){
            //     return redirect()->away($result->data->result->redirect_url);
            // }
        }
    }

    public function getDiffs($end_date, $oldDuration, $monthly_after_vat, $annual_after_vat)
    {
        


        $newPriceAfterVat = $monthly_after_vat;

        if ($oldDuration == 1) {
        } else if ($oldDuration == 2) {
            
        }
        return [
            'mustPaid' => $membershipMustPaid,
            'daysLeft' => $daysLeft,
            'nextStartMonth' => $nextStartMonth,
        ];
    }
    
}
