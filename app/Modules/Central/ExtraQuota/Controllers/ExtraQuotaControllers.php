<?php namespace App\Http\Controllers;

use App\Models\ExtraQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DataTables;


class ExtraQuotaControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.extraQuotas'),
            'url' => 'extraQuotas',
            'name' => 'extraQuotas',
            'nameOne' => 'extraQuota',
            'modelName' => 'ExtraQuota',
            'icon' => ' fas fa-star',
            'sortName' => 'title_'.LANGUAGE_PREF,
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '0',
                'label' => trans('main.id'),
                'specialAttr' => '',
            ],
            'extra_count' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '1',
                'label' => trans('main.extra_count'),
                'specialAttr' => '',
            ],
            'extra_type' => [
                'type' => 'select',
                'class' => 'form-control select2 m-input',
                'index' => '2',
                'options' => [
                    ['id'=> 1 , 'title' => trans('main.message')],
                    ['id'=> 2 , 'title' => trans('main.employee')],
                    ['id'=> 3 , 'title' => trans('main.gigaB')],
                ],
                'label' => trans('main.extra_type'),
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
            'extra_count' => [
                'label' => trans('main.extra_count'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'extra_count',
                'anchor-class' => 'editable',
            ],
            'extraTypeText' => [
                'label' => trans('main.extra_type'),
                'type' => '',
                'className' => 'edits selects',
                'data-col' => 'extra_type',
                'anchor-class' => 'editable',
            ],
            'monthly_price' => [
                'label' => trans('main.monthly_price'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'monthly_price',
                'anchor-class' => 'editable',
            ],
            'monthly_after_vat' => [
                'label' => trans('main.monthly_after_vat'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'monthly_after_vat',
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
        return $data;
    }

    protected function validateInsertObject($input){
        $rules = [
            'extra_count' => 'required',
            'extra_type' => 'required',
            'monthly_price' => 'required',
            'monthly_after_vat' => 'required',
        ];

        $message = [
            'extra_count.required' => trans('main.extraCountValidate'),
            'extra_type.required' => trans('main.extraTypeValidate'),
            'monthly_price.required' => trans('main.monthlyPriceValidate'),
            'monthly_after_vat.required' => trans('main.monthlyVatValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = ExtraQuota::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.User.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = ExtraQuota::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = ExtraQuota::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.extraQuotas') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        return view('Central.ExtraQuota.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $dataObj = ExtraQuota::NotDeleted()->find($id);
        if($dataObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $dataObj->extra_count = $input['extra_count'];
        $dataObj->extra_type = $input['extra_type'];
        $dataObj->monthly_price = $input['monthly_price'];
        $dataObj->monthly_after_vat = $input['monthly_after_vat'];
        $dataObj->status = $input['status'];
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.extraQuotas') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        return view('Central.ExtraQuota.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        
        $dataObj = new ExtraQuota;
        $dataObj->extra_count = $input['extra_count'];
        $dataObj->extra_type = $input['extra_type'];
        $dataObj->monthly_price = $input['monthly_price'];
        $dataObj->monthly_after_vat = $input['monthly_after_vat'];
        $dataObj->sort = ExtraQuota::newSortIndex();
        $dataObj->status = $input['status'];
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/add');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = ExtraQuota::getOne($id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = ExtraQuota::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
}
