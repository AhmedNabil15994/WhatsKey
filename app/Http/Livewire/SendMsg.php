<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SendMsg extends Component
{
    public $msgBody = '';
    public function mount(){

    }

    public function render()
    {
        return view('livewire.send-msg');
    }

    public function sendMsg(){
        dd($this->msgBody);
    }
}
