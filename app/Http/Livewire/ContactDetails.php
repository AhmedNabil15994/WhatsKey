<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChatDialog;
use App\Models\Contact;
use App\Models\User;

class ContactDetails extends Component
{
    public $chat;
    public $selected;
    public $mods;
    public $contacts;
    public $groupInviteLink;
    protected $listeners = ['setSelected','addGroupParticipants','removeGroupParticipants','promoteGroupParticipants','demoteGroupParticipants','getInviteCode','updateGroupSettings'];

    public function mount()
    {
        $this->mods = User::getModerators()['data'];
        $this->contacts = Contact::dataList(1)['data'];

    }

    public function setSelected($chatObj){
        $chatObj = json_decode(json_encode($chatObj), true);
        $contactObj = Contact::NotDeleted()->where('phone', str_replace('@c.us', '', $chatObj['id']))->orWhere('phone', str_replace('@g.us', '', $chatObj['id']))->first();
        if ($contactObj == null) {
            $contactObj = new Contact;
            $contactObj->group_id = 1;
            $contactObj->status = 1;
            $contactObj->phone =  str_contains($chatObj['id'], 'g.us') ? str_replace('@g.us', '', $chatObj['id']) : str_replace('@c.us', '', $chatObj['id']);
            $contactObj->save();
        }
        $contact_details = Contact::getData($contactObj, null, null, true);
        $contact_details->name = $chatObj['name'];
        $chatObj['contact_details'] = (array)$contact_details;
        $this->chat = $chatObj;
        $this->selected = $chatObj['id'];
        $this->emit('refreshDesign');
    }

    public function addGroupParticipants($numbers,$phones){
        $phones = json_decode($phones);
        $newPhones = [];
        if($numbers == 2){
            $phones = trim($phones);
            
            $numbersArr = explode(PHP_EOL, $phones);
            for ($i = 0; $i < count($numbersArr) ; $i++) {
                $phone = str_replace('\r', '', $numbersArr[$i]);
                $newPhones[] = $phone;
            }
            $phones = $newPhones;
        }
        if(!empty($phones)){
            $mainWhatsLoopObj = new \OfficialHelper();
            $result = $mainWhatsLoopObj->addParticipants(['phones'=>$phones,'groupId'=>$this->selected]);
        }
        return 1;
    }

    public function removeGroupParticipants($phone){
        $phones = [$phone];
        if(!empty($phones)){
            $mainWhatsLoopObj = new \OfficialHelper();
            $result = $mainWhatsLoopObj->removeParticipants(['phones'=>$phones,'groupId'=>$this->selected]);
        }
        return 1;
    }

    public function promoteGroupParticipants($phone){
        $phones = [$phone];
        if(!empty($phones)){
            $mainWhatsLoopObj = new \OfficialHelper();
            $result = $mainWhatsLoopObj->promoteParticipants(['phones'=>$phones,'groupId'=>$this->selected]);
        }
        return 1;
    }

    public function demoteGroupParticipants($phone){
        $phones = [$phone];
        if(!empty($phones)){
            $mainWhatsLoopObj = new \OfficialHelper();
            $result = $mainWhatsLoopObj->demoteParticipants(['phones'=>$phones,'groupId'=>$this->selected]);
        }
        return 1;
    }

    public function getInviteCode(){
        $mainWhatsLoopObj = new \OfficialHelper();
        $result = $mainWhatsLoopObj->getInviteCode(['groupId'=>$this->selected]);
        $result = $result->json();
        if($result && isset($result['data']) && isset($result['data']['code'])){
            $this->groupInviteLink = $result['data']['code'];
        } 
    }

    public function updateGroupSettings($name,$send_messages,$edit_info,$groupDescription){
        $chatObj = ChatDialog::getOne($this->selected);
        $mainWhatsLoopObj = new \OfficialHelper();

        if($name && $name != ''){
            $mainWhatsLoopObj->renameGroup(['groupId'=>$this->selected,'name'=>$name]);
            if($chatObj){
                $chatObj->name = $name;
                $chatObj->save();
            }
        }

        if($groupDescription && $groupDescription != ''){
            $mainWhatsLoopObj->updateDescription(['groupId'=>$this->selected,'description'=>$groupDescription]);
            if($chatObj){
                $chatObj->group_description = $groupDescription;
                $chatObj->save();
            }
        }

        if(in_array($send_messages, ['announcement','not_announcement'])){
            $mainWhatsLoopObj->updateSetting(['groupId'=>$this->selected,'setting'=>$send_messages]);
            if($chatObj){
                $chatObj->send_messages = $send_messages;
                $chatObj->save();
            }
        }
        
        if(in_array($edit_info, ['locked','unlocked'])){
            $mainWhatsLoopObj->updateSetting(['groupId'=>$this->selected,'setting'=>$edit_info]);
            if($chatObj){
                $chatObj->edit_info = $edit_info;
                $chatObj->save();
            }
        }
    }

    public function render()
    {
        return view('livewire.contact-details');
    }
    
}
