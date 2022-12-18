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
    protected $listeners = ['loadMore','searchAllChats','chatsChanges','changeDialogStatus'];

    public function mount(){
        $data['limit'] = $this->page_size;
        $data['name'] =  null;
        $this->chats = ChatDialog::dataList($this->page_size, null)['data'];
        $this->chats = json_decode(json_encode($this->chats), true);
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

        $dialogs = ChatDialog::whereHas('Messages')->orderBy('pinned','DESC')->orderByDesc(ChatMessage::select('time')
            ->whereColumn('messages.chatId', 'dialogs.id')
            ->where('time','!=', null)
            ->orderBy('time','DESC')
            ->take(1)
        )->skip($start)->take($this->page_size);
        $this->page += 1;
        $this->chats = array_merge($this->chats,ChatDialog::generateObj($dialogs,0)['data']);
    }

    public function searchAllChats($chats){
        $this->chats = $chats['chats'];
    }

    public function chatsChanges($chat,$domain){
        $oldChats = $this->chats;
        $newChats = [];
        $pinned = [];
        $notPinned = [];
        $item;
        $found = 0;
        $domainUrl = str_replace('myDomain',$domain,config('app.MY_DOMAIN'));
        $chat = json_decode(json_encode($chat), true);
        $chat['image'] = str_replace('http://localhost',$domainUrl,$chat['image']);
        foreach ($oldChats as $key => $value) {
            if($value['id'] == $chat['lastMessage']['chatId']){
                $found = 1;
                $item = $chat;
            }else{
                $item = $value;
            }
            $item = (object) $item;
            if(isset($item->lastMessage)){
                $item->lastMessage = (object) $item->lastMessage;
            }
            if($item->pinned > 0){
                $pinned[] = $item;
            }else{
                $notPinned[] = $item;
            }
        }

        if(!$found){
            $chat = (object) $chat;
            if(isset($chat->lastMessage)){
                $chat->lastMessage = (object) $chat->lastMessage;
            }
            if($chat->pinned > 0){
                $pinned[] = $chat;
            }else{
                $notPinned[] = $chat;
            }
        }

        usort($pinned, function($a, $b) {
            return ((int) $b->pinned) - ((int) $a->pinned);
        });

        usort($notPinned, function($a, $b) {
            return ((int) $b->lastMessage->time) - ((int) $a->lastMessage->time);
        });
        $this->chats = array_merge($pinned,$notPinned);
        if($chat['lastMessage']['fromMe'] == 0){
            $this->emit('playAudio');
        }
    }

    public function changeDialogStatus($data){
        $data = json_decode(json_encode($data), true);
        $pinned = [];
        $notPinned = [];
        $oldChats = $this->chats;
        $oldChats = json_decode(json_encode($oldChats), true);
        $found = 0;
        $chatData = $data['data'];
        if(isset($chatData['chatId'])){
            $chatObj = ChatDialog::getData(ChatDialog::getOne($chatData['chatId']));
            foreach ($oldChats as $key => $value) {
                if($value['id'] == $chatData['chatId']){
                    $found = 1;
                    $item = $chatObj;
                }else{
                    $item = $value;
                }
                $item = (object) $item;
                if(isset($item->lastMessage)){
                    $item->lastMessage = (object) $item->lastMessage;
                }
                if((int)$item->pinned > 0){
                    $pinned[] = $item;
                }else{
                    $notPinned[] = $item;
                }
            }

            if(!$found){
                $chat = $chatObj;
                if($chat){
                    if(isset($chat->lastMessage)){
                        $chat->lastMessage = (object) $chat->lastMessage;
                    }
                    if((int)$chat->pinned > 0){
                        $pinned[] = $chat;
                    }else{
                        $notPinned[] = $chat;
                    }
                }
            }

            usort($pinned, function($a, $b) {
                return ((int) $b->pinned) - ((int) $a->pinned);
            });

            usort($notPinned, function($a, $b) {
                return ((int) $b->lastMessage->time) - ((int) $a->lastMessage->time);
            });
            $this->emitTo('chat','changeDialogStatus',$chatObj,$data['domain']);
            $this->chats = array_merge($pinned,$notPinned);
        }
    }
}
