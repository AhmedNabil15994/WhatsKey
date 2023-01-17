<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Request;
use App\Models\ChatDialog;
use App\Models\Variable;
use App\Models\ChatMessage;

class SearchChats extends Component
{
    protected $listeners = ['searchChats'];
    public $name = null;
    public $hasSearch = 0;

    public function render()
    {
        return view('livewire.search-chats');
    }


    public function searchChats(){
        if($this->name != null){
            $varObj = Variable::getVar('disableDialogsArchive');

            $source = ChatDialog::whereHas('Messages')->with(['LastMessage','SenderLastMessage'])->where(function($whereQuery) use ($varObj){
                if($varObj == 1){
                    $whereQuery->where('archived',0)->orWhere('archived',null);
                }
            })->where('name','LIKE','%'.$this->name.'%')->orWhere('id','LIKE','%'. str_replace('+','',$this->name).'%')->orderBy('pinned','DESC')->orderByDesc(ChatMessage::select('time')
                ->whereColumn('messages.chatId', 'dialogs.id')
                ->orderBy('time','DESC')
                ->take(1)
            );

            $chats = ChatDialog::generateObj($source,20)['data'];
            $this->hasSearch = 1;
        }else{
            $chats = ChatDialog::dataList(20)['data'];
            $this->hasSearch = 0;
        }
        $this->name = '';
        $this->emitTo('chats','searchAllChats',[
            'chats' => $chats,
            'hasSearch' => $this->hasSearch,
        ]); 
        $this->emit('refreshDesign');
    }
}
