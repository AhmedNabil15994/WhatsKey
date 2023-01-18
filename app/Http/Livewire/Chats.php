<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChatDialog;
use App\Models\ChatMessage;
use App\Models\Variable;
use Request;
use Response;

class Chats extends Component
{
    public $page = 1;
    public $page_size = 20;
    public $chats;
    public $hasSearch=0;
    protected $listeners = ['loadMore','searchAllChats','chatsChanges','changeDialogStatus','readChat','pinChat','archiveChat','deleteChat','muteChat','labelChat','blockChat','leaveGroup','updateDialogPresence'];

    public function mount(){
        $data['limit'] = $this->page_size;
        $data['name'] =  null;
        $this->chats = ChatDialog::dataList($this->page_size, null)['data'];
        $this->chats = json_decode(json_encode($this->chats), true);
    }

    public function render()
    {   
        return view('livewire.chats');
    }

    public function loadMore(){
        $start = $this->page * $this->page_size;
        $varObj = Variable::getVar('disableDialogsArchive');
        $is_admin = Session::get('is_admin');
        $user_id = Session::get('user_id');
        $dialogs = ChatDialog::whereHas('Messages')->where(function($whereQuery) use ($varObj,$is_admin,$user_id){
            if($varObj == '1'){
                $whereQuery->where('archived',0)->orWhere('archived',null);
            }
            if(!$is_admin){
                $whereQuery->where('modsArr','LIKE','%'.$user_id.'%');
            }
        })->orderBy('pinned','DESC')->orderByDesc(ChatMessage::select('time')
            ->whereColumn('messages.chatId', 'dialogs.id')
            ->where('time','!=', null)
            ->orderBy('time','DESC')
            ->take(1)
        )->skip($start)->take($this->page_size);
        $this->page += 1;
        $chats = array_merge($this->chats,ChatDialog::generateObj($dialogs,0)['data']);
        $this->chats = json_decode(json_encode($chats), true);
        $this->emit('refreshDesign');
    }

    public function searchAllChats($chats){
        $this->chats = $chats['chats'];
        $this->hasSearch = $chats['hasSearch'];
        $this->emit('refreshDesign');
    }

    public function chatsChanges($chatId,$delete=false){
        $oldChats = $this->chats;
        $newChats = [];
        foreach ($oldChats as $key => $value) {
            if($value['id'] == $chatId){
                if(!$delete){
                    $chatObj = (array) ChatDialog::getData(ChatDialog::getOne($chatId));
                    $chatObj['presence'] = '';
                    $newChats[] = $chatObj;
                }
            }else{
                $newChats[] = $value;
            }
        }

        $this->chats = $newChats;
    }

    public function changeDialogStatus($data){
        if(!$this->hasSearch && \Session::get('is_admin') == 1){

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
                    if(isset($b->lastMessage) && isset($a->lastMessage)){
                        return ((int) $b->lastMessage->time) - ((int) $a->lastMessage->time);
                    }
                });

