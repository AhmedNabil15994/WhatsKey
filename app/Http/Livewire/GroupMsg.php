<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GroupMsg extends Component
{
    public $msg = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.group-msg');
    }
}
