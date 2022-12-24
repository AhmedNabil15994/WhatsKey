<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\ChatMessage;
use App\Models\ChatDialog;
use App\Models\UserExtraQuota;
use App\Models\CentralUser;
use App\Models\ChatEmpLog;
use App\Models\User;
use App\Models\Reply;
use App\Models\Template;
use App\Models\Contact;
use App\Events\IncomingMessage;
use Session;
use Illuminate\Http\Request;

class SendMsg extends Component
{
    public $msgBody = '';
    public $msgType = 1;
    public $selected;
    protected $listeners = ['sendMsg','uploadBlob','setReply','setContact','setLocation','forwardMsg','deleteForMeMsg','deleteForAllMsg','starMsg','labelMsg','reactionMessage','repeatHook'];
    public $file = '';
    public $dataUrl = '';
    public $originalName = '';
    public $contact = '';
    public $lat = '';
    public $lng = '';
    public $replyMsgId;

    use WithFileUploads;
    public function mount($selected){
        $this->selected = $selected;
    }

    public function render()
    {
        return view('livewire.send-msg');
    }

    public function uploadBlob(){
        $this->msgType = 4;
        $this->dataUrl = Session::get('audioDataURL');
        $this->originalName = Session::get('audioName');
    }

    public function fileUpload()
    {
        if($this->file && !Session::has('audioDataURL')){
            $image = $this->file;
            $file_size = $image->getSize();
            $file_size = $file_size / (1024 * 1024);
            $file_size = number_format($file_size, 2);
            $this->originalName = $image->getClientOriginalName();

            $uploadedSize = \Helper::getFolderSize(public_path() . '/uploads/' . Session::get('tenant_id') . '/');
            $totalStorage = Session::get('storageSize');
            $extraQuotas = UserExtraQuota::getOneForUserByType(Session::get('global_id'), 3);
            if ($totalStorage + $extraQuotas < (doubleval($uploadedSize) + $file_size) / 1024) {
                return \TraitsFunc::ErrorMessage(trans('main.storageQuotaError'));
            }

            $myType = explode('/', $image->getMimeType())[1];
            $message_type = \ImagesHelper::checkChatExtensionType($myType);        
            $fileName = \ImagesHelper::uploadFileFromRequest('chats', $image, $message_type);
            if ($image == false || $fileName == false) {
                return \TraitsFunc::ErrorMessage("Upload Files Failed !!", 400);
            }

            if($message_type == 'image'){
                $this->msgType = 2;
            }elseif($message_type == 'video'){
                $this->msgType = 3;
            }elseif($message_type == 'audio'){
                $this->msgType = 4;
            }elseif ($message_type == 'document') {
                $this->msgType = 5;
            }

            $this->dataUrl = config('app.BASE_URL')  . '/uploads/' . Session::get('tenant_id') . '/chats/' . $fileName;
        }
    }

