<?php

namespace App\Http\Livewire\Messages;

use Livewire\Component;

class AudioMsg extends Component
{
    public $msg = '';
    public $chatName = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.Messages.audio-msg');
    }
}