                $pinned = json_decode(json_encode($pinned), true);
                $notPinned = json_decode(json_encode($notPinned), true);
                // $this->emitTo('chat','changeDialogStatus',$chatObj,$data['domain']);
                $this->chats = json_decode(json_encode(array_merge($pinned,$notPinned)), true);
                $this->emit('refreshDesign');
            }
        }
    }

    public function updateDialogPresence($data)
    {
        if(\Session::get('is_admin') == 1){
            $data = json_decode(json_encode($data), true);
            $oldChats = $this->chats;
            $oldChats = json_decode(json_encode($oldChats), true);
            $newData = [];
            $chatData = $data['data'];
            if(isset($chatData['chatId'])){
                $presence = '';
                if(in_array($chatData['presenceData']['presence'],['recording','typing'])){
                    $presence = str_contains($chatData['chatId'],'@g.us') ?  $chatData['presenceData']['phone'] . ' is '. $chatData['presenceData']['presence'] . ' ......' : $chatData['presenceData']['presence'] . ' ......' ;
                    $this->emit('updatePresence',[
                        'chatId' => $chatData['chatId'],
                        'presence' => $presence,
                    ]);
                }
            }
        }
        
    }

    public function readChat($chatId){
        $chatObj = ChatDialog::getOne($chatId);
        if($chatObj){
            $mainWhatsLoopObj = new \OfficialHelper();
            if($chatObj->unreadCount == -1){
                $result = $mainWhatsLoopObj->readChat(str_contains($chatId, '@g.us') ? ['chat'=>$chatId] : ['phone'=>$chatId]);
                $chatObj->unreadCount = 0;
                $chatObj->save();
            }else{
                $result = $mainWhatsLoopObj->unreadChat(str_contains($chatId, '@g.us') ? ['chat'=>$chatId] : ['phone'=>$chatId]);
                $chatObj->unreadCount = -1;
                $chatObj->save();
            }
            $this->emit('refreshDesign');
        }
    }

    public function pinChat($chatId){
        $chatObj = ChatDialog::getOne($chatId);
        if($chatObj){
            $chatId = str_replace('@c.us','',$chatId);
            $mainWhatsLoopObj = new \OfficialHelper();
            if($chatObj->pinned == 0){
                $result = $mainWhatsLoopObj->pinChat(str_contains($chatId, '@g.us') ? ['chat'=>$chatId] : ['phone'=>$chatId]);
                $chatObj->pinned = 1;
                $chatObj->save();
            }else{
                $result = $mainWhatsLoopObj->unpinChat(str_contains($chatId, '@g.us') ? ['chat'=>$chatId] : ['phone'=>$chatId]);
                $chatObj->pinned = 0;
                $chatObj->save();
            }
            $this->emit('refreshDesign');
        }
    }

    public function archiveChat($chatId){
        $chatObj = ChatDialog::getOne($chatId);
        if($chatObj){
            $mainWhatsLoopObj = new \OfficialHelper();
            if($chatObj->archived == 0){
                $result = $mainWhatsLoopObj->archiveChat(str_contains($chatId, '@g.us') ? ['chat'=>$chatId] : ['phone'=>$chatId]);
                $chatObj->archived = 1;
                $chatObj->save();
            }else{
                $result = $mainWhatsLoopObj->unarchiveChat(str_contains($chatId, '@g.us') ? ['chat'=>$chatId] : ['phone'=>$chatId]);
                $chatObj->archived = 0;
                $chatObj->save();
            }
            $this->emit('refreshDesign');
        }
    }

    public function deleteChat($chatId){
        $chatObj = ChatDialog::getOne($chatId);
        if($chatObj){
            $newChats = [];
            $mainWhatsLoopObj = new \OfficialHelper();
            $result = $mainWhatsLoopObj->deleteChat(str_contains($chatId, '@g.us') ? ['chat'=>$chatId] : ['phone'=>$chatId]);
            $chatObj->delete();
            foreach($this->chats as $key => $chat){
                if($chat['id'] != $chatId){
                    $newChats[] = $chat;
                }
            }
            $this->chats = $newChats;
            $this->emit('refreshDesign');
        }
    }

    public function blockChat($chatId){
        $chatObj = ChatDialog::getOne($chatId);
        if($chatObj){
            $newChats = [];
            $mainWhatsLoopObj = new \OfficialHelper();
            if($chatObj->blocked == 0){
                $result = $mainWhatsLoopObj->blockUser(['phone'=>$chatId]);
                $chatObj->blocked = 1;
                $chatObj->save();
            }else{
                $result = $mainWhatsLoopObj->unblockUser(['phone'=>$chatId]);
                $chatObj->blocked = 0;
                $chatObj->save();
            }
            foreach($this->chats as $key => $chat){
                if($chat['id'] == $chatId){
                    $chat['blocked'] = $chatObj->blocked;
                }
                $newChats[] = $chat;
            }
            $this->chats = $newChats;
            $this->emitTo('conversation','updateChat',$chatId);
            $this->emit('refreshDesign');
        }
    }

    public function muteChat($chatId,$duration){
        $chatObj = ChatDialog::getOne($chatId);
        if($chatObj){
            $newChats = [];
            $mainWhatsLoopObj = new \OfficialHelper();
            if($chatObj->muted == 0){
                $result = $mainWhatsLoopObj->muteChat(str_contains($chatId, '@g.us') ? ['chat'=>$chatId,'duration' => $duration*3600] : ['phone'=>$chatId,'duration' => $duration*3600]);
                $chatObj->muted = 1;
                $chatObj->muted_until = date('Y-m-d H:i:s',strtotime('+'.$duration.' day',strtotime(date('Y-m-d H:i:s'))));
                $chatObj->save();
            }else{
                $result = $mainWhatsLoopObj->unmuteChat(str_contains($chatId, '@g.us') ? ['chat'=>$chatId] : ['phone'=>$chatId]);
                $chatObj->muted = 0;
                $chatObj->muted_until = null;
                $chatObj->save();
            }
            foreach($this->chats as $key => $chat){
                if($chat['id'] == $chatId){
                    $chat['muted'] = $chatObj->muted;
                }
                $newChats[] = $chat;
            }
            $this->chats = $newChats;
            $this->emitTo('conversation','updateChat',$chatId);
            $this->emit('refreshDesign');
        }
    }

    public function labelChat($chatId,$labels){
        if($chatId){
            $chatObj = ChatDialog::getOne($chatId);
            $mainWhatsLoopObj = new \OfficialHelper();
            if($chatObj){
                if($chatObj->labels == null && !empty($labels)){
                    foreach ($labels as $value) {
                        $result = $mainWhatsLoopObj->labelChat(str_contains($chatId, '@g.us') ? ['chat'=>$chatId,'labelId'=>$value] : ['phone'=>$chatId,'labelId'=>$value]);
                    }
                    $chatObj->labels = implode(',', $labels). (mb_substr(implode(',', $labels), -1) != ',' ? ',' : '');
                    $chatObj->save();
                }else if($chatObj->labels != null && $chatObj->labels != implode(',', $labels)){
                    $oldLabels = array_unique(explode(',',$chatObj->labels));
                    $newLabels = array_unique(array_diff($labels,$oldLabels));
                    $removedLabels = array_unique(array_diff($oldLabels,$labels));
                    if(!empty($removedLabels)){
                        foreach ($removedLabels as $removed) {
                            $result = $mainWhatsLoopObj->unlabelChat( str_contains($chatId, '@g.us') ? ['chat'=>$chatId,'labelId'=>$removed] : ['phone'=>$chatId,'labelId'=>$removed] );
                        }
                    }
                    if(!empty($newLabels)){
                        foreach ($newLabels as $newOne) {
                            $result = $mainWhatsLoopObj->labelChat(str_contains($chatId, '@g.us') ? ['chat'=>$chatId,'labelId'=>$newOne] : ['phone'=>$chatId,'labelId'=>$newOne]);
                        }
                    }
                    $updates = implode(',', $labels). (mb_substr(implode(',', $labels), -1) != ',' ? ',' : '');
                    $chatObj->labels = $updates == ',' ? null : $updates;
                    $chatObj->save();
                }
                $this->emitTo('conversation','updateChat', $chatId);
                $this->emit('refreshDesign');
            }
        }        
    }

    public function leaveGroup($chatId){
        if($chatId){
            $mainWhatsLoopObj = new \OfficialHelper();
            return $mainWhatsLoopObj->leaveGroup(['groupId'=>$chatId]);
        }
    }

}
