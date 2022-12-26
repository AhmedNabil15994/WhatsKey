<?php

use App\Models\CentralChannel;
use App\Models\UserChannels;
use Illuminate\Support\Facades\Http;

class OfficialHelper
{
    use TraitsFunc;

    protected $instanceId = "", $token = "", $baseUrl = "", $authToken = "", $create = "";

    public function __construct($instanceId = null, $token = null, $create = null)
    {
        $myInstanceId = '';
        $myInstanceToken = '';
        $this->create = $create;
        if ($this->create != 'create') {
            if ($instanceId) {
                $obj = CentralChannel::NotDeleted()->where([
                    ['id', $instanceId],
                    ['token', $token],
                ])->orWhere([
                    ['instanceId', $instanceId],
                    ['instanceToken', $token],
                ])->first();
                $myInstanceId = $obj->instanceId;
                $myInstanceToken = $obj->token;
            } else {
                $channelObj = UserChannels::NotDeleted()->orderBy('id', 'DESC')->first();
                // Logger("TENANT CHANNEL: " . $channelObj);
                if ($channelObj) {
                    $channelObj = CentralChannel::NotDeleted()->where('instanceId', $channelObj->id)->first();
                    // Logger("CENTRAL CHANNEL: " . $channelObj);
                    if (!$channelObj) {
                        // Logger("Channel Not Exist in Central");
                        return TraitsFunc::ErrorMessage("Channel Not Exist in Central", 401);
                    }
                    $myInstanceToken = $channelObj->token != null ? $channelObj->token : '';
                    $myInstanceId = $channelObj->instanceId != null ? $channelObj->instanceId : '';
                }
            }

        }

        $this->instanceId = $myInstanceId;
        $this->token = $myInstanceToken;
        $this->authToken = '29dc355a906104097199332d378dfc10';
        $this->baseUrl = config('app.OFFICIAL_DOMAIN') . '/';
    }

    /*----------------------------------------------------------
    Channels
    ----------------------------------------------------------*/
    public function createChannel($data)
    {
        $mainURL = $this->baseUrl . 'channels/createChannel';
        return Http::withToken($this->authToken)->post($mainURL, $data);
    }

    /*----------------------------------------------------------
    Instances
    ----------------------------------------------------------*/
    public function qr($data = [])
    {
        $mainURL = $this->baseUrl . 'instances/qr';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->get($mainURL, $data);
    }

    public function status($data = [])
    {
        $mainURL = $this->baseUrl . 'instances/status';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->get($mainURL, $data);
    }

    public function updateChannelSetting($data)
    {
        $mainURL = $this->baseUrl . 'instances/updateChannelSetting';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function screenshot($data = [])
    {
        $mainURL = $this->baseUrl . 'instances/screenshot';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->get($mainURL, $data);
    }

    public function disconnect()
    {
        $mainURL = $this->baseUrl . 'instances/disconnect';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL);
    }

