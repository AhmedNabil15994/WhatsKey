<?php
namespace App\Handler;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;
use \Spatie\WebhookClient\ProcessWebhookJob;
use \Spatie\WebhookServer\WebhookCall;

use App\Events\BotMessage;
use App\Events\IncomingMessage;
use App\Events\SentMessage;

use App\Models\User;
use App\Models\UserExtraQuota;
use App\Models\UserStatus;
use App\Models\Bot;

use App\Models\ChatDialog;
use App\Models\ChatMessage;

use App\Models\BotPlus;
use App\Models\ListMsg;
use App\Models\Poll;
use App\Models\Variable;
use App\Models\Template;

use App\Models\ChatSession;
use App\Models\ModTemplate;
use App\Models\OAuthData;
use App\Models\TemplateMsg;
use App\Models\ContactGroup;
use App\Models\GroupMsg;

class MessagesWebhook extends ProcessWebhookJob
{

    public function handle()
    {
        $data = json_decode($this->webhookCall, true);
        $allData = $data['payload'];

        $tenantUser = User::first();
        $tenantObj = DB::connection('main')->table('tenant_users')->where('global_user_id', $tenantUser->global_id)->first();
        $userObj = DB::connection('main')->table('domains')->where('tenant_id', $tenantObj->tenant_id)->first();

        $startDay = strtotime(date('Y-m-d 00:00:00'));
        $endDay = strtotime(date('Y-m-d 23:59:59'));

        $messagesCount = ChatMessage::where('fromMe', 1)->where('status', '!=', null)->where('time', '>=', $startDay)->where('time', '<=', $endDay)->count();


        $membershipFeatures = \DB::connection('main')->table('memberships')->where('id',$tenantUser->membership_id)->first()->features;
        $featuresId = unserialize($membershipFeatures);
        $features = \DB::connection('main')->table('membership_features')->whereIn('id',$featuresId)->pluck('title_en');
        $dailyMessageCount=0;
        foreach ($features as $value) {
            if(str_contains($value,'messages per day')){$dailyMessageCount = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);;}
        }
        $extraQuotas = UserExtraQuota::getOneForUserByType($tenantUser->global_id, 1);
        if ($dailyMessageCount + $extraQuotas <= $messagesCount) {
            return 1;
        }

