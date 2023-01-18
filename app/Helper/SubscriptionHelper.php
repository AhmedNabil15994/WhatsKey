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
        }else if($data['type'] == 'Change'){
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
            $this->sendNotifications($userObj,$invoiceObj,'Change',$data['tenant_id'],$instanceId);
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
       
        // $this->setTemplates($addons,$tenant,$instanceId);
        $this->sendNotifications($userObj,$invoiceObj,'New',$data['tenant_id'],$instanceId);
        $this->sendInvoice($invoiceObj,$userObj,$data['tenant_id']);
        $this->transferDays($instanceId);
        return 1;
    }

    public function sendNotifications($userObj,$invoiceObj,$type){
        $notificationTemplateObj = NotificationTemplate::getOne(2,'paymentSuccess');
        $myDomain = config('app.MY_DOMAIN');
        $loginUrl = str_replace('myDomain', $userObj->domain, $myDomain).'/login';
        $allData = [
            'name' => $userObj->name,
            'subject' => $notificationTemplateObj->title_ar,
            'content' => $notificationTemplateObj->content_ar,
            'email' => $userObj->email,
            'template' => 'tenant.emailUsers.default',
            'url' => $loginUrl,
            'extras' => [
                'invoiceObj' => Invoice::getData($invoiceObj),
                'company' => $userObj->company,
                'url' => $loginUrl,
            ],
        ];
        \MailHelper::prepareEmail($allData);

        $salesData = $allData;
        $salesData['email'] = 'sales@whatskey.net';
        \MailHelper::prepareEmail($salesData);

        $notificationTemplateObj = NotificationTemplate::getOne(1,'paymentSuccess');
        $phoneData = $allData;
        $phoneData['phone'] = $userObj->phone;
        \MailHelper::prepareEmail($phoneData,1);

        if($type == 'New'){
            // Second Email
            $notificationTemplateObj = NotificationTemplate::getOne(2,'activateAccount');
            $allData = [
                'name' => $userObj->name,
                'subject' => $notificationTemplateObj->title_ar,
                'content' => $notificationTemplateObj->content_ar,
                'email' => $userObj->email,
                'template' => 'tenant.emailUsers.default',
                'url' => $loginUrl,
                'extras' => [
                    'invoiceObj' => Invoice::getData($invoiceObj),
                    'company' => $userObj->company,
                    'url' => $loginUrl,
                ],
            ];
            \MailHelper::prepareEmail($allData);

            $notificationTemplateObj = NotificationTemplate::getOne(1,'activateAccount');
            $phoneData = $allData;
            $phoneData['phone'] = $userObj->phone;
            \MailHelper::prepareEmail($phoneData,1);
        }

        if($type == 'Change'){
            $notificationTemplateObj = NotificationTemplate::getOne(2,'upgradeSuccess');
            $allData = [
                'name' => $userObj->name,
                'subject' => $notificationTemplateObj->title_ar,
                'content' => $notificationTemplateObj->content_ar,
                'email' => $userObj->email,
                'template' => 'tenant.emailUsers.default',
                'url' => $loginUrl,
                'extras' => [
                    'invoiceObj' => Invoice::getData($invoiceObj),
                    'company' => $userObj->company,
                    'url' => $loginUrl,
                ],
            ];
            \MailHelper::prepareEmail($allData);

            $notificationTemplateObj = NotificationTemplate::getOne(1,'upgradeSuccess');
            $phoneData = $allData;
            $phoneData['phone'] = $userObj->phone;
            \MailHelper::prepareEmail($phoneData,1);
        }

        // if($type == 'Suspended'){
        //     $notificationTemplateObj = NotificationTemplate::getOne(2,'renewAccount');
        //     $allData = [
        //         'name' => $userObj->name,
        //         'subject' => $notificationTemplateObj->title_ar,
        //         'content' => $notificationTemplateObj->content_ar,
        //         'email' => $userObj->email,
        //         'template' => 'tenant.emailUsers.default',
        //         'url' => $loginUrl,
        //         'extras' => [
        //             'invoiceObj' => Invoice::getData(Invoice::find($invoiceObj->id)),
        //             'company' => $userObj->company,
        //             'url' => $loginUrl,
        //         ],
        //     ];
        //     \MailHelper::prepareEmail($allData);

        //     $notificationTemplateObj = NotificationTemplate::getOne(1,'renewAccount');
        //     $phoneData = $allData;
        //     $phoneData['phone'] = $userObj->phone;
        //     \MailHelper::prepareEmail($phoneData,1);
        // }
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
            // 'title' => ($one[1] != 'extra_quota' ? $dataObj->title_ar : $dataObj->extra_count . ' '.$dataObj->extraTypeText . ' ' . ($dataObj->extra_type == 1 ? trans('main.msgPerDay') : '') ),
            // 'title' => $dataObj->title,
            $total+= $price * $one['quantity'];
            $items[] = $one;
        }
        
        $invoiceObj = Invoice::where('client_id',$data['user_id'])->where('status',0)->first();
        if(!$invoiceObj){
            $invoiceObj = new Invoice;
        }
        $invoiceObj->client_id = $data['user_id'];
        $invoiceObj->due_date = $items[0]['start_date'];
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

    // public function setTemplates($addon,$tenant,$instanceId){
    //     if(!empty($addon) && in_array(9,$addon)){
    //         tenancy()->initialize($tenant);
    //         Template::insert([
    //             [
    //                 'channel' => $instanceId,
    //                 'name_ar' => 'whatsAppOrders',
    //                 'name_en' => 'whatsAppOrders',
    //                 'description_ar' => 'يااهلا بـ {CUSTOMERNAME} 😍

    //                                     طلبك رقم ( {ORDERID} ) جاهز الان للشراء 😎.

    //                                     اذا ما عليك امر تتوجه الي صفحة مراجعة طلبك 😊 من خلال الرابط التالي :

    //                                     ( {ORDERURL} )

    //                                     مع تحيات فريق عمل واتس لوب ❤️',
    //                 'description_en' => 'يااهلا بـ {CUSTOMERNAME} 😍

    //                                     طلبك رقم ( {ORDERID} ) جاهز الان للشراء 😎.

    //                                     اذا ما عليك امر تتوجه الي صفحة مراجعة طلبك 😊 من خلال الرابط التالي :

    //                                     ( {ORDERURL} )

    //                                     مع تحيات فريق عمل واتس لوب ❤️',
    //                 'status' => 1,
    //             ],
    //             [
    //                 'channel' => $instanceId,
    //                 'name_ar' => 'whatsAppInvoices',
    //                 'name_en' => 'whatsAppInvoices',
    //                 'description_ar' => 'يااهلا بـ {CUSTOMERNAME} 😍

    //                                     تم تأكيد شراء طلبك رقم ( {ORDERID} )  😎.

    //                                     اذا ما عليك امر تتوجه الي طباعة فاتورة طلبك 😊 من خلال الرابط التالي :

    //                                     ( {INVOICEURL} )

    //                                     مع تحيات فريق عمل واتس لوب ❤️',
    //                 'description_en' => 'يااهلا بـ {CUSTOMERNAME} 😍

    //                                     تم تأكيد شراء طلبك رقم ( {ORDERID} )  😎.

    //                                     اذا ما عليك امر تتوجه الي طباعة فاتورة طلبك 😊 من خلال الرابط التالي :

    //                                     ( {INVOICEURL} )

    //                                     مع تحيات فريق عمل واتس لوب ❤️',
    //                 'status' => 1,
    //             ],

    //         ]);

    //         tenancy()->end($tenant);
    //     }

    //     if(!empty($addon) && in_array(5,$addon)){
    //         tenancy()->initialize($tenant);
    //         $modCount = ModTemplate::where('mod_id',1)->count();
    //         if($modCount == 0){
    //             ModTemplate::insert([
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 1,
    //                     'status' => 1,
    //                     'statusText' => 'ترحيب بالعميل',
    //                     'content_ar' => 'يا اهلا بـ {CUSTOMERNAME} 😍
                                        
    //                                     اهلا وسهلا بك نورتنا وشرفتنا في متجرنا 🤩
                                        
    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                     'content_en' => 'يا اهلا بـ {CUSTOMERNAME} 😍
                                        
    //                                     اهلا وسهلا بك نورتنا وشرفتنا في متجرنا 🤩

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 1,
    //                     'status' => 1,
    //                     'statusText' => 'بإنتظار الدفع',
    //                     'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}',
    //                     'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 1,
    //                     'status' => 1,
    //                     'statusText' => 'بإنتظار المراجعة',
    //                     'content_ar' => 'يااهلا بـ {CUSTOMERNAME} 😍

    //                                     نشكرك على طلبك من متجر {STORENAME} 🤩 رقم طلبك هو ( {ORDERID} ) وحالته ( {ORDERSTATUS} ).

    //                                     ولاتشيل هم راح نراجع طلبك ونعتمده في أسرع وقت.

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                     'content_en' => 'يااهلا بـ {CUSTOMERNAME} 😍

    //                                     نشكرك على طلبك من متجر {STORENAME} 🤩 رقم طلبك هو ( {ORDERID} ) وحالته ( {ORDERSTATUS} ).

    //                                     ولاتشيل هم راح نراجع طلبك ونعتمده في أسرع وقت.

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 1,
    //                     'status' => 1,
    //                     'statusText' => 'قيد التنفيذ',
    //                     'content_ar' => 'يااهلا بـ {CUSTOMERNAME} 😍

    //                                     طلبك رقم  ( {ORDERID} ) نعمل على تجهيزه في اقرب وقت ممكن 😎 ( {ORDERSTATUS} ).

    //                                     اذا ما عليك امر تفيدنا بتقيمك للخدمه 😊 من خلال الرابط التالي :

    //                                     https://survey.whatskey.net/q/1.html

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                     'content_en' => 'يااهلا بـ {CUSTOMERNAME} 😍

    //                                     طلبك رقم  ( {ORDERID} ) نعمل على تجهيزه في اقرب وقت ممكن 😎 ( {ORDERSTATUS} ).

    //                                     اذا ما عليك امر تفيدنا بتقيمك للخدمه 😊 من خلال الرابط التالي :

    //                                     https://survey.whatskey.net/q/1.html

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 1,
    //                     'status' => 1,
    //                     'statusText' => 'تم التنفيذ',
    //                     'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}',
    //                     'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 1,
    //                     'status' => 1,
    //                     'statusText' => 'جاري التوصيل',
    //                     'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}',
    //                     'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 1,
    //                     'status' => 1,
    //                     'statusText' => 'تم التوصيل',
    //                     'content_ar' => 'يااهلا بـ  {CUSTOMERNAME} 😍

    //                                     سعيدين بانه طلبك رقم  ( {ORDERID} ) صارت حالته ( {ORDERSTATUS} ) 🤩 

    //                                     نتمنى لك تجربة ممتعه ويسعدنا تقييمك لنا على الرابط التالي :
    //                                     https://survey.whatskey.net/q/1.html

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                     'content_en' => 'يااهلا بـ  {CUSTOMERNAME} 😍

    //                                     سعيدين بانه طلبك رقم  ( {ORDERID} ) صارت حالته ( {ORDERSTATUS} ) 🤩 

    //                                     نتمنى لك تجربة ممتعه ويسعدنا تقييمك لنا على الرابط التالي :
    //                                     https://survey.whatskey.net/q/1.html

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 1,
    //                     'status' => 1,
    //                     'statusText' => 'تم الشحن',
    //                     'content_ar' => 'يا اهلا بـ  {CUSTOMERNAME} 😍

    //                                     طلبك رقم ( {ORDERID} ) طلع من عندنا الى شركة الشحن 🤩

    //                                      وصارت حالته ( {ORDERSTATUS} ). سيصلك قربيا باذن الله

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                     'content_en' => 'يا اهلا بـ  {CUSTOMERNAME} 😍

    //                                     طلبك رقم ( {ORDERID} ) طلع من عندنا الى شركة الشحن 🤩

    //                                      وصارت حالته ( {ORDERSTATUS} ). سيصلك قربيا باذن الله

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 1,
    //                     'status' => 1,
    //                     'statusText' => 'ملغي',
    //                     'content_ar' => 'يااهلا بـ {CUSTOMERNAME} 😭 

    //                                     يؤسفنا ابلاغكم بانه تم الغاء طلبكم رقم ( {ORDERID} ) وصارت حالته ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                     'content_en' => 'يااهلا بـ {CUSTOMERNAME} 😭 

    //                                     يؤسفنا ابلاغكم بانه تم الغاء طلبكم رقم ( {ORDERID} ) وصارت حالته ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 1,
    //                     'status' => 1,
    //                     'statusText' => 'مسترجع',
    //                     'content_ar' => 'يااهلا بـ {CUSTOMERNAME} 😍

    //                                     نفيدكم انه طلبكم رقم  ( {ORDERID} ) تم تغير حالته إلى ( {ORDERSTATUS} ).😥

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                     'content_en' => 'يااهلا بـ {CUSTOMERNAME} 😍

    //                                     نفيدكم انه طلبكم رقم  ( {ORDERID} ) تم تغير حالته إلى ( {ORDERSTATUS} ).😥

    //                                     مع تحيات فريق عمل {STORENAME} ❤️',
    //                 ],

    //             ]);
    //         }
    //         tenancy()->end($tenant);
    //     }

    //     if(!empty($addon) && in_array(4,$addon)){
    //         tenancy()->initialize($tenant);
    //         $modCount = ModTemplate::where('mod_id',2)->count();
    //         if($modCount == 0){
    //             ModTemplate::insert([
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 2,
    //                     'status' => 1,
    //                     'statusText' => 'جديد',
    //                     'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم انشاء طلبكم برقم ( {ORDERID} ) وحالته ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                     'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم انشاء طلبكم برقم ( {ORDERID} ) وحالته ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 2,
    //                     'status' => 1,
    //                     'statusText' => 'جاري التجهيز',
    //                     'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                     'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 2,
    //                     'status' => 1,
    //                     'statusText' => 'جاهز',
    //                     'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                     'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 2,
    //                     'status' => 1,
    //                     'statusText' => 'جارى التوصيل',
    //                     'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                     'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 2,
    //                     'status' => 1,
    //                     'statusText' => 'تم التوصيل',
    //                     'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     كما يسعدنا تقييمكم من خلال الرابط التالي :

    //                                     ضع رابط التقيم هنا

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                     'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

    //                                     كما يسعدنا تقييمكم من خلال الرابط التالي :

    //                                     ضع رابط التقيم هنا

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                 ],
    //                 [
    //                     'channel' => $instanceId,
    //                     'mod_id' => 2,
    //                     'status' => 1,
    //                     'statusText' => 'تم الالغاء',
    //                     'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ). 😞

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                     'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

    //                                     تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ). 😞

    //                                     مع تحيات فريق عمل {STORENAME}

    //                                     {ORDER_URL}',
    //                 ],
    //             ]);
    //         }
    //         tenancy()->end($tenant);
    //     }
    // }

    // public function sendNotifications($userObj,$invoiceObj,$type){
    //     $notificationTemplateObj = NotificationTemplate::getOne(2,'paymentSuccess');
    //     $myDomain = config('app.MY_DOMAIN');
    //     $loginUrl = str_replace('myDomain', $userObj->domain, $myDomain).'/login';
    //     $allData = [
    //         'name' => $userObj->name,
    //         'subject' => $notificationTemplateObj->title_ar,
    //         'content' => $notificationTemplateObj->content_ar,
    //         'email' => $userObj->email,
    //         'template' => 'tenant.emailUsers.default',
    //         'url' => $loginUrl,
    //         'extras' => [
    //             'invoiceObj' => Invoice::getData(Invoice::find($invoiceObj->id)),
    //             'company' => $userObj->company,
    //             'url' => $loginUrl,
    //         ],
    //     ];
    //     \MailHelper::prepareEmail($allData);

    //     $salesData = $allData;
    //     $salesData['email'] = 'sales@whatskey.net';
    //     \MailHelper::prepareEmail($salesData);

    //     $notificationTemplateObj = NotificationTemplate::getOne(1,'paymentSuccess');
    //     $phoneData = $allData;
    //     $phoneData['phone'] = $userObj->phone;
    //     \MailHelper::prepareEmail($phoneData,1);

    //     if($type == 'NewClient'){
    //         // Second Email
    //         $notificationTemplateObj = NotificationTemplate::getOne(2,'activateAccount');
    //         $allData = [
    //             'name' => $userObj->name,
    //             'subject' => $notificationTemplateObj->title_ar,
    //             'content' => $notificationTemplateObj->content_ar,
    //             'email' => $userObj->email,
    //             'template' => 'tenant.emailUsers.default',
    //             'url' => $loginUrl,
    //             'extras' => [
    //                 'invoiceObj' => Invoice::getData(Invoice::find($invoiceObj->id)),
    //                 'company' => $userObj->company,
    //                 'url' => $loginUrl,
    //             ],
    //         ];
    //         \MailHelper::prepareEmail($allData);

    //         $notificationTemplateObj = NotificationTemplate::getOne(1,'activateAccount');
    //         $phoneData = $allData;
    //         $phoneData['phone'] = $userObj->phone;
    //         \MailHelper::prepareEmail($phoneData,1);
    //     }

    //     if($type == 'Upgraded'){
    //         $notificationTemplateObj = NotificationTemplate::getOne(2,'upgradeSuccess');
    //         $allData = [
    //             'name' => $userObj->name,
    //             'subject' => $notificationTemplateObj->title_ar,
    //             'content' => $notificationTemplateObj->content_ar,
    //             'email' => $userObj->email,
    //             'template' => 'tenant.emailUsers.default',
    //             'url' => $loginUrl,
    //             'extras' => [
    //                 'invoiceObj' => Invoice::getData(Invoice::find($invoiceObj->id)),
    //                 'company' => $userObj->company,
    //                 'url' => $loginUrl,
    //             ],
    //         ];
    //         \MailHelper::prepareEmail($allData);

    //         $notificationTemplateObj = NotificationTemplate::getOne(1,'upgradeSuccess');
    //         $phoneData = $allData;
    //         $phoneData['phone'] = $userObj->phone;
    //         \MailHelper::prepareEmail($phoneData,1);
    //     }

    //     if($type == 'Suspended'){
    //         $notificationTemplateObj = NotificationTemplate::getOne(2,'renewAccount');
    //         $allData = [
    //             'name' => $userObj->name,
    //             'subject' => $notificationTemplateObj->title_ar,
    //             'content' => $notificationTemplateObj->content_ar,
    //             'email' => $userObj->email,
    //             'template' => 'tenant.emailUsers.default',
    //             'url' => $loginUrl,
    //             'extras' => [
    //                 'invoiceObj' => Invoice::getData(Invoice::find($invoiceObj->id)),
    //                 'company' => $userObj->company,
    //                 'url' => $loginUrl,
    //             ],
    //         ];
    //         \MailHelper::prepareEmail($allData);

    //         $notificationTemplateObj = NotificationTemplate::getOne(1,'renewAccount');
    //         $phoneData = $allData;
    //         $phoneData['phone'] = $userObj->phone;
    //         \MailHelper::prepareEmail($phoneData,1);
    //     }
    // }

    // public function newClient($data,$tenant_id,$global_id,$userId,$invoice_id,$transaction_id,$paymentGateaway){
    //     $items = [];
    //     $addons = [];
    //     $addonData = [];
    //     $extraQuotaData = [];
    //     $total = $data['invoiceObj']->total;
    //     $invoiceData = unserialize($data['invoiceObj']->items);
    //     $start_date = date('Y-m-d');
    //     $centralUser = CentralUser::find($userId);
    //     $membership_id = null;
    //     $duration_type = 1;
        
    //     foreach($invoiceData as $key => $one){
    //         $end_date =  $one['data']['duration_type'] == 1 ? date('Y-m-d',strtotime('+1 month',strtotime($start_date))) : date('Y-m-d',strtotime('+1 year',strtotime($start_date)));
    //         if($one['type'] == 'membership'){
    //             $dataObj = Membership::getOne($one['data']['id']);
    //             $membership_id = $dataObj->id;
    //             $duration_type = $one['data']['duration_type'];
    //         }
    //         // else if($one['type'] == 'addon'){
    //         //     $dataObj = Addons::getOne($one['data']['id']);
    //         //     $addons[] = $one['data']['id'];
    //         //     $addonData[] = [
    //         //         'tenant_id' => $tenant_id,
    //         //         'global_user_id' => $global_id,
    //         //         'user_id' => $userId,
    //         //         'addon_id' => $one['data']['id'],
    //         //         'status' => 1,
    //         //         'duration_type' => $one['data']['duration_type'],
    //         //         'start_date' => $start_date,
    //         //         'end_date' => $end_date, 
    //         //     ];
    //         // }else if($one['type'] == 'extra_quota'){
    //         //     $dataObj = ExtraQuota::getData(ExtraQuota::getOne($one['data']['id']));
    //         //     for ($i = 0; $i < $one['data']['quantity'] ; $i++) {
    //         //         $extraQuotaData[] = [
    //         //             'tenant_id' => $tenant_id,
    //         //             'global_user_id' => $global_id,
    //         //             'user_id' => $userId,
    //         //             'extra_quota_id' => $one['data']['id'],
    //         //             'duration_type' => $one['data']['duration_type'],
    //         //             'status' => 1,
    //         //             'start_date' => $start_date,
    //         //             'end_date' => $end_date, 
    //         //         ];
    //         //     }
    //         // }

    //         $price = $dataObj->monthly_price ;
    //         $price_after_vat = $dataObj->monthly_after_vat;
    //         if($one['data']['duration_type'] == 2){
    //             $price = $dataObj->annual_price ;
    //             $price_after_vat = $dataObj->annual_after_vat;
    //         }
    //         $item = $one;
    //         $items[] = $item;
    //     }

    //     $tenant = Tenant::find($tenant_id);
    //     tenancy()->initialize($tenant);
    //     $userObj = User::first();
    //     tenancy()->end($tenant);

    //     // if(!empty($addons)){
    //     //     $oldData = unserialize($centralUser->addons) != null ? unserialize($centralUser->addons) : [];
    //     //     $newData = array_merge($oldData,$addons);
    //     //     $newData = array_unique($newData);

    //     //     tenancy()->initialize($tenant);
    //     //     $mainUserChannel = UserChannels::first();
    //     //     User::where('id',$centralUser->id)->update([
    //     //         'addons' =>  serialize($newData),
    //     //     ]);
    //     //     tenancy()->end($tenant);
    //     //     $centralUser->update([
    //     //         'addons' =>  serialize($newData),
    //     //     ]);
    //     // }

    //     $invoiceObj = Invoice::find($invoice_id);
    //     $invoiceObj->main = 1;
    //     $invoiceObj->status = 1;
    //     $invoiceObj->paid_date = DATE_TIME;
    //     $invoiceObj->items = serialize($items);
    //     $invoiceObj->transaction_id = $transaction_id;
    //     $invoiceObj->payment_gateaway = $paymentGateaway;  
    //     $invoiceObj->payment_method = $paymentGateaway == 'Noon' ? 1 : 2;
    //     $invoiceObj->save();

    //     $this->sendNotifications($userObj,$invoiceObj,'NewClient');

    //     tenancy()->initialize($tenant);
    //     $mainUserChannel = UserChannels::first();
    //     tenancy()->end($tenant);

    //     // foreach($addonData as $oneAddonData){
    //     //     $userAddonObj = UserAddon::where('user_id',$oneAddonData['user_id'])->where('addon_id',$oneAddonData['addon_id'])->first();
    //     //     if($userAddonObj){
    //     //         $userAddonObj->update($oneAddonData);
    //     //     }else{
    //     //         UserAddon::insert($oneAddonData);
    //     //     }
    //     // }

    //     // foreach($extraQuotaData as $oneItemData){
    //     //     $userExtraQuotaObj = UserExtraQuota::where('user_id',$oneItemData['user_id'])->where('extra_quota_id',$oneItemData['extra_quota_id'])->where('status','!=',1)->first();
    //     //     if($userExtraQuotaObj){
    //     //         $userExtraQuotaObj->update($oneItemData);
    //     //     }else{
    //     //         UserExtraQuota::insert($oneItemData);                
    //     //     }
    //     // }
        
    //     $instanceId = '';
        
    //     $name = CentralChannel::orderBy('id','DESC')->first()->instanceId;
    //     $officialObj = new \OfficialHelper(null,null,'create');
    //     $updateResult = $officialObj->createChannel([
    //         'wlChannelName' => $name+1,
    //     ]);
    //     $result = $updateResult->json();
    //     $channel = [
    //         'token' => $result['data']['instance']['token'],
    //         'name' => 'Channel #'.$result['data']['instance']['id'],
    //         'start_date' => $start_date,
    //         'end_date' => $end_date,
    //     ];

    //     $instanceId = $name+1;

    //     $centralChannel = new CentralChannel;
    //     $centralChannel->token = $channel['token'];
    //     $centralChannel->start_date = $channel['start_date'];
    //     $centralChannel->end_date = $channel['end_date'];
    //     $centralChannel->name = $channel['name'];
    //     $centralChannel->tenant_id = $tenant_id;
    //     $centralChannel->global_user_id = $userObj->global_id;
    //     $centralChannel->instanceId = $instanceId;
    //     $centralChannel->instanceToken = $channel['token'];
    //     $centralChannel->save();

    //     $channel['name'] = 'Channel #'.$result['data']['instance']['id'];
    //     $channel['id'] = $result['data']['instance']['id'];

    //     tenancy()->initialize($tenant);
    //     $mainUserChannel = UserChannels::create($channel);
    //     $userObj->update([
    //         'channels' => serialize([$channel['id']]),
    //     ]);
    //     if($membership_id != null){
    //         $userObj->update([
    //             'membership_id' => $membership_id,
    //             'duration_type' => $duration_type,
    //         ]);
    //     }
    //     Variable::whereIn('var_key',['userCredits','start_date','cartObj','endDate','inv_status','bundle'])->delete();
    //     tenancy()->end($tenant);
        

    //     $centralUser->update([
    //         'channels' => serialize([$channel['id']]),
    //     ]);

    //     if($membership_id != null){
    //         $centralUser->update([
    //             'membership_id' => $membership_id,
    //             'duration_type' => $duration_type,
    //         ]);
    //     }
       
    //     $this->setTemplates($addons,$tenant,$instanceId);
    // }

}

