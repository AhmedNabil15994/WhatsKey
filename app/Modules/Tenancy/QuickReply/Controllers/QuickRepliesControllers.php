<?php namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DataTables;
use Storage;


class QuickRepliesControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.quickReplies'),
            'url' => 'quickReplies',
            'name' => 'quickReplies',
            'nameOne' => 'quickReply',
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
                'label' => trans('main.title'),
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
                'label' => trans('main.title'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'name_ar',
                'anchor-class' => 'editable',
            ],
            'description_ar' => [
                'label' => trans('main.description'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'description_ar',
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
                'label' => trans('main.title'),
                'specialAttr' => '',
                'required' => true,
            ],
            'description_ar' => [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => trans('main.description'),
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
            $data = Reply::dataList(null,2);
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        $data['disAdd'] = 1;
        $data['disFastEdit'] = 1;
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

        if($dataObj->reply_id){
            $mainWhatsLoopObj = new \OfficialHelper();
            $updateResult = $mainWhatsLoopObj->updateReply([
                'reply_id' => $dataObj->reply_id,
                'message' => $input['description_ar'],
                'shortcut' => $input['name_ar']
            ]);
            $updateResult = $updateResult->json();
            if(!isset($updateResult) || !isset($updateResult['data']) || !isset($updateResult['data']['id'])){
                Session::flash('error', $updateResult['status']['message']);
                return \Redirect::back()->withInput();
            }
        }

        $dataObj->channel = Session::get('channelCode');
        $dataObj->name_ar = $input['name_ar'];
        $dataObj->name_en = $input['name_ar'];
        $dataObj->description_ar = $input['description_ar'];
        $dataObj->description_en = $input['description_ar'];
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();
        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = Reply::getOne($id);
        if($dataObj && $dataObj->reply_id){
            $mainWhatsLoopObj = new \OfficialHelper();
            $updateResult = $mainWhatsLoopObj->deleteReply([
                'reply_id' => $dataObj->reply_id,
            ]);
            return \Helper::globalDelete($dataObj);
        }
    }
}