    public function sendMsg($msgBody,$replyMsgId,Request $request){
        $input['type'] = $this->msgType;
        $selected = $this->selected;
        if($replyMsgId){
            $msgObj = ChatMessage::where('id','LIKE','%'.$replyMsgId)->first();
            if($msgObj){
                $this->replyMsgId = $msgObj->id;
                $sendData['messageId'] = $this->replyMsgId;
            }
        }

        $this->msgBody = (string)$msgBody;
        $startDay = strtotime(date('Y-m-d 00:00:00'));
        $endDay = strtotime(date('Y-m-d 23:59:59'));
        $messagesCount = ChatMessage::where('fromMe', 1)->where('status', '!=', null)->where('time', '>=', $startDay)->where('time', '<=', $endDay)->count();
        $dailyCount = Session::get('dailyMessageCount');
        $extraQuotas = UserExtraQuota::getOneForUserByType(Session::get('global_id'), 1);

        if ($dailyCount + $extraQuotas <= $messagesCount) {
            return \TraitsFunc::ErrorMessage('Messages Quota Per Day Exceeded!!!');
        }
        if (!isset($input['type']) || empty($input['type'])) {
            return \TraitsFunc::ErrorMessage("Type Field Is Required");
        }
        if (!isset($selected) || empty($selected)) {
            return \TraitsFunc::ErrorMessage("Chat ID Field Is Required");
        }

        $centralUser = CentralUser::find(User::first()->id);
        $domain = explode('.', $request->getHost())[0];
        $senderStatus = ucwords(Session::get('name'));

        $mainWhatsLoopObj = new \OfficialHelper();
        $sendData['phone'] = str_replace('@c.us', '', $selected);
        $caption = '';
        $metadata = [];
        $message_type = '';
        $bodyData = '';

        if($input['type'] == 1) {
            if (!isset($this->msgBody)) {
                return \TraitsFunc::ErrorMessage("Message Field Is Required");
            }

            if(mb_substr($this->msgBody, 0, 1) == '@' && ! preg_match('/\s/',$this->msgBody)){
                $body = str_replace('@','',$this->msgBody);
                $message_type = 'mention';
                $sendData['mention'] = $body;
                $bodyData = $this->msgBody;
                if(isset($sendData['messageId']) && !empty($sendData['messageId'])){
                    $result = $mainWhatsLoopObj->sendReplyMention($sendData);
                }else{
                    $result = $mainWhatsLoopObj->sendMention($sendData);
                }
            }else{
                $message_type = 'text';
                $sendData['body'] = $this->msgBody;
                $bodyData = $this->msgBody;
                if(isset($sendData['messageId']) && !empty($sendData['messageId'])){
                    $result = $mainWhatsLoopObj->sendReplyText($sendData);
                }else{
                    $result = $mainWhatsLoopObj->sendMessage($sendData);
                }
            }
        }elseif ($input['type'] == 2) {
            if($this->dataUrl != ''){
                $message_type = 'image';
                if($this->msgBody != ''){
                    $sendData['caption'] = $this->msgBody;
                    $caption = $this->msgBody;
                }
                $sendData['url'] = $this->dataUrl;
                $bodyData = $this->dataUrl;
                if(isset($sendData['messageId']) && !empty($sendData['messageId'])){
                    $result = $mainWhatsLoopObj->sendReplyImage($sendData);
                }else{
                    $result = $mainWhatsLoopObj->sendImage($sendData);
                }
                $metadata['filename'] = $this->originalName;
            }      
        }elseif ($input['type'] == 3) {
            if($this->dataUrl != ''){
                $message_type = 'video';
                if($this->msgBody != ''){
                    $sendData['caption'] = $this->msgBody;
                    $caption = $this->msgBody;
                }
                $sendData['url'] = $this->dataUrl;
                $bodyData = $this->dataUrl;
                if(isset($sendData['messageId']) && !empty($sendData['messageId'])){
                    $result = $mainWhatsLoopObj->sendReplyVideo($sendData);
                }else{
                    $result = $mainWhatsLoopObj->sendVideo($sendData);
                }
                $metadata['filename'] = $this->originalName;
            }      
        }elseif ($input['type'] == 4) {
            if($this->dataUrl != ''){
                $message_type = 'audio';
                $sendData['url'] = $this->dataUrl;
                $bodyData = $this->dataUrl;
                if(isset($sendData['messageId']) && !empty($sendData['messageId'])){
                    $result = $mainWhatsLoopObj->sendReplyAudio($sendData);
                }else{
                    $result = $mainWhatsLoopObj->sendAudio($sendData);
                }
                $metadata['filename'] = $this->originalName;
            }      
        }elseif ($input['type'] == 5) {
            if($this->dataUrl != ''){
                $message_type = 'document';
                $sendData['url'] = $this->dataUrl;
                $bodyData = $this->dataUrl;
                if(isset($sendData['messageId']) && !empty($sendData['messageId'])){
                    $result = $mainWhatsLoopObj->sendReplyFile($sendData);
                }else{
                    $result = $mainWhatsLoopObj->sendFile($sendData);
                }
                $metadata['filename'] = $this->originalName;
            }      
        }elseif($input['type'] == 8){
            if (!isset($this->msgBody)) {
                return \TraitsFunc::ErrorMessage("Message Field Is Required");
            }
            $message_type = 'location';
            $sendData['lat'] = $this->lat;
            $sendData['lng'] = $this->lng;
            $sendData['address'] = $this->msgBody;
            if(isset($sendData['messageId']) && !empty($sendData['messageId'])){
                $sendData['latitude'] = $this->lat;
                $sendData['longitude'] = $this->lng;
                unset($sendData['lat']);
                unset($sendData['lng']);
                $result = $mainWhatsLoopObj->sendReplyLocation($sendData);
            }else{
                $result = $mainWhatsLoopObj->sendLocation($sendData);
            }
            $bodyData = $this->msgBody;
            $metadata['latitude'] =  $this->lat;
            $metadata['longitude'] =  $this->lng;
        }elseif($input['type'] == 9) {
            if (!isset($this->msgBody)) {
                return \TraitsFunc::ErrorMessage("Message Field Is Required");
            }

            $message_type = 'contact';
            $sendData['contactMobile'] = $this->msgBody;
            $sendData['name'] = $this->contact;
            if(isset($sendData['messageId']) && !empty($sendData['messageId'])){
                $sendData['contact'] = $sendData['contactMobile'];
                unset($sendData['contactMobile']);
                $result = $mainWhatsLoopObj->sendReplyContact($sendData);
            }else{
                $result = $mainWhatsLoopObj->sendContact($sendData);
            }
            $bodyData = $this->msgBody;
            $metadata['name'] =  $this->contact;
            $metadata['phone'] =  $this->msgBody;
        }

        $result = $result->json();
        // if (!str_contains($input['chatId'], '@g.us')) {
        //     $sendData['chatId'] = $sendData['phone'] . '@c.us';
        // } else {
        //     $sendData['chatId'] = $input['chatId'];
        // }
        $sendData['chatId'] = $selected;

        if (isset($result['data']) && isset($result['data']['id'])) {
            $checkMessageObj = ChatMessage::where('chatId', $sendData['chatId'])->where('chatName', '!=', null)->first();
            $messageId = $result['data']['id'];
            $lastMessage['status'] = $senderStatus;
            $lastMessage['id'] = $messageId;
            $lastMessage['fromMe'] = 1;
            $lastMessage['author'] = $sendData['chatId'];
            $lastMessage['chatId'] = $sendData['chatId'];
            $lastMessage['time'] = strtotime(date('Y-m-d H:i:s'));
            $lastMessage['body'] = $bodyData;
            $lastMessage['caption'] = $caption;
            $lastMessage['metadata'] = $metadata;
            $lastMessage['chatName'] = $checkMessageObj != null ? $checkMessageObj->chatName : '';
            $lastMessage['type'] = $message_type;
            $lastMessage['sending_status'] = 1;
            $lastMessage['notified'] = $message_type == 'contact' || (isset($sendData['messageId']) && !empty($sendData['messageId'])) ? 0 : 1;
            $messageObj = ChatMessage::newMessage($lastMessage);
            $dialog = ChatDialog::getOne($selected);
            $dialog->last_time = $lastMessage['time'];
            $dialogObj = ChatDialog::getData($dialog);
            $dialogObj->lastMessage = ChatMessage::getData($messageObj);
            ChatMessage::where('chatId', $selected)->where('fromMe', 0)->update(['sending_status' => 3]);
            if(
                ($message_type == 'text' && (str_contains($bodyData, 'http://') || str_contains($bodyData, 'https://'))) ||
                ($message_type == 'contact') ||
                (isset($sendData['messageId']) && !empty($sendData['messageId']))
            ){

            }else{
                broadcast(new IncomingMessage($domain, $dialogObj));
            }

            $this->reset(['msgBody','file','dataUrl','originalName','contact','lat','lng','replyMsgId']);
            $this->msgType = 1;
            $replyMsgId = 0;
            Session::forget('audioName');
            Session::forget('audioDataURL');

            $is_admin = Session::get('is_admin');
            $user_id = Session::get('user_id');
            if (!$is_admin) {
                if (in_array($user_id, $dialogObj->modsArr) || Session::get('is_admin')) {
                    ChatEmpLog::newLog($selected, 3);
                }
            }
        }
    }

