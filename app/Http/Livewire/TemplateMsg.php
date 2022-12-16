<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TemplateMsg extends Component
{
    public $msg = '';

    public function mount(){

    }
    
    public function render()
    {
        return view('livewire.template-msg');
    }
}
