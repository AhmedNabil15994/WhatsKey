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

    public function setInvoice($invoiceData,$userId,$tenant_id,$global_id,$type,$totalPrice=null){
        $items = [];
        $addons = [];
        $addonData = [];
        $extraQuotaData = [];
        $total = 0;
        $bundle = 0;
        $bundle = Variable::getVar('bundle');
        $main = 0;

        if($type == 'NewClient' || $type == 'SubscriptionChanged'){
            $start_date = date('Y-m-d');
        
            foreach($invoiceData as $key => $one){
                $end_date =  $one[3] == 1 ? date('Y-m-d',strtotime('+1 month',strtotime($start_date))) : date('Y-m-d',strtotime('+1 year',strtotime($start_date)));
                if($one[1] == 'membership'){
                    $dataObj = Membership::getOne($one[0]);
                    $main = 1;
                }else if($one[1] == 'addon'){
                    $dataObj = Addons::getOne($one[0]);
                    $addons[] = $one[0];
                    $addonData[] = [
                        'tenant_id' => $tenant_id,
                        'global_user_id' => $global_id,
                        'user_id' => $userId,
                        'addon_id' => $one[0],
                        'status' => 1,
                        'duration_type' => $one[3],
                        'start_date' => $start_date,
                        'end_date' => $end_date, 
                    ];        
                }else if($one[1] == 'extra_quota'){
                    $dataObj = ExtraQuota::getData(ExtraQuota::getOne($one[0]));
                    for ($i = 0; $i < $one[7] ; $i++) {
                        $extraQuotaData[] = [
                            'tenant_id' => $tenant_id,
                            'global_user_id' => $global_id,
                            'user_id' => $userId,
                            'extra_quota_id' => $one[0],
                            'duration_type' => $one[3],
                            'status' => 1,
                            'start_date' => $start_date,
                            'end_date' => $end_date, 
                        ];
                    }
                }
                $price = $one[6];
                $price_after_vat = $one[6];
               
                $item = [
                    'type' => $one[1],
                    'data' => [
                        'id' => $one[0],
                        'title_ar' => ($one[1] != 'extra_quota' ? $dataObj->title_ar : $dataObj->extra_count . ' '.$dataObj->extraTypeText . ' ' . ($dataObj->extra_type == 1 ? trans('main.msgPerDay') : '') ),
                        'title_en' => ($one[1] != 'extra_quota' ? $dataObj->title_en : $dataObj->extra_count . ' '.$dataObj->extraTypeText . ' ' . ($dataObj->extra_type == 1 ? trans('main.msgPerDay') : '') ),
                        'price' => $price,
                        'price_after_vat' => $price_after_vat,
                        'duration_type' => $one[3],
                        'quantity' => $one[7],
                    ],
                ];
                $total+= $price_after_vat * $one[7];
                $items[] = $item;
            }
        }

        $invoiceObj = Invoice::where('client_id',$userId)->where('status',0)->first();
        $centralUser = CentralUser::find($userId);
        $oldPrice = 0;
        if(!$invoiceObj){
            $invoiceObj = new Invoice;
        }
        $invoiceObj->client_id = $userId;
        $invoiceObj->transaction_id = null;
        $invoiceObj->payment_gateaway = null;  
        $invoiceObj->total = $totalPrice > 0 ? number_format((float)$totalPrice, 2, '.', '') : ($oldPrice > 0 && $main ? number_format((float)$oldPrice, 2, '.', '') : number_format((float)$total, 2, '.', '')) ;
        $invoiceObj->due_date = $start_date;
        $invoiceObj->main = $main;
        $invoiceObj->paid_date = null;
        $invoiceObj->items = serialize($items);
        $invoiceObj->status = 0;
        $invoiceObj->payment_method = null;
        $invoiceObj->sort = Invoice::newSortIndex();
        $invoiceObj->created_at = DATE_TIME;
        $invoiceObj->created_by = $userId;
        $invoiceObj->save();
        return $invoiceObj;
    }

    public function initSubscription($data){
        $tenantData = $this->setTenant($data['transferObj'],$data['invoiceObj']->client_id);

        $userObj = $tenantData['userObj'];
        $centralUser = CentralUser::find($userObj->id);
        $oldMembershipID = $centralUser->membership_id;
        
        $tenantObj = \DB::connection('main')->table('tenant_users')->where('global_user_id',$userObj->global_id)->first();
        $tenant_id = $tenantObj->tenant_id;
        
        if($data['type'] == 'NewClient'){
            $this->newClient($data,$tenant_id,$centralUser->global_id,$centralUser->id,$data['invoiceObj']->id,$data['transaction_id'],$data['paymentGateaway'],$centralUser->isBA);
        }
        return 1;//$this->sendInvoice($data['invoiceObj'],$tenant_id,$centralUser);
    }

    public function sendInvoice($invoiceObj,$tenant_id,$userObj){
        $data['invoice'] = Invoice::getData(Invoice::find($invoiceObj->id));
        $data['companyAddress'] = (object) [
            'servers' => CentralVariable::getVar('servers'),
            'address' => CentralVariable::getVar('address'),
            'region' => CentralVariable::getVar('region'),
            'city' => CentralVariable::getVar('city'),
            'postal_code' => CentralVariable::getVar('postal_code'),
            'country' => CentralVariable::getVar('country'),
            'tax_id' => CentralVariable::getVar('tax_id'),
        ];
        $tax = \Helper::calcTax($data['invoice']->total);
        tenancy()->initialize($tenant_id);
        $paymentObj = PaymentInfo::NotDeleted()->where('user_id',$invoiceObj->client_id)->first();
        if($paymentObj){
            $data['paymentObj'] = PaymentInfo::getData($paymentObj);
        }
        tenancy()->end();
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

        $fileName = 'invoice '.($invoiceObj->id+10000).'.pdf';
        if(!file_exists(public_path().'/uploads/invoices/'.$invoiceObj->id.'/'.$fileName)){
            $pdf = SnappyPdf::loadView('Tenancy.Invoice.Views.V5.invoicePDF',['data'=> (object)$data])
                ->setPaper('a4', 'portrait')
                ->setOption('margin-bottom', '0mm')
                ->setOption('margin-top', '0mm')
                ->setOption('margin-right', '0mm')
                ->setOption('margin-left', '0mm');
            $pdf->save(public_path().'/uploads/invoices/'.$invoiceObj->id.'/'.$fileName);
        }
        
        $baseURL = config('app.BASE_URL').'/public';
        $baseURL.= '/uploads/invoices/'.$invoiceObj->id.'/'.$fileName;
        $sendData = [
            'phone' => str_replace('+', '', $userObj->phone),
            'body' => $baseURL,
            'filename' => $fileName,
        ];
        $centralChannelObj = CentralChannel::NotDeleted()->orderBy('id','ASC')->first();
    
        $mainWhatsLoopObj = new OfficialHelper('10467','985022c87daecfc00c0e290effe3463b');
        $sendData['phone'] = str_replace('@c.us', '', $sendData['phone']);
        $sendData['url'] =  $sendData['body'];
        unset($sendData['body']);
        unset($sendData['filename']);
        

        $result = $mainWhatsLoopObj->sendFile($sendData);

        tenancy()->initialize($centralChannelObj->tenant_id);
        $botObj  = BotPlus::where('message','invoiceme')->first();
        tenancy()->end();

        if($botObj){
            $botObj = BotPlus::getData($botObj);
            if(isset($botObj->buttonsData) && !empty($botObj->buttonsData)){
                $buttons = [];
                foreach($botObj->buttonsData as $key => $oneItem){
                    $buttons[]= [
                        'id' => $key +1 ,
                        'title' => $oneItem['text'],
                    ];
                }
            }

            $sendButtonsData['title'] = $botObj->title;
            $sendButtonsData['body'] = $botObj->body;
            $sendButtonsData['footer'] = $botObj->footer;
            $sendButtonsData['buttons'] = $buttons;
        }

        $result2 = $mainWhatsLoopObj->sendButtons($sendButtonsData);
    }

    public function setTenant($transferObj=null,$userId){
        $tenant = null;
        $bundle = 0;
        $userCredits = 0;

        if($transferObj != null){
            $tenant = Tenant::find($transferObj->tenant_id);
        }else{
            $tenantUser = CentralUser::find($userId);
            $tenants = \DB::connection('main')->table('tenant_users')->where('global_user_id',$tenantUser->global_id)->first();
            $tenant = Tenant::find($tenants->tenant_id);
        }

        tenancy()->initialize($tenant);
        $userObj = User::first();
        $userCreditsObj = Variable::getVar('userCredits');
        $bundle = Variable::getVar('bundle');
        $start_date = Variable::getVar('start_date');
        if($userCreditsObj){
            $userCredits = $userCreditsObj;
        }
        tenancy()->end($tenant);

        return [
            'userObj' => $userObj,
            'bundle' => $bundle,
            'start_date' => $start_date,
            'userCredits' => $userCredits,
        ];
    }

    public function setTemplates($addon,$tenant,$instanceId){
        if(!empty($addon) && in_array(9,$addon)){
            tenancy()->initialize($tenant);
            Template::insert([
                [
                    'channel' => $instanceId,
                    'name_ar' => 'whatsAppOrders',
                    'name_en' => 'whatsAppOrders',
                    'description_ar' => 'يااهلا بـ {CUSTOMERNAME} 😍

                                        طلبك رقم ( {ORDERID} ) جاهز الان للشراء 😎.

                                        اذا ما عليك امر تتوجه الي صفحة مراجعة طلبك 😊 من خلال الرابط التالي :

                                        ( {ORDERURL} )

                                        مع تحيات فريق عمل واتس لوب ❤️',
                    'description_en' => 'يااهلا بـ {CUSTOMERNAME} 😍

                                        طلبك رقم ( {ORDERID} ) جاهز الان للشراء 😎.

                                        اذا ما عليك امر تتوجه الي صفحة مراجعة طلبك 😊 من خلال الرابط التالي :

                                        ( {ORDERURL} )

                                        مع تحيات فريق عمل واتس لوب ❤️',
                    'status' => 1,
                ],
                [
                    'channel' => $instanceId,
                    'name_ar' => 'whatsAppInvoices',
                    'name_en' => 'whatsAppInvoices',
                    'description_ar' => 'يااهلا بـ {CUSTOMERNAME} 😍

                                        تم تأكيد شراء طلبك رقم ( {ORDERID} )  😎.

                                        اذا ما عليك امر تتوجه الي طباعة فاتورة طلبك 😊 من خلال الرابط التالي :

                                        ( {INVOICEURL} )

                                        مع تحيات فريق عمل واتس لوب ❤️',
                    'description_en' => 'يااهلا بـ {CUSTOMERNAME} 😍

                                        تم تأكيد شراء طلبك رقم ( {ORDERID} )  😎.

                                        اذا ما عليك امر تتوجه الي طباعة فاتورة طلبك 😊 من خلال الرابط التالي :

                                        ( {INVOICEURL} )

                                        مع تحيات فريق عمل واتس لوب ❤️',
                    'status' => 1,
                ],

            ]);

            tenancy()->end($tenant);
        }

        if(!empty($addon) && in_array(5,$addon)){
            tenancy()->initialize($tenant);
            $modCount = ModTemplate::where('mod_id',1)->count();
            if($modCount == 0){
                ModTemplate::insert([
                    [
                        'channel' => $instanceId,
                        'mod_id' => 1,
                        'status' => 1,
                        'statusText' => 'ترحيب بالعميل',
                        'content_ar' => 'يا اهلا بـ {CUSTOMERNAME} 😍
                                        
                                        اهلا وسهلا بك نورتنا وشرفتنا في متجرنا 🤩
                                        
                                        مع تحيات فريق عمل {STORENAME} ❤️',
                        'content_en' => 'يا اهلا بـ {CUSTOMERNAME} 😍
                                        
                                        اهلا وسهلا بك نورتنا وشرفتنا في متجرنا 🤩

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 1,
                        'status' => 1,
                        'statusText' => 'بإنتظار الدفع',
                        'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}',
                        'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 1,
                        'status' => 1,
                        'statusText' => 'بإنتظار المراجعة',
                        'content_ar' => 'يااهلا بـ {CUSTOMERNAME} 😍

                                        نشكرك على طلبك من متجر {STORENAME} 🤩 رقم طلبك هو ( {ORDERID} ) وحالته ( {ORDERSTATUS} ).

                                        ولاتشيل هم راح نراجع طلبك ونعتمده في أسرع وقت.

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                        'content_en' => 'يااهلا بـ {CUSTOMERNAME} 😍

                                        نشكرك على طلبك من متجر {STORENAME} 🤩 رقم طلبك هو ( {ORDERID} ) وحالته ( {ORDERSTATUS} ).

                                        ولاتشيل هم راح نراجع طلبك ونعتمده في أسرع وقت.

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 1,
                        'status' => 1,
                        'statusText' => 'قيد التنفيذ',
                        'content_ar' => 'يااهلا بـ {CUSTOMERNAME} 😍

                                        طلبك رقم  ( {ORDERID} ) نعمل على تجهيزه في اقرب وقت ممكن 😎 ( {ORDERSTATUS} ).

                                        اذا ما عليك امر تفيدنا بتقيمك للخدمه 😊 من خلال الرابط التالي :

                                        https://survey.whatskey.net/q/1.html

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                        'content_en' => 'يااهلا بـ {CUSTOMERNAME} 😍

                                        طلبك رقم  ( {ORDERID} ) نعمل على تجهيزه في اقرب وقت ممكن 😎 ( {ORDERSTATUS} ).

                                        اذا ما عليك امر تفيدنا بتقيمك للخدمه 😊 من خلال الرابط التالي :

                                        https://survey.whatskey.net/q/1.html

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 1,
                        'status' => 1,
                        'statusText' => 'تم التنفيذ',
                        'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}',
                        'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 1,
                        'status' => 1,
                        'statusText' => 'جاري التوصيل',
                        'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}',
                        'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 1,
                        'status' => 1,
                        'statusText' => 'تم التوصيل',
                        'content_ar' => 'يااهلا بـ  {CUSTOMERNAME} 😍

                                        سعيدين بانه طلبك رقم  ( {ORDERID} ) صارت حالته ( {ORDERSTATUS} ) 🤩 

                                        نتمنى لك تجربة ممتعه ويسعدنا تقييمك لنا على الرابط التالي :
                                        https://survey.whatskey.net/q/1.html

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                        'content_en' => 'يااهلا بـ  {CUSTOMERNAME} 😍

                                        سعيدين بانه طلبك رقم  ( {ORDERID} ) صارت حالته ( {ORDERSTATUS} ) 🤩 

                                        نتمنى لك تجربة ممتعه ويسعدنا تقييمك لنا على الرابط التالي :
                                        https://survey.whatskey.net/q/1.html

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 1,
                        'status' => 1,
                        'statusText' => 'تم الشحن',
                        'content_ar' => 'يا اهلا بـ  {CUSTOMERNAME} 😍

                                        طلبك رقم ( {ORDERID} ) طلع من عندنا الى شركة الشحن 🤩

                                         وصارت حالته ( {ORDERSTATUS} ). سيصلك قربيا باذن الله

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                        'content_en' => 'يا اهلا بـ  {CUSTOMERNAME} 😍

                                        طلبك رقم ( {ORDERID} ) طلع من عندنا الى شركة الشحن 🤩

                                         وصارت حالته ( {ORDERSTATUS} ). سيصلك قربيا باذن الله

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 1,
                        'status' => 1,
                        'statusText' => 'ملغي',
                        'content_ar' => 'يااهلا بـ {CUSTOMERNAME} 😭 

                                        يؤسفنا ابلاغكم بانه تم الغاء طلبكم رقم ( {ORDERID} ) وصارت حالته ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                        'content_en' => 'يااهلا بـ {CUSTOMERNAME} 😭 

                                        يؤسفنا ابلاغكم بانه تم الغاء طلبكم رقم ( {ORDERID} ) وصارت حالته ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 1,
                        'status' => 1,
                        'statusText' => 'مسترجع',
                        'content_ar' => 'يااهلا بـ {CUSTOMERNAME} 😍

                                        نفيدكم انه طلبكم رقم  ( {ORDERID} ) تم تغير حالته إلى ( {ORDERSTATUS} ).😥

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                        'content_en' => 'يااهلا بـ {CUSTOMERNAME} 😍

                                        نفيدكم انه طلبكم رقم  ( {ORDERID} ) تم تغير حالته إلى ( {ORDERSTATUS} ).😥

                                        مع تحيات فريق عمل {STORENAME} ❤️',
                    ],

                ]);
            }
            tenancy()->end($tenant);
        }

        if(!empty($addon) && in_array(4,$addon)){
            tenancy()->initialize($tenant);
            $modCount = ModTemplate::where('mod_id',2)->count();
            if($modCount == 0){
                ModTemplate::insert([
                    [
                        'channel' => $instanceId,
                        'mod_id' => 2,
                        'status' => 1,
                        'statusText' => 'جديد',
                        'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم انشاء طلبكم برقم ( {ORDERID} ) وحالته ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                        'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم انشاء طلبكم برقم ( {ORDERID} ) وحالته ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 2,
                        'status' => 1,
                        'statusText' => 'جاري التجهيز',
                        'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                        'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 2,
                        'status' => 1,
                        'statusText' => 'جاهز',
                        'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                        'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 2,
                        'status' => 1,
                        'statusText' => 'جارى التوصيل',
                        'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                        'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 2,
                        'status' => 1,
                        'statusText' => 'تم التوصيل',
                        'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        كما يسعدنا تقييمكم من خلال الرابط التالي :

                                        ضع رابط التقيم هنا

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                        'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ).

                                        كما يسعدنا تقييمكم من خلال الرابط التالي :

                                        ضع رابط التقيم هنا

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                    ],
                    [
                        'channel' => $instanceId,
                        'mod_id' => 2,
                        'status' => 1,
                        'statusText' => 'تم الالغاء',
                        'content_ar' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ). 😞

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                        'content_en' => 'عميلنا العزيز، {CUSTOMERNAME}

                                        تم تغيير حالة طلبكم برقم ( {ORDERID} ) إلى ( {ORDERSTATUS} ). 😞

                                        مع تحيات فريق عمل {STORENAME}

                                        {ORDER_URL}',
                    ],
                ]);
            }
            tenancy()->end($tenant);
        }
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
                'invoiceObj' => Invoice::getData(Invoice::find($invoiceObj->id)),
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

        if($type == 'NewClient'){
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
                    'invoiceObj' => Invoice::getData(Invoice::find($invoiceObj->id)),
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

        if($type == 'Suspended'){
            $notificationTemplateObj = NotificationTemplate::getOne(2,'renewAccount');
            $allData = [
                'name' => $userObj->name,
                'subject' => $notificationTemplateObj->title_ar,
                'content' => $notificationTemplateObj->content_ar,
                'email' => $userObj->email,
                'template' => 'tenant.emailUsers.default',
                'url' => $loginUrl,
                'extras' => [
                    'invoiceObj' => Invoice::getData(Invoice::find($invoiceObj->id)),
                    'company' => $userObj->company,
                    'url' => $loginUrl,
                ],
            ];
            \MailHelper::prepareEmail($allData);

            $notificationTemplateObj = NotificationTemplate::getOne(1,'renewAccount');
            $phoneData = $allData;
            $phoneData['phone'] = $userObj->phone;
            \MailHelper::prepareEmail($phoneData,1);
        }

        if($type == 'Upgraded'){
            $notificationTemplateObj = NotificationTemplate::getOne(2,'upgradeSuccess');
            $allData = [
                'name' => $userObj->name,
                'subject' => $notificationTemplateObj->title_ar,
                'content' => $notificationTemplateObj->content_ar,
                'email' => $userObj->email,
                'template' => 'tenant.emailUsers.default',
                'url' => $loginUrl,
                'extras' => [
                    'invoiceObj' => Invoice::getData(Invoice::find($invoiceObj->id)),
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
    }

    public function newClient($data,$tenant_id,$global_id,$userId,$invoice_id,$transaction_id,$paymentGateaway){
        $items = [];
        $addons = [];
        $addonData = [];
        $extraQuotaData = [];
        $total = $data['invoiceObj']->total;
        $invoiceData = unserialize($data['invoiceObj']->items);
        $start_date = date('Y-m-d');
        $centralUser = CentralUser::find($userId);
        $membership_id = null;
        $duration_type = 1;
        
        foreach($invoiceData as $key => $one){
            $end_date =  $one['data']['duration_type'] == 1 ? date('Y-m-d',strtotime('+1 month',strtotime($start_date))) : date('Y-m-d',strtotime('+1 year',strtotime($start_date)));
            if($one['type'] == 'membership'){
                $dataObj = Membership::getOne($one['data']['id']);
                $membership_id = $dataObj->id;
                $duration_type = $one['data']['duration_type'];
            }else if($one['type'] == 'addon'){
                $dataObj = Addons::getOne($one['data']['id']);
                $addons[] = $one['data']['id'];
                $addonData[] = [
                    'tenant_id' => $tenant_id,
                    'global_user_id' => $global_id,
                    'user_id' => $userId,
                    'addon_id' => $one['data']['id'],
                    'status' => 1,
                    'duration_type' => $one['data']['duration_type'],
                    'start_date' => $start_date,
                    'end_date' => $end_date, 
                ];
            }else if($one['type'] == 'extra_quota'){
                $dataObj = ExtraQuota::getData(ExtraQuota::getOne($one['data']['id']));
                for ($i = 0; $i < $one['data']['quantity'] ; $i++) {
                    $extraQuotaData[] = [
                        'tenant_id' => $tenant_id,
                        'global_user_id' => $global_id,
                        'user_id' => $userId,
                        'extra_quota_id' => $one['data']['id'],
                        'duration_type' => $one['data']['duration_type'],
                        'status' => 1,
                        'start_date' => $start_date,
                        'end_date' => $end_date, 
                    ];
                }
            }

            $price = $dataObj->monthly_price ;
            $price_after_vat = $dataObj->monthly_after_vat;
            if($one['data']['duration_type'] == 2){
                $price = $dataObj->annual_price ;
                $price_after_vat = $dataObj->annual_after_vat;
            }
            $item = $one;
            $items[] = $item;
        }

        $tenant = Tenant::find($tenant_id);
        tenancy()->initialize($tenant);
        $userObj = User::first();
        tenancy()->end($tenant);

        if(!empty($addons)){
            $oldData = unserialize($centralUser->addons) != null ? unserialize($centralUser->addons) : [];
            $newData = array_merge($oldData,$addons);
            $newData = array_unique($newData);

            tenancy()->initialize($tenant);
            $mainUserChannel = UserChannels::first();
            User::where('id',$centralUser->id)->update([
                'addons' =>  serialize($newData),
            ]);
            tenancy()->end($tenant);
            $centralUser->update([
                'addons' =>  serialize($newData),
            ]);
        }

        $invoiceObj = Invoice::find($invoice_id);
        $invoiceObj->main = 1;
        $invoiceObj->status = 1;
        $invoiceObj->paid_date = DATE_TIME;
        $invoiceObj->items = serialize($items);
        $invoiceObj->transaction_id = $transaction_id;
        $invoiceObj->payment_gateaway = $paymentGateaway;  
        $invoiceObj->payment_method = $paymentGateaway == 'Noon' ? 1 : 2;
        $invoiceObj->save();

        $this->sendNotifications($userObj,$invoiceObj,'NewClient');
        tenancy()->initialize($tenant);
        $mainUserChannel = UserChannels::first();
        tenancy()->end($tenant);
        foreach($addonData as $oneAddonData){
            $userAddonObj = UserAddon::where('user_id',$oneAddonData['user_id'])->where('addon_id',$oneAddonData['addon_id'])->first();
            if($userAddonObj){
                $userAddonObj->update($oneAddonData);
            }else{
                UserAddon::insert($oneAddonData);
            }
        }

        foreach($extraQuotaData as $oneItemData){
            $userExtraQuotaObj = UserExtraQuota::where('user_id',$oneItemData['user_id'])->where('extra_quota_id',$oneItemData['extra_quota_id'])->where('status','!=',1)->first();
            if($userExtraQuotaObj){
                $userExtraQuotaObj->update($oneItemData);
            }else{
                UserExtraQuota::insert($oneItemData);                
            }
        }
        
        $instanceId = '';
        
        $name = CentralChannel::orderBy('instanceId','DESC')->first()->instanceId;
        $officialObj = new \OfficialHelper(null,null,'create');
        $updateResult = $officialObj->createChannel([
            'wlChannelName' => $name+1,
        ]);
        $result = $updateResult->json();

        $channel = [
            'token' => $result['data']['instance']['token'],
            'name' => 'Channel #'.$result['data']['instance']['id'],
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        $generatedData = CentralChannel::generateNewKey($result['data']['instance']['id']); // [ generated Key , generated Token]
        $instanceId = $generatedData[0];

        $centralChannel = new CentralChannel;
        $centralChannel->token = $channel['token'];
        $centralChannel->start_date = $channel['start_date'];
        $centralChannel->end_date = $channel['end_date'];
        $centralChannel->name = $channel['name'];
        $centralChannel->tenant_id = $tenant_id;
        $centralChannel->global_user_id = $userObj->global_id;
        $centralChannel->instanceId = $generatedData[0];
        $centralChannel->instanceToken = $channel['token'];
        $centralChannel->save();

        $channel['name'] = 'Channel #'.$result['data']['instance']['id'];
        $channel['id'] = $result['data']['instance']['id'];

        tenancy()->initialize($tenant);
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
        Variable::whereIn('var_key',['userCredits','start_date','cartObj','endDate','inv_status','bundle'])->delete();
        tenancy()->end($tenant);
        

        $centralUser->update([
            'channels' => serialize([$channel['id']]),
        ]);

        if($membership_id != null){
            $centralUser->update([
                'membership_id' => $membership_id,
                'duration_type' => $duration_type,
            ]);
        }
       

        $this->setTemplates($addons,$tenant,$instanceId);
    }

}

