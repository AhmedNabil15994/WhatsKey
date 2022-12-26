<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ChatActions extends Component
{
    public $name;
    protected $listeners = ['newGroup','newMessage'];
    
    public function render()
    {
        return view('livewire.chat-actions');
    }

    public function newGroup($name,$numbers,$phones){
        $phones = json_decode($phones);
        $newPhones = [];
        if($numbers == 3){
            $phones = trim($phones);
            
            $numbersArr = explode(PHP_EOL, $phones);
            for ($i = 0; $i < count($numbersArr) ; $i++) {
                $phone = str_replace('\r', '', $numbersArr[$i]);
                $newPhones[] = $phone;
            }
            $phones = $newPhones;
        }
        if(!empty($phones) && !empty($name)){
            $mainWhatsLoopObj = new \OfficialHelper();
            $result = $mainWhatsLoopObj->createGroup(['phones'=>$phones,'name'=>$name]);
        }
        return 1;
    }

    public function newMessage($msg,$numbers,$phones){
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
        if(!empty($phones) && !empty($msg)){
            $mainWhatsLoopObj = new \OfficialHelper();
            $result = $mainWhatsLoopObj->sendBulkText(['phones'=>$phones,'interval'=> 3,'body'=>$msg]);
        }
        return 1;
    }
}
