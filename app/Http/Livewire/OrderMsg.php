<?php

namespace App\Http\Livewire;

use Livewire\Component;

class OrderMsg extends Component
{
    public $msg = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.order-msg');
    }
}
