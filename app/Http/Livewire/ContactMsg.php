<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ContactMsg extends Component
{
    public $msg = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.contact-msg');
    }
}
