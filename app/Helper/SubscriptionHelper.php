<?php
use App\Models\CentralVariable;
use App\Models\User;
use App\Models\CentralUser;
use App\Models\Variable;
use App\Models\Membership;
use App\Models\Addons;
use App\Models\ExtraQuota;
use App\Models\Invoice;
use App\Models\UserAddon;
use App\Models\UserExtraQuota;
use App\Models\UserChannels;
use App\Models\CentralChannel;
use App\Models\Tenant;
use App\Models\ModTemplate;
use App\Models\Template;
use App\Models\NotificationTemplate;

use App\Models\PaymentInfo;
use App\Models\BotPlus;


use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;
use Barryvdh\Snappy\Facades\SnappyPdf;

class SubscriptionHelper {

    public function initSubscription($data){
        $centralUser = CentralUser::find($data['user_id']);
        $oldMembershipID = $centralUser->membership_id;
        $tenant_id = $data['tenant_id'];
        
        if($data['type'] == 'New'){
            $this->newClient($data);
        }else if($data['type'] == 'Change' || $data['type'] == 'Renew'){
            $this->changeSubscription($data);
        }

        tenancy()->initialize($data['tenant_id']);
        Variable::whereIn('var_key',['userCredits','cartObj','inv_status'])->delete();
        tenancy()->end($data['tenant_id']);

        return 1;
    }

