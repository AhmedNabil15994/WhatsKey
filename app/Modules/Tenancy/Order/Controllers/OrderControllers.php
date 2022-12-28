<?php namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DataTables;


class OrderControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.orders'),
            'url' => 'orders',
            'name' => 'orders',
            'nameOne' => 'order',
            'modelName' => 'Order',
            'icon' => 'la la-shopping-cart',
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
                'specialAttr' => '',
            ],
            'order_id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '1',
                'label' => trans('main.order_id'),
                'specialAttr' => '',
            ],
            'order_token' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '2',
                'label' => trans('main.order_token'),
                'specialAttr' => '',
            ],
            'title' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '4',
                'label' => trans('main.title'),
                'specialAttr' => '',
            ],
            'itemCount' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '5',
                'label' => trans('main.productsCount'),
                'specialAttr' => '',
            ],
            'price' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '6',
                'label' => trans('main.price'),
                'specialAttr' => '',
            ],
            'chatId' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '7',
                'label' => trans('main.phone'),
                'specialAttr' => '',
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
            'order_id' => [
                'label' => trans('main.order_id'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'order_id',
                'anchor-class' => 'editable',
            ],
            'token' => [
                'label' => trans('main.order_token'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'token',
                'anchor-class' => 'editable',
            ],
            'title' => [
                'label' => trans('main.title'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'title',
                'anchor-class' => 'editable',
            ],
            'itemCount' => [
                'label' => trans('main.productsCount'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'itemCount',
                'anchor-class' => 'editable',
            ],
            'price' => [
                'label' => trans('main.price'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'price',
                'anchor-class' => 'editable',
            ],
            'currency' => [
                'label' => trans('main.currency'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'currency',
                'anchor-class' => 'editable',
            ],
            'chatId' => [
                'label' => trans('main.phone'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'chatId',
                'anchor-class' => 'editable',
            ],
            'actions' => [
                'label' => trans('main.actions'),
                'type' => '',
                'className' => '',
                'data-col' => '',
                'anchor-class' => '',
            ],
        ];

        $data['modelData'] = [];
        return $data;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Order::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function view($id) {
        $id = (int) $id;

        $userObj = Order::find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Order::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.view') . ' '.trans('main.orders') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-eye';
        return view('Tenancy.Order.Views.view')->with('data', (object) $data);      
    }
}