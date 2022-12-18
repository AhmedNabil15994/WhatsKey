<?php

namespace App\Http\Livewire\Messages;

use Livewire\Component;

class ListSections extends Component
{
    public $msg;
    protected $listeners = ['showModal'];

    public function mount($msg){
        $this->msg = $msg;
    }

    public function showModal($msg){
        $this->msg = $msg;
    }
    
    public function render()
    {
        return view('livewire.Messages.list-sections');
    }

    public function openModal()
    {
        $this->emit('showModal');
    }

    public function closeModal()
    {
        $this->emit('closeModal');
    }
}
