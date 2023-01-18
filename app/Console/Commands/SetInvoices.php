<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\CentralUser;
use App\Models\UserAddon;
use App\Models\UserExtraQuota;
use App\Models\ExtraQuota;
use App\Models\CentralChannel;
use App\Models\Membership;
use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Variable;
use App\Models\NotificationTemplate;


class SetInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set User Invoices Every Day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        $channels = CentralChannel::dataList()['data'];
        $disableAddonAutoInvoice = Variable::getVar('disableAddonAutoInvoice');
        $disableExtraQuotaAutoInvoice = Variable::getVar('disableExtraQuotaAutoInvoice');
        $membershipInvoices = [];
        $addonInvoices = [];
        $extraQuotaInvoices = [];
        foreach ($channels as $value) {
            if($value->leftDays <= 7 && $value->leftDays >= -30 ){
                $userObj = CentralUser::where('global_id',$value->global_user_id)->first();
                if(isset($userObj->membership_id) && $userObj->membership_id != null && in_array($userObj->duration_type,[1,2])){
                    $membershipObj = $userObj->Membership; 
                    $start_date =  $value->leftDays < 0 ? date('Y-m-d') : date('Y-m-d',strtotime('+1 day',strtotime($value->end_date)));
                    $end_date = $userObj->duration_type == 1 ? date('Y-m-d',strtotime('+1 month',strtotime($start_date))) : date('Y-m-d',strtotime('+1 year',strtotime($start_date)));
                    if($userObj->duration_type == 1){
                        $price = $membershipObj->monthly_after_vat;
                    }else if($userObj->duration_type == 2){
                        $price = $membershipObj->annual_after_vat;
                    }
                    $membershipInvoices[$userObj->id][$start_date] = [
                        'data' => [
                            'total' => $price,
                            'leftDays' => $value->leftDays,
                            'tenant_id' => $value->tenant_id,
                            'user_id' => $userObj->id,
                            'main' => 1,
                            'items' => [
                                [
                                    'id' => $membershipObj->id,
                                    'type' => 'membership',
                                    'duration_type' => $userObj->duration_type,
                                    'title' => $membershipObj->title_ar,
                                    'start_date' => $start_date,
                                    'end_date' => $end_date,
                                    'price' => $price,
                                    'quantity' => 1,
                                ]
                            ],
                        ]
                    ];
                }
            }
        }

        // Check New Invoices For Addons
        $userAddons = UserAddon::NotDeleted()->groupBy(['user_id'])->get();
        foreach ($userAddons as $addon) {
            $addons = UserAddon::NotDeleted()->where('user_id',$addon->user_id)->pluck('addon_id');     
            $userAddon = UserAddon::dataList(reset($addons),$addon->user_id)['data'];      
            $userObj = CentralUser::find($addon->user_id);

            foreach ($userAddon as $key => $addonValue) {
                if($userObj && $addonValue->leftDays <= 7 && $addonValue->leftDays >= -30 && in_array($addonValue->duration_type,[1,2])){

                    $addonObj = $addonValue->Addon; 
                    $start_date =  $addonValue->leftDays < 0 ? date('Y-m-d') : date('Y-m-d',strtotime('+1 day',strtotime($addonValue->end_date)));
                    $end_date = $userObj->duration_type == 1 ? date('Y-m-d',strtotime('+1 month',strtotime($start_date))) : date('Y-m-d',strtotime('+1 year',strtotime($start_date)));
                    if($addonValue->duration_type == 1){
                        $price = $addonObj->monthly_after_vat;
                    }else if($addonValue->duration_type == 2){
                        $price = $addonObj->annual_after_vat;
                    }

                    if(isset($addonInvoices[$userObj->id])){
                        if(isset($addonInvoices[$userObj->id][$start_date])){
                            $addonInvoices[$userObj->id][$start_date]['data']['total'] =  $addonInvoices[$userObj->id][$start_date]['data']['total'] + $price;
                            $addonInvoices[$userObj->id][$start_date]['data']['items'][] = [
                                'id' => $addonObj->id,
                                'type' => 'addon',
                                'duration_type' => $addonValue->duration_type,
                                'title' => $addonObj->title_ar,
                                'start_date' => $start_date,
                                'end_date' => $end_date,
                                'price' => $price,
                                'quantity' => 1,
                            ]; 
                        }else{
                            $addonInvoices[$userObj->id][$start_date]['data'] = [
                                'total' => $price,
                                'leftDays' => $addonValue->leftDays,
                                'tenant_id' => $addonValue->tenant_id,
                                'user_id' => $userObj->id,
                                'main' => 0,
                                'items' => [
                                    [
                                        'id' => $addonObj->id,
                                        'type' => 'addon',
                                        'duration_type' => $addonValue->duration_type,
                                        'title' => $addonObj->title_ar,
                                        'start_date' => $start_date,
                                        'end_date' => $end_date,
                                        'price' => $price,
                                        'quantity' => 1,
                                    ]
                                ],
                            ];
                        }
                    }else{
                        $addonInvoices[$userObj->id][$start_date] = [
                            'data' => [
                                'total' => $price,
                                'leftDays' => $addonValue->leftDays,
                                'tenant_id' => $addonValue->tenant_id,
                                'user_id' => $userObj->id,
                                'main' => 0,
                                'items' => [
                                    [
                                        'id' => $addonObj->id,
                                        'type' => 'addon',
                                        'duration_type' => $addonValue->duration_type,
                                        'title' => $addonObj->title_ar,
                                        'start_date' => $start_date,
                                        'end_date' => $end_date,
                                        'price' => $price,
                                        'quantity' => 1,
                                    ]
                                ],
                            ]
                        ];
                    }
                    
                    if($addonValue->leftDays < 0){
                        UserAddon::where('user_id',$addon->user_id)->where('addon_id',$addonValue->addon_id)->update(['status' => 2]);
                    }
                }    
            }
        }

        // Check New Invoices For Extra Quota
        $userExtraQuotas = UserExtraQuota::NotDeleted()->groupBy(['user_id'])->get();
        foreach ($userExtraQuotas as $userExtraQuota) {
            $userExtra = UserExtraQuota::dataList($userExtraQuota->user_id,$userExtraQuota->end_date)['data'];
            $userObj = CentralUser::find($userExtraQuota->user_id);
            foreach ($userExtra as $extraQuotaKey => $extraQuotaValue) {
                if($userObj && $extraQuotaValue->leftDays <= 7 && $extraQuotaValue->leftDays >= -30 && in_array($extraQuotaValue->duration_type,[1,2])){

                    $start_date =  $extraQuotaValue->leftDays < 0 ? date('Y-m-d') : date('Y-m-d',strtotime('+1 day',strtotime($extraQuotaValue->end_date)));
                    $end_date = $userObj->duration_type == 1 ? date('Y-m-d',strtotime('+1 month',strtotime($start_date))) : date('Y-m-d',strtotime('+1 year',strtotime($start_date)));

                    $extraQuotaObj = ExtraQuota::getData($extraQuotaValue->ExtraQuota);
                    if($extraQuotaValue->duration_type == 1){
                        $price = $extraQuotaObj->monthly_after_vat;
                    }else if($extraQuotaValue->duration_type == 2){
                        $price = $extraQuotaObj->annual_after_vat;
                    }

                    if(isset($extraQuotaInvoices[$userObj->id])){
                        if(isset($extraQuotaInvoices[$userObj->id][$start_date])){
                            $extraQuotaInvoices[$userObj->id][$start_date]['data']['total'] =  $extraQuotaInvoices[$userObj->id][$start_date]['data']['total'] + $price;
                            $extraQuotaInvoices[$userObj->id][$start_date]['data']['items'][] = [
                                'id' => $extraQuotaObj->id,
                                'type' => 'extra_quota',
                                'duration_type' => $extraQuotaValue->duration_type,
                                'title' => $extraQuotaObj->title,
                                'start_date' => $start_date,
                                'end_date' => $end_date,
                                'price' => $price,
                                'quantity' => 1,
                            ]; 
                        }else{
                            $extraQuotaInvoices[$userObj->id][$start_date]['data'] = [
                                'total' => $price,
                                'leftDays' => $extraQuotaValue->leftDays,
                                'tenant_id' => $extraQuotaValue->tenant_id,
                                'user_id' => $userObj->id,
                                'main' => 0,
                                'items' => [
                                    [
                                        'id' => $extraQuotaObj->id,
                                        'type' => 'extra_quota',
                                        'duration_type' => $extraQuotaValue->duration_type,
                                        'title' => $extraQuotaObj->title,
                                        'start_date' => $start_date,
                                        'end_date' => $end_date,
                                        'price' => $price,
                                        'quantity' => 1,
                                    ]
                                ],
                            ];
                        }
                    }else{
                        $extraQuotaInvoices[$userObj->id][$start_date] = [
                            'data' => [
                                'total' => $price,
                                'leftDays' => $extraQuotaValue->leftDays,
                                'tenant_id' => $extraQuotaValue->tenant_id,
                                'user_id' => $userObj->id,
                                'main' => 0,
                                'items' => [
                                    [
                                        'id' => $extraQuotaObj->id,
                                        'type' => 'extra_quota',
                                        'duration_type' => $extraQuotaValue->duration_type,
                                        'title' => $extraQuotaObj->title,
                                        'start_date' => $start_date,
                                        'end_date' => $end_date,
                                        'price' => $price,
                                        'quantity' => 1,
                                    ]
                                ],
                            ]
                        ];
                    }

                    if($extraQuotaValue->leftDays < 0){
                        UserExtraQuota::where('user_id',$userExtraQuota->user_id)->where('extra_quota_id',$extraQuotaValue->extra_quota_id)->update(['status' => 2]);
                    }
                }    
            }
        }

        if(!empty($membershipInvoices)){
            $this->setInvoices($membershipInvoices);
        }
        if(!empty($addonInvoices) && $disableAddonAutoInvoice != 1){
            $this->setInvoices($addonInvoices);
        }
        if(!empty($extraQuotaInvoices) && $disableExtraQuotaAutoInvoice != 1){
            $this->setInvoices($extraQuotaInvoices);
        }
        return 1;
    }

    public function setInvoices($invoices){
        if(!empty($invoices)){
            $channelObj = \DB::connection('main')->table('channels')->first();
            foreach($invoices as $invoiceKey  =>  $userInvoices){
                $tenant_id = array_values($userInvoices)[0]['data']['tenant_id'];
                tenancy()->initialize($tenant_id);
                $userObj = User::find($invoiceKey);
                tenancy()->end($tenant_id);
                $centralUserObj = CentralUser::find($invoiceKey);
                foreach ($userInvoices as $invoiceDate => $oneItem) {
                    $emailData = [
                        'name' => $userObj->name,
                        'email' => $userObj->email,
                    ];

                    $status = 2;
                    if(date('Y-m-d',strtotime($invoiceDate)) > date('Y-m-d')){
                        $status = 3;
                    }

                    $checkInv = Invoice::where('client_id',$invoiceKey)->where('status','!=',1)->where('main',$oneItem['data']['main'])->where('updated_by',5)->delete();
                    
                    $invoiceObj = Invoice::NotDeleted()->where('client_id',$invoiceKey)->where('due_date',$invoiceDate)->where('main',$oneItem['data']['main'])->where('items',serialize($oneItem['data']['items']))->first();
                    
                    $invoiceObj = new Invoice;
                    $invoiceObj->client_id = $invoiceKey;
                    $invoiceObj->due_date = $invoiceDate;
                    $invoiceObj->total = $oneItem['data']['total'] ;
                    $invoiceObj->items = serialize($oneItem['data']['items']);
                    $invoiceObj->main = $oneItem['data']['main'];
                    $invoiceObj->status = $status;
                    $invoiceObj->discount_type = ($centralUserObj && $centralUserObj->balance  > 0) ? 1 : null;
                    $invoiceObj->discount_value = ($centralUserObj && $centralUserObj->balance > 0) ? $centralUserObj->balance : null;
                    $invoiceObj->sort = Invoice::newSortIndex();
                    $invoiceObj->created_at = date('Y-m-d H:i:s');
                    $invoiceObj->created_by = 1;
                    $invoiceObj->updated_by = 5;
                    $invoiceObj->save();
                    
                    $this->sendInvoice($oneItem,$invoiceObj,$userObj);
                }
            }
        }
        return 1;
    }

    public function sendInvoice($oneItem,$invoiceObj,$userObj){
        $myDomain = config('app.MY_DOMAIN');
        $loginUrl = str_replace('myDomain', $userObj->domain, $myDomain).'/invoices/view/'.$invoiceObj->id;
        if($oneItem['data']['leftDays'] == 7 /*&& (int) date('H') == 12*/){
            $notificationTemplateObj = NotificationTemplate::getOne(2,'newInvoice');
            $whatsappTemplateObj = NotificationTemplate::getOne(1,'newInvoice');
        }else if($oneItem['data']['leftDays'] == 3 /*&& (int) date('H') == 12*/){
            // First Reminder
            $notificationTemplateObj = NotificationTemplate::getOne(2,'firstReminder');
            $whatsappTemplateObj = NotificationTemplate::getOne(1,'firstReminder');
        }else if($oneItem['data']['leftDays'] == 1 /*&& (int) date('H') == 12*/){
            // Second Reminder // تذكير بسداد الفاتورة
            $notificationTemplateObj = NotificationTemplate::getOne(2,'secondReminder');
            $whatsappTemplateObj = NotificationTemplate::getOne(1,'secondReminder');
        }else if($oneItem['data']['leftDays'] <= 0 /*&& (int) date('H') == 12*/){
            // Suspend 
            if($invoiceObj->status == 2  /*&& (int) date('H') == 9*/ ){
                $notificationTemplateObj = NotificationTemplate::getOne(2,'accountSuspended');                
                $whatsappTemplateObj = NotificationTemplate::getOne(1,'accountSuspended');
            }   
        }else if($oneItem['data']['leftDays'] == -1 /*&& (int) date('H') == 12*/){
            // Whatskey Customer Service
            $notificationTemplateObj = NotificationTemplate::getOne(2,'leadContact');
            $whatsappTemplateObj = NotificationTemplate::getOne(1,'leadContact');
        }

        $allData = [
            'name' => $userObj->name,
            'email' => $userObj->email,
            'template' => 'tenant.emailUsers.default',
            'url' => $loginUrl,
            'extras' => [
                'invoiceObj' => Invoice::getData($invoiceObj),
                'company' => $userObj->company,
                'url' => $loginUrl,
            ],
        ];

        if($notificationTemplateObj){
            $allData['subject'] = $notificationTemplateObj->title_ar;
            $allData['content'] = $notificationTemplateObj->content_ar;
            \MailHelper::prepareEmail($allData);
        }
        
        $phoneData = $allData;
        $phoneData['phone'] = $userObj->phone;
        if($whatsappTemplateObj){
            $phoneData['subject'] = $whatsappTemplateObj->title_ar;
            $phoneData['content'] = $whatsappTemplateObj->content_ar;
            \MailHelper::prepareEmail($phoneData,1);
        }
        
        return 1;
    }
}
