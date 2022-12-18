<?php

namespace App\Http\Livewire\Messages;

use Livewire\Component;

class OrderMsg extends Component
{
    public $msg = '';
    public $chatName = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.Messages.order-msg');
    }
}
