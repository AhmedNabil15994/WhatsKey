<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\GroupMsg;
use App\Models\ContactReport;
use App\Models\Contact;
use App\Models\ChatMessage;
use App\Models\BotPlus;
use App\Models\UserAddon;
use App\Models\User;
use App\Models\CentralUser;

// implements ShouldQueue
class GroupMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $contacts;
    public $messageObj;

    public function __construct($contacts,$messageObj)
    {
        $this->contacts = $contacts;
        $this->messageObj = $messageObj;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $unsent = $this->messageObj->unsent_msgs;
        $sent = $this->messageObj->sent_msgs;
        
        $botObj = null;
        $messageObj = GroupMsg::NotDeleted()->where('id',$this->messageObj->id)->first();
        if($messageObj->bot_plus_id != null){
            $botObjs = BotPlus::find($messageObj->bot_plus_id);
            $botObj = BotPlus::getData($botObjs);
        }

        $disBotPlus = 0;
        $tenantUser = User::first();
        $disabled = UserAddon::getDeactivated($tenantUser->id);
        if(in_array(10,$disabled)){
            $disBotPlus = 1;
        }

        $centralUser = CentralUser::find(User::first()->id);

        foreach ($this->contacts as $contact) {
            $result = $this->sendData($contact,(array) $this->messageObj,$botObj,$disBotPlus,$centralUser->isBA);
            sleep(3);
            if($result == 1){
                $sent+=1;
            }else{
                $unsent+=1;
            }
        }

        return $messageObj->update([
            'sent_count' => $sent,
            'unsent_count' => $unsent,
            'later' => 0,
        ]);
    }

    public function sendData($contact,$messageObj,$botObj=null,$disBotPlus=0,$isBA=0){
        $contact = (object) $contact;
        $sendData['chatId'] = str_replace('+', '', $contact->phone).'@c.us';
        $status = 0;
        
        $chatId = $sendData['chatId'];
        unset($sendData['chatId']);
        $sendData['phone'] = str_replace('+', '', $contact->phone);


        $responseData = $this->sendGroupMessage($chatId,$contact,$sendData,$messageObj,$botObj,$disBotPlus);
        $sendData = $responseData['sendData'];
        $resp = $responseData['result'];

        if(!$resp){
            sleep(10);
            $responseData = $this->sendGroupMessage($chatId,$contact,$sendData,$messageObj,$botObj,$disBotPlus);
            $sendData = $responseData['sendData'];
            $resp = $responseData['result'];
        }
        
        $sendData['chatId'] = $chatId;
             
        
        $messageId = '';
        if(isset($resp) && $resp && isset($resp['data']) && isset($resp['data']['id'])){
            $messageId = $resp['data']['id'];
            $lastMessage['status'] = 'APP';
            $lastMessage['id'] = $messageId;
            $lastMessage['chatId'] = $sendData['chatId'];
            $status = 1;
            ChatMessage::newMessage($lastMessage);
        }
        ContactReport::newStatus(str_replace('@c.us','',$sendData['chatId']),$messageObj['group_id'],$messageObj['id'],$status,$messageId);
        
        return $status;
    }
    
    public function sendGroupMessage($chatId,$contact,$sendData,$messageObj,$botObj,$disBotPlus){
        $mainWhatsLoopObj = new \OfficialHelper();
        $status = 0;
        $result = null;
        $msg = ChatMessage::where('fromMe',1)->where('chatId',$chatId)->where('time','>=',strtotime(date('Y-m-d H:i:s'))-1800);

        if($messageObj['message_type'] == 1){
            $sendData['body'] = $this->reformMessage($messageObj['message'],$contact->name,str_replace('+', '', $contact->phone));
            if(!$msg->where('body',$sendData['body'])->first()){
                $result = $mainWhatsLoopObj->sendMessage($sendData);
            }
        }elseif($messageObj['message_type'] == 2){
            $sendData['url'] = $messageObj['file'];
            $sendData['caption'] = $this->reformMessage($messageObj['message'],$contact->name,str_replace('+', '', $contact->phone));
            if($messageObj['file_type'] == 'file' && $messageObj['file'] != null){
                if(!$msg->where('body',$sendData['url'])->first()){
                    $result = $mainWhatsLoopObj->sendFile([
                        'phone' => $sendData['phone'],
                        'url' => $sendData['url'],
                    ]);
                }
            }else{
                if(!$msg->where('body',$sendData['url'])->first()){
                    $result = $mainWhatsLoopObj->sendImage($sendData);
                }
            }
        }elseif($messageObj['message_type'] == 3){
            $sendData['url'] = $messageObj['file'];
            if(!$msg->where('body',$sendData['url'])->first()){
                $result = $mainWhatsLoopObj->sendAudio($sendData);
            }
        }elseif($messageObj['message_type'] == 4){            
            $sendData['body'] = $messageObj['url_title'] . " \r\n \r\n ";
            $sendData['body'] .= $messageObj['https_url'] . " \r\n \r\n ";
            $sendData['body'] .= $messageObj['url_desc'];
            $sendData['body'] = $this->reformMessage($sendData['body'],$contact->name,str_replace('+', '', $contact->phone));
            if(!$msg->where('body',$sendData['body'])->first()){
                $result = $mainWhatsLoopObj->sendMessage($sendData);
            }
        }elseif($messageObj['message_type'] == 5){
            $sendData['contactMobile'] = str_replace('+','',$messageObj['whatsapp_no']);
            $sendData['name'] = str_replace('+','',$messageObj['whatsapp_no']);
            if(!$msg->where('body',$sendData['contactMobile'])->first()){
                $result = $mainWhatsLoopObj->sendContact($sendData);
            }
        }elseif($messageObj['message_type'] == 6){
            if(isset($botObj->buttonsData) && !empty($botObj->buttonsData) && !$disBotPlus){
                $buttons = [];
                foreach($botObj->buttonsData as $key => $oneItem){
                    $buttons[]= [
                        'id' => $key+1,
                        'title' => $oneItem['text'],
                    ];
                }
                $sendData['body'] = $this->reformMessage($botObj->body,$contact->name,str_replace('+', '', $contact->phone));
                $sendData['title'] = $this->reformMessage($botObj->title,$contact->name,str_replace('+', '', $contact->phone));
                $sendData['footer'] = $this->reformMessage($botObj->footer,$contact->name,str_replace('+', '', $contact->phone));
                $sendData['buttons'] = $buttons;
                $result = $mainWhatsLoopObj->sendButtons($sendData);
            }
        }

        if(isset($result) && $result){
            $result = $result->json();
            if(isset($result['status']) && isset($result['status']['status']) && $result['status']['status'] != 1){
                $status = 0;
            }else{
                $status = 1;
            }
        }

        return [
            'result' => $result,
            'sendData' => $sendData,
            'status' => $status,
        ];
    }

    public function reformMessage($text,$contactName,$contactPhone){
        $newText = str_replace("{CUSTOMER_NAME}",$contactName,$text);
        $newText = str_replace("{CUSTOMER_PHONE}",$contactPhone,$newText);
        return $newText;
    }
}
