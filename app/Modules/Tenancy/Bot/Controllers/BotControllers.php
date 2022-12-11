<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bot;
use App\Models\Variable;
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

// 1  == Text ,  2 == Image , 3 == Video , 4 == Audio , 5 == Document , 6 == Sticker , 7 == Gif , 8 == Location , 
// 9  == Contact, 10 == Disappearing , 11 == Mention , 13 == Buttons , 14 == Template , 15 == List, 16 == Link With Preview,  
// 17 == Group Invitation, 18 == Product , 19 == Catalog  , 20 == Poll
        
class BotControllers extends Controller {

    use \TraitsFunc;
    public $addonId = '1';
    
    public function addBotReply(){
        $input = \Request::all();
        if(isset($input['message']) || empty($input['message'])){
            $varObj = Variable::where('var_key','UNKNOWN_BOT_REPLY')->first();
            if($varObj){
                $varObj->var_value = isset($input['message']) && !empty($input['message']) ? $input['message'] : '';
                $varObj->save();
            }else{
                $varObj = new Variable;
                $varObj->var_key = 'UNKNOWN_BOT_REPLY';
                $varObj->var_value = isset($input['message']) && !empty($input['message']) ? $input['message'] : '';
                $varObj->save();
            }
            return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
        }
        return \TraitsFunc::ErrorMessage(trans('main.replyValidate'));
    }

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

        $replyTypes = [
            ['id'=>1,'title'=>trans('main.text')],
            ['id'=>2,'title'=>trans('main.botPhoto')],
            ['id'=>3,'title'=>trans('main.video')],
            ['id'=>4,'title'=>trans('main.sound')],
            ['id'=>5,'title'=>trans('main.file')],
            ['id'=>8,'title'=>trans('main.mapLocation')],
            ['id'=>9,'title'=>trans('main.whatsappNos')],
            ['id'=>10,'title'=>trans('main.disappearing')],
            ['id'=>11,'title'=>trans('main.mention')],
            ['id'=>16,'title'=>trans('main.link')],
            ['id'=>50,'title'=>trans('main.webhook')],
        ];

