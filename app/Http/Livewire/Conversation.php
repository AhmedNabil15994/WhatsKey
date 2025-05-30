<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Reply;
use App\Models\Template;
use App\Models\Contact;
use App\Models\ChatMessage;
use App\Models\ChatDialog;
use App\Models\ChatEmpLog;
use App\Models\Category;
use App\Models\Variable;

class Conversation extends Component
{
    // public $chatId;
    // public function mount($chatId)
    // {
    //     $this->chatId = $chatId;
    // }
    public $selected;
    public $chat;
    public $messages;
    public $myImage;
    public $page = 1;
    public $page_size = 30;
    public $replies = [];
    public $templates = [];
    public $contacts = [];
    public $labels = [];

    protected $listeners = ['loadMessages','loadMoreMsgs','newIncomingMsg','sendMsg','changeMessageStatus','updateMsg','updateChat','rejectCall'];

    public function mount($contacts){
        $this->myImage = User::getData(User::find(USER_ID))->photo;
        $this->replies = Reply::dataList(null,3)['data'];
        $this->templates = Template::dataList(1)['data'];
        $this->contacts = json_decode(json_encode($contacts), true);
        $this->labels = Category::dataList(null,null,true)['data'];
    }

    public function loadMessages($data){
        $this->messages = $data['messages']['data'];
        $this->chat = $data['chat'];
        $this->selected = $data['chat']['id'];

        $is_admin = \Session::get('is_admin');
        $user_id = \Session::get('user_id');
        if(!$is_admin){
            ChatEmpLog::newLog($data['chat']['id']);
        }
        $this->emit('conversationOpened');
    }

    public function loadMoreMsgs(){
        $start = $this->page * $this->page_size;
        $msgs = ChatMessage::where('chatId',$this->chat['id'])->orderBy('time','DESC')->skip($start)->take($this->page_size);
        $this->page += 1;
        $this->messages = json_decode(json_encode(array_merge($this->messages,ChatMessage::generateObj($msgs,0)['data'])), true);
        $this->emit('updateMessages');
    }

    public function render()
    {   
        return view('livewire.conversation');
    }

    public function newIncomingMsg($data){
        $data = json_decode(json_encode($data), true);
        $chat = $data['message'];
        $chat['lastMessage'] = (array) $chat['lastMessage'];
        $msg = $chat['lastMessage'];
        $is_admin = \Session::get('is_admin');
        if($msg['chatId'] == $this->selected){
            $msgs = array_reverse($this->messages);
            if(isset($this->messages[0]) && $this->messages[0]['id'] != $chat['lastMessage']['id']){
                if(!in_array($msg['message_type'],['reaction',])){
                    $msgs[]= $msg;
                }else{
                    $newMsgs = [];
                    $msg = json_decode(json_encode($msg), true);
                    foreach ($msgs as $key => $value) {
                        $value = json_decode(json_encode($value), true);
                        if(isset($msg['metadata']['quotedMessageId']) && $value['id'] == $msg['metadata']['quotedMessageId']){
                            $value = ChatMessage::getData(ChatMessage::getOne($msg['metadata']['quotedMessageId']));
                        }
                        $newMsgs[] = $value;
                    }
                    $msgs = $newMsgs;
                }
            }else{
                if($msg['message_type'] != 'call'){
                    $msgs[] = $msg;
                }
            }
            $msgs = array_reverse($msgs);
            $this->messages = $msgs;
        }else{
            if($chat['lastMessage']['fromMe'] == 0 && !$chat['muted'] && $is_admin){
                $this->emit('playAudio');
            }
        }

        $this->emit('updatePresence',[
            'chatId' => $msg['chatId'],
            'presence' => '',
        ]);
        $this->emit('scrollDown');

        if($msg['message_type'] == 'call'){
            $varObj = Variable::getVar('disableReceivingCalls');
            if($varObj == '0' || $varObj == null){
                if(!in_array($msg['metadata']['status'],['reject','timeout'])){
                    $callObj = (object) [
                        'title' => $msg['metadata']['isVideo'] ? trans('main.incomingVideoCall') : trans('main.incomingVoiceCall'),
                        'name' => $chat['name'],
                        'image' => $chat['image'],
                        'text' => $chat['lastMessage']['body'],
                        'callId' => array_reverse(explode('_',$msg['id']))[0],
                    ];
                    $this->emit('incomingCall', $callObj);
                }else{
                    $this->emit('closeCall');
                }    
            }
        } 

        if(isset($chat['lastMessage']) && isset($chat['lastMessage']['id']) && isset($this->messages) && isset($this->messages[0])){
            $this->emitTo('chats','chatsChanges',$msg['chatId']); 
        }
    }

