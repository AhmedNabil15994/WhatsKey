<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChatMessage;

class Chat extends Component
{
    public $chat;
    public $selected;


    public function mount($chat)
    {
        $this->chat = $chat;
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
}
