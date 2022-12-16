<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GifMsg extends Component
{
    public $msg = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.gif-msg');
    }
}
