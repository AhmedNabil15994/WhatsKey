<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DisappearingMsg extends Component
{
    public $msg = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.disappearing-msg');
    }
}
