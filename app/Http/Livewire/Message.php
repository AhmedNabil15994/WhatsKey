<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Message extends Component
{
    public $msg = '';
    public $chatName = '';
    public function render()
    {
        return view('livewire.message');
    }
}