    public function changeMessageStatus($data){
        $data = json_decode(json_encode($data), true);
        $msgs = [];
        $incomingFromMe = str_contains($data['messageId'], 'true_');
        if($data['chatId'] == $this->selected){
            foreach ($this->messages as $key => $value) {
                if($value['id'] == $data['messageId']){
                    if(!in_array($data['statusInt'], ['starred','unstarred','labelled','unlabelled'])){
                        $value['sending_status'] = (int)$data['statusInt'];
                    }
                    if($data['statusInt'] == 6){
                        $value['deleted_at'] = date('Y-m-d H:i:s'); 
                    }

                    if(isset($data['statusInt']) && in_array($data['statusInt'], ['starred','unstarred'])){
                        $value['starred'] = $data['statusInt'] == 'starred' ? 1 : 0; 
                    }

                    if(isset($data['statusInt']) && in_array($data['statusInt'], ['labelled','unlabelled'])){
                        $value = (array) ChatMessage::getData(ChatMessage::getOne($data['messageId']));
                    }
                }else if ($value['fromMe'] == $incomingFromMe && in_array($data['statusInt'], [3,])) {
                    $value['sending_status'] = $data['statusInt'];
                }
                $msgs[] = $value;
            }
            $this->messages = $msgs;
        }

        if($data['chatId']){
            // $this->emitTo('chats','chatsChanges',$data['message'],$data['domain']); 
            $this->emitTo('chat','lastUpdates',$data['messageId'],$data['chatId'],$data['statusInt'],$data['domain']); 
        }
        $this->emit('updateMessages');
    }

    public function updateMsg($msgId,$type){
        $msgs = [];
        $msgObj = ChatMessage::getOne($msgId);
        if($msgObj->chatId == $this->selected){
            foreach ($this->messages as $key => $value) {
                if($value['id'] == $msgId){
                    $value = (array) ChatMessage::getData($msgObj);
                }
                $msgs[] = $value;
            }
            $this->messages = $msgs;
        }

        if($msgObj->chatId){
            $this->emitTo('chat','lastUpdates',$msgId,$msgObj->chatId,$type,\Session::get('domain')); 
        }
    }    

    public function updateChat($chatId)
    {
        $chatObj = ChatDialog::getOne($chatId);
        if($chatObj && $chatId == $this->selected){
            $data = ChatDialog::getData($chatObj);
            $chat = json_decode(json_encode($data), true);
            $this->chat = $chat;
            $this->emitTo('chats','chatsChanges',$chatId); 
            $this->emitTo('chat-actions','updateSelected',$chat['name']);
            $this->emitTo('contact-details','setSelected',$chat);
        }
    }

    public function rejectCall($callId){
        $msgObj = ChatMessage::where('id','LIKE','%'.$callId.'%')->first();
        if($msgObj){
            $chatId = $msgObj->chatId;
            $mainWhatsLoopObj = new \OfficialHelper();
            $result = $mainWhatsLoopObj->rejectCall(str_contains($chatId, '@g.us') ? ['chat'=>$chatId,'callId' => $callId] : ['phone'=>$chatId,'callId' => $callId]);
            $this->emit('closeCall');
        }
    }
}
