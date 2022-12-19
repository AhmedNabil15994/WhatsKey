<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChatMessage;

class Chat extends Component
{
    public $chat;
    public $selected;

    protected $listeners = ['lastUpdates','changeDialogStatus'];

    public function mount($chat)
    {
        $chat = json_decode(json_encode($chat), true);
        $this->chat = $chat;
        // dd($this->chat);
    }

    public function render()
    {
        return view('livewire.chat');
    }

    public function openMessages($chatId){
        $this->selected = $chatId;
        $this->emitTo('conversation','loadMessages',[
            'chat' => $this->chat,
            'messages' => ChatMessage::dataList($this->chat['id'], 30),
        ]); 
    }

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
