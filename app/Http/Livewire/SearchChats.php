<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Request;
use App\Models\ChatDialog;

class SearchChats extends Component
{
    protected $listeners = ['searchChats'];
    public $name = null;

    public function render()
    {
        return view('livewire.search-chats');
    }


    public function searchChats(){
        $input['limit'] = 30;
        $input['name'] =  $this->name;
        $chats = ChatDialog::dataList($input['limit'], $input['name'])['data'];
        $this->name = '';
        $this->emitTo('chats','searchAllChats',[
            'chats' => $chats,
        ]); 
        $this->emit('refreshDesign');
    }
}
