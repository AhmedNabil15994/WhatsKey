<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ImageMsg extends Component
{
    public $msg = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.image-msg');
    }
}
