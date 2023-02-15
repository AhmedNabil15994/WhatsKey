<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\TemplateMsg;
use App\Models\BotPlus;
use App\Models\Bot;
use App\Models\UserExtraQuota;
use App\Models\Template;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\UserAddon;
use DataTables;
use Storage;
use Redirect;

class TemplateMsgControllers extends Controller {

    use \TraitsFunc;
    public function getData(){
        $messageTypes=[
            [
                'id'=> '1',
                'title' => trans('main.equal'),
            ],
            [
                'id'=> '2',
                'title' => trans('main.part'),
            ],
        ];

        $data['mainData'] = [
            'title' => trans('main.templateMsg'),
            'url' => 'templateMsg',
            'name' => 'templates-messages',
            'nameOne' => 'template-message',
            'modelName' => 'TemplateMsg',
            'icon' => 'fas fa-layer',
            'sortName' => 'message',
            'addOne' => trans('main.newTemplate'),
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
                'specialAttr' => '',
            ],
            'message_type' => [
                'type' => 'select',
                'class' => 'form-control',
                'index' => '',
                'options' => $messageTypes,
                'label' => trans('main.messageType'),
            ],
            'message' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '3',
                'label' => trans('main.clientMessage'),
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
            'message_type_text' => [
                'label' => trans('main.messageType'),
                'type' => '',
                'className' => '',
                'data-col' => 'message_type',
                'anchor-class' => '',
            ],
            'message' => [
                'label' => trans('main.clientMessage'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'message',
                'anchor-class' => 'editable',
            ],
            'buttons' => [
                'label' => trans('main.buttons'),
                'type' => '',
                'className' => '',
                'data-col' => 'buttons',
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
            'message_type' => 'required',
            'message' => 'required',
            'title' => 'required',
            'body' => 'required',
            'footer' => 'required',
            'buttons' => 'required',
        ];

        $message = [
            'message_type.required' => trans('main.messageTypeValidate'),
            'message.required' => trans('main.messageValidate'),
            'title.required' => trans('main.titleValidate'),
            'body.required' => trans('main.bodyValidate'),
            'footer.required' => trans('main.footerValidate'),
            'buttons.required' => trans('main.buttonsValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);
        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = TemplateMsg::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;
        $checkAvail = UserAddon::checkUserAvailability('BotPlus');
        $dataObj = TemplateMsg::find($id);
        if($dataObj == null || !$checkAvail) {
            return Redirect('404');
        }

        $checkAvailBot = UserAddon::checkUserAvailability('Bot');
        $checkAvailBotPlus = UserAddon::checkUserAvailability('BotPlus');

        $data['data'] = TemplateMsg::getData($dataObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.templateMsg') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['bots'] = $checkAvailBot ? Bot::dataList(1)['data'] : [];
        $data['botPlus'] = $checkAvailBotPlus ? BotPlus::dataList(1)['data'] : [];
        $data['templates'] = Template::dataList(1)['data'];
        return view('Tenancy.TemplateMsg.Views.edit')->with('data', (object) $data);      
    }

    public function copy($id) {
        $id = (int) $id;

        $dataObj = TemplateMsg::find($id);
        $checkAvail = UserAddon::checkUserAvailability('BotPlus');
        if($dataObj == null || !$checkAvail) {
            return Redirect('404');
        }

        $newDataObj = $dataObj->replicate();
        $newDataObj->save();
        return Redirect::to('/templateMsg/edit/'.$newDataObj->id);      
    }

    public function changeStatus($id) {
        $id = (int) $id;

        $dataObj = TemplateMsg::find($id);
        $checkAvail = UserAddon::checkUserAvailability('BotPlus');
        if($dataObj == null || !$checkAvail) {
            return Redirect('404');
        }

        $dataObj->status = $dataObj->status == 1 ? 0 : 1;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return Redirect::to('/templateMsg/');      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $botObj = TemplateMsg::find($id);
        if($botObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $myData = [];
        for ($i = 0; $i < $input['buttons']; $i++) {
            if(!isset($input['btn_text_'.($i+1)]) || empty($input['btn_text_'.($i+1)]) || $input['btn_text_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidText',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if(!isset($input['btn_type_'.($i+1)]) || empty($input['btn_type_'.($i+1)]) || $input['btn_type_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidButtonType',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if(!isset($input['btn_reply_type_'.($i+1)]) || empty($input['btn_reply_type_'.($i+1)]) || $input['btn_reply_type_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidType',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $replyType = (int)$input['btn_reply_type_'.($i+1)];
            if($replyType == 1 && ( !isset($input['btn_reply_'.($i+1)]) || empty($input['btn_reply_'.($i+1)]) ) && (!isset($input['url_'.($i+1)]) && !isset($input['contact_'.($i+1)])) ){
                Session::flash('error', trans('main.invalidReply',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if($replyType == 2 && ( !isset($input['btn_msg_'.($i+1)]) || empty($input['btn_msg_'.($i+1)]) ) && (!isset($input['url_'.($i+1)]) && !isset($input['contact_'.($i+1)]))){
                Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $modelType = '';
            if($replyType == 2 && ( !isset($input['btn_msg_type_'.($i+1)]) || empty($input['btn_msg_type_'.($i+1)]) ) && (!isset($input['url_'.($i+1)]) && !isset($input['contact_'.($i+1)])) ){
                Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $modelType = $input['btn_msg_type_'.($i+1)];
            $modelName = $modelType != null && $replyType >= 2 ?  ((int)$modelType == 1 ? '\App\Models\Bot' : '\App\Models\BotPlus')  : '';
            $msg = $replyType == 1 ? $input['btn_reply_'.($i+1)] : '';

            if($modelName != '' && $msg == ''){
                $dataObj = $modelName::find($input['btn_msg_'.($i+1)]);
                if($dataObj){
                    $msg = $dataObj->id;
                }
            }
            if($modelName == '' && $msg == ''){
                $modelType= '';
                $msg = isset($input['url_'.($i+1)]) ? $input['url_'.($i+1)] : '';
                if($msg == ''){
                    $msg = isset($input['contact_'.($i+1)]) ? $input['contact_'.($i+1)] : '';
                }
            }

            $myData[] = [
                'id' => $i + 1,
                'text' => $input['btn_text_'.($i+1)],
                'button_type' => $input['btn_type_'.($i+1)],
                'reply_type' => $replyType,
                'msg_type' => $modelType,
                'model_name' => $modelName,
                'msg' => $msg,
            ];
        }

        $botObj->message_type = $input['message_type'];
        $botObj->message = $input['message'];
        $botObj->title = isset($input['title']) ? $input['title'] : '';
        $botObj->image = '';        
        $botObj->body = $input['body'];
        $botObj->footer = $input['footer'];
        $botObj->buttons = $input['buttons'];
        $botObj->buttonsData = serialize($myData);
        $botObj->updated_at = DATE_TIME;
        $botObj->updated_by = USER_ID;
        $botObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $checkAvail = UserAddon::checkUserAvailability('BotPlus');
        if(!$checkAvail){
            return redirect(404);
        }

        $checkAvailBot = UserAddon::checkUserAvailability('Bot');
        $checkAvailBotPlus = UserAddon::checkUserAvailability('BotPlus');

        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.templateMsg') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['bots'] = $checkAvailBot ? Bot::dataList(1)['data'] : [];
        $data['botPlus'] = $checkAvailBotPlus ? BotPlus::dataList(1)['data'] : [];
        $data['templates'] = Template::dataList(1)['data'];
        return view('Tenancy.TemplateMsg.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $myData = [];
        for ($i = 0; $i < $input['buttons']; $i++) {
            if(!isset($input['btn_text_'.($i+1)]) || empty($input['btn_text_'.($i+1)]) || $input['btn_text_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidText',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if(!isset($input['btn_type_'.($i+1)]) || empty($input['btn_type_'.($i+1)]) || $input['btn_type_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidButtonType',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if(!isset($input['btn_reply_type_'.($i+1)]) || empty($input['btn_reply_type_'.($i+1)]) || $input['btn_reply_type_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidType',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $replyType = (int)$input['btn_reply_type_'.($i+1)];
            if($replyType == 1 && ( !isset($input['btn_reply_'.($i+1)]) || empty($input['btn_reply_'.($i+1)]) ) && (!isset($input['url_'.($i+1)]) && !isset($input['contact_'.($i+1)])) ){
                Session::flash('error', trans('main.invalidReply',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if($replyType == 2 && ( !isset($input['btn_msg_'.($i+1)]) || empty($input['btn_msg_'.($i+1)]) ) && (!isset($input['url_'.($i+1)]) && !isset($input['contact_'.($i+1)]))){
                Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $modelType = '';
            if($replyType == 2 && ( !isset($input['btn_msg_type_'.($i+1)]) || empty($input['btn_msg_type_'.($i+1)]) ) && (!isset($input['url_'.($i+1)]) && !isset($input['contact_'.($i+1)])) ){
                Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $modelType = $input['btn_msg_type_'.($i+1)];
            $modelName = $modelType != null && $replyType >= 2 ?  ((int)$modelType == 1 ? '\App\Models\Bot' : '\App\Models\BotPlus')  : '';
            $msg = $replyType == 1 ? $input['btn_reply_'.($i+1)] : '';

            if($modelName != '' && $msg == ''){
                $dataObj = $modelName::find($input['btn_msg_'.($i+1)]);
                if($dataObj){
                    $msg = $dataObj->id;
                }
            }
            if($modelName == '' && $msg == ''){
                $modelType= '';
                $msg = isset($input['url_'.($i+1)]) ? $input['url_'.($i+1)] : '';
                if($msg == ''){
                    $msg = isset($input['contact_'.($i+1)]) ? $input['contact_'.($i+1)] : '';
                }
            }

            $myData[] = [
                'id' => $i + 1,
                'text' => $input['btn_text_'.($i+1)],
                'button_type' => $input['btn_type_'.($i+1)],
                'reply_type' => $replyType,
                'msg_type' => $modelType,
                'model_name' => $modelName,
                'msg' => $msg,
            ];
        }

        $dataObj = new TemplateMsg;
        $dataObj->message_type = $input['message_type'];
        $dataObj->message = $input['message'];
        $dataObj->title = isset($input['title']) ? $input['title'] : '';
        $dataObj->image = '';
        $dataObj->body = $input['body'];
        $dataObj->footer = $input['footer'];
        $dataObj->buttons = $input['buttons'];
        $dataObj->buttonsData = serialize($myData);
        $dataObj->sort = TemplateMsg::newSortIndex();
        $dataObj->status = 1;
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $checkAvail = UserAddon::checkUserAvailability('BotPlus');
        if(!$checkAvail){
            return \TraitsFunc::SuccessResponse(trans('main.unAvail'));
        }

        $dataObj = TemplateMsg::getOne($id);
        \ImagesHelper::deleteDirectory(public_path('/').'uploads/'.TENANT_ID.'/'.$this->getData()['mainData']['name'].'/'.$id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        $checkAvail = UserAddon::checkUserAvailability('BotPlus');
        if(!$checkAvail){
            return \TraitsFunc::SuccessResponse(trans('main.unAvail'));
        }
        
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = TemplateMsg::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
}
