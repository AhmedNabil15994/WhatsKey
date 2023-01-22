<?php namespace App\Http\Controllers;

use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DataTables;


class NotificationTemplateControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.notificationTemplates'),
            'url' => 'notificationTemplates',
            'name' => 'notificationTemplates',
            'nameOne' => 'notificationTemplate',
            'modelName' => 'NotificationTemplate',
            'icon' => 'far fa-id-card',
            'sortName' => 'title_ar',
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
            'type' => [
                'type' => 'select',
                'class' => 'form-control m-input',
                'index' => '1',
                'label' => trans('main.templateType'),
                'options' => [
                    ['id' => 1 , 'title' => trans('main.whatsAppMessage')],
                    ['id' => 2 , 'title' => trans('main.emailMessage')],
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
            'typeText' => [
                'label' => trans('main.templateType'),
                'type' => '',
                'className' => '',
                'data-col' => 'typeText',
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
                'className' => '',
                'data-col' => 'title_en',
                'anchor-class' => '',
            ],
            'content_ar' => [
                'label' => trans('main.content_ar'),
                'type' => '',
                'className' => '',
                'data-col' => 'content_ar',
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
            'content_ar' => 'required',
        ];

        $message = [
            'title_ar.required' => trans('main.titleArValidate'),
            'content_ar.required' => trans('main.contentArValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = NotificationTemplate::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.User.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = NotificationTemplate::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = NotificationTemplate::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.notificationTemplates') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        return view('Central.NotificationTemplate.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Request::all();
        
        $dataObj = NotificationTemplate::getOneById($id);
        if($dataObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }


        $dataObj->title_ar = $input['title_ar'];
        $dataObj->content_ar = $input['content_ar'];
        $dataObj->status = $input['status'];
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = NotificationTemplate::getOneById($id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = NotificationTemplate::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
    
}
