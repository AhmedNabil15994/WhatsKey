<?php namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\CentralUser;
use App\Models\UserAddon;
use App\Models\UserExtraQuota;
use App\Models\ExtraQuota;
use App\Models\Tenant;
use App\Models\PaymentInfo;
use App\Models\Domain;
use App\Models\CentralChannel;
use App\Models\CentralVariable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\BankTransfer;
use App\Models\Coupon;
use DataTables;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;
use PDF;



class InvoiceControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.invoices'),
            'url' => 'invoices',
            'name' => 'invoices',
            'nameOne' => 'invoice',
            'modelName' => 'Invoice',
            'icon' => 'fas fa-file-invoice',
            'sortName' => 'id',
        ];
        $data['clients'] = CentralUser::NotDeleted()->where('status',1)->where('group_id',0)->get(['name','id']);
        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '0',
                'label' => trans('main.id'),
                'specialAttr' => '',
            ],
            'client_id' => [
                'type' => 'select',
                'class' => 'form-control m-input',
                'index' => '1',
                'label' => trans('main.client'),
                'options' => $data['clients'],
            ],
            'due_date' => [
                'type' => 'text',
                'class' => 'form-control m-input datepicker',
                'index' => '2',
                'label' => trans('main.due_date'),
                'specialAttr' => '',
            ],
            'status' => [
                'type' => 'select',
                'class' => 'form-control m-input',
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
            'created_at' => [
                'label' => trans('main.created_at'),
                'type' => '',
                'className' => '',
                'data-col' => 'created_at',
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
        if($invoiceObj == null) {
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

        $userObj = CentralUser::NotDeleted()->find($invoiceObj->client_id);
        $userObjData = CentralUser::getData($userObj);
        $domainObj = Domain::where('domain',$userObjData->domain)->first();
        $tenant = Tenant::find($domainObj->tenant_id);
        tenancy()->initialize($tenant);
        $paymentObj = PaymentInfo::where('user_id',$userObj->id)->first();
        if($paymentObj) $data['paymentObj'] = PaymentInfo::getData($paymentObj);
        tenancy()->end($tenant);
        $data['qrImage'] = GenerateQrCode::fromArray([
            new Seller($data['companyAddress']->servers), // seller name        
            new TaxNumber($data['companyAddress']->tax_id), // seller tax number
            new InvoiceDate(date('Y-m-d\TH:i:s\Z',strtotime($data['invoice']->due_date))), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
            new InvoiceTotalAmount($data['invoice']->total), // invoice total amount
            new InvoiceTaxAmount($tax) // invoice tax amount
            // TODO :: Support others tags
        ])->render();
        $pdf = PDF::loadView('Central.Invoice.Views.invoicePDF',['data'=> (object)$data])
                ->setPaper('a4', 'portrait')
                ->setOption('margin-bottom', '0mm')
                ->setOption('margin-top', '0mm')
                ->setOption('margin-right', '0mm')
                ->setOption('header-html', 'testing')
                ->setOption('margin-left', '0mm');
        return $pdf->download('invoice #'.($id+10000).'.pdf');
    }
    
    public function index(Request $request) {
        if($request->ajax()){
            $data = Invoice::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.User.Views.index')->with('data', (object) $data);
    }

    public function view($id) {
        $id = (int) $id;

        $invoiceObj = Invoice::NotDeleted()->find($id);
        if($invoiceObj == null) {
            return Redirect('404');
        }

        $userObj = CentralUser::NotDeleted()->find($invoiceObj->client_id);
        $userObjData = CentralUser::getData($userObj);
        $domainObj = Domain::where('domain',$userObjData->domain)->first();
        $tenant = Tenant::find($domainObj->tenant_id);
        tenancy()->initialize($tenant);
        $paymentObj = PaymentInfo::where('user_id',$userObj->id)->first();
        $data['paymentInfo'] = $paymentObj != null ? PaymentInfo::getData($paymentObj) : [];
        tenancy()->end($tenant);

        $data['data'] = Invoice::getData($invoiceObj);
        $data['companyAddress'] = (object) [
            'servers' => CentralVariable::getVar('servers'),
            'address' => CentralVariable::getVar('address'),
            'region' => CentralVariable::getVar('region'),
            'city' => CentralVariable::getVar('city'),
            'postal_code' => CentralVariable::getVar('postal_code'),
            'country' => CentralVariable::getVar('country'),
            'tax_id' => $invoiceObj->due_date >= date('Y-m-d',strtotime('2022-05-01')) ? CentralVariable::getVar('tax_id2') : CentralVariable::getVar('tax_id'),
        ];
        $data['designElems'] = $this->getData();
        $data['clients'] = $data['designElems']['clients'];
        $data['designElems']['mainData']['title'] = trans('main.view') . ' '.trans('main.invoices') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-eye';
        return view('Central.Invoice.Views.view')->with('data', (object) $data);      
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = Invoice::getOne($id);
        BankTransfer::where('invoice_id',$id)->update(['deleted_at'=>DATE_TIME,'deleted_by'=>USER_ID]);
        return \Helper::globalDelete($dataObj);
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['clients'] = $data['designElems']['clients'];
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.invoices') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['coupons'] = Coupon::availableCoupons2();
        return view('Central.Invoice.Views.add')->with('data', (object) $data);      
    }
}
