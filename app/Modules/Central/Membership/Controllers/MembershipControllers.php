<?php namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DataTables;


class MembershipControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.memberships'),
            'url' => 'memberships',
            'name' => 'memberships',
            'nameOne' => 'membership',
            'modelName' => 'Membership',
            'icon' => 'far fa-id-card',
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
            'title_ar' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '1',
                'label' => trans('main.name_ar'),
                'specialAttr' => '',
            ],
            'title_en' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '2',
                'label' => trans('main.name_en'),
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
            'title_ar' => [
                'label' => trans('main.name_ar'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'title_ar',
                'anchor-class' => 'editable',
            ],
            'title_en' => [
                'label' => trans('main.name_en'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'title_en',
                'anchor-class' => 'editable',
            ],
            'monthly_price' => [
                'label' => trans('main.monthly_price'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'monthly_price',
                'anchor-class' => 'editable',
            ],
            'annual_price' => [
                'label' => trans('main.annual_price'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'annual_price',
                'anchor-class' => 'editable',
            ],
            'monthly_after_vat' => [
                'label' => trans('main.monthly_after_vat'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'monthly_after_vat',
                'anchor-class' => 'editable',
            ],
            'annual_after_vat' => [
                'label' => trans('main.annual_after_vat'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'annual_after_vat',
                'anchor-class' => 'editable',
            ],
            'featruesText' => [
                'label' => trans('main.features'),
                'type' => '',
                'className' => '',
                'data-col' => 'featruesText',
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
            'title_ar' => 'required',
            'title_en' => 'required',
            'monthly_price' => 'required',
            'annual_price' => 'required',
            'monthly_after_vat' => 'required',
            'annual_after_vat' => 'required',
        ];

        $message = [
            'title_ar.required' => trans('main.titleArValidate'),
            'title_en.required' => trans('main.titleEnValidate'),
            'monthly_price.required' => trans('main.monthlyPriceValidate'),
            'annual_price.required' => trans('main.annualPriceValidate'),
            'monthly_after_vat.required' => trans('main.monthlyVatValidate'),
            'annual_after_vat.required' => trans('main.annualVatValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Membership::dataList();
            return Datatables::of($data['data'])->rawColumns(['featruesText'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.User.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Membership::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Membership::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.memberships') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['features'] = Feature::dataList(1)['data'];
        return view('Central.Membership.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $dataObj = Membership::NotDeleted()->find($id);
        if($dataObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }


        $dataObj->title_ar = $input['title_ar'];
        $dataObj->title_en = $input['title_en'];
        $dataObj->monthly_price = $input['monthly_price'];
        $dataObj->annual_price = $input['annual_price'];
        $dataObj->monthly_after_vat = $input['monthly_after_vat'];
        $dataObj->annual_after_vat = $input['annual_after_vat'];
        if(isset($input['features']) && !empty($input['features'])){
            $dataObj->features = serialize($input['features']);
        }
        $dataObj->status = $input['status'];
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.memberships') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['features'] = Feature::dataList(1)['data'];
        return view('Central.Membership.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        
        $permissionsArr = [];
        foreach ($input as $key => $oneItem) {
            if(strpos($key, 'permission') !== false){
                $morePermission = str_replace('permission','', $key);
                if($oneItem == 'on'){
                    $permissionsArr[] = $morePermission;
                }
            }
        }

        $dataObj = new Membership;
        $dataObj->title_ar = $input['title_ar'];
        $dataObj->title_en = $input['title_en'];
        $dataObj->monthly_price = $input['monthly_price'];
        $dataObj->annual_price = $input['annual_price'];
        $dataObj->monthly_after_vat = $input['monthly_after_vat'];
        $dataObj->annual_after_vat = $input['annual_after_vat'];
        if(isset($input['features']) && !empty($input['features'])){
            $dataObj->features = serialize($input['features']);
        }
        // $dataObj->rules = serialize($permissionsArr);
        $dataObj->sort = Membership::newSortIndex();
        $dataObj->status = $input['status'];
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = Membership::getOne($id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = Membership::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
}
