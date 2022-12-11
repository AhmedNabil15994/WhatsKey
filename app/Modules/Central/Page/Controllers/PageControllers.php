<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Page;
use App\Models\Photo;
use App\Models\CentralVariable;
use App\Models\CentralUser;
use Illuminate\Http\Request;
use DataTables;


class PageControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.pages'),
            'url' => 'pages',
            'name' => 'pages',
            'nameOne' => 'page',
            'modelName' => 'Page',
            'icon' => 'far fa-file',
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

    protected function validateObject($input){
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

    public function index(Request $request){   
        if($request->ajax()){
            $data = Page::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.User.Views.index')->with('data', (object) $data);
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.pages') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        return view('Central.Page.Views.add')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $menuObj = Page::find($id);
        if($menuObj == null) {
            return Redirect('404');
        }

        $data['data'] = Page::getData($menuObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.pages') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        return view('Central.Page.Views.edit')->with('data', (object) $data);          
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = Page::find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateObject($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $menuObj->title_ar = $input['title_ar'];
        $menuObj->title_en = $input['title_en'];
        $menuObj->status = $input['status'];
        $menuObj->updated_at = DATE_TIME;
        $menuObj->updated_by = USER_ID;
        $menuObj->save();

        \Session::flash('success', "تنبيه! تم التعديل بنجاح");
        return \Redirect::back()->withInput();
    }
    
    public function create() {
        $input = \Request::all();
        
        $validate = $this->validateObject($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        
        $menuObj = new Page;
        $menuObj->title_ar = $input['title_ar'];
        $menuObj->title_en = $input['title_en'];
        $menuObj->status = $input['status'];
        $menuObj->sort = Page::newSortIndex();
        $menuObj->created_at = DATE_TIME;
        $menuObj->created_by = USER_ID;
        $menuObj->save();

        \Session::flash('success', "تنبيه! تم الحفظ بنجاح");
        return redirect()->to('pages/');
    }

    public function delete($id) {
        $id = (int) $id;
        $menuObj = Page::getOne($id);
        return \Helper::globalDelete($menuObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $menuObj = Page::find($item[0]);
            $menuObj->$col = $item[2];
            $menuObj->updated_at = DATE_TIME;
            $menuObj->updated_by = USER_ID;
            $menuObj->save();
        }

        return \TraitsFunc::SuccessResponse('تم التعديل بنجاح');
    }

    public function notifications(){
        $data['designElems']['mainData'] = [
            'title' => trans('main.notifications'),
            'url' => 'pages/notifications',
            'name' => 'notifications',
            'nameOne' => 'notification',
            'modelName' => 'Variable',
            'icon' => 'far fa-file',
            'sortName' => 'title_'.LANGUAGE_PREF,
        ];
        $varObj = CentralVariable::getVar('NOTIFICATION');
        if($varObj){
            $varObj = json_decode($varObj);
        }
        $data['data'] = $varObj;
        return view('Central.Page.Views.notifications')->with('data', (object) $data);
    }

    public function createNotification(){
        $input = \Request::all();
        $rules = [
            'title_ar' => 'required',
            'title_en' => 'required',
            'description_en' => 'required',
            'description_ar' => 'required',
        ];

        $message = [
            'title_ar.required' => trans('main.titleArValidate'),
            'title_en.required' => trans('main.titleEnValidate'),
            'description_en.required' => trans('main.descriptionEnValidate'),
            'description_ar.required' => trans('main.descriptionArValidate'),
        ];
        $validate = \Validator::make($input, $rules, $message);

        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $sendPhones = 0;
        if(isset($input['whatsAppMessage']) && $input['whatsAppMessage'] == 'on'){
            $sendPhones = 1;
            $input['whatsAppMessage'] = 1;
        }

        $varObj = CentralVariable::where('var_key','NOTIFICATION')->first();
        if(!$varObj){
            $varObj = new CentralVariable;
            $varObj->var_key = 'NOTIFICATION';
            $varObj->save();
        }

        unset($input["_token"]);
        $varObj->var_value = json_encode($input);
        $varObj->save();

        if($sendPhones){
            $users = CentralUser::NotDeleted()->where([
                ['status',1],
                ['group_id',0]
            ])->pluck('phone');
            $users = reset($users);

            $channelObj = \DB::connection('main')->table('channels')->where('deleted_by',null)->orderBy('id','ASC')->first();
            $whatsLoopObj =  new \OfficialHelper($channelObj->id,$channelObj->token);
            $sendBulk = $whatsLoopObj->sendBulkText([
                'phones' => $users,
                'body' => $input['description_'.LANGUAGE_PREF],
                'interval' => 3
            ]);
        }

        \Session::flash('success', "تنبيه! تم الحفظ بنجاح");
        return redirect()->to('pages/notifications');
    }

}
