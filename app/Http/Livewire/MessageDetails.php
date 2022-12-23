<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MessageDetails extends Component
{
    public $msg = '';
    public $noMargin = '';
    public $chatName;
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.message-details');
    }
}