    public function setReply($replyId,$type=1,$replyMsgId){
        if($replyMsgId){
            $msgObj = ChatMessage::where('id','LIKE','%'.$replyMsgId)->first();
            if($msgObj){
                $this->replyMsgId = $msgObj->id;
            }
        }
        $replyId = (int) $replyId;
        $replyObj = $type == 1 ? Reply::find($replyId) : Template::find($replyId);
        if($replyObj){
            $replyObj = $type == 1 ? Reply::getData($replyObj) : Template::getData($replyObj);
            $contactName = ChatDialog::getOne($this->selected) ? ChatDialog::getOne($this->selected)->name : '';
            $body = self::reformMessage($replyObj->description,$contactName,str_replace('@c.us', '', $this->selected));
            $this->emit('setMessageText',$body);
            $this->msgBody = $body;
        }
    }

    public function setContact($replyId,$replyMsgId){
        if($replyMsgId){
            $msgObj = ChatMessage::where('id','LIKE','%'.$replyMsgId)->first();
            if($msgObj){
                $this->replyMsgId = $msgObj->id;
            }
        }
        $replyId = (int) $replyId;
        $replyObj = Contact::find($replyId);
        if($replyObj){
            $name = str_replace('+', '', $replyObj->name);
            $phone = str_replace('+', '', $replyObj->phone);
            $this->emit('setMessageContact',$phone);
            $this->msgBody = $phone;
            $this->msgType = 9;
            $this->contact = $name;
        }
    }