    public function changeSubscription($data){
        $items = [];
        $addons = [];
        $addonData = [];
        $extraQuotaData = [];

        $centralUser = CentralUser::find($data['user_id']);
        $invoiceData = $data['cartData'];        

        $membership_id = null;
        $duration_type = 1;
        $main = 0;
        $channelStartDate = null;
        $channelEndDate = null;

        foreach($invoiceData as $key => $one){
            if($one['type'] == 'membership'){
                $dataObj = Membership::getOne($one['id']);
                $membership_id = $dataObj->id;
                $main = 1;
                $duration_type = $one['duration_type'];
                $channelStartDate = $one['start_date'];
                $channelEndDate = $one['end_date'];
                $price = $dataObj->monthly_price;
                $price_after_vat = $dataObj->monthly_after_vat;
                if($duration_type == 2){
                    $price = $dataObj->annual_price ;
                    $price_after_vat = $dataObj->annual_after_vat;
                }
            }else if($one['type'] == 'addon'){
                $dataObj = Addons::getOne($one['id']);
                $addons[] = $one['id'];
                $addonData[] = [
                    'tenant_id' => $data['tenant_id'],
                    'global_user_id' => $data['global_id'],
                    'user_id' => $data['user_id'],
                    'addon_id' => $one['id'],
                    'status' => 1,
                    'duration_type' => $one['duration_type'],
                    'start_date' => $one['start_date'],
                    'end_date' => $one['end_date'], 
                ];
            }else if($one['type'] == 'extra_quota'){
                $dataObj = ExtraQuota::getData(ExtraQuota::getOne($one['id']));
                for ($i = 0; $i < $one['quantity'] ; $i++) {
                    $extraQuotaData[] = [
                        'tenant_id' => $data['tenant_id'],
                        'global_user_id' => $data['global_id'],
                        'user_id' => $data['user_id'],
                        'extra_quota_id' => $one['id'],
                        'duration_type' => $one['duration_type'],
                        'status' => 1,
                        'start_date' => $one['start_date'],
                        'end_date' => $one['end_date'], 
                    ];
                }
            }
            $items[] = $one;
        }

        tenancy()->initialize($data['tenant_id']);
        $userObj = User::first();
        $mainUserChannel = UserChannels::first();
        tenancy()->end($data['tenant_id']);

        if(!empty($addon)){
            $oldData = unserialize($centralUser->addons) != null ? unserialize($centralUser->addons) : [];
            $newData = array_merge($oldData,$addon);
            $newData = array_unique($newData);

            tenancy()->initialize($data['tenant_id']);
            User::where('id',$centralUser->id)->update([
                'addons' =>  serialize($newData),
            ]);
            tenancy()->end($data['tenant_id']);
            $centralUser->update([
                'addons' =>  serialize($newData),
            ]);
        }


        foreach($addonData as $oneAddonData){
            $userAddonObj = UserAddon::where('user_id',$oneAddonData['user_id'])->where('addon_id',$oneAddonData['addon_id'])->first();
            if($userAddonObj){
                $userAddonObj->update($oneAddonData);
            }else{
                UserAddon::insert($oneAddonData);
            }
        }

        foreach($extraQuotaData as $oneItemData){
            $userExtraQuotaObj = UserExtraQuota::where('user_id',$oneItemData['user_id'])->where('extra_quota_id',$oneItemData['extra_quota_id'])->first();
            if($userExtraQuotaObj){
                $userExtraQuotaObj->update($oneItemData);
            }else{
                UserExtraQuota::insert($oneItemData);                
            }
        }

        $invoiceObj = Invoice::find($data['invoice_id']);
        $invoiceObj->main = $main;
        $invoiceObj->status = 1;
        $invoiceObj->paid_date = DATE_TIME;
        $invoiceObj->items = serialize($items);
        $invoiceObj->transaction_id = $data['transaction_id'];
        $invoiceObj->payment_gateaway = $data['payment_gateaway'];  
        $invoiceObj->payment_method = $data['payment_gateaway'] == 'Noon' ? 1 : 2;
        $invoiceObj->save();

        // Check If there is unpaid invoice then delete it 
        if($main){
            Invoice::where('client_id',$data['user_id'])->where('main',1)->where('status','!=',1)->delete();
        }

        if($mainUserChannel){
            $centralChannelObj = CentralChannel::where('instanceId',$mainUserChannel->id)->first();
            $instanceId = $centralChannelObj->instanceId;
             
            if($main){
                tenancy()->initialize($data['tenant_id']);
                $mainUserChannel->start_date = $channelStartDate;
                $mainUserChannel->end_date = $channelEndDate;
                $mainUserChannel->save();
                tenancy()->end($data['tenant_id']);
                
                $centralChannelObj->start_date = $channelStartDate;
                $centralChannelObj->end_date = $channelEndDate;
                $centralChannelObj->save();
            }
        }     

        tenancy()->initialize($data['tenant_id']);
        if($membership_id != null){
            $userObj->update([
                'membership_id' => $membership_id,
                'duration_type' => $duration_type,
            ]);
        }
        tenancy()->end($data['tenant_id']);
        
        if($membership_id != null){
            $centralUser->update([
                'membership_id' => $membership_id,
                'duration_type' => $duration_type,
            ]);
        }

        if($mainUserChannel){
            $this->sendNotifications($userObj,$invoiceObj,$data['type'],$data['tenant_id'],$instanceId);
            $this->transferDays($instanceId);
        }
        $this->sendInvoice($invoiceObj,$userObj,$data['tenant_id']);
        return 1;
    }

