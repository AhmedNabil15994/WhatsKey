<?php

namespace App\Http\Livewire;

use Livewire\Component;

class VideoMsg extends Component
{
    public $msg = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.video-msg');
    }
}
