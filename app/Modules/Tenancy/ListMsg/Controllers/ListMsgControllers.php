<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\ListMsg;
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

class ListMsgControllers extends Controller {

    use \TraitsFunc;
    public $addonId = '14';

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
            'title' => trans('main.listMsg'),
            'url' => 'lists',
            'name' => 'lists',
            'nameOne' => 'list',
            'modelName' => 'ListMsg',
            'icon' => 'fas fa-layer',
            'sortName' => 'message',
            'addOne' => trans('main.newList'),
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
            'sections' => [
                'label' => trans('main.sections'),
                'type' => '',
                'className' => '',
                'data-col' => 'sections',
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
            'sections' => 'required',
            'buttonText' => 'required',
        ];

        $message = [
            'message_type.required' => trans('main.messageTypeValidate'),
            'message.required' => trans('main.messageValidate'),
            'title.required' => trans('main.titleValidate'),
            'body.required' => trans('main.bodyValidate'),
            'footer.required' => trans('main.footerValidate'),
            'sections.required' => trans('main.sectionsValidate'),
            'buttonText.required' => trans('main.buttonTextValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);
        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = ListMsg::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;
        $checkAvail = 1;//UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        $dataObj = ListMsg::find($id);
        if($dataObj == null || !$checkAvail) {
            return Redirect('404');
        }

        $checkAvailBot = 1;//UserAddon::checkUserAvailability(ROOT_ID,1);
        $checkAvailBotPlus = 1;//UserAddon::checkUserAvailability(ROOT_ID,10);

        $data['data'] = ListMsg::getData($dataObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.listMsg') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['bots'] = $checkAvailBot ? Bot::dataList(1)['data'] : [];
        $data['botPlus'] = $checkAvailBotPlus ? BotPlus::dataList(1)['data'] : [];
        $data['templates'] = Template::dataList(1)['data'];
        return view('Tenancy.ListMsg.Views.edit')->with('data', (object) $data);      
    }

    public function copy($id) {
        $id = (int) $id;

        $dataObj = ListMsg::find($id);
        $checkAvail = 1;//UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        if($dataObj == null || !$checkAvail) {
            return Redirect('404');
        }

        $newDataObj = $dataObj->replicate();
        $newDataObj->save();
        return Redirect::to('/lists/edit/'.$newDataObj->id);      
    }

    public function changeStatus($id) {
        $id = (int) $id;

        $dataObj = ListMsg::find($id);
        $checkAvail = 1;//UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        if($dataObj == null || !$checkAvail) {
            return Redirect('404');
        }

        $dataObj->status = $dataObj->status == 1 ? 0 : 1;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return Redirect::to('/lists/');      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $botObj = ListMsg::find($id);
        if($botObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $myData = [];
        $itemCount = 0;

        for ($i = 0; $i < $input['sections']; $i++) {
            if(!isset($input['title_'.($i+1)]) || empty($input['title_'.($i+1)]) || $input['title_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidSectionTitle',['section'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if(!isset($input['options_'.($i+1)]) || empty($input['options_'.($i+1)]) || $input['options_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidOptions',['section'=>($i+1)]));
                return redirect()->back()->withInput();
            }
            $sectionTitle = $input['title_'.($i+1)];
            $sectionOptions = $input['options_'.($i+1)];


            $itemData = [];
            for ($x = 1; $x <= $sectionOptions; $x++) {
                $itemCount++;
                if(!isset($input['item_title_'.($i+1).'_'.$x]) || empty($input['item_title_'.($i+1).'_'.$x]) || $input['item_title_'.($i+1).'_'.$x] == null ){
                    Session::flash('error', trans('main.invalidType',['button'=>($i+1)]));
                    return redirect()->back()->withInput();
                }

                if(!isset($input['item_reply_type_'.($i+1).'_'.$x]) || empty($input['item_reply_type_'.($i+1).'_'.$x]) || $input['item_reply_type_'.($i+1).'_'.$x] == null ){
                    Session::flash('error', trans('main.invalidType',['button'=>($i+1)]));
                    return redirect()->back()->withInput();
                }

                $replyType = (int)$input['item_reply_type_'.($i+1).'_'.$x];

                if($replyType == 1 && ( !isset($input['btn_reply_'.($i+1).'_'.$x]) || empty($input['btn_reply_'.($i+1).'_'.$x]) ) && (!isset($input['url_'.($i+1).'_'.$x]) && !isset($input['contact_'.($i+1).'_'.$x])) ){
                    Session::flash('error', trans('main.invalidReply',['button'=>($i+1)]));
                    return redirect()->back()->withInput();
                }

                if($replyType == 2 && ( !isset($input['btn_msg_'.($i+1).'_'.$x]) || empty($input['btn_msg_'.($i+1).'_'.$x]) )){
                    Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                    return redirect()->back()->withInput();
                }

                $modelType = '';
                if($replyType == 2 && ( !isset($input['btn_msg_type_'.($i+1).'_'.$x]) || empty($input['btn_msg_type_'.($i+1).'_'.$x]) )  ){
                    Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                    return redirect()->back()->withInput();
                }

                $itemTitle = $input['item_title_'.($i+1).'_'.$x];
                $itemDesc  = @$input['item_description_'.($i+1).'_'.$x];
                $modelType = $input['btn_msg_type_'.($i+1).'_'.$x];
                $modelName = $modelType != null ?  ((int)$modelType == 1 ? '\App\Models\Bot' : '\App\Models\BotPlus')  : '';
                $msg = $replyType == 1 ? $input['btn_reply_'.($i+1).'_'.$x] : '';

                if($modelName != '' && $msg == ''){
                    $dataObj = $modelName::find($input['btn_msg_'.($i+1).'_'.$x]);
                    if($dataObj){
                        $msg = $dataObj->id;
                    }
                }
                $itemData[] = [
                    'title' => $itemTitle,
                    'description' => $itemDesc,
                    'rowId' => $itemCount,
                    'reply_type' => $replyType,
                    'msg_type' => $modelType,
                    'model_name' => $modelName,
                    'msg' => $msg,
                ];
            }

            $myData[] = [
                'id' => $i + 1,
                'title' => $sectionTitle,
                'rows' => $itemData,
            ];
        }
        
        $botObj->message_type = $input['message_type'];
        $botObj->message = $input['message'];
        $botObj->title = $input['title'];
        $botObj->footer = $input['footer'];
        $botObj->buttonText = $input['buttonText'];
        $botObj->sections = $input['sections'];
        $botObj->sectionsData = serialize($myData);
        $botObj->updated_at = DATE_TIME;
        $botObj->updated_by = USER_ID;
        $botObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $checkAvail = 1;//UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        if(!$checkAvail){
            return redirect(404);
        }

        $checkAvailBot = 1;//UserAddon::checkUserAvailability(ROOT_ID,1);
        $checkAvailBotPlus = 1;//UserAddon::checkUserAvailability(ROOT_ID,10);

        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.listMsg') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['bots'] = $checkAvailBot ? Bot::dataList(1)['data'] : [];
        $data['botPlus'] = $checkAvailBotPlus ? BotPlus::dataList(1)['data'] : [];
        $data['templates'] = Template::dataList(1)['data'];
        return view('Tenancy.ListMsg.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        // dd($input);
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $myData = [];
        $itemCount = 0;

        for ($i = 0; $i < $input['sections']; $i++) {
            if(!isset($input['title_'.($i+1)]) || empty($input['title_'.($i+1)]) || $input['title_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidSectionTitle',['section'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if(!isset($input['options_'.($i+1)]) || empty($input['options_'.($i+1)]) || $input['options_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidOptions',['section'=>($i+1)]));
                return redirect()->back()->withInput();
            }
            $sectionTitle = $input['title_'.($i+1)];
            $sectionOptions = $input['options_'.($i+1)];


            $itemData = [];
            for ($x = 1; $x <= $sectionOptions; $x++) {
                $itemCount++;
                if(!isset($input['item_title_'.($i+1).'_'.$x]) || empty($input['item_title_'.($i+1).'_'.$x]) || $input['item_title_'.($i+1).'_'.$x] == null ){
                    Session::flash('error', trans('main.invalidType',['button'=>($i+1)]));
                    return redirect()->back()->withInput();
                }

                if(!isset($input['item_reply_type_'.($i+1).'_'.$x]) || empty($input['item_reply_type_'.($i+1).'_'.$x]) || $input['item_reply_type_'.($i+1).'_'.$x] == null ){
                    Session::flash('error', trans('main.invalidType',['button'=>($i+1)]));
                    return redirect()->back()->withInput();
                }

                $replyType = (int)$input['item_reply_type_'.($i+1).'_'.$x];

                if($replyType == 1 && ( !isset($input['btn_reply_'.($i+1).'_'.$x]) || empty($input['btn_reply_'.($i+1).'_'.$x]) ) && (!isset($input['url_'.($i+1).'_'.$x]) && !isset($input['contact_'.($i+1).'_'.$x])) ){
                    Session::flash('error', trans('main.invalidReply',['button'=>($i+1)]));
                    return redirect()->back()->withInput();
                }

                if($replyType == 2 && ( !isset($input['btn_msg_'.($i+1).'_'.$x]) || empty($input['btn_msg_'.($i+1).'_'.$x]) )){
                    Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                    return redirect()->back()->withInput();
                }

                $modelType = '';
                if($replyType == 2 && ( !isset($input['btn_msg_type_'.($i+1).'_'.$x]) || empty($input['btn_msg_type_'.($i+1).'_'.$x]) )  ){
                    Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                    return redirect()->back()->withInput();
                }

                $itemTitle = $input['item_title_'.($i+1).'_'.$x];
                $itemDesc  = @$input['item_description_'.($i+1).'_'.$x];
                $modelType = $input['btn_msg_type_'.($i+1).'_'.$x];
                $modelName = $modelType != null ?  ((int)$modelType == 1 ? '\App\Models\Bot' : '\App\Models\BotPlus')  : '';
                $msg = $replyType == 1 ? $input['btn_reply_'.($i+1).'_'.$x] : '';

                if($modelName != '' && $msg == ''){
                    $dataObj = $modelName::find($input['btn_msg_'.($i+1).'_'.$x]);
                    if($dataObj){
                        $msg = $dataObj->id;
                    }
                }
                $itemData[] = [
                    'title' => $itemTitle,
                    'description' => $itemDesc,
                    'rowId' => $itemCount,
                    'reply_type' => $replyType,
                    'msg_type' => $modelType,
                    'model_name' => $modelName,
                    'msg' => $msg,
                ];
            }

            $myData[] = [
                'id' => $i + 1,
                'title' => $sectionTitle,
                'rows' => $itemData,
            ];
        }

        $dataObj = new ListMsg;
        $dataObj->message_type = $input['message_type'];
        $dataObj->message = $input['message'];
        $dataObj->title = $input['title'];
        $dataObj->buttonText = $input['buttonText'];
        $dataObj->body = $input['body'];
        $dataObj->footer = $input['footer'];
        $dataObj->sections = $input['sections'];
        $dataObj->sectionsData = serialize($myData);
        $dataObj->sort = ListMsg::newSortIndex();
        $dataObj->status = 1;
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $checkAvail = 1;//UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        if(!$checkAvail){
            return \TraitsFunc::SuccessResponse(trans('main.unAvail'));
        }

        $dataObj = ListMsg::getOne($id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        $checkAvail = 1;//UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        if(!$checkAvail){
            return \TraitsFunc::SuccessResponse(trans('main.unAvail'));
        }
        
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = ListMsg::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }

}