    public function newClient($data){
        $items = [];
        $start_date = date('Y-m-d');
        $centralUser = CentralUser::find($data['user_id']);
        $invoiceData = $data['cartData'];
        $membership_id = null;
        $duration_type = 1;
        
        foreach($invoiceData as $key => $one){
            $end_date =  $one['duration_type'] == 1 ? date('Y-m-d',strtotime('+1 month',strtotime($start_date))) : date('Y-m-d',strtotime('+1 year',strtotime($start_date)));
            if($one['type'] == 'membership'){
                $dataObj = Membership::getOne($one['id']);
                $membership_id = $dataObj->id;
                $duration_type = $one['duration_type'];
                $price = $dataObj->monthly_price;
                $price_after_vat = $dataObj->monthly_after_vat;
                if($duration_type == 2){
                    $price = $dataObj->annual_price ;
                    $price_after_vat = $dataObj->annual_after_vat;
                }
            }
            
            $items[] = $one;
        }

        tenancy()->initialize($data['tenant_id']);
        $userObj = User::first();
        tenancy()->end($data['tenant_id']);

        $invoice_id = $this->setInvoice($data);

        $invoiceObj = Invoice::find($invoice_id);
        $invoiceObj->main = 1;
        $invoiceObj->status = 1;
        $invoiceObj->paid_date = DATE_TIME;
        $invoiceObj->items = serialize($items);
        $invoiceObj->transaction_id = $data['transaction_id'];
        $invoiceObj->payment_gateaway = $data['payment_gateaway'];  
        $invoiceObj->payment_method = $data['payment_gateaway'] == 'Noon' ? 1 : 2;
        $invoiceObj->save();

        $name = CentralChannel::orderBy('id','DESC')->first()->instanceId;
        $instanceId = $name+1;
        $officialObj = new \OfficialHelper(null,null,'create');
        $updateResult = $officialObj->createChannel([
            'wlChannelName' => $instanceId,
        ]);
        $result = $updateResult->json();

        $channel = [
            'token' => $result['data']['instance']['token'],
            'name' => 'Channel #'.$result['data']['instance']['id'],
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        $centralChannel = new CentralChannel;
        $centralChannel->token = $channel['token'];
        $centralChannel->start_date = $channel['start_date'];
        $centralChannel->end_date = $channel['end_date'];
        $centralChannel->name = $channel['name'];
        $centralChannel->tenant_id = $data['tenant_id'];
        $centralChannel->global_user_id = $data['global_id'];
        $centralChannel->instanceId = $instanceId;
        $centralChannel->instanceToken = $channel['token'];
        $centralChannel->save();

        $channel['name'] = 'Channel #'.$result['data']['instance']['id'];
        $channel['id'] = $result['data']['instance']['id'];

        tenancy()->initialize($data['tenant_id']);
        $mainUserChannel = UserChannels::create($channel);
        $userObj->update([
            'channels' => serialize([$channel['id']]),
        ]);
        if($membership_id != null){
            $userObj->update([
                'membership_id' => $membership_id,
                'duration_type' => $duration_type,
            ]);
        }
        tenancy()->end($data['tenant_id']);
        
        $centralUser->update([
            'channels' => serialize([$channel['id']]),
        ]);

        if($membership_id != null){
            $centralUser->update([
                'membership_id' => $membership_id,
                'duration_type' => $duration_type,
            ]);
        }
       
        $this->sendNotifications($userObj,$invoiceObj,'New',$data['tenant_id'],$instanceId);
        $this->sendInvoice($invoiceObj,$userObj,$data['tenant_id']);
        $this->transferDays($instanceId);
        return 1;
    }

    public function sendNotifications($userObj,$invoiceObj,$type){
        $myDomain = config('app.MY_DOMAIN');
        $loginUrl = str_replace('myDomain', $userObj->domain, $myDomain).'/login';
        $notificationTemplateObj = NotificationTemplate::getOne(2,'paymentSuccess');
        $invoiceObj = Invoice::getData($invoiceObj);

        $allData = [
            'name' => $userObj->name,
            'subject' => $notificationTemplateObj->title_ar,
            'content' => $notificationTemplateObj->content_ar,
            'email' => $userObj->email,
            'template' => 'tenant.emailUsers.default',
            'url' => $loginUrl,
            'extras' => [
                'invoiceObj' => $invoiceObj,
                'company' => $userObj->company,
                'url' => $loginUrl,
            ],
        ];
        \MailHelper::prepareEmail($allData);

        $salesData = $allData;
        $salesData['email'] = 'sales@whatskey.net';
        \MailHelper::prepareEmail($salesData);

        $whatsappTemplateObj = NotificationTemplate::getOne(1,'paymentSuccess');
        $phoneData = $allData;
        $phoneData['phone'] = $userObj->phone;
        $phoneData['subject'] = $whatsappTemplateObj->title_ar;
        $phoneData['content'] = $whatsappTemplateObj->content_ar;
        \MailHelper::prepareEmail($phoneData,1);

        if($type == 'New'){
            // Second Email
            $notificationTemplateObj = NotificationTemplate::getOne(2,'activateAccount');
            $allData['subject'] = $notificationTemplateObj->title_ar;
            $allData['content'] = $notificationTemplateObj->content_ar;
            \MailHelper::prepareEmail($allData);

            $whatsappTemplateObj = NotificationTemplate::getOne(1,'activateAccount');
            $phoneData['subject'] = $whatsappTemplateObj->title_ar;
            $phoneData['content'] = $whatsappTemplateObj->content_ar;
            \MailHelper::prepareEmail($phoneData,1);
        }

        if($type == 'Change'){
            $notificationTemplateObj = NotificationTemplate::getOne(2,'upgradeSuccess');
            $allData['subject'] = $notificationTemplateObj->title_ar;
            $allData['content'] = $notificationTemplateObj->content_ar;
            \MailHelper::prepareEmail($allData);

            $whatsappTemplateObj = NotificationTemplate::getOne(1,'upgradeSuccess');
            $phoneData['subject'] = $whatsappTemplateObj->title_ar;
            $phoneData['content'] = $whatsappTemplateObj->content_ar;
            \MailHelper::prepareEmail($phoneData,1);
        }

        if($type == 'Renew'){
            $notificationTemplateObj = NotificationTemplate::getOne(2,'renewAccount');
            $allData['subject'] = $notificationTemplateObj->title_ar;
            $allData['content'] = $notificationTemplateObj->content_ar;
            \MailHelper::prepareEmail($allData);

            $whatsappTemplateObj = NotificationTemplate::getOne(1,'renewAccount');
            $phoneData['subject'] = $whatsappTemplateObj->title_ar;
            $phoneData['content'] = $whatsappTemplateObj->content_ar;
            \MailHelper::prepareEmail($phoneData,1);
        }
        return 1;
    }

    public function setInvoice($data){
        $items = [];
        $addons = [];
        $addonData = [];
        $extraQuotaData = [];
        $total = 0;
        $main = 0;
        $invoiceData = $data['cartData'];

        foreach($invoiceData as $key => $one){
            if($one['type'] == 'membership'){
                $dataObj = Membership::getOne($one['id']);
                $main = 1;
            }else if($one['type'] == 'addon'){
                $dataObj = Addons::getOne($one['id']);
                $addons[] = $one['id'];
                $addonData[] = [
                    'tenant_id' => $data['tenant_id'],
                    'global_user_id' => $data['global_id'],
                    'user_id' => $data['user_id'],
                    'addon_id' => $one['id'],
                    'status' => 1,
                    'duration_type' => $one['duration_type'],
                    'start_date' => $one['start_date'],
                    'end_date' => $one['end_date'], 
                ];        
            }else if($one['type'] == 'extra_quota'){
                $dataObj = ExtraQuota::getData(ExtraQuota::getOne($one['id']));
                for ($i = 0; $i < $one['quantity'] ; $i++) {
                    $extraQuotaData[] = [
                        'tenant_id' => $data['tenant_id'],
                        'global_user_id' => $data['global_id'],
                        'user_id' => $data['user_id'],
                        'extra_quota_id' => $one['id'],
                        'duration_type' => $one['duration_type'],
                        'status' => 1,
                        'start_date' => $one['start_date'],
                        'end_date' => $one['end_date'], 
                    ];
                }
            }
            $price = $one['price'];
            $total+= $price * $one['quantity'];
            $items[] = $one;
        }
        
        $invoiceObj = Invoice::where('client_id',$data['user_id'])->where('status',0)->first();
        if(!$invoiceObj){
            $invoiceObj = new Invoice;
        }
        $invoiceObj->client_id = $data['user_id'];
        $invoiceObj->due_date = isset($data['due_date']) ? $data['due_date'] : $items[0]['start_date'];
        $invoiceObj->main = $main;
        $invoiceObj->items = serialize($items);

        $invoiceObj->total = $total - (isset($data['user_credits']) ? $data['user_credits'] : 0);
        $invoiceObj->status = 0;
        $invoiceObj->transaction_id = isset($data['transaction_id']) ? $data['transaction_id'] : null;
        $invoiceObj->payment_gateaway = isset($data['payment_gateaway']) ? $data['payment_gateaway'] : null;  
        $invoiceObj->user_credits = isset($data['user_credits']) ? $data['user_credits'] : 0;
        $invoiceObj->coupon_code = isset($data['coupon_code']) ? $data['coupon_code'] : null;
        $invoiceObj->paid_date = null;
        $invoiceObj->discount_type = null;
        $invoiceObj->discount_value = null;
        $invoiceObj->sort = Invoice::newSortIndex();
        $invoiceObj->created_at = date('Y-m-d H:i:s');
        $invoiceObj->created_by = $data['user_id'];
        $invoiceObj->save();
        return $invoiceObj->id;
    }

    public function sendInvoice($invoiceObj,$userObj,$tenant_id){
        $data['invoice'] = Invoice::getData($invoiceObj);
        $data['companyAddress'] = (object) [
            'servers' => CentralVariable::getVar('servers'),
            'address' => CentralVariable::getVar('address'),
            'region' => CentralVariable::getVar('region'),
            'city' => CentralVariable::getVar('city'),
            'postal_code' => CentralVariable::getVar('postal_code'),
            'country' => CentralVariable::getVar('country'),
            'tax_id' => CentralVariable::getVar('tax_id'),
        ];

        $baseURL = config('app.MY_DOMAIN');
        $baseURL = str_replace('myDomain', $userObj->domain, $baseURL);
        $data['fontFile'] = $baseURL. '/assets/tenant/css/font.css';
        $data['logoFile'] = $baseURL. '/assets/images/whiteLogo.png';
        $data['backFile'] = $baseURL. '/assets/tenant/media/bg/bg-invoice-5.jpg';            

        $tax = \Helper::calcTax($data['invoice']->total);
        tenancy()->initialize($tenant_id);
        $paymentObj = PaymentInfo::NotDeleted()->where('user_id',$invoiceObj->client_id)->first();
        if($paymentObj){
            $data['paymentObj'] = PaymentInfo::getData($paymentObj);
        }
        tenancy()->end($tenant_id);

        if(!defined('LANGUAGE_PREF')){
            define('LANGUAGE_PREF','ar');
        }
        if(!defined('DIRECTION')){
            define('DIRECTION','rtl');
        }
        $data['qrImage'] = GenerateQrCode::fromArray([
            new Seller($data['companyAddress']->servers),
            new TaxNumber($data['companyAddress']->tax_id),
            new InvoiceDate(date('Y-m-d\TH:i:s\Z',strtotime($data['invoice']->due_date))),
            new InvoiceTotalAmount($data['invoice']->total),
            new InvoiceTaxAmount($tax)
        ])->render();

        $fileName = 'invoice'.($invoiceObj->id+10000).'.pdf';
        if(!file_exists(public_path().'/uploads/invoices/'.$invoiceObj->id.'/'.$fileName)){
            $pdf = SnappyPdf::loadView('Tenancy.Invoice.Views.invoicePDF',['data'=> (object)$data])
                ->setPaper('a4', 'portrait')
                ->setOption('margin-bottom', '0mm')
                ->setOption('margin-top', '0mm')
                ->setOption('margin-right', '0mm')
                ->setOption('margin-left', '0mm');
            $pdf->save(public_path().'/uploads/invoices/'.$invoiceObj->id.'/'.$fileName);
        }
        
        $baseURL.= '/uploads/invoices/'.$invoiceObj->id.'/'.$fileName;
        $centralChannelObj = CentralChannel::NotDeleted()->orderBy('id','ASC')->first();
        $mainWhatsLoopObj = new OfficialHelper($centralChannelObj->id,$centralChannelObj->token);
        $result = $mainWhatsLoopObj->sendFile([
            'phone' => str_replace('+', '', $userObj->phone),
            'url' => $baseURL,
        ]);
        return 1;
    }

    public function transferDays($receiver){
        $centralChannelObj = CentralChannel::NotDeleted()->orderBy('id','ASC')->first();
        $mainWhatsLoopObj = new OfficialHelper($centralChannelObj->id,$centralChannelObj->token);
        $result = $mainWhatsLoopObj->transferDays([
            'receiver' => $receiver,
            'sender' => $centralChannelObj->instanceId,
            'days' => 2,
        ]);

        return 1;
    }

}