    public function clearInstance()
    {
        $mainURL = $this->baseUrl . 'instances/clearInstance';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL);
    }

    public function clearInstanceData()
    {
        $mainURL = $this->baseUrl . 'instances/clearInstanceData';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL);
    }

    /*----------------------------------------------------------
    Users
    ----------------------------------------------------------*/
    public function me()
    {
        $mainURL = $this->baseUrl . 'profiles/me';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->get($mainURL);
    }

    public function checkPhone($data)
    {
        $mainURL = $this->baseUrl . 'users/checkPhone';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function contacts($data)
    {
        $mainURL = $this->baseUrl . 'users/contacts';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->get($mainURL, $data);
    }

    public function blockList()
    {
        $mainURL = $this->baseUrl . 'users/blockList';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->get($mainURL);
    }

    public function blockUser($data)
    {
        $mainURL = $this->baseUrl . 'users/blockUser';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function unblockUser($data)
    {
        $mainURL = $this->baseUrl . 'users/unblockUser';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }
    /*----------------------------------------------------------
    Chats
    ----------------------------------------------------------*/
    public function dialogs($data)
    {
        $mainURL = $this->baseUrl . 'chats';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->get($mainURL, $data);
    }

    public function readChat($data)
    {
        $mainURL = $this->baseUrl . 'chats/readChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function unreadChat($data)
    {
        $mainURL = $this->baseUrl . 'chats/unreadChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function pinChat($data)
    {
        $mainURL = $this->baseUrl . 'chats/pinChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function unpinChat($data)
    {
        $mainURL = $this->baseUrl . 'chats/unpinChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function archiveChat($data)
    {
        $mainURL = $this->baseUrl . 'chats/archiveChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function unarchiveChat($data)
    {
        $mainURL = $this->baseUrl . 'chats/unarchiveChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function muteChat($data)
    {
        $mainURL = $this->baseUrl . 'chats/muteChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function unmuteChat($data)
    {
        $mainURL = $this->baseUrl . 'chats/unmuteChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function deleteChat($data)
    {
        $mainURL = $this->baseUrl . 'chats/deleteChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function clearChat($data)
    {
        $mainURL = $this->baseUrl . 'chats/clearChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    /*----------------------------------------------------------
    Queues
    ----------------------------------------------------------*/
    public function getMessagesQueue()
    {
        $mainURL = $this->baseUrl . 'queues/getMessagesQueue';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->get($mainURL);
    }

    public function clearMessagesQueue($data = [])
    {
        $mainURL = $this->baseUrl . 'queues/clearMessagesQueue';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    /*----------------------------------------------------------
    Messages
    ----------------------------------------------------------*/
    public function messages($data)
    {
        $mainURL = $this->baseUrl . 'messages';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->get($mainURL, $data);
    }

    public function sendMessage($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendMessage';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendFile($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendFile';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendImage($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendImage';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendAudio($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendAudio';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendVideo($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendVideo';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendGif($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendGif';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendSticker($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendSticker';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReaction($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendReaction';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendContact($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendContact';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendLocation($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendLocation';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendLink($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendLink';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendMention($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendMention';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function disappearingText($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendDisappearingMessage';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }
    
    public function sendButtons($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendButtons';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendTemplates($data){
        $mainURL = $this->baseUrl.'messages/sendTemplates';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendList($data){
        $mainURL = $this->baseUrl.'messages/sendList';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendPoll($data){
        $mainURL = $this->baseUrl.'messages/sendPoll';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendGroupInvitation($data){
        $mainURL = $this->baseUrl.'messages/sendGroupInvitation';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function deleteMessageForAll($data)
    {
        $mainURL = $this->baseUrl . 'messages/deleteMessageForAll';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function forwardMessage($data)
    {
        $mainURL = $this->baseUrl . 'messages/forwardMessage';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function starMessage($data)
    {
        $mainURL = $this->baseUrl . 'messages/starMessage';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function unstarMessage($data)
    {
        $mainURL = $this->baseUrl . 'messages/unstarMessage';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }
    
    public function repeatHook($data)
    {
        $mainURL = $this->baseUrl . 'messages/repeatHook';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    /*----------------------------------------------------------
    Bulk Messages
    ----------------------------------------------------------*/
    public function sendBulkText($data)
    {
        $mainURL = $this->baseUrl . 'messages/textBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkImage($data)
    {
        $mainURL = $this->baseUrl . 'messages/imageBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkVideo($data)
    {
        $mainURL = $this->baseUrl . 'messages/videoBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkAudio($data)
    {
        $mainURL = $this->baseUrl . 'messages/audioBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkFile($data)
    {
        $mainURL = $this->baseUrl . 'messages/fileBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkLink($data)
    {
        $mainURL = $this->baseUrl . 'messages/linkBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkSticker($data)
    {
        $mainURL = $this->baseUrl . 'messages/stickerBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkGif($data)
    {
        $mainURL = $this->baseUrl . 'messages/gifBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkLocation($data)
    {
        $mainURL = $this->baseUrl . 'messages/locationBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkContact($data)
    {
        $mainURL = $this->baseUrl . 'messages/contactBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkDisappearing($data)
    {
        $mainURL = $this->baseUrl . 'messages/disappearingBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkMention($data)
    {
        $mainURL = $this->baseUrl . 'messages/mentionBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkButtons($data)
    {
        $mainURL = $this->baseUrl . 'messages/buttonsBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkTemplate($data)
    {
        $mainURL = $this->baseUrl . 'messages/templateBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkList($data)
    {
        $mainURL = $this->baseUrl . 'messages/listBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkPoll($data)
    {
        $mainURL = $this->baseUrl . 'messages/pollBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkGroupInvite($data)
    {
        $mainURL = $this->baseUrl . 'messages/groupInvitationBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkProduct($data)
    {
        $mainURL = $this->baseUrl . 'messages/productBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkCatalog($data)
    {
        $mainURL = $this->baseUrl . 'messages/catalogBulk';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    /*----------------------------------------------------------
    Reply Messages
    ----------------------------------------------------------*/
    public function sendReplyText($data)
    {
        $mainURL = $this->baseUrl . 'messages/textReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplyFile($data)
    {
        $mainURL = $this->baseUrl . 'messages/fileReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplyImage($data)
    {
        $mainURL = $this->baseUrl . 'messages/imageReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplyAudio($data)
    {
        $mainURL = $this->baseUrl . 'messages/audioReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplyVideo($data)
    {
        $mainURL = $this->baseUrl . 'messages/videoReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplyGif($data)
    {
        $mainURL = $this->baseUrl . 'messages/gifReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplySticker($data)
    {
        $mainURL = $this->baseUrl . 'messages/stickerReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplyReaction($data)
    {
        $mainURL = $this->baseUrl . 'messages/reactionReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplyContact($data)
    {
        $mainURL = $this->baseUrl . 'messages/contactReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplyLocation($data)
    {
        $mainURL = $this->baseUrl . 'messages/locationReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplyLink($data)
    {
        $mainURL = $this->baseUrl . 'messages/linkReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplyMention($data)
    {
        $mainURL = $this->baseUrl . 'messages/mentionReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function disappearingReplyText($data)
    {
        $mainURL = $this->baseUrl . 'messages/disappearingReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }
    
    public function sendReplyButtons($data)
    {
        $mainURL = $this->baseUrl . 'messages/buttonsReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReplyTemplates($data){
        $mainURL = $this->baseUrl.'messages/templateReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendReplyList($data){
        $mainURL = $this->baseUrl.'messages/listReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendReplyPoll($data){
        $mainURL = $this->baseUrl.'messages/pollReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendReplyGroupInvitation($data){
        $mainURL = $this->baseUrl.'messages/groupInvitationReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendReplyProduct($data){
        $mainURL = $this->baseUrl.'messages/productReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendReplyCatalog($data){
        $mainURL = $this->baseUrl.'messages/catalogReply';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }
    /*----------------------------------------------------------
    Business Profile
    ----------------------------------------------------------*/
    public function labels($data)
    {
        $mainURL = $this->baseUrl . 'business/labels';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->get($mainURL, $data);
    }

    public function sendProduct($data){
        $mainURL = $this->baseUrl.'business/sendProduct';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendCatalog($data){
        $mainURL = $this->baseUrl.'business/sendCatalog';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function labelMessage($data)
    {
        $mainURL = $this->baseUrl . 'business/labelMessage';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function unlabelMessage($data)
    {
        $mainURL = $this->baseUrl . 'business/unlabelMessage';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }
   
    public function labelChat($data)
    {
        $mainURL = $this->baseUrl . 'business/labelChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function unlabelChat($data)
    {
        $mainURL = $this->baseUrl . 'business/unlabelChat';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }
    

    /*----------------------------------------------------------
    Groups
    ----------------------------------------------------------*/
    public function groups()
    {
        $mainURL = $this->baseUrl . 'groups';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->get($mainURL);
    }

    public function createGroup($data)
    {
        $mainURL = $this->baseUrl . 'groups/createGroup';
        return Http::withToken($this->authToken)->withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }
    

   


}
