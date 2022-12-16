<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LinkMsg extends Component
{
    public $msg = '';

    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.link-msg');
    }
}
