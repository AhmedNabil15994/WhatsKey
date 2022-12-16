<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PollMsg extends Component
{
    public $msg = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.poll-msg');
    }
}
