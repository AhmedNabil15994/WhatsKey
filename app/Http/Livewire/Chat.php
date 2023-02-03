<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChatMessage;
use App\Models\ChatDialog;
use Session;

class Chat extends Component
{
    public $chat;
    public $selected;

    protected $listeners = ['lastUpdates','changeDialogStatus'];

    public function mount($chat)
    {
        $chat = json_decode(json_encode($chat), true);
        $this->chat = $chat;
    }

    public function render()
    {
        return view('livewire.chat');
    }

    public function openMessages($chatId){
        if(!Session::has('selected_chat_id') || (Session::has('selected_chat_id') && Session::get('selected_chat_id') != $chatId)){
            if(!$this->chat['disable_read']){
                $mainWhatsLoopObj = new \OfficialHelper();
                $result = $mainWhatsLoopObj->readChat(str_contains($this->chat['id'], '@g.us') ? ['chat'=>$this->chat['id']] : ['phone'=>$this->chat['id']]);
                ChatDialog::where('id',$this->chat['id'])->update(['unreadCount'=>0]);
            }
            $this->emitTo('conversation','loadMessages',[
                'chat' => $this->chat,
                'messages' => ChatMessage::dataList($this->chat['id'], 30),
            ]); 

            $this->emitTo('contact-details','setSelected',(array) $this->chat);
        }
        Session::put('selected_chat_id',$chatId);
    }

    public function lastUpdates($msgId,$chatId,$status,$domain){
        $data = ChatDialog::getData(ChatDialog::getOne($chatId));
        if($data){
            $msgObj = ChatMessage::getData(ChatMessage::getOne($msgId));
            $chat = json_decode(json_encode($data), true);
            if($this->chat['id'] == $chatId && $chat['lastMessage']['id'] == $msgId){
                $domainUrl = str_replace('myDomain',$domain,config('app.MY_DOMAIN'));
                $chat['image'] = str_replace('http://localhost',$domainUrl,$chat['image']);
                $chat['lastMessage'] = json_decode(json_encode($msgObj), true);
                $this->chat =  $chat;
            }
        }
    }

    // public function changeDialogStatus($chatId){
    //     $data = ChatDialog::getData(ChatDialog::getOne($chatId));
    //     $data = json_decode(json_encode($data), true);
    //     $domain = \Session::get('domain');
    //     if(isset($data) && isset($data['id']) && $this->chat['id'] == $data['id']){
    //         $domainUrl = str_replace('myDomain',$domain,config('app.MY_DOMAIN'));
    //         $data['image'] = str_replace('http://localhost',$domainUrl,$data['image']);
    //         $this->chat =  $data;
    //     }
    // }

    // public function lastUpdates($data,$domain){
    //     $chat = json_decode(json_encode($data), true);
    //     if($this->chat['id'] == $data['lastMessage']['chatId']){
    //         $domainUrl = str_replace('myDomain',$domain,config('app.MY_DOMAIN'));
    //         $data['image'] = str_replace('http://localhost',$domainUrl,$data['image']);
    //         $this->chat =  $data;
    //     }
    // }

    // public function changeDialogStatus($data,$domain){
    //     $data = json_decode(json_encode($data), true);
    //     if(isset($data) && isset($data['id']) && $this->chat['id'] == $data['id']){
    //         $domainUrl = str_replace('myDomain',$domain,config('app.MY_DOMAIN'));
    //         $data['image'] = str_replace('http://localhost',$domainUrl,$data['image']);
    //         $this->chat =  $data;
    //     }
    // }

    
}
