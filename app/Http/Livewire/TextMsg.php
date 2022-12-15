<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TextMsg extends Component
{
    public $msg = '';

    public function mount(){

    }

    public function render()
    {
        return view('livewire.text-msg');
    }
}
