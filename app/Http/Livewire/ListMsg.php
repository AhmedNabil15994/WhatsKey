<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ListMsg extends Component
{
    public $msg = '';

    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.list-msg');
    }
}
