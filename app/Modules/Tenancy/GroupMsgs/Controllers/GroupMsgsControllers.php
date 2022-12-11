<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GroupMsg;
use App\Models\GroupNumber;
use App\Models\Contact;
use App\Models\ChatMessage;
use App\Models\ContactReport;
use App\Models\UserExtraQuota;
use App\Models\UserAddon;
use App\Models\Bot;
use App\Models\BotPlus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\WebActions;
use App\Jobs\GroupMessageJob;
use App\Jobs\CheckWhatsappJob;
use App\Jobs\FixReport;
use DataTables;
use Storage;
use Redirect;

class GroupMsgsControllers extends Controller {

    use \TraitsFunc;

    public function checkPerm(){
        $disabled = UserAddon::getDeactivated(User::first()->id);
        $dis = 0;
        if(in_array(3,$disabled)){
            $dis = 1;
        }
        return $dis;
    }

    public function getData(){
        $groups = GroupNumber::dataList(1)['data'];

        $messageTypes = [
            ['id'=>1,'title'=>trans('main.text')],
            ['id'=>2,'title'=>trans('main.photoOrFile')],
            ['id'=>4,'title'=>trans('main.sound')],
            ['id'=>5,'title'=>trans('main.link')],
            ['id'=>6,'title'=>trans('main.whatsappNos')],
        ];

        $sent_types = [
            ['id'=>1,'title'=>trans('main.sent')],
            ['id'=>2,'title'=>trans('main.received')],
            ['id'=>3,'title'=>trans('main.seen')],
        ];

        $data['mainData'] = [
            'title' => trans('main.groupMsgs'),
            'url' => 'groupMsgs',
            'name' => 'groupMessages',
            'nameOne' => 'group-message',
            'modelName' => 'GroupMsg',
            'icon' => 'mdi mdi-send',
            'sortName' => 'message',
            'addOne' => trans('main.newGroupMessage'),
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
                'specialAttr' => '',
            ],
            'group_id' => [
                'type' => 'select',
                'class' => 'form-control',
                'index' => '',
                'options' => $groups,
                'label' => trans('main.group'),
            ],
            'message_type' => [
                'type' => 'select',
                'class' => 'form-control',
                'index' => '',
                'options' => $messageTypes,
                'label' => trans('main.message_type'),
            ],
            'sent_type' => [
                'type' => 'select',
                'class' => 'form-control',
                'index' => '',
                'options' => $sent_types,
                'label' => trans('main.sent_type'),
            ],
            'message' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '3',
                'label' => trans('main.message_content'),
            ],
            'from' => [
                'type' => 'text',
                'class' => 'form-control datatable-input datepicker',
                'index' => '7',
                'id' => 'datepicker1',
                'label' => trans('main.dateFrom'),
            ],
            'to' => [
                'type' => 'text',
                'class' => 'form-control datatable-input datepicker',
                'index' => '8',
                'id' => 'datepicker2',
                'label' => trans('main.dateTo'),
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
            'group' => [
                'label' => trans('main.group'),
                'type' => '',
                'className' => '',
                'data-col' => 'group_id',
                'anchor-class' => '',
            ],
            'message_type_text' => [
                'label' => trans('main.message_type'),
                'type' => '',
                'className' => '',
                'data-col' => 'message_type',
                'anchor-class' => '',
            ],
            'message' => [
                'label' => trans('main.message_content'),
                'type' => '',
                'className' => '',
                'data-col' => 'message',
                'anchor-class' => '',
            ],
            'sent_type' => [
                'label' => trans('main.sent_type'),
                'type' => '',
                'className' => '',
                'data-col' => 'sent_type',
                'anchor-class' => '',
            ],
            'contacts_count' => [
                'label' => trans('main.contacts_count'),
                'type' => '',
                'className' => '',
                'data-col' => 'contacts_count',
                'anchor-class' => '',
            ],
            'sent_msgs' => [
                'label' => trans('main.sent_msgs'),
                'type' => '',
                'className' => '',
                'data-col' => 'sent_msgs',
                'anchor-class' => '',
            ],
            'unsent_msgs' => [
                'label' => trans('main.unsent_msgs'),
                'type' => '',
                'className' => '',
                'data-col' => 'unsent_msgs',
                'anchor-class' => '',
            ],
            'viewed_msgs' => [
                'label' => trans('main.viewed_msgs'),
                'type' => '',
                'className' => '',
                'data-col' => 'viewed_msgs',
                'anchor-class' => '',
            ],
            'publish_at' => [
                'label' => trans('main.sentDate'),
                'type' => '',
                'className' => '',
                'data-col' => 'publish_at',
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
            'group_id' => 'required',
            'message_type' => 'required',
        ];

        $message = [
            'group_id.required' => trans('main.groupValidate'),
            'message_type.required' => trans('main.messageTypeValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);
        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = GroupMsg::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function add() {

        // if($this->checkPerm()){
        //     Session::flash('error','Please Re-activate Group Messages Addon');
        //     return redirect()->back();
        // }

        $startDay = strtotime(date('Y-m-d 00:00:00'));
        $endDay = strtotime(date('Y-m-d 23:59:59'));
        $messagesCount = ChatMessage::where('fromMe',1)->whereNotIn('status',[null,'APP'])->where('time','>=',$startDay)->where('time','<=',$endDay)->count();
        $dailyCount = Session::get('dailyMessageCount');
        $extraQuotas = UserExtraQuota::getOneForUserByType(GLOBAL_ID,1);
        if($dailyCount + $extraQuotas < $messagesCount){
            Session::flash('error', trans('main.messageQuotaError'));
            return redirect()->back()->withInput();
        }

        Session::forget('msgFile');
        $checkAvailBot = 1;//UserAddon::checkUserAvailability(USER_ID,1);
        $checkAvailBotPlus = 1;//UserAddon::checkUserAvailability(USER_ID,10);

        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.groupMsgs') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['groups'] = GroupNumber::dataList(1,[1])['data'];
        $data['contacts'] = Contact::dataList(1)['data'];
        $data['bots'] = $checkAvailBot ? Bot::dataList(1)['data'] : [];
        $data['botPlus'] = $checkAvailBotPlus ? BotPlus::dataList(1)['data'] : [];
        // $data['botPlus'] = $dataObj->type > 1 ? BotPlus::getData(BotPlus::find($dataObj->type)) : [];        
        $data['checkAvailBotPlus'] = $checkAvailBotPlus != null ? 1 : 0;        
        $data['checkAvailBot'] = $checkAvailBot != null ? 1 : 0;
        return view('Tenancy.GroupMsgs.Views.add')->with('data', (object) $data);
    }

    protected function validateInsertBotPlusObject($input){
        $rules = [
            'title' => 'required',
            'body' => 'required',
            'footer' => 'required',
            'buttons' => 'required',
        ];

        $message = [
            'title.required' => trans('main.titleValidate'),
            'body.required' => trans('main.bodyValidate'),
            'footer.required' => trans('main.footerValidate'),
            'buttons.required' => trans('main.buttonsValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);
        return $validate;
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $myData = [];
        if($input['message_type'] == 1){
            if(!isset($input['replyText']) || empty($input['replyText'])){
                Session::flash('error', trans('main.messageValidate'));
                return redirect()->back()->withInput();
            }
        }
        elseif($input['message_type'] == 30){
            $validate = $this->validateInsertBotPlusObject($input);
            if($validate->fails()){
                Session::flash('error', $validate->messages()->first());
                return redirect()->back()->withInput();
            }

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
        }

        $groupObj = GroupNumber::getOne($input['group_id']);
        if($groupObj == null){
            return redirect('404');
        }
        
        $date = now();
        $flag = 0;
        if(isset($input['date']) && !empty($input['date'])){
            $date = $input['date'];
            $flag = 1;
        }

        $message = '';
        if($input['message_type'] == 1){
            $message = $input['replyText'];
        }else if($input['message_type'] == 2){
            $message = $input['reply']; 
        }else if($input['message_type'] == 3){
            $message = $input['caption']; 
        }else if($input['message_type'] == 10){
            $message = $input['disappearingText'];
        }else if($input['message_type'] == 16){
            $message = $input['https_url'];
        }else if($input['message_type'] == 9){
            $message = $input['phone1'];
        }else if($input['message_type'] == 11){
            $message = $input['phone2'];
        }else if($input['message_type'] == 8){
            $message = $input['address'];
        }else if($input['message_type'] == 30){
            $message = $input['body'];
        }

        $contactsCount = Contact::NotDeleted()->where('group_id',$groupObj->id)->count();
        $messagesArr = 1;

        $startDay = strtotime(date('Y-m-d 00:00:00'));
        $endDay = strtotime(date('Y-m-d 23:59:59'));
        $messagesCount = ChatMessage::where('fromMe',1)->whereNotIn('status',[null,'APP'])->where('time','>=',$startDay)->where('time','<=',$endDay)->count();
        $dailyCount = Session::get('dailyMessageCount');
        $extraQuotas = UserExtraQuota::getOneForUserByType(GLOBAL_ID,1);
        if($dailyCount + $extraQuotas < $messagesCount + $contactsCount ){
            Session::flash('error', trans('main.messageQuotaError'));
            return redirect()->back()->withInput();
        }

        $dataObj = new GroupMsg;
        $dataObj->channel = $groupObj->channel;
        $dataObj->group_id = $groupObj->id;
        $dataObj->message_type = $input['message_type'];
        $dataObj->message = $message;
        $dataObj->publish_at = $date;
        $dataObj->later = $flag;
        $dataObj->contacts_count = $contactsCount;
        $dataObj->messages_count = 1;
        $dataObj->sort = GroupMsg::newSortIndex();
        $dataObj->status = 1;
        if($input['message_type'] == 4){
            $dataObj->https_url = $input['https_url'];
            $dataObj->url_title = $input['url_title'];
            $dataObj->url_desc = $input['url_desc'];
        }else if($input['message_type'] == 8){
            $dataObj->lat = $input['lat'];
            $dataObj->lng = $input['lng'];
        }else if($input['message_type'] == 9){
            $dataObj->whatsapp_no = $input['phone1'];
        }else if($input['message_type'] == 10){
            $dataObj->expiration_in_seconds = isset($input['expires_in']) && !empty($input['expires_in']) ? $input['expires_in'] * 60 : '';
        }else if($input['message_type'] == 11){
            $dataObj->mention = $input['phone2'];
        }else if($input['message_type'] == 16){
            $dataObj->https_url = $input['https_url'];
            $dataObj->url_title = $input['url_title'];
            $dataObj->url_desc = $input['url_desc'];
        }
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        if(in_array($input['message_type'], [2,3,4,5])){
            $file = Session::get('msgFile');
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

        if($input['message_type'] == 30){
            $botObj = new BotPlus;
            $botObj->message_type = 1;
            $botObj->message = 'Group Message '.$dataObj->id;
            $botObj->title = $input['title'];
            $botObj->body = $input['body'];
            $botObj->footer = $input['footer'];
            $botObj->buttons = $input['buttons'];
            $botObj->buttonsData = serialize($myData);
            $botObj->sort = BotPlus::newSortIndex();
            $botObj->status = 1;
            $botObj->deleted_by = 1;
            $botObj->deleted_at = DATE_TIME;
            $botObj->save();

            $dataObj->bot_plus_id = $botObj->id;
            $dataObj->save();
        }

        $dataObj = GroupMsg::getData($dataObj);
        $chunks = 100;

        if($flag == 0){
            $contacts = Contact::NotDeleted()->where('group_id',$groupObj->id)->where('status',1)->chunk($chunks,function($data) use ($dataObj){
                try {
                    dispatch(new GroupMessageJob(reset($data),$dataObj))->onConnection('database');
                    // dispatch(new GroupMessageJob(reset($data),$dataObj))->onConnection('cjobs');
                } catch (Exception $e) {}
            });
        }else{
            $now = \Carbon\Carbon::now();
            $sendDate = \Carbon\Carbon::parse($date);
            $diff =  $sendDate->diffInSeconds($now);
            $on = \Carbon\Carbon::now()->addSeconds($diff);   
            $contacts = Contact::NotDeleted()->where('group_id',$groupObj->id)->where('status',1)->chunk($chunks,function($data) use ($dataObj,$on){
                try {
                    dispatch(new GroupMessageJob(reset($data),$dataObj))->onConnection('database')->delay($on);
                    // dispatch(new GroupMessageJob(reset($data),$dataObj))->onConnection('cjobs')->delay($on);
                } catch (Exception $e) {}
            });
        }

        Session::forget('msgFile');
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/view/'.$dataObj->id);
    }

    public function view($id,Request $request) {
        $id = (int) $id;
        $isBA = \App\Models\CentralUser::find(User::first()->id)->isBA;
        $groupMsgObj = GroupMsg::NotDeleted()->find($id);
        if($groupMsgObj == null) {
            return Redirect('404');
        }

        $data = Contact::getFullContactsInfo($groupMsgObj->group_id,$groupMsgObj->id);
        if($request->ajax()){
            return Datatables::of($data['data'])->make(true);
        }

        $phone = str_replace("+", '', $groupMsgObj->Creator->phone);
        $checkAvailBotPlus = 1;//UserAddon::checkUserAvailability(USER_ID,10);

        $data['checkAvailBotPlus'] = $checkAvailBotPlus != null ? 1 : 0;        
        $data['msg'] = GroupMsg::getData($groupMsgObj);        
        $data['phone'] = $phone;
        $data['botPlus'] = $groupMsgObj->bot_plus_id > 1 ? BotPlus::getData(BotPlus::find($groupMsgObj->bot_plus_id)) : [];       
        $data['designElems']['mainData'] = $this->getData()['mainData'];
        $data['designElems']['mainData']['title'] = trans('main.view') . ' '.trans('main.groupMsgs') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-eye';
        $data['designElems']['mainData']['url'] = 'groupMsgs/view/'.$id;
        $data['designElems']['tableData'] = [
            'id' => [
                'label' => trans('main.id'),
                'type' => '',
                'className' => '',
                'data-col' => '',
                'anchor-class' => '',
            ],
            'phone' => [
                'label' => trans('main.phone'),
                'type' => '',
                'className' => '',
                'data-col' => 'phone',
                'anchor-class' => '',
            ],
            'status' => [
                'label' => trans('main.status'),
                'type' => '',
                'className' => '',
                'data-col' => 'status',
                'anchor-class' => '',
            ],
            'date' => [
                'label' => trans('main.date'),
                'type' => '',
                'className' => '',
                'data-col' => 'date',
                'anchor-class' => '',
            ],
        ];
        return view('Tenancy.GroupMsgs.Views.view')->with('data', (object) $data);
    }

    public function resend($id,$status){
        $id = (int) $id;
        $groupMsgObj = GroupMsg::NotDeleted()->find($id);
        if($groupMsgObj == null) {
            return Redirect('404');
        }

        $dataObj = GroupMsg::getData($groupMsgObj);
        $chunks = 100;
        if($status == 1){
            $contacts = Contact::NotDeleted()->where('group_id',$groupMsgObj->group_id)->where('status',1)->chunk($chunks,function($data) use ($dataObj){
                try {
                    // dispatch(new GroupMessageJob(reset($data),$dataObj))->onConnection('cjobs');
                    dispatch(new GroupMessageJob(reset($data),$dataObj))->onConnection('database');
                } catch (Exception $e) {}
            });
        }else{
            $sentContacts = ContactReport::where('group_message_id',$id)->where('message_id','!=',null)->pluck('contact');
            $sentContacts = reset($sentContacts);
            
            $notSentContacts = ContactReport::where('group_message_id',$id)->where('status',0)->pluck('contact');
            $notSentContacts = reset($notSentContacts);
            
            $allContacts =  Contact::NotDeleted()->where('group_id',$groupMsgObj->group_id)->pluck('phone');
            $allContacts = reset($allContacts);
            
            $allContacts = array_diff( $allContacts, $sentContacts );
            $oldContacts = array_unique(array_merge( $allContacts, $notSentContacts ));

            $contacts = Contact::NotDeleted()->where('group_id',$groupMsgObj->group_id)->whereIn('phone',$oldContacts)->chunk($chunks,function($data) use ($dataObj){
                try {
                    // dispatch(new GroupMessageJob(reset($data),$dataObj))->onConnection('cjobs');
                    dispatch(new GroupMessageJob(reset($data),$dataObj))->onConnection('database');
                } catch (Exception $e) {}
            });
        }

        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/view/'.$groupMsgObj->id);
    }

    public function refresh($id){
        $id = (int) $id;
        $groupMsgObj = GroupMsg::NotDeleted()->find($id);
        if($groupMsgObj == null) {
            return Redirect('404');
        }

        try {
            // dispatch(new FixReport($id))->onConnection('cjobs');
            dispatch(new FixReport($id))->onConnection('database');
        } catch (Exception $e) {}

        Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
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
            Session::put('msgFile',$rand);
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

}
