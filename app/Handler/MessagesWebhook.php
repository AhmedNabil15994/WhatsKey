<?php
namespace App\Handler;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;
use \Spatie\WebhookClient\ProcessWebhookJob;
use \Spatie\WebhookServer\WebhookCall;

use App\Events\BotMessage;
use App\Events\IncomingMessage;
use App\Events\MessageStatus;
use App\Events\SentMessage;

use App\Models\User;
use App\Models\UserAddon;
use App\Models\UserExtraQuota;
use App\Models\UserStatus;
use App\Models\Bot;
use App\Models\CentralVariable;
use App\Models\ChatDialog;
use App\Models\ChatMessage;


use App\Models\ContactReport;

use App\Models\BotPlus;
use App\Models\TemplateMsg;
use App\Models\ChatSession;
use App\Models\ModTemplate;
use App\Models\OAuthData;
use App\Models\Template;
use App\Models\Variable;
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
            // Fire Incoming Message Event For Web Application
            $lastM = $this->handleMessages($userObj->domain, $message, $tenantObj->tenant_id);
            // TODO: Make Feature in Client to disable or enable bot in groups

            if ($message['fromMe'] == false && !str_contains($message['chatId'], '@g.us')) {
                // $this->handleNotification($message, $lastM);

                if ($message['type'] == 'buttons_response') {
                    $this->handleButtonsResponse($message, $sender, $userObj, $tenantObj);
                } else {    
                    // Find Out Bot Object Based on incoming message
                    $langPref = 0;
                    $botObj1 = Bot::findBotMessage($langPref, $senderMessage);
                    if($botObj1){
	                    $this->handleBasicBot($botObj1, $userObj->domain, $sender, $tenantObj->tenant_id, $message);
                    }else{
                    	$botObj2 = Bot::findBotMessage(!$langPref, $senderMessage);
                    	if($botObj2){
	                    	$this->handleBasicBot($botObj2, $userObj->domain, $sender, $tenantObj->tenant_id, $message);
                    	}
                    }
                    

                    // // Find BotPlus Object Based on incoming message
                    // $botPlusObjs = BotPlus::findBotMessage($langPref, $senderMessage);
                    // if ($botPlusObjs) {
                    //     $botPlusObj = BotPlus::getData($botPlusObjs);
                    //     $this->handleBotPlus($message, $botPlusObj, $userObj->domain, $sender);
                    // }

                    // // Find TemplateMsg Object Based on incoming message
                    // $templateObj = TemplateMsg::findBotMessage($langPref, $senderMessage);
                    // if ($templateObj) {
                    //     $templateObj = TemplateMsg::getData($templateObj);
                    //     $this->handleTemplateMsg($message, $templateObj, $userObj->domain, $sender);
                    // }

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
        else if (isset($allData['event']) && $allData['event'] == 'newMessageStatus') {

            $actions = $allData['ack'];
            $this->handleUpdates($userObj->domain, $actions);
            // Fire Webhook For Client
            $this->fireWebhook($actions);
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
            $message['message_type'] = in_array($message['type'], ['product', 'order']) ? $message['type'] : 'text';
        }
        $message['sending_status'] = $message['status'];
        $message['time'] = $message['time'];
        $checkMessageObj = ChatMessage::where('chatId', $message['chatId'])->where('chatName', '!=', null)->orderBy('time', 'DESC')->first();
        
        $message['status'] = $message['fromMe'] == 1 ? (isset($message['metadata']) && isset($message['metadata']['replyButtons']) ? 'BOT PLUS' : 'APP') : '';

        if (isset($message['quotedMsgId'])) {
            $messageObj = ChatMessage::find($message['quotedMsgId']);
            $message['quotedMsgBody'] = $messageObj != null ? $messageObj->body : null;
        }
        if (isset($message['metadata'])) {
            $message['metadata'] = $message['metadata'];
        }

        $messageObj = ChatMessage::newMessage($message);
        $dialog = ChatDialog::updateOrCreate(['id' => $message['chatId']], ['name' => $message['chatId'], 'last_time' => $message['time']]);

        $dialogObj = ChatDialog::getData($dialog);
        if ($message['fromMe'] == 0) {
            broadcast(new IncomingMessage($domain, $dialogObj));
        } else {
            broadcast(new SentMessage($domain, $dialogObj));
        }

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

    public function handleBasicBot($botObj, $domain, $sender, $tenantId, $message)
    {
        $mainWhatsLoopObj = new \OfficialHelper();
        $botObj = Bot::getData($botObj, $tenantId);
        $botObj->file = str_replace('localhost', $domain . '.whatskey.net', $botObj->file);
        // For Local
        // $botObj->file = str_replace('newdomain1.whatskey.net/', '3aa6-154-182-246-229.ngrok.io', $botObj->file);
        
        $myMessage = $botObj->reply;
        $message_type = '';
        $sendData['phone'] = str_replace('@c.us', '', $sender);
        // 1 - 2 - 3 - 4 - 5 - 8 - 9 - 10 - 11 - 16 -50
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
            // $result = $mainWhatsLoopObj->sendMessage($sendData);
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

    public function handleRequest($message, $domain, $result, $sendData, $status, $message_type, $channel, $botObj = null)
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
            $lastMessage['sending_status'] = 2;
            $lastMessage['caption'] = $message['caption'];
            $lastMessage['type'] = $message_type;
            // $lastMessage['metadata'] = json_encode($message['metadata']);
            if ($message_type == 'contact') {
                $lastMessage['body'] = $message['body'];
            }
            if ($message_type == 'location') {
                $lastMessage['body'] = $message['body'];
                $lastMessage['caption'] = $message['caption'];
            }
            $messageObj = ChatMessage::newMessage($lastMessage);
            $dialog = ChatDialog::getOne($sendData['chatId']);
            $dialog->last_time = $lastMessage['time'];
            $dialogObj = ChatDialog::getData($dialog);
            if ($channel == 'SentMessage') {
                return broadcast(new SentMessage($domain, $dialogObj));
            } else if ($channel == 'BotMessage' && $botObj) {
                $dialogObj->lastMessage = $messageObj;
                $dialogObj->lastMessage->bot_details = $botObj;
                return broadcast(new BotMessage($domain, $dialogObj));
            }
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
            // $webhook = Variable::get_Webhook_url();
            // if ($webhook) {
            //     Logger('URL from tenant db: ' . $webhook);
            //     return WebhookCall::create()
            //         ->url($webhook)
            //         ->payload(['data' => $data])
            //         ->doNotSign()
            //         ->dispatch();
            // }
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

    public function handleUpdates($domain, $actions)
    {
        foreach ($actions as $action) {
            $action = (array) $action;
            $sender = $action['chatId'];
            $messageId = $action['id'];
            $messageObj = ChatMessage::where('id', $messageId)->first();
            if ($action['status'] == 'delivered') {
                $statusInt = 2;
                $contactObj = ContactReport::where('contact', str_replace('@c.us', '', $sender))->where('message_id', $messageId)->update(['status' => $statusInt]);
            } elseif ($action['status'] == 'viewed') {
                $statusInt = 3;
                $contactObj = ContactReport::where('contact', str_replace('@c.us', '', $sender))->where('message_id', $messageId)->update(['status' => $statusInt]);
            } elseif ($action['status'] == 'sent') {
                $statusInt = 1;
            }

            if (isset($messageObj) && $statusInt > $messageObj->sending_status) {
                $messageObj->update(['sending_status' => $statusInt]);
                if ($statusInt == 3) {
                    ChatMessage::where('fromMe', $messageObj->fromMe)->where('chatId', $sender)->where('sending_status', '<=', 2)->update(['sending_status' => 3]);
                }
            }
            broadcast(new MessageStatus($domain, $sender, $messageId, $statusInt));
        }
        return 1;
    }

    public function handleTemplateMsg($message, $botObj, $domain, $sender)
    {
        $buttons = [];
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

            $sendData['body'] = $botObj->body;
            if($botObj->title != null){
                $sendData['title'] = $botObj->title;
            }

            if($botObj->image != null){
                $sendData['image'] = $botObj->image;
            }

            $sendData['footer'] = $botObj->footer;
            $sendData['buttons'] = $buttons;
            $sendData['phone'] = str_replace('@c.us', '', $sender);
            $result = $mainWhatsLoopObj->sendTemplates($sendData);

            $sendData['chatId'] = $sender;

            if ($botObj->moderator_id != null) {
                $modObj = User::find($botObj->moderator_id);
                if ($modObj) {
                    $dialogObj = ChatDialog::getOne($sendData['chatId']);
                    $modArrs = $dialogObj->modsArr;
                    if ($modArrs == null) {
                        $dialogObj->modsArr = serialize([$botObj->moderator_id]);
                        $dialogObj->save();
                    } else {
                        $oldArr = unserialize($dialogObj->modsArr);
                        if (!in_array($botObj->moderator_id, $oldArr)) {
                            array_push($oldArr, $botObj->moderator_id);
                            $dialogObj->modsArr = serialize($oldArr);
                            $dialogObj->save();
                        }
                    }
                }
            }

            // if($botObj->category_id != null){
            //     $categoryObj = Category::find($botObj->category_id);
            //        if($categoryObj){
            //            $labelData['liveChatId'] = $sendData['chatId'];
            //         $labelData['labelId'] = $categoryObj->labelId;

            //         $varObj = Variable::getVar('BUSINESS');
            //         if($varObj){
            //             $mainWhatsLoopObj2 = new \MainWhatsLoop();
            //             $result1 = $mainWhatsLoopObj2->unlabelChat($labelData);
            //             $result2 = $mainWhatsLoopObj2->labelChat($labelData);
            //             $result3 = $result2->json();
            //         }

            //         $contactLabelObj = ContactLabel::newRecord(str_replace('@c.us','',$sendData['chatId']),$labelData['labelId']);
            //         broadcast(new ChatLabelStatus($domain, ChatDialog::getData(ChatDialog::getOne($labelData['liveChatId'])) , Category::getData($categoryObj) , 1 ));
            //        }
            // }

            return $this->handleRequest($message, $domain, $result, $sendData, 'BOT PLUS', 'text', 'chat', 'BotMessage', $botObj);
        }
        return 1;
    }

    public function handleBotPlus($message, $botObj, $domain, $sender)
    {
        $buttons = [];
        $mainWhatsLoopObj = new \OfficialHelper();
        if (isset($botObj->buttonsData) && !empty($botObj->buttonsData)) {
            foreach ($botObj->buttonsData as $key => $oneItem) {
                $buttons[] = [
                    'id' => $key + 1,
                    'title' => $oneItem['text'],
                ];
            }

            $sendData['body'] = $botObj->body;
            $sendData['title'] = $botObj->title;
            $sendData['footer'] = $botObj->footer;
            $sendData['buttons'] = $buttons;
            $sendData['phone'] = str_replace('@c.us', '', $sender);
            $result = $mainWhatsLoopObj->sendButtons($sendData);

            $sendData['chatId'] = $sender;

            if ($botObj->moderator_id != null) {
                $modObj = User::find($botObj->moderator_id);
                if ($modObj) {
                    $dialogObj = ChatDialog::getOne($sendData['chatId']);
                    $modArrs = $dialogObj->modsArr;
                    if ($modArrs == null) {
                        $dialogObj->modsArr = serialize([$botObj->moderator_id]);
                        $dialogObj->save();
                    } else {
                        $oldArr = unserialize($dialogObj->modsArr);
                        if (!in_array($botObj->moderator_id, $oldArr)) {
                            array_push($oldArr, $botObj->moderator_id);
                            $dialogObj->modsArr = serialize($oldArr);
                            $dialogObj->save();
                        }
                    }
                }
            }

            // if($botObj->category_id != null){
            //     $categoryObj = Category::find($botObj->category_id);
            //        if($categoryObj){
            //            $labelData['liveChatId'] = $sendData['chatId'];
            //         $labelData['labelId'] = $categoryObj->labelId;

            //         $varObj = Variable::getVar('BUSINESS');
            //         if($varObj){
            //             $mainWhatsLoopObj2 = new \MainWhatsLoop();
            //             $result1 = $mainWhatsLoopObj2->unlabelChat($labelData);
            //             $result2 = $mainWhatsLoopObj2->labelChat($labelData);
            //             $result3 = $result2->json();
            //         }

            //         $contactLabelObj = ContactLabel::newRecord(str_replace('@c.us','',$sendData['chatId']),$labelData['labelId']);
            //         broadcast(new ChatLabelStatus($domain, ChatDialog::getData(ChatDialog::getOne($labelData['liveChatId'])) , Category::getData($categoryObj) , 1 ));
            //        }
            // }

            return $this->handleRequest($message, $domain, $result, $sendData, 'BOT PLUS', 'text', 'chat', 'BotMessage', $botObj);
        }
        return 1;
    }

    public function handleButtonsResponse($message, $sender, $userObj, $tenantObj)
    {
        $mainWhatsLoopObj = new \OfficialHelper();
        $msgText = '';
//         if(is_array($message['quotedMsgBody'])){
//             $msgText = isset($message['quotedMsgBody']['content']) ? $message['quotedMsgBody']['content'] : $message['quotedMsgBody'];
//         }
//         if(is_string($message['quotedMsgBody'])){
//             $msgText= isset(json_decode($message['quotedMsgBody'])->content) ? json_decode($message['quotedMsgBody'])->content : $message['quotedMsgBody'];
//         }
        if (isset($message['quotedMsgId'])) {
            $msID = $message['quotedMsgId'];
            $messageObjs = ChatMessage::where('id', 'LIKE', '%' . $msID)->first();
            if ($messageObjs && isset($messageObjs->body)) {
                $msgText = isset($message['quotedMsgBody']['content']) ? $message['quotedMsgBody']['content'] : $messageObjs->body;
            }
        }
        $botObjs = BotPlus::getMsg($msgText);
        $replyData = null;
        if (isset($botObjs->buttonsData)) {
            foreach ($botObjs->buttonsData as $buttonData) {
                if ($buttonData['text'] == $message['body']) {
                    $replyData = $buttonData;
                }
            }
        }

        if ($replyData == null) {
            $botPlusObj = BotPlus::getMsg2($message['body']);
            $newReplyData = null;
            if (isset($botPlusObj->buttonsData)) {
                foreach ($botPlusObj->buttonsData as $buttonData) {
                    if ($buttonData['text'] == $message['body']) {
                        $newReplyData = $buttonData;
                    }
                }
            }
            if ($newReplyData == null) {
                $this->handleBotPlus($message, $botPlusObj, $userObj->domain, $sender);
            } else {
                if (isset($newReplyData['reply_type']) && $newReplyData['reply_type'] == 1) {
                    $sendData['body'] = $newReplyData['msg'];
                    $sendData['phone'] = str_replace('@c.us', '', $sender);
                    $result = $mainWhatsLoopObj->sendMessage($sendData);
                    $sendData['chatId'] = $sender;
                    $this->handleRequest($message, $userObj->domain, $result, $sendData, 'BOT PLUS', 'text', 'chat', 'BotMessage');
                } else if (isset($newReplyData['reply_type']) && $newReplyData['reply_type'] == 2) {
                    if ($newReplyData['msg_type'] == 2) {
                        $botObj = BotPlus::getData(BotPlus::getOne($newReplyData['msg']));
                        $this->handleBotPlus($message, $botObj, $userObj->domain, $sender);
                    } elseif ($newReplyData['msg_type'] == 1) {
                        $botObj = Bot::getData(Bot::getOne($newReplyData['msg']), $tenantObj->tenant_id);
                        $this->handleBasicBot($botObj, $userObj->domain, $sender, $tenantObj->tenant_id, $message);
                    }
                }
            }
        } else {
            if (isset($replyData['reply_type']) && $replyData['reply_type'] == 1) {
                $sendData['body'] = $replyData['msg'];
                $sendData['phone'] = str_replace('@c.us', '', $sender);
                $result = $mainWhatsLoopObj->sendMessage($sendData);
                $sendData['chatId'] = $sender;
                $this->handleRequest($message, $userObj->domain, $result, $sendData, 'BOT PLUS', 'text', 'chat', 'BotMessage');
            } else if (isset($replyData['reply_type']) && $replyData['reply_type'] == 2) {
                if ($replyData['msg_type'] == 2) {
                    $botObj = BotPlus::getData(BotPlus::getOne($replyData['msg']));
                    $this->handleBotPlus($message, $botObj, $userObj->domain, $sender);
                } elseif ($replyData['msg_type'] == 1) {
                    $botObj = Bot::getData(Bot::getOne($replyData['msg']), $tenantObj->tenant_id);
                    $this->handleBasicBot($botObj, $userObj->domain, $sender, $tenantObj->tenant_id, $message);
                }
            }
        }
        return 1;
    }

    public function handleTemplateButtonsResponse($message, $sender, $userObj, $tenantObj)
    {
        $mainWhatsLoopObj = new \OfficialHelper();
        $msgId = $message['quotedMsgId'];
        $msgObj = ChatMessage::find($msgId);
        if ($msgObj) {
            if (in_array($msgObj->module_id, [4, 5]) && $msgObj->module_status != '') {
                $mod_id = $msgObj->module_id == 4 ? 2 : 1;
                $templateObj = ModTemplate::where('mod_id', $mod_id)->where('statusText', $msgObj->module_status)->first();
                if ($templateObj && $templateObj->type > 1) {
                    $botObj = BotPlus::find($templateObj->type);
                    $replyData = null;
                    $botObj = BotPlus::getData($botObj);
                    if ($botObj && isset($botObj->buttonsData)) {
                        foreach ($botObj->buttonsData as $buttonData) {
                            if ($buttonData['text'] == $message['body']) {
                                $replyData = $buttonData;
                            }
                        }
                    }
                    if ($replyData != null) {
                        if (isset($replyData['reply_type']) && $replyData['reply_type'] == 1) {
                            $sendData['body'] = $replyData['msg'];
                            // $sendData['chatId'] = $sender;
                            $sendData['phone'] = str_replace('@c.us', '', $sender);
                            $result = $mainWhatsLoopObj->sendMessage($sendData);
                            $sendData['chatId'] = $sender;
                            $this->handleRequest($message, $userObj->domain, $result, $sendData, 'BOT PLUS', 'text', 'chat', 'BotMessage');
                        } else if (isset($replyData['reply_type']) && $replyData['reply_type'] == 2) {
                            if ($replyData['msg_type'] == 2) {
                                $botObj = BotPlus::getData(BotPlus::getOne($replyData['msg']));
                                $this->handleBotPlus($message, $botObj, $userObj->domain, $sender);
                            } elseif ($replyData['msg_type'] == 1) {
                                $botObj = Bot::getData(Bot::getOne($replyData['msg']), $tenantObj->tenant_id);
                                $this->handleBasicBot($botObj, $userObj->domain, $sender, $tenantObj->tenant_id, $message);
                            }
                        } else if (isset($replyData['reply_type']) && $replyData['reply_type'] == 3) {
                            $this->handleTemplateOrderStatus($mod_id, $msgObj->module_order_id, $replyData);
                        }
                    }
                }
            }

        }
        return 1;
    }

    public function handleTemplateOrderStatus($mod_id, $module_order_id, $replyData)
    {
        if ($mod_id == 1) { // Salla
            $status = $replyData['msg'];
            $baseUrl = 'https://api.salla.dev/admin/v2/orders/' . $module_order_id . '/status';
            $token = Variable::getVar('SallaStoreToken');
            $userObj = User::first();
            $oauthDataObj = OAuthData::where('user_id', $userObj->id)->where('type', 'salla')->first();
            if ($oauthDataObj && $oauthDataObj->authorization != null) {
                $token = $oauthDataObj->authorization;
            }

            $data = Http::withToken($token)->post($baseUrl, ['status_id' => $status]);
            $result = $data->json();
        } elseif ($mod_id == 2) { // Zid
            $status = $replyData['msg'];
            if ($replyData['msg'] == 'جديد') {
                $status = 'new';
            } elseif ($replyData['msg'] == 'جاري التجهيز') {
                $status = 'preparing';
            } elseif ($replyData['msg'] == 'جاهز') {
                $status = 'ready';
            } elseif ($replyData['msg'] == 'جارى التوصيل') {
                $status = 'indelivery';
            } elseif ($replyData['msg'] == 'تم التوصيل') {
                $status = 'delivered';
            } elseif ($replyData['msg'] == 'تم الالغاء') {
                $status = 'cancelled';
            }

            $baseUrl = 'https://api.zid.sa/v1/managers/store/orders/' . $module_order_id . '/change-order-status';
            $storeID = Variable::getVar('ZidStoreID');
            $storeToken = CentralVariable::getVar('ZidMerchantToken');
            $managerToken = Variable::getVar('ZidStoreToken');

            $oauthDataObj = OAuthData::where('type', 'zid')->where('user_id', User::first()->id)->first();
            $authorize = $oauthDataObj != null && $oauthDataObj->token_type != null ? $oauthDataObj->token_type . ' ' . $oauthDataObj->authorization : '';
            $myHeaders = [
                "X-MANAGER-TOKEN" => $managerToken,
                "order-id" => $module_order_id,
            ];
            if ($authorize != '') {
                $data = Http::withToken($oauthDataObj->authorization)->withHeaders($myHeaders)->post($baseUrl, ['order_status' => $status]);
                $result = $data->json();
            } else {
                $myHeaders = [
                    "X-MANAGER-TOKEN" => $managerToken,
                    "STORE-ID" => $storeID,
                    "ROLE" => 'Manager',
                    'User-Agent' => 'whatsloop/1.00.00 (web)',
                ];

                // $dataArr = [
                //     'baseUrl' => $baseUrl,
                //     'storeToken' => $storeToken,
                //     'dataURL' => $dataURL,
                //     'tableName' => $tableName,
                //     'myHeaders' => $myHeaders,
                //     'service' => $service,
                //     'params' => [],
                // ];
                $data = Http::withToken($storeToken)->withHeaders($myHeaders)->post($baseUrl, ['order_status' => $status]);
                $result = $data->json();
            }
        }
        return 1;
    }

    public function failed(Throwable $exception)
    {
        // Logger($exception);
        // $data = json_decode($this->webhookCall, true);
        // system('/usr/local/bin/php /home/wloop/public_html/artisan queue:retry '.$data['uuid']);
    }
}
