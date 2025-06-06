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
use App\Models\ListMsg;
use App\Models\TemplateMsg;
use App\Models\Poll;
use App\Models\UserAddon;
use App\Models\User;
use App\Models\CentralUser;
use App\Models\ContactGroup;

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

        if($messageObj->list_id != null){
            $botObjs = ListMsg::find($messageObj->list_id);
            $botObj = ListMsg::getData($botObjs);
        }

        if($messageObj->poll_id != null){
            $botObjs = Poll::find($messageObj->poll_id);
            $botObj = Poll::getData($botObjs);
        }

        if($messageObj->template_id != null){
            $botObjs = TemplateMsg::find($messageObj->template_id);
            $botObj = TemplateMsg::getData($botObjs);
        }

        return $this->reformData($this->contacts,(array) $this->messageObj,$botObj);
    }

    public function reformData($contacts,$messageObj,$botObj=null){
        $mainWhatsLoopObj = new \OfficialHelper();
        $allowedContacts = [];
        $hasWhatsapp=0;
        $hasNotWhatsapp=0;
        foreach ($contacts as $contact){
            $checkData['phone'] = str_replace('+', '', $contact->phone);
            $status = 0;
            $checkResult = $mainWhatsLoopObj->checkPhone($checkData);
            $result = $checkResult->json();
            if($result && isset($result['data'])){
                $status = $result['data']['exists'] == true ? 1 : 0;
            }

            if($status){
                $hasWhatsapp+=1;

                $oldName = $contact->name;
                $newName = str_replace('+', '',  str_replace(' ','',$oldName) );
                if($newName == $checkData['phone']){
                    $checkContactObj = Contact::where('phone',$contact->phone)->where('name','!=',$oldName)->first();
                    if($checkContactObj){
                        $newName = $checkContactObj->name;
                    }else{
                        $newName = $oldName;
                    }
                }

                $allowedContacts[] = [
                    'hasWhatsapp' => 1,
                    'phone' => $checkData['phone'],
                    'name' => $newName,
                ];
            }else{
                $hasNotWhatsapp+=1;
            }
            ContactReport::newStatus($checkData['phone'],$messageObj['group_id'],$messageObj['id'],$status,'');
            ContactGroup::newRecord($checkData['phone'],$messageObj['id'],$messageObj['created_at']);
        }
        // For Local
        // $messageObj['file'] = str_replace('newdomain1.whatskey.localhost/', 'd677-154-182-251-196.ngrok.io', $messageObj['file']);

        $phones = [];
        $messageData = [];
        $messageFunction = '';
        $hasVar = 0;
        foreach ($allowedContacts as $key => $contact){
            $phones[$key] = str_replace('+', '', $contact['phone']);
            if($messageObj['message_type'] == 1){
                $messageData[$key]['body'] = $this->reformMessage($messageObj['message'],$contact['name'],str_replace('+', '', $contact['phone']));
                $messageFunction = 'sendBulkText';
                $hasVar = str_contains($messageObj['message'], '{CUSTOMER_NAME}') || str_contains($messageObj['message'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }else if($messageObj['message_type'] == 2){
                $messageData[$key]['caption'] = $this->reformMessage($messageObj['message'],$contact['name'],str_replace('+', '', $contact['phone']));
                 $messageData[$key]['url'] = $messageObj['file'];
                $messageFunction = 'sendBulkImage';
                $hasVar = str_contains($messageObj['message'], '{CUSTOMER_NAME}') || str_contains($messageObj['message'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }else if($messageObj['message_type'] == 3){
                $messageData[$key]['caption'] = $this->reformMessage($messageObj['message'],$contact['name'],str_replace('+', '', $contact['phone']));
                $messageData[$key]['url'] = $messageObj['file'];
                $messageFunction = 'sendBulkVideo';
                $hasVar = str_contains($messageObj['message'], '{CUSTOMER_NAME}') || str_contains($messageObj['message'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }else if($messageObj['message_type'] == 4){
                $messageData[$key]['url'] = $messageObj['file'];
                $messageFunction = 'sendBulkAudio';
            }else if($messageObj['message_type'] == 5){
                $messageData[$key]['url'] = $messageObj['file'];
                $messageFunction = 'sendBulkFile';
            }else if($messageObj['message_type'] == 8){
                $messageData[$key]['address'] = $this->reformMessage($messageObj['message'],$contact['name'],str_replace('+', '', $contact['phone']));
                $messageData[$key]['lat'] = $messageObj['lat'];
                $messageData[$key]['lng'] = $messageObj['lng'];
                $messageFunction = 'sendBulkLocation';
                $hasVar = str_contains($messageObj['message'], '{CUSTOMER_NAME}') || str_contains($messageObj['message'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }else if($messageObj['message_type'] == 9){
                $messageData[$key]['contact'] = str_replace('+', '', $messageObj['message']);
                $messageData[$key]['name'] = str_replace('+', '', $messageObj['message']);
                $messageFunction = 'sendBulkContact';
                $hasVar = str_contains($messageObj['message'], '{CUSTOMER_NAME}') || str_contains($messageObj['message'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }else if($messageObj['message_type'] == 10){
                $messageData[$key]['body'] = $this->reformMessage($messageObj['message'],$contact['name'],str_replace('+', '', $contact['phone']));
                $messageData[$key]['expiration'] = $messageObj['expiration_in_seconds'];
                $messageFunction = 'sendBulkDisappearing';
                $hasVar = str_contains($messageObj['message'], '{CUSTOMER_NAME}') || str_contains($messageObj['message'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }else if($messageObj['message_type'] == 11){
                $messageData[$key]['contact'] = str_replace('+','',$messageObj['message']);
                $messageFunction = 'sendBulkMention';
                $hasVar = str_contains($messageObj['message'], '{CUSTOMER_NAME}') || str_contains($messageObj['message'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }else if($messageObj['message_type'] == 16){
                $messageData[$key]['title'] = $this->reformMessage($messageObj['url_title'],$contact['name'],str_replace('+', '', $contact['phone']));
                $messageData[$key]['url'] = $messageObj['message'];
                $messageData[$key]['description'] = $this->reformMessage($messageObj['url_title'],$contact['name'],str_replace('+', '', $contact['phone']));
                $messageFunction = 'sendBulkLink';
                $hasVar = str_contains($messageObj['url_title'], '{CUSTOMER_NAME}') || str_contains($messageObj['url_title'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }else if($messageObj['message_type'] == 30){
                $buttons = [];
                foreach ($botObj->buttonsData as $buttonKey => $oneItem) {
                    $buttons[] = [
                        'id' => $buttonKey + 1,
                        'title' => $oneItem['text'],
                    ];
                }
                $messageData[$key]['body'] = $botObj->title;
                $messageData[$key]['body'] .= " \r\n \r\n".$this->reformMessage($botObj->body,$contact['name'],str_replace('+', '', $contact['phone']));
                $messageData[$key]['footer'] = $botObj->footer;
                $messageData[$key]['buttons'] = $buttons;
                $messageFunction = 'sendBulkButtons';
                $hasVar = str_contains($botObj->body, '{CUSTOMER_NAME}') || str_contains($messageObj['message'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }else if($messageObj['message_type'] == 31){
                $sections = [];
                foreach ($botObj->sectionsData as $listKey => $oneItem) {
                    $rows = [];
                    foreach($oneItem['rows'] as $oneRow){
                        $rows[] = [
                            'rowId' => $oneRow['rowId'],
                            'title' => $oneRow['title'],
                            'description' => $oneRow['description'],
                        ];
                    }
                    $sections[] = [
                        'title' => $oneItem['title'],
                        'rows' => $rows,
                    ];
                }

                $messageData[$key]['title'] = $botObj->title;
                $messageData[$key]['body'] = $this->reformMessage($botObj->body,$contact['name'],str_replace('+', '', $contact['phone']));
                $messageData[$key]['footer'] = $botObj->footer;
                $messageData[$key]['buttonText'] = $botObj->buttonText;
                $messageData[$key]['sections'] = $sections;
                $messageFunction = 'sendBulkList';
                $hasVar = str_contains($botObj->body, '{CUSTOMER_NAME}') || str_contains($messageObj['message'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }else if($messageObj['message_type'] == 32){
                $options = [];
                foreach ($botObj->optionsData as $pollKey => $oneItem) {
                    $options[] = $oneItem['text'];
                }

                $messageData[$key]['body'] = $this->reformMessage($botObj->body,$contact['name'],str_replace('+', '', $contact['phone']));
                $messageData[$key]['selectableOptionsCount'] = $botObj->selected_options;
                $messageData[$key]['options'] = $options;
                $messageFunction = 'sendBulkPoll';
                $hasVar = str_contains($botObj->body, '{CUSTOMER_NAME}') || str_contains($messageObj['message'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }else if($messageObj['message_type'] == 33){
                $buttons = [];
                foreach ($botObj->buttonsData as $buttonKey => $oneItem) {
                    $buttons[] = [
                        'id' => $oneItem['id'],
                        'title' => $oneItem['text'],
                        'type' => (int)$oneItem['button_type'],
                        'extra_data' => in_array($oneItem['button_type'],[1,2]) ? $oneItem['msg'] : ('id'.$oneItem['id']),
                    ];
                }
                $messageData[$key]['body'] = $botObj->title;
                $messageData[$key]['body'] .= " \r\n \r\n".$this->reformMessage($botObj->body,$contact['name'],str_replace('+', '', $contact['phone']));
                $messageData[$key]['footer'] = $botObj->footer;
                $messageData[$key]['buttons'] = $buttons;
                $messageFunction = 'sendBulkTemplate';
                $hasVar = str_contains($botObj->body, '{CUSTOMER_NAME}') || str_contains($messageObj['message'], '{CUSTOMER_PHONE}') ? 1 : 0;
            }
        }

        $sendRequest = [
            'phones' => $phones,
            'interval' => $messageObj['interval_in_sec'],
            'checked' => 1,
        ];

        if($hasVar){
            $sendRequest['messageData'] = $messageData;
        }else{
            $sendRequest = array_merge($sendRequest,$messageData[0]);
        }

        if(!empty($phones)){
            if($messageObj['message_type'] == 4){
                $testResult = $mainWhatsLoopObj->sendBulkAudio([
                    'phones' => $phones,
                    'interval' => 30 + $messageObj['interval_in_sec'],
                    'url' => $messageObj['file'],
                    'checked' => 1,
                ]);
            }else{
                $mainWhatsLoopObj->$messageFunction($sendRequest);
            }
            $oldObj = GroupMsg::find($messageObj['id']);
            if($messageObj['messages'] > $oldObj->sent_count + $oldObj->unsent_count){
                $oldObj->sent_count = $oldObj->sent_count + $hasWhatsapp;
                $oldObj->unsent_count = $oldObj->unsent_count + $hasNotWhatsapp;
            }            
            $oldObj->later = 0;
            return $oldObj->save();
        }

        return 1;
    }

    public function reformMessage($text,$contactName,$contactPhone){
        $newText = str_replace("{CUSTOMER_NAME}",$contactName,$text);
        $newText = str_replace("{CUSTOMER_PHONE}",$contactPhone,$newText);
        return $newText;
    }
}
