<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProductMsg extends Component
{
    public $msg = '';
    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.product-msg');
    }
}