    public function setLocation($lat,$lng,$address,$replyMsgId){
        if($replyMsgId){
            $msgObj = ChatMessage::where('id','LIKE','%'.$replyMsgId)->first();
            if($msgObj){
                $this->replyMsgId = $msgObj->id;
            }
        }
        $this->msgType = 8;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->msgBody = $address;
        $this->emit('setMessageLocation',$address);
    }

    public function forwardMsg($contactId,$forwardedId){
        if($forwardedId){
            $msgObj = ChatMessage::where('id','LIKE','%'.$forwardedId)->first();
            if($msgObj && $msgObj->fromMe == 0){
                $msgId = $msgObj->id;
                $contactObj = Contact::where('phone','LIKE','%'.$contactId)->first();
                if($contactObj){
                    $mainWhatsLoopObj = new \OfficialHelper();
                    $result = $mainWhatsLoopObj->forwardMessage(['phone'=>$contactId,'messageId'=>$msgId]);
                }
            }
        }        
    }

    public function deleteForMeMsg($msgId){
        if($msgId){
            $msgObj = ChatMessage::where('id','LIKE','%'.$msgId)->first();
            if($msgObj && $msgObj->deleted_by == null){
                $msgObj->deleted_by = Session::get('user_id');
                $msgObj->save();
                $this->emitTo('conversation','updateMsg', $msgObj->id,5);
            }else if($msgObj && $msgObj->deleted_by != null){
                $msgObj->deleted_by = null;
                $msgObj->save();
                $this->emitTo('conversation','updateMsg', $msgObj->id,5);
            }
        }        
    }

    public function deleteForAllMsg($msgId){
        if($msgId){
            $msgObj = ChatMessage::where('id','LIKE','%'.$msgId)->first();
            if($msgObj && $msgObj->deleted_at == null){
                $mainWhatsLoopObj = new \OfficialHelper();
                $result = $mainWhatsLoopObj->deleteMessageForAll(['messageId'=>$msgObj->id]);
                $msgObj->deleted_at = date('Y-m-d H:i:s');
                $msgObj->deleted_by = null;
                $msgObj->save();
                $this->emitTo('conversation','updateMsg', $msgObj->id,6);
            }
        }        
    }

