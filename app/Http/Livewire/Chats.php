<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChatDialog;
use App\Models\ChatMessage;
use Request;
use Response;

class Chats extends Component
{
    public $page = 1;
    public $page_size = 30;
    public $chats;
    protected $listeners = ['loadMore','searchAllChats'];

    public function mount(){
        $data['limit'] = $this->page_size;
        $data['name'] =  null;
        $this->chats = ChatDialog::dataList($this->page_size, null)['data'];
    }

    public function render()
    {   
        $input = Request::all();
        // if ((!isset($input['mine']) || empty($input['mine'])) && !\Helper::checkRules('list-livechat')) {
        //     $dataList['data'] = 'disabled';
        //     $dataList['status'] = \TraitsFunc::SuccessMessage();
        //     return Response::json((object) $dataList);
        // }

        $data['limit'] = isset($input['limit']) && !empty($input['limit']) ? $input['limit'] : $this->page_size;
        $data['name'] = isset($input['name']) && !empty($input['name']) ? $input['name'] : null;

        // if(!IS_ADMIN && !\Helper::checkRules('list-dialogs')){
        //     $request->merge(['mine' => "USER_ID"]);
        // }

        $dialogs = ChatDialog::dataList($data['limit'], $data['name']);
        // $dataList = $dialogs;
        // if ($data['name'] == null && IS_ADMIN ) {
        //     $dataList['pinnedConvs'] = ChatDialog::getPinned()['data'];
        // }
        

        // $data['chats'] = $dialogs['data'];
        return view('livewire.chats');//->with('chats',(object) $dialogs['data']);
    }

    public function loadMore(){
        $start = $this->page * $this->page_size;

        $dialogs = ChatDialog::orderBy('pinned','DESC')->orderByDesc(ChatMessage::select('time')
            ->whereColumn('messages.chatId', 'dialogs.id')
            ->orderBy('time','DESC')
            ->take(1)
        )->skip($start)->take($this->page_size);
        $this->page += 1;
        $this->chats = array_merge($this->chats,ChatDialog::generateObj($dialogs,0)['data']);
    }

    public function searchAllChats($chats){
        $this->chats = $chats['chats'];
    }

}