        $data['mainData'] = [
            'title' => trans('main.classicBot'),
            'url' => 'bots',
            'name' => 'bots',
            'nameOne' => 'bot',
            'modelName' => 'Bot',
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
                'index' => '2',
                'label' => trans('main.clientMessage'),
            ],
            'reply_type' => [
                'type' => 'select',
                'class' => 'form-control',
                'index' => '',
                'options' => $replyTypes,
                'label' => trans('main.replyType'),
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
            'reply_type_text' => [
                'label' => trans('main.replyType'),
                'type' => '',
                'className' => '',
                'data-col' => 'reply_type',
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
            'reply_type' => 'required',
            'lang' => 'required',
        ];

        $message = [
            'message_type.required' => trans('main.messageTypeValidate'),
            'message.required' => trans('main.messageValidate'),
            'reply_type.required' => trans('main.replyTypeValidate'),
            'lang.required' => trans('main.langValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);
        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Bot::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function add() {
        // $checkAvail = UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        // if(!$checkAvail){
        //     return redirect(404);
        // }

        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.classicBot') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['bots'] = Bot::dataList(1)['data'];
        $data['templates'] = Template::dataList(1)['data'];
        return view('Tenancy.Bot.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        if($input['reply_type'] == 1){
            if(!isset($input['replyText']) || empty($input['replyText'])){
                Session::flash('error', trans('main.replyValidate'));
                return redirect()->back()->withInput();
            }
        }

        $dataObj = new Bot;
        $dataObj->message_type = $input['message_type'];
        $dataObj->message = $input['message'];
        $dataObj->reply_type = $input['reply_type'];
        $dataObj->sort = Bot::newSortIndex();
        $dataObj->status = 1;
        $dataObj->lang = $input['lang'];
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        if($input['reply_type'] == 1){
            $dataObj->reply = $input['replyText'];
            $dataObj->save();
        }
        else if($input['reply_type'] == 2){
            $dataObj->reply = $input['reply']; 
            $dataObj->save();
        }
        else if($input['reply_type'] == 3){
            $dataObj->reply = $input['caption']; 
            $dataObj->save();
        }
        else if($input['reply_type'] == 10){
            $dataObj->reply = $input['disappearingText'];
            $dataObj->expiration_in_seconds = isset($input['expires_in']) && !empty($input['expires_in']) ? $input['expires_in'] * 60 : '';
            $dataObj->save();
        }
        else if($input['reply_type'] == 16){
            $dataObj->https_url = $input['https_url'];
            $dataObj->url_title = $input['url_title'];
            $dataObj->url_desc = $input['url_desc'];
            $dataObj->save();
        }else if($input['reply_type'] == 9){
            $dataObj->whatsapp_no = $input['phone1'];
            $dataObj->save();
        }else if($input['reply_type'] == 11){
            $dataObj->mention = $input['phone2'];
            $dataObj->save();
        }else if($input['reply_type'] == 8){
            $dataObj->lat = $input['lat'];
            $dataObj->lng = $input['lng'];
            $dataObj->address = $input['address'];
            $dataObj->save();
        }else if($input['reply_type'] == 50){
            $dataObj->webhook_url = $input['webhook_url'];
            if(isset($input['templates']) && !empty($input['templates'])){
                $dataObj->templates = serialize($input['templates']);
            }
            $dataObj->save();
        }

        if(in_array($input['reply_type'], [2,3,4,5])){
            $file = Session::get('botFile');
            if($file){
                $storageFile = Storage::files($file);
                if(count($storageFile) > 0){
                    $images = self::addImage($storageFile[0],$dataObj->id);
                    if ($images == false) {
                        Session::flash('error', trans('main.uploadProb'));
                        return redirect()->back()->withInput();
                    }
                    $dataObj->file_name = $images;
                    $dataObj->save();
                }
            }
        }

        Session::forget('botFile');
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function edit($id) {
        $id = (int) $id;
        // $checkAvail = UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        $dataObj = Bot::NotDeleted()->find($id);
        if($dataObj == null /*|| !$checkAvail*/) {
            return Redirect('404');
        }

        $data['data'] = Bot::getData($dataObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.classicBot') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['bots'] = Bot::dataList(1)['data'];
        $data['templates'] = Template::dataList(1)['data'];
        return view('Tenancy.Bot.Views.edit')->with('data', (object) $data);      
    }

    public function copy($id) {
        $id = (int) $id;

        $dataObj = Bot::NotDeleted()->find($id);
        $checkAvail = UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        if($dataObj == null /*|| !$checkAvail*/) {
            return Redirect('404');
        }

        $newDataObj = $dataObj->replicate();
        $newDataObj->save();
        return Redirect::to('/bots/edit/'.$newDataObj->id);      
    }
    
    public function changeStatus($id) {
        $id = (int) $id;

        $dataObj = Bot::NotDeleted()->find($id);
        $checkAvail = UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        if($dataObj == null /*|| !$checkAvail*/) {
            return Redirect('404');
        }


        $dataObj->status = $dataObj->status == 1 ? 0 : 1;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return Redirect::to('/bots/');      
    }
    
    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $dataObj = Bot::NotDeleted()->find($id);
        if($dataObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        if($input['reply_type'] == 1){
            if(!isset($input['replyText']) || empty($input['replyText'])){
                Session::flash('error', trans('main.replyValidate'));
                return redirect()->back()->withInput();
            }
        }

        $dataObj->message_type = $input['message_type'];
        $dataObj->message = $input['message'];
        $dataObj->reply_type = $input['reply_type'];
        $dataObj->status = $input['status'];
        $dataObj->lang = $input['lang'];
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();

        if($input['reply_type'] == 1){
            $dataObj->reply = $input['replyText'];
            $dataObj->save();
        }
        else if($input['reply_type'] == 2){
            $dataObj->reply = $input['reply']; 
            $dataObj->save();
        }
        else if($input['reply_type'] == 3){
            $dataObj->reply = $input['caption']; 
            $dataObj->save();
        }
        else if($input['reply_type'] == 10){
            $dataObj->reply = $input['disappearingText'];
            $dataObj->expiration_in_seconds = isset($input['expires_in']) && !empty($input['expires_in']) ? $input['expires_in'] * 60 : '';
            $dataObj->save();
        }
        else if($input['reply_type'] == 16){
            $dataObj->https_url = $input['https_url'];
            $dataObj->url_title = $input['url_title'];
            $dataObj->url_desc = $input['url_desc'];
            $dataObj->save();
        }else if($input['reply_type'] == 9){
            $dataObj->whatsapp_no = $input['phone1'];
            $dataObj->save();
        }else if($input['reply_type'] == 11){
            $dataObj->mention = $input['phone2'];
            $dataObj->save();
        }else if($input['reply_type'] == 8){
            $dataObj->lat = $input['lat'];
            $dataObj->lng = $input['lng'];
            $dataObj->address = $input['address'];
            $dataObj->save();
        }else if($input['reply_type'] == 50){
            $dataObj->webhook_url = $input['webhook_url'];
            if(isset($input['templates']) && !empty($input['templates'])){
                $dataObj->templates = serialize($input['templates']);
            }
            $dataObj->save();
        }

        if(in_array($input['reply_type'], [2,3,4,5])){
            $file = Session::get('botFile');
            if($file){
                $storageFile = Storage::files($file);
                if(count($storageFile) > 0){
                    $images = self::addImage($storageFile[0],$dataObj->id);
                    if ($images == false) {
                        Session::flash('error', trans('main.uploadProb'));
                        return redirect()->back()->withInput();
                    }
                    $dataObj->file_name = $images;
                }
            }
        }

        $dataObj->save();

        Session::forget('botFile');
        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function delete($id) {
        $id = (int) $id;
        // $checkAvail = UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        // if(!$checkAvail){
        //     return \TraitsFunc::SuccessResponse(trans('main.unAvail'));
        // }

        $dataObj = Bot::getOne($id);
        \ImagesHelper::deleteDirectory(public_path('/').'uploads/'.TENANT_ID.'/'.$this->getData()['mainData']['name'].'/'.$id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        // $checkAvail = UserAddon::checkUserAvailability(USER_ID,$this->addonId);
        // if(!$checkAvail){
        //     return \TraitsFunc::SuccessResponse(trans('main.unAvail'));
        // }
        
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = Bot::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }   

    public function uploadImage($type,Request $request){
        $rand = rand() . date("YmdhisA");
        $typeID = (int) $type;
        if(!in_array($typeID, [2,3,4,5])){
            return Redirect('404');
        }
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
            
            if( $typeID == 2 && !in_array($type, ['file','photo']) ){
                return \TraitsFunc::ErrorMessage(trans('main.selectFile'));
            }

            if( $typeID == 3 && $type != 'video' ){
                return \TraitsFunc::ErrorMessage(trans('main.selectVideo'));
            }

            if( $typeID == 4 && $type != 'sound' ){
                return \TraitsFunc::ErrorMessage(trans('main.selectSound'));
            }

            if( $typeID == 5 && $type != 'file' ){
                return \TraitsFunc::ErrorMessage(trans('main.urlImage'));
            }

            Storage::put($rand,$files);
            Session::put('botFile',$rand);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images,$nextID=false){
        $fileName = \ImagesHelper::UploadFile($this->getData()['mainData']['name'], $images, $nextID);
        if($fileName == false){
            return false;
        }
        return $fileName;        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();
        $menuObj = Bot::find($id);
        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage(trans('main.botNotFound'));
        }

        \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id.'/'.$menuObj->file_name);
        $menuObj->file_name = '';
        $menuObj->save();
        return \TraitsFunc::SuccessResponse(trans('main.imgDeleted'));
    }
}
