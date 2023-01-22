<?php namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DataTables;


class CouponControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.coupons'),
            'url' => 'coupons',
            'name' => 'coupons',
            'nameOne' => 'coupon',
            'modelName' => 'Coupon',
            'icon' => ' fas fa-star',
            'sortName' => 'code',
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '0',
                'label' => trans('main.id'),
                'specialAttr' => '',
            ],
            'code' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '1',
                'label' => trans('main.coupon_code'),
                'specialAttr' => '',
            ],
            'discount_type' => [
                'type' => 'select',
                'class' => 'form-control',
                'index' => '2',
                'options' => [
                    ['id' => 1 , 'title' => trans('main.discount_type_1')],
                    ['id' => 2 , 'title' => trans('main.discount_type_2')],
                ],
                'label' => trans('main.discount_type'),
                'specialAttr' => ' data-toggle="select2"',
            ],
            'discount_value' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '3',
                'label' => trans('main.discount_value'),
                'specialAttr' => '',
            ],
            'valid_type' => [
                'type' => 'select',
                'class' => 'form-control',
                'index' => '4',
                'options' => [
                    ['id' => 1 , 'title' => trans('main.valid_type_1')],
                    ['id' => 2 , 'title' => trans('main.valid_type_2')],
                ],
                'label' => trans('main.valid_type'),
                'specialAttr' => ' data-toggle="select2"',
            ],
            'valid_value' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '5',
                'label' => trans('main.valid_value'),
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
            'code' => [
                'label' => trans('main.coupon_code'),
                'type' => '',
                'className' => '',
                'data-col' => 'code',
                'anchor-class' => '',
            ],
            'discount_typeText' => [
                'label' => trans('main.discount_type'),
                'type' => '',
                'className' => '',
                'data-col' => 'discount_type',
                'anchor-class' => '',
            ],
            'discount_value' => [
                'label' => trans('main.discount_value'),
                'type' => '',
                'className' => '',
                'data-col' => 'discount_value',
                'anchor-class' => '',
            ],
            'valid_typeText' => [
                'label' => trans('main.valid_type'),
                'type' => '',
                'className' => '',
                'data-col' => 'valid_type',
                'anchor-class' => '',
            ],
            'valid_value' => [
                'label' => trans('main.valid_value'),
                'type' => '',
                'className' => '',
                'data-col' => 'valid_value',
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
            'code' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'valid_type' => 'required',
            'valid_value' => 'required',
        ];

        $message = [
            'code.required' => trans('main.codeValidate'),
            'discount_type.required' => trans('main.discountTypeValidate'),
            'discount_value.required' => trans('main.discountValueValidate'),
            'valid_type.required' => trans('main.validTypeValidate'),
            'valid_value.required' => trans('main.validValueVatValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Coupon::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.User.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Coupon::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Coupon::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.coupons') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        return view('Central.Coupon.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $dataObj = Coupon::NotDeleted()->find($id);
        if($dataObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $checkObj = Coupon::checkCouponByCode($input['code'],$id);
        if($checkObj != null){
            \Session::flash('error', trans('main.codeFound'));
            return redirect()->back()->withInput();
        }

        $dataObj->code = $input['code'];
        $dataObj->discount_type = $input['discount_type'];
        $dataObj->discount_value = $input['discount_value'];
        $dataObj->valid_type = $input['valid_type'];
        $dataObj->valid_value = $input['valid_value'];
        $dataObj->status = $input['status'];
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.coupons') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        return view('Central.Coupon.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        
        $checkObj = Coupon::checkCouponByCode($input['code']);
        if($checkObj != null){
            \Session::flash('error', trans('main.codeFound'));
            return redirect()->back()->withInput();
        }

        $dataObj = new Coupon;
        $dataObj->code = $input['code'];
        $dataObj->discount_type = $input['discount_type'];
        $dataObj->discount_value = $input['discount_value'];
        $dataObj->valid_type = $input['valid_type'];
        $dataObj->valid_value = $input['valid_value'];
        $dataObj->sort = Coupon::newSortIndex();
        $dataObj->status = $input['status'];
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = Coupon::getOne($id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = Coupon::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
}
