<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\ChatMessage;
use App\Models\ChatDialog;
use App\Models\UserExtraQuota;
use App\Models\CentralUser;
use App\Models\ChatEmpLog;
use App\Models\User;
use App\Events\IncomingMessage;
use Session;
use Illuminate\Http\Request;

class SendMsg extends Component
{
    public $msgBody = '';
    public $msgType = 1;
    public $selected;
    protected $listeners = ['sendMsg'];
    public $file = '';
    public $dataUrl = '';
    public $originalName = '';

    use WithFileUploads;
    public function mount($selected){
        $this->selected = $selected;
    }

    public function render()
    {
        return view('livewire.send-msg');
    }

    public function fileUpload()
    {
        $image = $this->file;
        $file_size = $image->getSize();
        $file_size = $file_size / (1024 * 1024);
        $file_size = number_format($file_size, 2);
        $this->originalName = $image->getClientOriginalName();

        $uploadedSize = \Helper::getFolderSize(public_path() . '/uploads/' . Session::get('tenant_id') . '/');
        $totalStorage = Session::get('storageSize');
        $extraQuotas = UserExtraQuota::getOneForUserByType(Session::get('global_id'), 3);
        if ($totalStorage + $extraQuotas < (doubleval($uploadedSize) + $file_size) / 1024) {
            return \TraitsFunc::ErrorMessage(trans('main.storageQuotaError'));
        }

        $myType = explode('/', $image->getMimeType())[1];
        $message_type = \ImagesHelper::checkChatExtensionType($myType);        
        $fileName = \ImagesHelper::uploadFileFromRequest('chats', $image, $message_type);
        if ($image == false || $fileName == false) {
            return \TraitsFunc::ErrorMessage("Upload Files Failed !!", 400);
        }

        if($message_type == 'image'){
            $this->msgType = 2;
        }elseif($message_type == 'video'){
            $this->msgType = 3;
        }elseif($message_type == 'audio'){
            $this->msgType = 4;
        }elseif ($message_type == 'document') {
            $this->msgType = 5;
        }

        $this->dataUrl = config('app.BASE_URL')  . '/uploads/' . Session::get('tenant_id') . '/chats/' . $fileName;
    }

