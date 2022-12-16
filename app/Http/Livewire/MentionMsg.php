<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MentionMsg extends Component
{
    public $msg = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.mention-msg');
    }
}
