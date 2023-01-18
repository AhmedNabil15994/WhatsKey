<?php namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use App\Models\CentralUser;
use App\Models\Membership;
use App\Models\Addons;
use App\Models\ExtraQuota;
use App\Models\UserAddon;
use App\Models\UserExtraQuota;
use App\Models\UserChannels;
use App\Models\BankAccount;
use App\Models\CentralChannel;
use App\Models\Variable;
use App\Models\PaymentInfo;
use App\Models\CentralVariable;
use App\Models\OldMembership;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\WebActions;
use DataTables;
use App\Jobs\NewClient;

use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;
use PDF;

class TenantInvoiceControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.subs_invoices'),
            'url' => 'invoices',
            'name' => 'invoices',
            'nameOne' => 'invoice',
            'modelName' => 'Invoice',
            'icon' => 'fas fa-file-invoice',
            'sortName' => 'id',
        ];
        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
                'specialAttr' => '',
            ],
            'status' => [
                'type' => 'select',
                'class' => 'form-control datatable-input',
                'index' => '4',
                'label' => trans('main.status'),
                'options' => [
                    ['id' => 0 , 'title' => trans('main.invoice_status_0')],
                    ['id' => 1 , 'title' => trans('main.invoice_status_1')],
                    ['id' => 2 , 'title' => trans('main.invoice_status_2')],
                    ['id' => 3 , 'title' => trans('main.invoice_status_3')],
                    ['id' => 4 , 'title' => trans('main.invoice_status_4')],
                    ['id' => 5 , 'title' => trans('main.invoice_status_5')],
                ],
            ],
            'from' => [
                'type' => 'text',
                'class' => 'form-control datatable-input datepicker',
                'index' => '',
                'id' => 'datepicker1',
                'label' => trans('main.dateFrom'),
            ],
            'to' => [
                'type' => 'text',
                'class' => 'form-control datatable-input datepicker',
                'index' => '',
                'id' => 'datepicker2',
                'label' => trans('main.dateTo'),
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
            'client' => [
                'label' => trans('main.client'),
                'type' => '',
                'className' => '',
                'data-col' => 'client_id',
                'anchor-class' => '',
            ],
            'due_date' => [
                'label' => trans('main.due_date'),
                'type' => '',
                'className' => '',
                'data-col' => 'due_date',
                'anchor-class' => '',
            ],
            'total' => [
                'label' => trans('main.total'),
                'type' => '',
                'className' => '',
                'data-col' => 'total',
                'anchor-class' => '',
            ],
            'statusText' => [
                'label' => trans('main.status'),
                'type' => '',
                'className' => '',
                'data-col' => 'status',
                'anchor-class' => '',
            ],
            'paid_date' => [
                'label' => trans('main.paid_date'),
                'type' => '',
                'className' => '',
                'data-col' => 'paid_date',
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
    
    public function downloadPDF($id){
        $id = (int) $id;

        $invoiceObj = Invoice::NotDeleted()->find($id);
        if($invoiceObj == null || $invoiceObj->client_id != User::first()->id) {
            return Redirect('404');
        }

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
        $tax = \Helper::calcTax($data['invoice']->total);
        $paymentObj = PaymentInfo::NotDeleted()->where('user_id',$invoiceObj->client_id)->first();
        if($paymentObj){
            $data['paymentObj'] = PaymentInfo::getData($paymentObj);
        }
        $data['qrImage'] = GenerateQrCode::fromArray([
            new Seller($data['companyAddress']->servers), // seller name        
            new TaxNumber($data['companyAddress']->tax_id), // seller tax number
            new InvoiceDate(date('Y-m-d\TH:i:s\Z',strtotime($data['invoice']->due_date))), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
            new InvoiceTotalAmount($data['invoice']->total), // invoice total amount
            new InvoiceTaxAmount($tax) // invoice tax amount
            // TODO :: Support others tags
        ])->render();
        
        $pdf = PDF::loadView('Tenancy.Invoice.Views.invoicePDF',['data'=> (object)$data])
                ->setPaper('a4', 'portrait')
                ->setOption('margin-bottom', '0mm')
                ->setOption('margin-top', '0mm')
                ->setOption('margin-right', '0mm')
                ->setOption('margin-left', '0mm');
        return $pdf->download('invoice #'.($id+10000).'.pdf');
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Invoice::dataList(null,User::first()->id);
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();

        // Fetch Subscription Data
        $membershipObj = Session::get('membership') != null ?  Membership::getData(Membership::getOne(Session::get('membership'))) : [];
        $channelObj = Session::get('channel') != null ?  CentralChannel::getData(CentralChannel::getOne(Session::get('channel'))) : null;
        if($channelObj){
            $channelStatus = ($channelObj->leftDays > 0 && date('Y-m-d') <= $channelObj->end_date) ? 1 : 0;
        }

        $data['subscription'] = (object) [
            'package_name' => $channelObj ? $membershipObj->title : '',
            'channelStatus' => $channelObj ? $channelStatus : '',
            'start_date' => $channelObj ? $channelObj->start_date : '',
            'end_date' => $channelObj ? $channelObj->end_date : '',
            'leftDays' => $channelObj ? $channelObj->leftDays : '',
        ];
        return view('Tenancy.Invoice.Views.index')->with('data', (object) $data);
    }
   
    public function view($id) {
        $id = (int) $id;

        $userObj = Invoice::NotDeleted()->find($id);
        if($userObj == null || $userObj->client_id != User::first()->id) {
            return Redirect('404');
        }
        // dd('here');
        $data['data'] = Invoice::getData($userObj);
        $data['companyAddress'] = (object) [
            'servers' => CentralVariable::getVar('servers'),
            'address' => CentralVariable::getVar('address'),
            'region' => CentralVariable::getVar('region'),
            'city' => CentralVariable::getVar('city'),
            'postal_code' => CentralVariable::getVar('postal_code'),
            'country' => CentralVariable::getVar('country'),
            'tax_id' => $userObj->due_date >= date('Y-m-d',strtotime('2022-05-01')) ? CentralVariable::getVar('tax_id2') : CentralVariable::getVar('tax_id'),
        ];
        $data['designElems'] = $this->getData();
        $data['clients'] = User::NotDeleted()->where('status',1)->where('group_id',0)->get(['name','id']);
        $data['designElems']['mainData']['title'] = trans('main.view') . ' '.trans('main.invoices') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-eye';
        $tax = \Helper::calcTax($data['data']->total);
        $data['qrImage'] = GenerateQrCode::fromArray([
            new Seller($data['companyAddress']->servers), // seller name        
            new TaxNumber($data['companyAddress']->tax_id), // seller tax number
            new InvoiceDate(date('Y-m-d\TH:i:s\Z',strtotime($data['data']->due_date))), 
            new InvoiceTotalAmount($data['data']->total), // invoice total amount
            new InvoiceTaxAmount($tax) // invoice tax amount
        ])->render();
        return view('Tenancy.Invoice.Views.view')->with('data', (object) $data);      
    }
  
    public function checkout($id){
        $id = (int) $id;
        if(!IS_ADMIN){
            return redirect()->to('/dashboard');
        }

        $invoiceObj = Invoice::NotDeleted()->find($id);
        $userObj = User::first();
        if($invoiceObj == null || $invoiceObj->client_id != $userObj->id) {
            return Redirect('404');
        } 
        
        $userObj = User::authenticatedUser();
        $myData  = unserialize($invoiceObj->items);
       
        $testData = [];
        $main = 0;

        $type = 'PayInvoice';
        if(Session::has('invoice_id')){
            $type = 'Renew';
        }

        foreach($myData as $key => $one){
            if($one['type'] == 'membership'){
                $main = 1;
            }
            $start_date = $type == 'Renew' ? date('Y-m-d') : $one['start_date'];
            $one['start_date'] = $start_date;
            $one['end_date'] = $one['duration_type'] == 1 ? date('Y-m-d',strtotime('+1 month',strtotime($start_date))) : date('Y-m-d',strtotime('+1 year',strtotime($start_date)));
            $testData[] = $one;
        }
        
        $invoiceObj->items = serialize($testData);
        $invoiceObj->save();

        $userCredits = 0;
        \Session::put('userCredits',$userCredits);

        $data['userCredits'] = $userCredits;
        $data['paymentInfo'] = $userObj->paymentInfo;
        $data['items'] = $testData;
  
        $subscriptionHelperData = [
            'user_id' => ROOT_ID,
            'tenant_id' => TENANT_ID,
            'global_id' => GLOBAL_ID,
            'cartData' => $testData,
            'type' => $type == 'PayInvoice' ? 'Change' : $type,
            'transaction_id' => null,
            'payment_gateaway' => null,
            'user_credits' => $userCredits,
            'coupon_code' => null,
        ];

        Variable::where('var_key','inv_status')->firstOrCreate(['var_key'=>'inv_status','var_value'=> $type == 'PayInvoice' ? 'Change' : $type]);
        $subscriptionHelperObj = new \SubscriptionHelper;
        $data['invoice_id'] = $subscriptionHelperObj->setInvoice($subscriptionHelperData);
        return view('Tenancy.Profile.Views.cart')->with('data', (object) $data);
    }
}
