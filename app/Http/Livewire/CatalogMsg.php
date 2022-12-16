<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CatalogMsg extends Component
{
    public $msg = '';

    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.catalog-msg');
    }
}
