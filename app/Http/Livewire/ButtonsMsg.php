<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ButtonsMsg extends Component
{
    public $msg = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.buttons-msg');
    }
}