    public function sendMsg($msgBody,Request $request){
        $input['type'] = $this->msgType;
        $selected = $this->selected;
        $this->msgBody = $msgBody;

        $startDay = strtotime(date('Y-m-d 00:00:00'));
        $endDay = strtotime(date('Y-m-d 23:59:59'));
        $messagesCount = ChatMessage::where('fromMe', 1)->where('status', '!=', null)->where('time', '>=', $startDay)->where('time', '<=', $endDay)->count();
        $dailyCount = Session::get('dailyMessageCount');
        $extraQuotas = UserExtraQuota::getOneForUserByType(Session::get('global_id'), 1);

        if ($dailyCount + $extraQuotas <= $messagesCount) {
            return \TraitsFunc::ErrorMessage('Messages Quota Per Day Exceeded!!!');
        }
        if (!isset($input['type']) || empty($input['type'])) {
            return \TraitsFunc::ErrorMessage("Type Field Is Required");
        }
        if (!isset($selected) || empty($selected)) {
            return \TraitsFunc::ErrorMessage("Chat ID Field Is Required");
        }

        $centralUser = CentralUser::find(User::first()->id);
        $domain = explode('.', $request->getHost())[0];
        $senderStatus = ucwords(Session::get('name'));

        $mainWhatsLoopObj = new \OfficialHelper();
        $sendData['phone'] = str_replace('@c.us', '', $selected);
        $caption = '';
        $metadata = [];
        $message_type = '';
        $bodyData = '';

        if($input['type'] == 1) {
            if (!isset($this->msgBody) || empty($this->msgBody)) {
                return \TraitsFunc::ErrorMessage("Message Field Is Required");
            }

            $message_type = 'text';
            $sendData['body'] = $this->msgBody;
            $bodyData = $this->msgBody;
            $result = $mainWhatsLoopObj->sendMessage($sendData);
        }elseif ($input['type'] == 2) {
            if($this->dataUrl != ''){
                $message_type = 'image';
                if($this->msgBody != ''){
                    $sendData['caption'] = $this->msgBody;
                    $caption = $this->msgBody;
                }
                $sendData['url'] = $this->dataUrl;
                $bodyData = $this->dataUrl;
                $result = $mainWhatsLoopObj->sendImage($sendData);
                $metadata['filename'] = $this->originalName;
            }      
        }elseif ($input['type'] == 3) {
            if($this->dataUrl != ''){
                $message_type = 'video';
                if($this->msgBody != ''){
                    $sendData['caption'] = $this->msgBody;
                    $caption = $this->msgBody;
                }
                $sendData['url'] = $this->dataUrl;
                $bodyData = $this->dataUrl;
                $result = $mainWhatsLoopObj->sendVideo($sendData);
                $metadata['filename'] = $this->originalName;
            }      
        }elseif ($input['type'] == 4) {
            if($this->dataUrl != ''){
                $message_type = 'audio';
                $sendData['url'] = $this->dataUrl;
                $bodyData = $this->dataUrl;
                $result = $mainWhatsLoopObj->sendAudio($sendData);
                $metadata['filename'] = $this->originalName;
            }      
        }elseif ($input['type'] == 5) {
            if($this->dataUrl != ''){
                $message_type = 'document';
                $sendData['url'] = $this->dataUrl;
                $bodyData = $this->dataUrl;
                $result = $mainWhatsLoopObj->sendFile($sendData);
                $metadata['filename'] = $this->originalName;
            }      
        }


        // } elseif ($input['type'] == 4) {
        //     if ($request->hasFile('file')) {
        //         $image = $request->file('file');
        //         if (isset($input['size']) && !empty($input['size'])) {
        //             $file_size = $input['size'];
        //             $file_size = $file_size / (1024 * 1024);
        //             $file_size = number_format($file_size, 2);
        //         } else {
        //             $file_size = $image->getSize();
        //             $file_size = $file_size / (1024 * 1024);
        //             $file_size = number_format($file_size, 2);
        //         }
        //         $uploadedSize = \Helper::getFolderSize(public_path() . '/uploads/' . TENANT_ID . '/');
        //         $totalStorage = Session::get('storageSize');
        //         $extraQuotas = UserExtraQuota::getOneForUserByType(GLOBAL_ID, 3);
        //         if ($totalStorage + $extraQuotas < (doubleval($uploadedSize) + $file_size) / 1024) {
        //             return \TraitsFunc::ErrorMessage(trans('main.storageQuotaError'));
        //         }

        //         $fileName = \ImagesHelper::uploadFileFromRequest('chats', $image, null, 'sounds');
        //         if ($image == false || $fileName == false) {
        //             return \TraitsFunc::ErrorMessage("Upload Files Failed !!", 400);
        //         }
        //         $bodyData = config('app.BASE_URL') . '/public/uploads/' . TENANT_ID . '/chats/' . $fileName;
        //         $message_type = "sound";
        //         $whats_message_type = 'ppt';
        //         $sendData['url'] = $bodyData;
        //         $result = $mainWhatsLoopObj->sendAudio($sendData);
        //     }
        // } elseif ($input['type'] == 5) {
        //     if (!isset($input['contact']) || empty($input['contact'])) {
        //         return \TraitsFunc::ErrorMessage("Contact Field Is Required");
        //     }
        //     $message_type = 'contact';
        //     $whats_message_type = 'contact';
        //     $sendData['contactMobile'] = $input['contact'];
        //     $sendData['name'] = $input['contact'];
        //     $result = $mainWhatsLoopObj->sendContact($sendData);
        //     $bodyData = $input['contact'];
        // } elseif ($input['type'] == 6) {
        //     if (!isset($input['lat']) || empty($input['lat'])) {
        //         return \TraitsFunc::ErrorMessage("Latitude Field Is Required");
        //     }

        //     if (!isset($input['lng']) || empty($input['lng'])) {
        //         return \TraitsFunc::ErrorMessage("Longitude Field Is Required");
        //     }

        //     $message_type = 'location';
        //     $whats_message_type = 'location';
        //     $sendData['lat'] = $input['lat'];
        //     $sendData['lng'] = $input['lng'];
        //     $sendData['address'] = $input['address'];
        //     $bodyData = $sendData['lat'] . ':' . $sendData['lng'];
        //     $caption = $input['address'];
        //     $result = $mainWhatsLoopObj->sendLocation($sendData);
        // } elseif ($input['type'] == 7) {
        //     if (!isset($input['link']) || empty($input['link'])) {
        //         return \TraitsFunc::ErrorMessage("Link Field Is Required");
        //     }

        //     if (!isset($input['link_title']) || empty($input['link_title'])) {
        //         return \TraitsFunc::ErrorMessage("Link Title Field Is Required");
        //     }

        //     if ($request->hasFile('file')) {
        //         $image = $request->file('file');

        //         $file_size = $image->getSize();
        //         $file_size = $file_size / (1024 * 1024);
        //         $file_size = number_format($file_size, 2);
        //         $uploadedSize = \Helper::getFolderSize(public_path() . '/uploads/' . TENANT_ID . '/');
        //         $totalStorage = Session::get('storageSize');
        //         $extraQuotas = UserExtraQuota::getOneForUserByType(GLOBAL_ID, 3);
        //         if ($totalStorage + $extraQuotas < (doubleval($uploadedSize) + $file_size) / 1024) {
        //             return \TraitsFunc::ErrorMessage(trans('main.storageQuotaError'));
        //         }

        //         $fileName = \ImagesHelper::uploadFileFromRequest('chats', $image);
        //         if ($image == false || $fileName == false) {
        //             return \TraitsFunc::ErrorMessage("Upload Files Failed !!", 400);
        //         }
        //         $fullUrl = config('app.BASE_URL') . '/public/uploads/' . TENANT_ID . '/chats/' . $fileName;
        //     }

        //     $message_type = 'link';
        //     $whats_message_type = 'link';
        //     $sendData['body'] = $input['link_title'] . " \r\n \r\n ";
        //     $sendData['body'] .= $input['link'] . " \r\n \r\n ";
        //     $sendData['body'] .= $input['link_description'];
        //     $bodyData = $sendData['body'];
        //     $result = $mainWhatsLoopObj->sendMessage($sendData);
        // }
        $result = $result->json();
        // if (!str_contains($input['chatId'], '@g.us')) {
        //     $sendData['chatId'] = $sendData['phone'] . '@c.us';
        // } else {
        //     $sendData['chatId'] = $input['chatId'];
        // }
        $sendData['chatId'] = $selected;
        if (isset($result['data']) && isset($result['data']['id'])) {
            $checkMessageObj = ChatMessage::where('chatId', $sendData['chatId'])->where('chatName', '!=', null)->first();
            $messageId = $result['data']['id'];
            $lastMessage['status'] = $senderStatus;
            $lastMessage['id'] = $messageId;
            $lastMessage['fromMe'] = 1;
            $lastMessage['author'] = $sendData['chatId'];
            $lastMessage['chatId'] = $sendData['chatId'];
            $lastMessage['time'] = strtotime(date('Y-m-d H:i:s'));
            $lastMessage['body'] = $bodyData;
            $lastMessage['caption'] = $caption;
            $lastMessage['metadata'] = $metadata;
            $lastMessage['chatName'] = $checkMessageObj != null ? $checkMessageObj->chatName : '';
            $lastMessage['type'] = $message_type;
            $lastMessage['sending_status'] = 1;
            // if (isset($quotedMessageObj)) {
            //     $lastMessage['quotedMsgId'] = $input['replyOn'];
            //     $lastMessage['quotedMsgBody'] = $quotedMessageObj->body;
            //     $lastMessage['quotedMsgType'] = $quotedMessageObj->whatsAppMessageType;
            // }
            // if (isset($input['frontId']) && !empty($input['frontId'])) {
            //     $lastMessage['frontId'] = $input['frontId'];
            // }
            $messageObj = ChatMessage::newMessage($lastMessage);
            $dialog = ChatDialog::getOne($selected);
            $dialog->last_time = $lastMessage['time'];
            $dialogObj = ChatDialog::getData($dialog);
            $dialogObj->lastMessage = ChatMessage::getData($messageObj);
            ChatMessage::where('chatId', $selected)->where('fromMe', 0)->update(['sending_status' => 3]);
            broadcast(new IncomingMessage($domain, $dialogObj));

            $this->reset(['msgBody']);
            $is_admin = Session::get('is_admin');
            $user_id = Session::get('user_id');
            if (!$is_admin) {
                if (in_array($user_id, $dialogObj->modsArr) || Session::get('is_admin')) {
                    ChatEmpLog::newLog($selected, 3);
                }
            }
        }
    }
}
