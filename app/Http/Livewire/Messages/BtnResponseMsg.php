<?php

namespace App\Http\Livewire\Messages;

use Livewire\Component;

class BtnResponseMsg extends Component
{
    public $msg = '';
    public $chatName = '';
    public function mount(){

    }

    public function render()
    {
        return view('livewire.Messages.btn-response-msg');
    }
}
