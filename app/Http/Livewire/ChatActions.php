<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ChatActions extends Component
{
    public $name;
    
    public function render()
    {
        return view('livewire.chat-actions');
    }
}