        $mainWhatsLoopObj = new \OfficialHelper();
        if (isset($allData['event']) && $allData['event'] == 'message-new') {

            $message = (array) $allData['messages'];
            $sender = $message['chatId'];
            $senderMessage = $message['body'];
            $senderMessageType = $message['type'];

            if($message['fromMe'] == true){
                $contact = str_replace('@c.us','',$message['chatId']);
                if($message['type'] == 'buttons'){
                    $buttonsNumber = count($message['metadata']['buttons']);
                    $textsArr = preg_split('/ \r\n| \r|\n /',$message['metadata']['content']);
                    $title = $textsArr[0];
                    $footer = $message['metadata']['footer'];
                    $contactGroups = ContactGroup::where('contact',$contact)->get();
                    foreach($contactGroups as $oneGroup){
                        $groupMsgId = $oneGroup->group_id;
                        $groupMsgObj = GroupMsg::find($groupMsgId);
                        $trues = 0;
                        if($groupMsgObj && $groupMsgObj->bot_plus_id != null){
                            $deleteBot = BotPlus::find($groupMsgObj->bot_plus_id);
                            if($deleteBot && $deleteBot->title == $title && $deleteBot->footer == $footer && $deleteBot->buttons == $buttonsNumber){
                                $buttonsData = $deleteBot->buttonsData != null ? unserialize($deleteBot->buttonsData) : [];
                                $buttonsData = json_decode(json_encode($buttonsData), true);
                                for ($i=0; $i < $buttonsNumber; $i++) { 
                                    if(str_replace('id','',$message['metadata']['buttons'][$i]['id']) == $buttonsData[$i]['id'] && $message['metadata']['buttons'][$i]['title'] == $buttonsData[$i]['text']){
                                        $trues+=1;
                                    }
                                }
                                if($trues == $buttonsNumber){
                                    $message['metadata']['botPlusId'] = $groupMsgObj->bot_plus_id;
                                    $oneGroup->delete();
                                }
                            }
                        }
                    }
                }else if($message['type'] == 'list'){
                    $sectionsNumber = count($message['metadata']['sections']);
                    $title = $message['metadata']['title'];
                    $buttonText = $message['metadata']['buttonText'];
                    $footer = $message['metadata']['footer'];
                    $contactGroups = ContactGroup::where('contact',$contact)->get();
                    foreach($contactGroups as $oneGroup){
                        $groupMsgId = $oneGroup->group_id;
                        $groupMsgObj = GroupMsg::find($groupMsgId);
                        $trues = 0;
                        if($groupMsgObj && $groupMsgObj->list_id != null){
                            $deleteBot = ListMsg::find($groupMsgObj->list_id);
                            if($deleteBot && $deleteBot->title == $title && $deleteBot->footer == $footer && $deleteBot->sections == $sectionsNumber && $deleteBot->buttonText == $buttonText){
                                $sectionsData = $deleteBot->sectionsData != null ? unserialize($deleteBot->sectionsData) : [];
                                $sectionsData = json_decode(json_encode($sectionsData), true);
                                $rows = 0;
                                for ($i=0; $i < $sectionsNumber; $i++) { 
                                    if(count($message['metadata']['sections'][$i]['rows']) == count($sectionsData[$i]['rows']) && $message['metadata']['sections'][$i]['title'] == $sectionsData[$i]['title']){
                                        $rows+=count($message['metadata']['sections'][$i]['rows']);
                                        for ($x=0; $x < count($message['metadata']['sections'][$i]['rows']) ; $x++) { 
                                            if($message['metadata']['sections'][$i]['rows'][$x]['id'] == $sectionsData[$i]['rows'][$x]['rowId'] && $message['metadata']['sections'][$i]['rows'][$x]['title'] == $sectionsData[$i]['rows'][$x]['title'] && $message['metadata']['sections'][$i]['rows'][$x]['description'] == $sectionsData[$i]['rows'][$x]['description']){
                                                $trues+=1;
                                            }
                                        }
                                    }
                                }
                                if($trues == $rows){
                                    $message['metadata']['listId'] = $groupMsgObj->list_id;
                                    $oneGroup->delete();
                                }
                            }
                        }
                    }
                }else if($message['type'] == 'poll'){
                    $optionsNumber = count($message['metadata']['options']);
                    $contactGroups = ContactGroup::where('contact',$contact)->get();
                    foreach($contactGroups as $oneGroup){
                        $groupMsgId = $oneGroup->group_id;
                        $groupMsgObj = GroupMsg::find($groupMsgId);
                        $trues = 0;
                        if($groupMsgObj && $groupMsgObj->poll_id != null){
                            $deleteBot = Poll::find($groupMsgObj->poll_id);
                            if($deleteBot && $deleteBot->options == $optionsNumber){
                                $optionsData = $deleteBot->optionsData != null ? unserialize($deleteBot->optionsData) : [];
                                $optionsData = json_decode(json_encode($optionsData), true);
                                $optionArr = [];
                                for ($i=0; $i < $optionsNumber; $i++) { 
                                    $optionArr[] = $optionsData[$i]['text'];
                                    $trues+=1;
                                }
                                if($trues == $optionsNumber && $optionArr == $message['metadata']['options']){
                                    $message['metadata']['pollId'] = $groupMsgObj->poll_id;
                                    $oneGroup->delete();
                                }
                            }
                        }
                    }
                }if($message['type'] == 'template'){
                    $buttonsNumber = count($message['metadata']['buttons']);
                    $textsArr = preg_split('/ \r\n| \r|\n /',$message['metadata']['content']);
                    $title = $textsArr[0];
                    $footer = $message['metadata']['footer'];
                    $contactGroups = ContactGroup::where('contact',$contact)->get();
                    foreach($contactGroups as $oneGroup){
                        $groupMsgId = $oneGroup->group_id;
                        $groupMsgObj = GroupMsg::find($groupMsgId);
                        $found = 0;
                        if($groupMsgObj && $groupMsgObj->template_id != null){
                            $deleteBot = TemplateMsg::find($groupMsgObj->template_id);
                            if($deleteBot && $deleteBot->title == $title && $deleteBot->footer == $footer && $deleteBot->buttons == $buttonsNumber){
                                $buttonsData = $deleteBot->buttonsData != null ? unserialize($deleteBot->buttonsData) : [];
                                $buttonsData = json_decode(json_encode($buttonsData), true);
                                for ($i=0; $i < $buttonsNumber; $i++) { 
                                    if(isset($message['metadata']['buttons'][$i]['normalButton']) && $message['metadata']['buttons'][$i]['normalButton']['title'] == $buttonsData[$i]['text']){
                                        $found =1;
                                    }
                                }
                                if($found){
                                    $message['metadata']['templateId'] = $groupMsgObj->template_id;
                                    $oneGroup->delete();
                                }
                            }
                        }
                    }
                }
            }
            // Fire Incoming Message Event For Web Application
            $lastM = $this->handleMessages($userObj->domain, $message, $tenantObj->tenant_id);
            
            $varObj = Variable::getVar('disableGroupsReply');

            if ($message['fromMe'] == false && ($varObj == 1 && !str_contains($message['chatId'], '@g.us')) ||
                    $message['fromMe'] == false && ($varObj == '0' || $varObj == null)
                ) {
                // $this->handleNotification($message, $lastM);

                if ($message['type'] == 'buttons_response') {
                    $this->handleButtonsResponse($message, $sender, $userObj, $tenantObj);
                }else if ($message['type'] == 'list_response') {
                    $this->handleListResponse($message, $sender, $userObj, $tenantObj);
                }else if ($message['type'] == 'poll_vote') {
                    $this->handlePollResponse($message, $sender, $userObj, $tenantObj);
                }else if ($message['type'] == 'template_buttons_response') {
                    $this->handleTemplateButtonsResponse($message, $sender, $userObj, $tenantObj);
                }else {    
                    // Find Out Bot Object Based on incoming message
                    $langPref = 0;
                    $botObj1 = Bot::findBotMessage($senderMessage);
                    if($botObj1){
	                    $this->handleBasicBot($botObj1, $userObj->domain, $sender, $tenantObj->tenant_id, $message);
                    }

                    // Find BotPlus Object Based on incoming message
                    $botPlusObj1 = BotPlus::findBotMessage($senderMessage);
                    if($botPlusObj1){
	                    $this->handleBotPlus($message, $botPlusObj1, $userObj->domain, $sender,$tenantObj->tenant_id,$senderMessage);
                    }
                    
                    // Find ListMsg Object Based on incoming message
                    $listObj = ListMsg::findBotMessage($senderMessage);
                    if ($listObj) {
                        $this->handleListMsg($message, $listObj, $userObj->domain, $sender,$senderMessage);
                    }

                    // Find Poll Object Based on incoming message
                    $listObj = Poll::findBotMessage($senderMessage);
                    if ($listObj) {
                        $this->handlePoll($message, $listObj, $userObj->domain, $sender,$senderMessage);
                    }

                    // // Find TemplateMsg Object Based on incoming message
                    $templateObj = TemplateMsg::findBotMessage($senderMessage);
                    if ($templateObj) {
                        $this->handleTemplateMsg($message, $templateObj, $userObj->domain, $sender,$senderMessage);
                    }

                    // if (((!$botObj) || count($botObj) == 0) && !$botPlusObjs && !$templateObj && $message['type'] != 'order') {
                    //     $varObj = Variable::getVar('UNKNOWN_BOT_REPLY');
                    //     if ($varObj) {
                    //         $myMessage = $varObj;
                    //         $sendData['body'] = $myMessage;
                    //         // $sendData['chatId'] = $sender;
                    //         $sendData['phone'] = str_replace('@c.us', '', $sender);
                    //         $result = $mainWhatsLoopObj->sendMessage($sendData);
                    //     }
                    // }

                    
                }
            }

            // Fire Webhook For Client
            // $this->fireWebhook($message);
        } 
        else if (isset($allData['event']) && $allData['event'] == 'connectionStatus') {

            $varObj = Variable::where('var_key', 'QRIMAGE')->first();
            if ($allData['type'] == 'removed') {
                $image = 'QRIMAGE';
            } else {
                $image = '';
            }
            if (!$varObj) {
                $varObj = new Variable;
                $varObj->var_key = 'QRIMAGE';
                $varObj->var_value = $image;
                $varObj->save();
            } else {
                $varObj->var_value = $image;
                $varObj->save();
            }
            UserStatus::latest('id')->first()->update(['status' => 4]);
        }
        return 1;
    }

    public function handleMessages($domain, $message, $tenantId)
    {
        $hasOrders = 0;
        $lastOrder = [];
        if (filter_var($message['body'], FILTER_VALIDATE_URL) && !in_array($message['type'], ['product', 'order', 'text'])) {
            $message['message_type'] = \ImagesHelper::checkExtensionType(substr($message['body'], strrpos($message['body'], '.') + 1));
            $message['body'] = $message['body'];
            $message['caption'] = $message['caption'];
        } else {
            $message['message_type'] = $message['type'];
        }
        $message['sending_status'] = 1;
        $message['time'] = $message['time'];
        $checkMessageObj = ChatMessage::where('chatId', $message['chatId'])->where('chatName', '!=', null)->orderBy('time', 'DESC')->first();
        
        $message['status'] = $message['fromMe'] == 1 ? (isset($message['metadata']) && isset($message['metadata']['replyButtons']) ? 'BOT PLUS' : 'APP') : '';

        if (isset($message['metadata'])) {
            $message['metadata'] = $message['metadata'];
        }

        $messageObj = ChatMessage::newMessage($message);
		$dialog = ChatDialog::where('id',$message['chatId'])->first();
        if(!$dialog){
            $dialog = new ChatDialog;
            $dialog->id = $message['chatId'];
            $dialog->last_time = $message['time'];
            $dialog->save();
            
        }else{
            $dialog->last_time = $message['time'];
            $dialog->save();
        }

        $dialogObj = ChatDialog::getData($dialog);
        $dialogObj->lastMessage = ChatMessage::getData($messageObj);
        if(!$messageObj->notified){
	        broadcast(new IncomingMessage(strtolower($domain), $dialogObj));
        }
        // if ($message['fromMe'] == 0) {
        //     broadcast(new IncomingMessage($domain, $dialogObj));
        // } else {
        //     // broadcast(new SentMessage($domain, $dialogObj));
        // }

        if ($message['type'] == 'text') {
            return $message['body'];
        } elseif ($message['type'] == 'document') {
            return 'Document ';
        } elseif ($message['type'] == 'video') {
            return 'Video ';
        } elseif ($message['type'] == 'audio') {
            return 'Sound ';
        } elseif ($message['type'] == 'image') {
            return 'Photo ';
        } elseif ($message['type'] == 'contact') {
            $number = @explode(':+', explode(';waid=', $message['body'])[1])[0];
            return 'Contact ' . $message['metadata']['phone'];
        }
        return 1;
    }

    public function handleBasicBot($botObj, $domain, $sender, $tenantId, $message)
    {
        $mainWhatsLoopObj = new \OfficialHelper();
        $botObj = Bot::getData($botObj, $tenantId);
        $botObj->file = str_replace('localhost', $domain . '.whatskey.net', $botObj->file);
        // For Local
        // $botObj->file = str_replace('newdomain1.whatskey.net/', 'e720-156-219-175-151.ngrok.io', $botObj->file);
        $myMessage = $botObj->reply;
        $message_type = '';
        if(str_contains($sender, '@g.us')){
            $sendData['chat'] = $sender;
        }else{
            $sendData['phone'] = str_replace('@c.us', '', $sender);
        }

        if ($botObj->reply_type == 1) {
            $message_type = 'text';
            $sendData['body'] = $myMessage;
            $result = $mainWhatsLoopObj->sendMessage($sendData);
        }elseif ($botObj->reply_type == 2) {
            $message_type = \ImagesHelper::checkExtensionType(substr($botObj->file_name, strrpos($botObj->file_name, '.') + 1));
            $sendData['url'] = $botObj->file;
            $sendData['caption'] = $botObj->reply;
            $result = $mainWhatsLoopObj->sendImage($sendData);            
        }elseif ($botObj->reply_type == 3) {
            $message_type = 'video';
            $sendData['url'] = $botObj->file;
            $sendData['caption'] = $botObj->reply;
            $result = $mainWhatsLoopObj->sendVideo($sendData);
        }elseif ($botObj->reply_type == 4) {
            $message_type = 'sound';
            $sendData['url'] = $botObj->file;
            $result = $mainWhatsLoopObj->sendAudio($sendData);
        }elseif ($botObj->reply_type == 5) {
            $message_type = \ImagesHelper::checkExtensionType(substr($botObj->file_name, strrpos($botObj->file_name, '.') + 1));
            $sendData['url'] = $botObj->file;
            $result = $mainWhatsLoopObj->sendFile($sendData);
        }elseif ($botObj->reply_type == 8) {
            $message_type = 'location';
            $sendData['lat'] = $botObj->lat;
            $sendData['lng'] = $botObj->lng;
            $sendData['address'] = $botObj->address;
            $result = $mainWhatsLoopObj->sendLocation($sendData);
            $sendData['body'] = $botObj->address;
        }elseif ($botObj->reply_type == 9) {
            $message_type = 'contact';
            $sendData['contactMobile'] = str_replace('+', '', $botObj->whatsapp_no);
            $sendData['name'] = str_replace('+', '', $botObj->whatsapp_no);
            $result = $mainWhatsLoopObj->sendContact($sendData);
            $sendData['body'] = str_replace('+', '', $botObj->whatsapp_no);
        }elseif ($botObj->reply_type == 10) {
            $message_type = 'disappearing';
            $sendData['body'] = $myMessage;
            $sendData['expiration'] = $botObj->expiration_in_seconds;
            $result = $mainWhatsLoopObj->disappearingText($sendData);
            $sendData['body'] = str_replace('+', '', $botObj->whatsapp_no);
        }elseif ($botObj->reply_type == 11) {
            $message_type = 'mention';
            $sendData['mention'] = str_replace('+', '', $botObj->mention);
            $result = $mainWhatsLoopObj->sendMention($sendData);
            $sendData['body'] = str_replace('+', '', $botObj->mention);
        }elseif ($botObj->reply_type == 16) {
            $message_type = 'link';
            $sendData['description'] = $botObj->url_desc;
            $sendData['url'] = $botObj->https_url;
            $sendData['title'] = $botObj->url_title;
            $result = $mainWhatsLoopObj->sendLink($sendData);
        }elseif ($botObj->reply_type == 50) {
            $message_type = 'webhook';
            $sendData['body'] = $botObj->webhook_url;
            $message['author'] = str_replace('@c.us', '', $message['author']);
            $message['chatId'] = str_replace('@c.us', '', $message['chatId']);
            $message['chatName'] = str_replace('@c.us', '', $message['chatName']);
            $webhookData = [
                'message' => $message,
                'templates' => Template::dataList(null, $botObj->templates)['data'],
            ];
            $this->fireWebhook($webhookData, $botObj->webhook_url);
        }

        if ($message_type != 'webhook') {
            $sendData['chatId'] = $sender;
            if (isset($sendData['url'])) {
                $sendData['body'] = $sendData['url'];
            }
            $this->handleRequest($message, $domain, $result, $sendData, 'BOT', $message_type, 'BotMessage', $botObj);
        }
        return 1;
    }

    public function handleRequest($message, $domain, $result, $sendData, $status, $message_type, $channel, $botObj = null,$botId=null)
    {
        if (isset($result['data']) && isset($result['data']['id'])) {
            $checkMessageObj = ChatMessage::where('chatId', $sendData['chatId'])->where('chatName', '!=', null)->orderBy('time', 'DESC')->first();
            $messageId = $result['data']['id'];
            $lastMessage['status'] = $channel == 'SentMessage' ? (isset($message['metadata']) && isset($message['metadata']['replyButtons']) ? 'BOT PLUS' : 'APP') : $status;
            $lastMessage['id'] = $messageId;
            $lastMessage['fromMe'] = 1;
            if ($status == 'BOT' && $message_type == 'photo') {
                $lastMessage['caption'] = $sendData['caption'];
            }
            $lastMessage['chatId'] = $sendData['chatId'];
            $lastMessage['time'] = strtotime(date('Y-m-d H:i:s'));
            $lastMessage['body'] = $sendData['body'];
            $lastMessage['chatName'] = $checkMessageObj != null ? $checkMessageObj->chatName : '';
            $lastMessage['message_type'] = $message_type;
            $lastMessage['sending_status'] = 1;
            $lastMessage['caption'] = $message['caption'];
            $lastMessage['type'] = $message_type;
            if(isset($result['data']['metadata'])){
                $lastMessage['metadata'] = $result['data']['metadata'];
            }
            if($message_type == 'buttons'){
            	$lastMessage['metadata']['botPlusId'] = $botId;
            }else if($message_type == 'list'){
            	$lastMessage['metadata']['listId'] = $botId;
            }else if($message_type == 'poll'){
            	$lastMessage['metadata']['pollId'] = $botId;
            }else if($message_type == 'template'){
                $lastMessage['metadata']['templateId'] = $botId;
            }

            $messageObj = ChatMessage::newMessage($lastMessage);
        }
        return 1;
    }

    public function handleBotPlus($message, $botObj, $domain, $sender,$tenantId,$senderMessage)
    {
        $buttons = [];
        $botObj = BotPlus::getData($botObj,$tenantId);
        $mainWhatsLoopObj = new \OfficialHelper();
        if (isset($botObj->buttonsData) && !empty($botObj->buttonsData)) {
            foreach ($botObj->buttonsData as $key => $oneItem) {
                $buttons[] = [
                    'id' => $key + 1,
                    'title' => $oneItem['text'],
                ];
            }

            $sendData['body'] = $botObj->body;
            if($botObj->title != ''){
	            $sendData['title'] = $botObj->title;
            }
            if($botObj->image != ''){
        		$botObj->image = str_replace('localhost', $domain . '.whatskey.net', $botObj->image);
        		// $botObj->image = str_replace('newdomain1.whatskey.net/', 'e720-156-219-175-151.ngrok.io', $botObj->image);
	            $sendData['image'] = $botObj->image;
            }
            $sendData['footer'] = $botObj->footer;
            $sendData['buttons'] = $buttons;
            if(str_contains($sender, '@g.us')){
                $sendData['chat'] = $sender;
            }else{
                $sendData['phone'] = str_replace('@c.us', '', $sender);
            }
            $result = $mainWhatsLoopObj->sendButtons($sendData);

            $sendData['chatId'] = $sender;
            return $this->handleRequest($message, $domain, $result, $sendData, 'BOT PLUS', 'buttons', 'BotMessage', $botObj,$botObj->id);
        }
        return 1;
    }

    public function handleButtonsResponse($message, $sender, $userObj, $tenantObj)
    {
        $mainWhatsLoopObj = new \OfficialHelper();
        $msgText = '';
        if(isset($message['metadata']['quotedMessageId'])){
        	$messageObjs = ChatMessage::where('id', 'LIKE', '%' . $message['metadata']['quotedMessageId'])->first();
        	if($messageObjs){
                $metDa = json_decode($messageObjs->metadata);
        		$msgText = isset($metDa) && isset($metDa->botPlusId) ? $metDa->botPlusId : '';
        	}

        	$botObjs = BotPlus::getMsgBotByMsg($msgText);
	        $replyData = null;
	        if (isset($botObjs->buttonsData)) {
	            foreach ($botObjs->buttonsData as $buttonData) {
	                if ($buttonData['text'] == $message['metadata']['selectedButtonText']) {
	                    $replyData = $buttonData;
	                }
	            }
	        }
    	    if (isset($replyData['reply_type']) && $replyData['reply_type'] == 1) {
                $sendData['body'] = $replyData['msg'];
                if(str_contains($sender, '@g.us')){
                    $sendData['chat'] = $sender;
                }else{
                    $sendData['phone'] = str_replace('@c.us', '', $sender);
                }
                $result = $mainWhatsLoopObj->sendMessage($sendData);
                $sendData['chatId'] = $sender;
                $this->handleRequest($message, $userObj->domain, $result, $sendData, 'BOT PLUS', 'text', 'BotMessage');
            } else if (isset($replyData['reply_type']) && $replyData['reply_type'] == 2) {
                if ($replyData['msg_type'] == 2) {
                    $botObj = BotPlus::getOne($replyData['msg']);
                    if($botObj){
                    	$this->handleBotPlus($message, $botObj, $userObj->domain, $sender,$tenantObj->tenant_id, $message);
                    }
                } elseif ($replyData['msg_type'] == 1) {
                    $botObj = Bot::getOne($replyData['msg']);
                    if($botObj){
	                    $this->handleBasicBot($botObj, $userObj->domain, $sender, $tenantObj->tenant_id, $message);
                    }
                }
            }
        }
        return 1;
    }

    public function handleListMsg($message, $botObj, $domain, $sender,$senderMessage)
    {
		$botObj = ListMsg::getData($botObj);
        $sections = [];
        $mainWhatsLoopObj = new \OfficialHelper();
        if (isset($botObj->sectionsData) && !empty($botObj->sectionsData)) {
            foreach ($botObj->sectionsData as $key => $oneItem) {
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

            $sendData['body'] = $botObj->body;
            $sendData['title'] = $botObj->title;
            $sendData['footer'] = $botObj->footer;
            $sendData['buttonText'] = $botObj->buttonText;
            $sendData['sections'] = $sections;
            if(str_contains($sender, '@g.us')){
                $sendData['chat'] = $sender;
            }else{
                $sendData['phone'] = str_replace('@c.us', '', $sender);
            }
            $result = $mainWhatsLoopObj->sendList($sendData);
            $sendData['chatId'] = $sender;
            return $this->handleRequest($message, $domain, $result, $sendData, 'List Message', 'list', 'BotMessage', $botObj,$botObj->id);
        }
        return 1;
    }

    public function handleListResponse($message, $sender, $userObj, $tenantObj)
    {
        $mainWhatsLoopObj = new \OfficialHelper();
        $msgText = '';
        if(isset($message['metadata']['quotedMessageId'])){
        	$messageObjs = ChatMessage::where('id', 'LIKE', '%' . $message['metadata']['quotedMessageId'])->first();
        	if($messageObjs){
        		$msgText = json_decode($messageObjs->metadata)->listId;
        	}

        	$botObjs = ListMsg::getMsgBotByMsg($msgText);
	        $replyData = null;
	        if (isset($botObjs->sectionsData)) {
	            foreach ($botObjs->sectionsData as $buttonData) {
	            	foreach ($buttonData['rows'] as $oneSection) {
		                if ($oneSection['title'] == $message['metadata']['selectedOptionText'] && $oneSection['rowId'] == $message['metadata']['selectedRowId']) {
		                    $replyData = $oneSection;
		                }
	                }
	            }
	        }
    	    if (isset($replyData['reply_type']) && $replyData['reply_type'] == 1) {
                $sendData['body'] = $replyData['msg'];
                if(str_contains($sender, '@g.us')){
                    $sendData['chat'] = $sender;
                }else{
                    $sendData['phone'] = str_replace('@c.us', '', $sender);
                }
                $result = $mainWhatsLoopObj->sendMessage($sendData);
                $sendData['chatId'] = $sender;
                $this->handleRequest($message, $userObj->domain, $result, $sendData, 'BOT PLUS', 'text', 'BotMessage');
            } else if (isset($replyData['reply_type']) && $replyData['reply_type'] == 2) {
                if ($replyData['msg_type'] == 2) {
                    $botObj = BotPlus::getOne($replyData['msg']);
                    if($botObj){
                    	$this->handleBotPlus($message, $botObj, $userObj->domain, $sender,$tenantObj->tenant_id, $message);
                    }
                } elseif ($replyData['msg_type'] == 1) {
                    $botObj = Bot::getOne($replyData['msg']);
                    if($botObj){
	                    $this->handleBasicBot($botObj, $userObj->domain, $sender, $tenantObj->tenant_id, $message);
                    }
                }
            }
        }
        return 1;
    }

    public function handlePoll($message, $botObj, $domain, $sender,$senderMessage)
    {
        $options = [];
        $botObj = Poll::getData($botObj);
        $mainWhatsLoopObj = new \OfficialHelper();
        if (isset($botObj->optionsData) && !empty($botObj->optionsData)) {
            foreach ($botObj->optionsData as $key => $oneItem) {
                $options[] = $oneItem['text'];
            }

            $sendData['body'] = $botObj->body;
            $sendData['selectableOptionsCount'] = $botObj->selected_options;
            $sendData['options'] = $options;
            if(str_contains($sender, '@g.us')){
                $sendData['chat'] = $sender;
            }else{
                $sendData['phone'] = str_replace('@c.us', '', $sender);
            }
            $result = $mainWhatsLoopObj->sendPoll($sendData);

            $sendData['chatId'] = $sender;
            return $this->handleRequest($message, $domain, $result, $sendData, 'POLL', 'poll', 'BotMessage', $botObj,$botObj->id);
        }
        return 1;
    }

    public function handlePollResponse($message, $sender, $userObj, $tenantObj)
    {
        $mainWhatsLoopObj = new \OfficialHelper();
        $msgText = '';
        if(isset($message['metadata']['quotedMessageId'])){
        	$messageObjs = ChatMessage::where('id', 'LIKE', '%' . $message['metadata']['quotedMessageId'])->first();
        	if($messageObjs){
        		$msgText = json_decode($messageObjs->metadata)->pollId;
        	}

        	$botObjs = Poll::getMsgBotByMsg($msgText);
	        $replyData = null;
	        if (isset($botObjs->optionsData)) {
	            foreach ($botObjs->optionsData as $optionData) {
	                if ($optionData['text'] == array_reverse($message['metadata']['selectedOptions'])[0]) {
	                    $replyData = $optionData;
	                }
	            }
	        }
    	    if (isset($replyData['reply_type']) && $replyData['reply_type'] == 1) {
                $sendData['body'] = $replyData['msg'];
                if(str_contains($sender, '@g.us')){
                    $sendData['chat'] = $sender;
                }else{
                    $sendData['phone'] = str_replace('@c.us', '', $sender);
                }
                $result = $mainWhatsLoopObj->sendMessage($sendData);
                $sendData['chatId'] = $sender;
                $this->handleRequest($message, $userObj->domain, $result, $sendData, 'BOT PLUS', 'text', 'BotMessage');
            } else if (isset($replyData['reply_type']) && $replyData['reply_type'] == 2) {
                if ($replyData['msg_type'] == 2) {
                    $botObj = BotPlus::getOne($replyData['msg']);
                    if($botObj){
                    	$this->handleBotPlus($message, $botObj, $userObj->domain, $sender,$tenantObj->tenant_id, $message);
                    }
                } elseif ($replyData['msg_type'] == 1) {
                    $botObj = Bot::getOne($replyData['msg']);
                    if($botObj){
	                    $this->handleBasicBot($botObj, $userObj->domain, $sender, $tenantObj->tenant_id, $message);
                    }
                }
            }
        }
        return 1;
    }

    public function handleNotification($message, $lastM)
    {
        $vars = Variable::where('var_key', 'LIKE', 'ONESIGNALPLAYERID_%')->get();
        $ids = [];
        foreach ($vars as $var) {
            $more = array_values((array) json_decode($var->var_value));
            $ids = array_merge($ids, $more);
        }
        $ids = array_unique($ids);

        if (!empty($ids)) {
            return \OneSignalHelper::sendnotification([
                'title' => $message['senderName'],
                'message' => $lastM,
                'type' => 'ios',
                'to' => array_values($ids),
                'image' => '',
            ]);
        }
        return 1;
    }

    public function fireWebhook($data, $url = null)
    {
        if ($url) {
            return WebhookCall::create()
                ->url($url)
                ->payload($data)
                ->doNotSign()
                ->dispatch();
        } else {
            $webhook = Variable::getVar('WEBHOOK_URL');
            if($webhook){
                WebhookCall::create()
                   ->url($webhook)
                   ->payload(['data' => $data])
                   ->doNotSign()
                   ->dispatch();
            }
        }
        return 1;
    }

    public function handleTemplateMsg($message, $botObj, $domain, $sender,$senderMessage)
    {
        $buttons = [];
        $botObj = TemplateMsg::getData($botObj);
        $mainWhatsLoopObj = new \OfficialHelper();
        if (isset($botObj->buttonsData) && !empty($botObj->buttonsData)) {
            foreach ($botObj->buttonsData as $key => $oneItem) {
                $buttons[] = [
                    'id' => $oneItem['id'],
                    'title' => $oneItem['text'],
                    'type' => (int)$oneItem['button_type'],
                    'extra_data' => in_array($oneItem['button_type'],[1,2]) ? $oneItem['msg'] : ('id'.$oneItem['id']),
                ];
            }

            $sendData['title'] = $botObj->title;
            $sendData['body'] = $botObj->body;
            $sendData['footer'] = $botObj->footer;

            $sendData['buttons'] = $buttons;
            if(str_contains($sender, '@g.us')){
                $sendData['chat'] = $sender;
            }else{
                $sendData['phone'] = str_replace('@c.us', '', $sender);
            }
            $result = $mainWhatsLoopObj->sendTemplates($sendData);

            $sendData['chatId'] = $sender;
            return $this->handleRequest($message, $domain, $result, $sendData, 'BOT PLUS', 'template', 'BotMessage', $botObj,$botObj->id);
        }
        return 1;
    }

    public function handleTemplateButtonsResponse($message, $sender, $userObj, $tenantObj)
    {
        
        $mainWhatsLoopObj = new \OfficialHelper();
        $msgText = '';
        if(isset($message['metadata']['quotedMessageId'])){
            $messageObjs = ChatMessage::where('id', 'LIKE', '%' . $message['metadata']['quotedMessageId'])->first();
            if($messageObjs){
                $metDa = json_decode($messageObjs->metadata);
                $msgText = isset($metDa) && isset($metDa->templateId) ? $metDa->templateId : '';
            }

            $botObjs = TemplateMsg::getMsgBotByMsg($msgText);
            $replyData = null;
            if (isset($botObjs->buttonsData)) {
                foreach ($botObjs->buttonsData as $buttonData) {
                    if ($buttonData['text'] == $message['metadata']['selectedButtonText']) {
                        $replyData = $buttonData;
                    }
                }
            }

            if (isset($replyData['reply_type']) && $replyData['reply_type'] == 1) {
                $sendData['body'] = $replyData['msg'];
                if(str_contains($sender, '@g.us')){
                    $sendData['chat'] = $sender;
                }else{
                    $sendData['phone'] = str_replace('@c.us', '', $sender);
                }
                $result = $mainWhatsLoopObj->sendMessage($sendData);
                $sendData['chatId'] = $sender;
                $this->handleRequest($message, $userObj->domain, $result, $sendData, 'BOT PLUS', 'text', 'BotMessage');
            } else if (isset($replyData['reply_type']) && $replyData['reply_type'] == 2) {
                if ($replyData['msg_type'] == 2) {
                    $botObj = BotPlus::getOne($replyData['msg']);
                    if($botObj){
                        $this->handleBotPlus($message, $botObj, $userObj->domain, $sender,$tenantObj->tenant_id, $message);
                    }
                } elseif ($replyData['msg_type'] == 1) {
                    $botObj = Bot::getOne($replyData['msg']);
                    if($botObj){
                        $this->handleBasicBot($botObj, $userObj->domain, $sender, $tenantObj->tenant_id, $message);
                    }
                }
            }
        }
        return 1;
    }

    // public function handleTemplateOrderStatus($mod_id, $module_order_id, $replyData)
    // {
    //     if ($mod_id == 1) { // Salla
    //         $status = $replyData['msg'];
    //         $baseUrl = 'https://api.salla.dev/admin/v2/orders/' . $module_order_id . '/status';
    //         $token = Variable::getVar('SallaStoreToken');
    //         $userObj = User::first();
    //         $oauthDataObj = OAuthData::where('user_id', $userObj->id)->where('type', 'salla')->first();
    //         if ($oauthDataObj && $oauthDataObj->authorization != null) {
    //             $token = $oauthDataObj->authorization;
    //         }

    //         $data = Http::withToken($token)->post($baseUrl, ['status_id' => $status]);
    //         $result = $data->json();
    //     } elseif ($mod_id == 2) { // Zid
    //         $status = $replyData['msg'];
    //         if ($replyData['msg'] == 'جديد') {
    //             $status = 'new';
    //         } elseif ($replyData['msg'] == 'جاري التجهيز') {
    //             $status = 'preparing';
    //         } elseif ($replyData['msg'] == 'جاهز') {
    //             $status = 'ready';
    //         } elseif ($replyData['msg'] == 'جارى التوصيل') {
    //             $status = 'indelivery';
    //         } elseif ($replyData['msg'] == 'تم التوصيل') {
    //             $status = 'delivered';
    //         } elseif ($replyData['msg'] == 'تم الالغاء') {
    //             $status = 'cancelled';
    //         }

    //         $baseUrl = 'https://api.zid.sa/v1/managers/store/orders/' . $module_order_id . '/change-order-status';
    //         $storeID = Variable::getVar('ZidStoreID');
    //         $storeToken = CentralVariable::getVar('ZidMerchantToken');
    //         $managerToken = Variable::getVar('ZidStoreToken');

    //         $oauthDataObj = OAuthData::where('type', 'zid')->where('user_id', User::first()->id)->first();
    //         $authorize = $oauthDataObj != null && $oauthDataObj->token_type != null ? $oauthDataObj->token_type . ' ' . $oauthDataObj->authorization : '';
    //         $myHeaders = [
    //             "X-MANAGER-TOKEN" => $managerToken,
    //             "order-id" => $module_order_id,
    //         ];
    //         if ($authorize != '') {
    //             $data = Http::withToken($oauthDataObj->authorization)->withHeaders($myHeaders)->post($baseUrl, ['order_status' => $status]);
    //             $result = $data->json();
    //         } else {
    //             $myHeaders = [
    //                 "X-MANAGER-TOKEN" => $managerToken,
    //                 "STORE-ID" => $storeID,
    //                 "ROLE" => 'Manager',
    //                 'User-Agent' => 'whatsloop/1.00.00 (web)',
    //             ];

    //             // $dataArr = [
    //             //     'baseUrl' => $baseUrl,
    //             //     'storeToken' => $storeToken,
    //             //     'dataURL' => $dataURL,
    //             //     'tableName' => $tableName,
    //             //     'myHeaders' => $myHeaders,
    //             //     'service' => $service,
    //             //     'params' => [],
    //             // ];
    //             $data = Http::withToken($storeToken)->withHeaders($myHeaders)->post($baseUrl, ['order_status' => $status]);
    //             $result = $data->json();
    //         }
    //     }
    //     return 1;
    // }
}
