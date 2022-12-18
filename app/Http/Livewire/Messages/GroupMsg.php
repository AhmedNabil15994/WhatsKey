<?php

namespace App\Http\Livewire\Messages;

use Livewire\Component;

class GroupMsg extends Component
{
    public $msg = '';
    public $chatName = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.Messages.group-msg');
    }
}
