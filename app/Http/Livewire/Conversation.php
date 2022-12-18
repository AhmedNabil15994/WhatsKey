<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ChatMessage;

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

    protected $listeners = ['loadMessages','loadMoreMsgs','newIncomingMsg','sendMsg','changeMessageStatus'];

    public function mount(){
        $this->myImage = User::getData(User::find(USER_ID))->photo;
    }

    public function loadMessages($data){
        $this->messages = $data['messages']['data'];
        $this->chat = $data['chat'];
        $this->selected = $data['chat']['id'];
        $this->emit('conversationOpened');
    }

    public function loadMoreMsgs(){
        $start = $this->page * $this->page_size;
        $msgs = ChatMessage::where('chatId',$this->chat['id'])->orderBy('time','DESC')->skip($start)->take($this->page_size);
        $this->page += 1;
        $this->messages = json_decode(json_encode(array_merge($this->messages,ChatMessage::generateObj($msgs,0)['data'])), true);
    }

    public function render()
    {   
        return view('livewire.conversation');
    }

    public function newIncomingMsg($data){
        $chat = $data['message'];
        $chat['lastMessage'] = (array) $chat['lastMessage'];
        $msg = $chat['lastMessage'];
        if($msg['chatId'] == $this->selected){
            if($this->messages[0]['id'] != $chat['lastMessage']['id']){
                $msgs = array_reverse($this->messages);
                if(!in_array($msg['message_type'],['reaction','poll_vote','poll_unvote'])){
                    $msgs[]= $msg;
                }
                $msgs = array_reverse($msgs);
                $this->messages = $msgs;
                $this->emit('conversationOpened');
            }
        }
        if(isset($chat['lastMessage']) && isset($chat['lastMessage']['id']) && $this->messages[0]['id'] != $chat['lastMessage']['id']){
            $this->emitTo('chats','chatsChanges',$data['message'],$data['domain']); 
            $this->emitTo('chat','lastUpdates',$data['message'],$data['domain']); 
        }
    }

    public function changeMessageStatus($data){
        $data = json_decode(json_encode($data), true);
        $msgs = [];
        $incomingFromMe = str_contains($data['messageId'], 'true_');
        if($data['chatId'] == $this->selected){
            foreach ($this->messages as $key => $value) {
                if($value['id'] == $data['messageId']){
                    $value['sending_status'] = $data['statusInt'];
                }else if ($value['fromMe'] == $incomingFromMe) {
                    $value['sending_status'] = $data['statusInt'];
                }
                $msgs[] = $value;
            }
        }
        $this->messages = $msgs;
    }    
}
