<?php namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DataTables;


class GroupsControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.groups'),
            'url' => 'groups',
            'name' => 'groups',
            'nameOne' => 'group',
            'modelName' => 'Group',
            'icon' => 'fas fa-layer-group',
            'sortName' => 'name_'.LANGUAGE_PREF,
            'addOne' => trans('main.newUserGroup'),
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
                'specialAttr' => '',
            ],
            'name_ar' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '1',
                'label' => trans('main.name_ar'),
                'specialAttr' => '',
            ],
            'name_en' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
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
            'name_ar' => [
                'label' => trans('main.name_ar'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'name_ar',
                'anchor-class' => 'editable',
            ],
            'name_en' => [
                'label' => trans('main.name_en'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'name_en',
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

        $data['modelData'] = [
            'name_ar' => [
                'type' => 'text',
                'class' => 'form-control',
                'label' => trans('main.name_ar'),
                'specialAttr' => '',
                'required' => true,
            ],
            'name_en' => [
                'type' => 'text',
                'class' => 'form-control',
                'label' => trans('main.name_en'),
                'specialAttr' => '',
                'required' => true,
            ],
        ];
        return $data;
    }

    protected function validateInsertObject($input){
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
        ];

        $message = [
            'name_ar.required' => trans('main.nameArValidate'),
            'name_en.required' => trans('main.nameEnValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Group::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Group::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Group::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.groups') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['permissions'] = \Helper::getPermissions(true);
        return view('Tenancy.Template.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $dataObj = Group::NotDeleted()->find($id);
        if($dataObj == null || $id == 1) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $dataObj->name_ar = $input['name_ar'];
        $dataObj->name_en = $input['name_en'];
        $dataObj->rules = isset($input['permission']) && !empty($input['permission']) ? serialize($input['permission']) : '';
        $dataObj->status = $input['status'];
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.groups') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['permissions'] = \Helper::getPermissions(true);
        return view('Tenancy.Template.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        

        $dataObj = new Group;
        $dataObj->name_ar = $input['name_ar'];
        $dataObj->name_en = $input['name_en'];
        $dataObj->rules = isset($input['permission']) && !empty($input['permission']) ? serialize($input['permission']) : '';
        $dataObj->sort = Group::newSortIndex();
        $dataObj->status = isset($input['status']) && !empty($input['status']) ? $input['status'] : 1;
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = Group::getOne($id);
        if($id == 1){
            return \TraitsFunc::ErrorMessage(trans('main.notDeleted'));
        }
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = Group::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
}