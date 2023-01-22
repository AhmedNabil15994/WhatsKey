<?php namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DataTables;


class FeatureControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.features'),
            'url' => 'features',
            'name' => 'features',
            'nameOne' => 'feature',
            'modelName' => 'Feature',
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
        ];

        $message = [
            'title_ar.required' => trans('main.titleArValidate'),
            'title_en.required' => trans('main.titleEnValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Feature::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.User.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Feature::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Feature::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.features') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        return view('Central.Feature.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $dataObj = Feature::NotDeleted()->find($id);
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
        $dataObj->status = $input['status'];
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.features') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        return view('Central.Feature.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $dataObj = new Feature;
        $dataObj->title_ar = $input['title_ar'];
        $dataObj->title_en = $input['title_en'];
        $dataObj->sort = Feature::newSortIndex();
        $dataObj->status = $input['status'];
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = Feature::getOne($id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = Feature::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
}
