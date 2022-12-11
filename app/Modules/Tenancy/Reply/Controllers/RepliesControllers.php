<?php namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DataTables;
use Storage;


class RepliesControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.replies'),
            'url' => 'replies',
            'name' => 'replies',
            'nameOne' => 'reply',
            'modelName' => 'Reply',
            'icon' => ' far fa-comment-alt',
            'sortName' => 'name_'.LANGUAGE_PREF,
            'addOne' => trans('main.newReply'),
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'name_ar' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '1',
                'label' => trans('main.titleAr'),
            ],
            'name_en' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '2',
                'label' => trans('main.titleEn'),
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
                'label' => trans('main.titleAr'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'name_ar',
                'anchor-class' => 'editable',
            ],
            'name_en' => [
                'label' => trans('main.titleEn'),
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
                'label' => trans('main.titleAr'),
                'specialAttr' => '',
                'required' => true,
            ],
            'name_en' => [
                'type' => 'text',
                'class' => 'form-control',
                'label' => trans('main.titleEn'),
                'specialAttr' => '',
            ],
            'description_ar' => [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => trans('main.descriptionAr'),
                'specialAttr' => '',
            ],
            'description_en' => [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => trans('main.descriptionEn'),
                'specialAttr' => '',
            ],
            
        ];
        return $data;
    }

    protected function validateInsertObject($input){
        $rules = [
            'name_ar' => 'required',
            // 'name_en' => 'required',
        ];

        $message = [
            'name_ar.required' => trans('main.titleArValidate'),
            // 'name_en.required' => trans('main.titleEnValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Reply::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Reply::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Reply::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.replies') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        return view('Tenancy.Template.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        // dd($input);
        $dataObj = Reply::NotDeleted()->find($id);
        if($dataObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $dataObj->channel = Session::get('channelCode');
        $dataObj->name_ar = $input['name_ar'];
        $dataObj->name_en = $input['name_en'];
        $dataObj->description_ar = $input['description_ar'];
        $dataObj->description_en = $input['description_en'];
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.replies') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        return view('Tenancy.Template.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        

        $dataObj = new Reply;
        $dataObj->channel = Session::get('channelCode');
        $dataObj->name_ar = $input['name_ar'];
        $dataObj->name_en = !empty($input['name_en']) ? $input['name_en'] : ' ';
        $dataObj->description_ar = $input['description_ar'];
        $dataObj->description_en = $input['description_en'];
        $dataObj->sort = Reply::newSortIndex();
        $dataObj->status = 1;
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = Reply::getOne($id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = Reply::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
}