    public function starMsg($msgId){
        if($msgId){
            $msgObj = ChatMessage::where('id','LIKE','%'.$msgId)->first();
            $mainWhatsLoopObj = new \OfficialHelper();
            if($msgObj && !$msgObj->starred){
                $result = $mainWhatsLoopObj->starMessage(['messageId'=>$msgObj->id]);
                $msgObj->starred = 1;
                $msgObj->save();
                $this->emitTo('conversation','updateMsg', $msgObj->id,7);
            }else if($msgObj && $msgObj->starred){
                $result = $mainWhatsLoopObj->unstarMessage(['messageId'=>$msgObj->id]);
                $msgObj->starred = 0;
                $msgObj->save();
                $this->emitTo('conversation','updateMsg', $msgObj->id,8);
            }
        }        
    }

    public function labelMsg($msgId,$labels){
        if($msgId){
            $msgObj = ChatMessage::where('id','LIKE','%'.$msgId)->first();
            $mainWhatsLoopObj = new \OfficialHelper();
            if($msgObj){
                if($msgObj->labelled == null && !empty($labels)){
                    foreach ($labels as $value) {
                        $result = $mainWhatsLoopObj->labelMessage(['messageId'=>$msgObj->id,'labelId'=>$value]);
                    }
                    $msgObj->labelled = implode(',', $labels). (mb_substr(implode(',', $labels), -1) != ',' ? ',' : '');
                    $msgObj->save();
                }else if($msgObj->labelled != null && $msgObj->labelled != implode(',', $labels)){
                    $oldLabels = array_unique(explode(',',$msgObj->labelled));
                    $newLabels = array_diff($labels,$oldLabels);
                    $removedLabels = array_diff($oldLabels,$labels);
                    if(!empty($removedLabels)){
                        foreach ($removedLabels as $removed) {
                            $result = $mainWhatsLoopObj->unlabelMessage(['messageId'=>$msgObj->id,'labelId'=>$removed]);
                        }
                    }
                    if(!empty($newLabels)){
                        foreach ($newLabels as $newOne) {
                            $result = $mainWhatsLoopObj->labelMessage(['messageId'=>$msgObj->id,'labelId'=>$newOne]);
                        }
                    }

                    $msgObj->labelled = implode(',', $labels). (mb_substr(implode(',', $labels), -1) != ',' ? ',' : '');
                    $msgObj->save();
                }
                $this->emitTo('conversation','updateMsg', $msgObj->id,9);
            }
        }        
    }

    public function reactionMessage($msgId,$emoji){
        $msgObj = ChatMessage::where('id','LIKE','%'.$msgId)->first();
        $mainWhatsLoopObj = new \OfficialHelper();
        if($msgObj){
            $reactions = ChatMessage::where('quotedMessageId',$msgObj->id)->where('fromMe',1)->orderBy('time','DESC')->first();
            $emoji = $reactions && $reactions->body == $emoji ? 'unset' : $emoji;
            $result = $mainWhatsLoopObj->sendReaction(['messageId'=>$msgObj->id,'phone'=>str_replace('@c.us', '', $this->selected),'reaction'=>$emoji]);
            $this->emitTo('conversation','updateMsg', $msgObj->id,12);
        }
    }

    public function repeatHook($msgId){
        if($msgId){
            $mainWhatsLoopObj = new \OfficialHelper();
            $mainWhatsLoopObj->repeatHook(['messageId'=>$msgId,]);
            $this->emitTo('conversation','updateMsg', $msgId,11);
        }        
    }

    public function reformMessage($text,$contactName,$contactPhone){
        $newText = str_replace("{CUSTOMER_NAME}",$contactName,$text);
        $newText = str_replace("{CUSTOMER_PHONE}",$contactPhone,$newText);
        return $newText;
    }
}
