<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MessageReply extends Component
{
    public $msg = '';
    public $chatName = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.message-reply');
    }
}
