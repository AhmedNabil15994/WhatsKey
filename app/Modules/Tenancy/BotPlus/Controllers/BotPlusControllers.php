<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
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

class BotPlusControllers extends Controller {

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
            'title' => trans('main.botPlus'),
            'url' => 'botPlus',
            'name' => 'bots-plus',
            'nameOne' => 'bot-plus',
            'modelName' => 'BotPlus',
            'icon' => 'fas fa-robot',
            'sortName' => 'message',
            'addOne' => trans('main.newBot'),
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
            'body' => 'required',
            'footer' => 'required',
            'buttons' => 'required',
        ];

        $message = [
            'message_type.required' => trans('main.messageTypeValidate'),
            'message.required' => trans('main.messageValidate'),
            'body.required' => trans('main.bodyValidate'),
            'footer.required' => trans('main.footerValidate'),
            'buttons.required' => trans('main.buttonsValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);
        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = BotPlus::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;
        $checkAvail = UserAddon::checkUserAvailability('BotPlus');
        $dataObj = BotPlus::find($id);
        if($dataObj == null || !$checkAvail) {
            return Redirect('404');
        }

        $checkAsvail = 1;
        $data['data'] = BotPlus::getData($dataObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.botPlus') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $checkAsvail = UserAddon::checkUserAvailability('Bot');
        $data['bots'] = $checkAsvail ? Bot::dataList(1)['data'] : [];
        $data['botPlus'] = BotPlus::dataList(1)['data'];
        $data['templates'] = Template::dataList(1)['data'];
        return view('Tenancy.BotPlus.Views.edit')->with('data', (object) $data);      
    }

    public function copy($id) {
        $id = (int) $id;

        $dataObj = BotPlus::find($id);
        $checkAvail = UserAddon::checkUserAvailability('BotPlus');
        if($dataObj == null || !$checkAvail) {
            return Redirect('404');
        }

        $newDataObj = $dataObj->replicate();
        $newDataObj->save();
        return Redirect::to('/botPlus/edit/'.$newDataObj->id);      
    }

    public function changeStatus($id) {
        $id = (int) $id;

        $dataObj = BotPlus::find($id);
        $checkAvail = UserAddon::checkUserAvailability('BotPlus');
        if($dataObj == null || !$checkAvail) {
            return Redirect('404');
        }


        $dataObj->status = $dataObj->status == 1 ? 0 : 1;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return Redirect::to('/botPlus/');      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $botObj = BotPlus::find($id);
        if($botObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        if($input['title_type'] == 1 && (!isset($input['title']) || empty($input['title']))){
            Session::flash('error', trans('main.titleValidate'));
            return redirect()->back()->withInput();
        }else if($input['title_type'] == 2 && (!Session::has('botFile') && $botObj->image == '')){
            Session::flash('error', trans('main.titleValidate'));
            return redirect()->back()->withInput();
        }

        $myData = [];
        for ($i = 0; $i < $input['buttons']; $i++) {
            if(!isset($input['btn_text_'.($i+1)]) || empty($input['btn_text_'.($i+1)]) || $input['btn_text_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidText',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if(!isset($input['btn_reply_type_'.($i+1)]) || empty($input['btn_reply_type_'.($i+1)]) || $input['btn_reply_type_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidType',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $replyType = (int)$input['btn_reply_type_'.($i+1)];
            if($replyType == 1 && ( !isset($input['btn_reply_'.($i+1)]) || empty($input['btn_reply_'.($i+1)]) )){
                Session::flash('error', trans('main.invalidReply',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if($replyType == 2 && ( !isset($input['btn_msg_'.($i+1)]) || empty($input['btn_msg_'.($i+1)]) )){
                Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $modelType = '';
            if($replyType == 2 && ( !isset($input['btn_msg_type_'.($i+1)]) || empty($input['btn_msg_type_'.($i+1)]) )){
                Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $modelType = (int)$input['btn_msg_type_'.($i+1)];
            $modelName = $modelType != '' ?  ($modelType == 1 ? '\App\Models\Bot' : '\App\Models\BotPlus')  : '';
            $msg = $replyType == 1 ? $input['btn_reply_'.($i+1)] : '';

            if($modelName != '' && $msg == ''){
                $dataObj = $modelName::find($input['btn_msg_'.($i+1)]);
                if($dataObj){
                    $msg = $dataObj->id;
                }
            }

            $myData[] = [
                'id' => $i + 1,
                'text' => $input['btn_text_'.($i+1)],
                'reply_type' => $input['btn_reply_type_'.($i+1)],
                'msg_type' => $modelType,
                'model_name' => $modelName,
                'msg' => $msg,
            ];
        }
        $botObj->message_type = $input['message_type'];
        $botObj->message = $input['message'];
        $botObj->title = $input['title'];
        $botObj->body = $input['body'];
        $botObj->footer = $input['footer'];
        $botObj->buttons = $input['buttons'];
        $botObj->buttonsData = serialize($myData);
        $botObj->updated_at = DATE_TIME;
        $botObj->updated_by = USER_ID;
        $botObj->save();

        $file = Session::get('botFile');
        if($file){
            $storageFile = Storage::files($file);
            if(count($storageFile) > 0){
                $images = self::addImage($storageFile[0],$botObj->id);
                if ($images == false) {
                    Session::flash('error', trans('main.uploadProb'));
                    return redirect()->back()->withInput();
                }
                $botObj->image = $images;
                $botObj->save();
            }
        }

        Session::forget('botFile');

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $checkAvail = UserAddon::checkUserAvailability('BotPlus');
        if(!$checkAvail){
            return redirect(404);
        }
        $checkAsvail = 1;
        $userObj = User::find(USER_ID);
        $channels = [];
        $channelObj = new \stdClass();
        $channelObj->id = Session::get('channelCode');
        $channelObj->title = unserialize($userObj->channels)[0];
        $channels[] = $channelObj;

        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.botPlus') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $checkAsvail = UserAddon::checkUserAvailability('Bot');
        $data['bots'] = $checkAsvail ? Bot::dataList(1)['data'] : [];
        $data['botPlus'] = BotPlus::dataList(1)['data'];
        $data['templates'] = Template::dataList(1)['data'];
        return view('Tenancy.BotPlus.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        if($input['title_type'] == 1 && (!isset($input['title']) || empty($input['title']))){
            Session::flash('error', trans('main.titleValidate'));
            return redirect()->back()->withInput();
        }else if($input['title_type'] == 2 && !Session::has('botFile')){
            Session::flash('error', trans('main.titleValidate'));
            return redirect()->back()->withInput();
        }

        //btn_text_1,btn_reply_type_1,btn_reply_1,btn_msg_1
        //invalidText,invalidType,invalidReply,invalidMsg
        $myData = [];
        for ($i = 0; $i < $input['buttons']; $i++) {
            if(!isset($input['btn_text_'.($i+1)]) || empty($input['btn_text_'.($i+1)]) || $input['btn_text_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidText',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if(!isset($input['btn_reply_type_'.($i+1)]) || empty($input['btn_reply_type_'.($i+1)]) || $input['btn_reply_type_'.($i+1)] == null ){
                Session::flash('error', trans('main.invalidType',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $replyType = (int)$input['btn_reply_type_'.($i+1)];
            if($replyType == 1 && ( !isset($input['btn_reply_'.($i+1)]) || empty($input['btn_reply_'.($i+1)]) )){
                Session::flash('error', trans('main.invalidReply',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            if($replyType == 2 && ( !isset($input['btn_msg_'.($i+1)]) || empty($input['btn_msg_'.($i+1)]) )){
                Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $modelType = '';
            if($replyType == 2 && ( !isset($input['btn_msg_type_'.($i+1)]) || empty($input['btn_msg_type_'.($i+1)]) )){
                Session::flash('error', trans('main.invalidMsg',['button'=>($i+1)]));
                return redirect()->back()->withInput();
            }

            $modelType = (int)$input['btn_msg_type_'.($i+1)];
            $modelName = $modelType != '' ?  ($modelType == 1 ? '\App\Models\Bot' : '\App\Models\BotPlus')  : '';
            $msg = $replyType == 1 ? $input['btn_reply_'.($i+1)] : '';

            if($modelName != '' && $msg == ''){
                $dataObj = $modelName::find($input['btn_msg_'.($i+1)]);
                if($dataObj){
                    $msg = $dataObj->id;
                }
            }

            $myData[] = [
                'id' => $i + 1,
                'text' => $input['btn_text_'.($i+1)],
                'reply_type' => $input['btn_reply_type_'.($i+1)],
                'msg_type' => $modelType,
                'model_name' => $modelName,
                'msg' => $msg,
            ];
        }
        
        $dataObj = new BotPlus;
        $dataObj->channel = Session::get('channelCode');
        $dataObj->message_type = $input['message_type'];
        $dataObj->message = $input['message'];
        $dataObj->title = $input['title'];
        $dataObj->body = $input['body'];
        $dataObj->footer = $input['footer'];
        $dataObj->buttons = $input['buttons'];
        $dataObj->buttonsData = serialize($myData);
        // $dataObj->category_id = $input['category_id'];
        // $dataObj->moderator_id = $input['moderator_id'];
        $dataObj->sort = BotPlus::newSortIndex();
        $dataObj->status = 1;
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        $file = Session::get('botFile');
        if($file){
            $storageFile = Storage::files($file);
            if(count($storageFile) > 0){
                $images = self::addImage($storageFile[0],$dataObj->id);
                if ($images == false) {
                    Session::flash('error', trans('main.uploadProb'));
                    return redirect()->back()->withInput();
                }
                $dataObj->image = $images;
                $dataObj->save();
            }
        }

        Session::forget('botFile');
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $checkAvail = UserAddon::checkUserAvailability('BotPlus');
        if(!$checkAvail){
            return \TraitsFunc::SuccessResponse(trans('main.unAvail'));
        }

        $dataObj = BotPlus::getOne($id);
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
            $dataObj = BotPlus::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }

    public function uploadImage(Request $request){
        $rand = rand() . date("YmdhisA");
    
        if ($request->hasFile('file')) {
            $files = $request->file('file');

            $file_size = $files->getSize();
            $file_size = $file_size/(1024 * 1024);
            $file_size = number_format($file_size,2);
            $uploadedSize = \Helper::getFolderSize(public_path().'/uploads/'.TENANT_ID.'/');
            $totalStorage = Session::get('storageSize');
            $extraQuotas = UserExtraQuota::getOneForUserByType(GLOBAL_ID,3);
            if($totalStorage + $extraQuotas < (doubleval($uploadedSize) + $file_size) / 1024){
                return \TraitsFunc::ErrorMessage(trans('main.storageQuotaError'));
            }

            $type = \ImagesHelper::checkFileExtension($files->getClientOriginalName());
            
            if( $type != 'photo' ){
                return \TraitsFunc::ErrorMessage(trans('main.selectFile'));
            }

            Storage::put($rand,$files);
            Session::put('botFile',$rand);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images,$nextID=false){
        $fileName = \ImagesHelper::UploadFile('botPlus', $images, $nextID);
        if($fileName == false){
            return false;
        }
        return $fileName;        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();
        $menuObj = BotPlus::find($id);
        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage(trans('main.botNotFound'));
        }

        \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id.'/'.$menuObj->image);
        $menuObj->image = '';
        $menuObj->save();
        return \TraitsFunc::SuccessResponse(trans('main.imgDeleted'));
    }
}
