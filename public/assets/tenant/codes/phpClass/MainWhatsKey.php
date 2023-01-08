<?php
class MainWhatsKey {
    protected $instanceId = "", $token = "",$baseUrl = "";
    public function __construct($instanceId=null,$token=null) {

        $this->instanceId = $instanceId;
        $this->token = $token;
        $this->baseUrl = 'http://whatskey.net/engine/';
    }

    /*----------------------------------------------------------
    Messages
    ----------------------------------------------------------*/

    public function sendMessage($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendMessage';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendFile($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendFile';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendImage($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendImage';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendAudio($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendAudio';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendVideo($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendVideo';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendGif($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendGif';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendSticker($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendSticker';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendReaction($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendReaction';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendContact($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendContact';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendLocation($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendLocation';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendLink($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendLink';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendMention($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendMention';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function disappearingText($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendDisappearingMessage';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }
    
    public function sendButtons($data)
    {
        $mainURL = $this->baseUrl . 'messages/sendButtons';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendTemplates($data){
        $mainURL = $this->baseUrl.'messages/sendTemplates';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendList($data){
        $mainURL = $this->baseUrl.'messages/sendList';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendPoll($data){
        $mainURL = $this->baseUrl.'messages/sendPoll';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendGroupInvitation($data){
        $mainURL = $this->baseUrl.'messages/sendGroupInvitation';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendProduct($data){
        $mainURL = $this->baseUrl.'messages/sendProduct';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendCatalog($data){
        $mainURL = $this->baseUrl.'messages/sendCatalog';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL,$data);
    }

    public function sendBulkText($data)
    {
        $mainURL = $this->baseUrl . 'messages/textBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkImage($data)
    {
        $mainURL = $this->baseUrl . 'messages/imageBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkVideo($data)
    {
        $mainURL = $this->baseUrl . 'messages/videoBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkAudio($data)
    {
        $mainURL = $this->baseUrl . 'messages/audioBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkFile($data)
    {
        $mainURL = $this->baseUrl . 'messages/fileBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkLink($data)
    {
        $mainURL = $this->baseUrl . 'messages/linkBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkSticker($data)
    {
        $mainURL = $this->baseUrl . 'messages/stickerBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkGif($data)
    {
        $mainURL = $this->baseUrl . 'messages/gifBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkLocation($data)
    {
        $mainURL = $this->baseUrl . 'messages/locationBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkContact($data)
    {
        $mainURL = $this->baseUrl . 'messages/contactBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkDisappearing($data)
    {
        $mainURL = $this->baseUrl . 'messages/disappearingBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkMention($data)
    {
        $mainURL = $this->baseUrl . 'messages/mentionBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkButtons($data)
    {
        $mainURL = $this->baseUrl . 'messages/buttonsBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkTemplate($data)
    {
        $mainURL = $this->baseUrl . 'messages/templateBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkList($data)
    {
        $mainURL = $this->baseUrl . 'messages/listBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkPoll($data)
    {
        $mainURL = $this->baseUrl . 'messages/pollBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkGroupInvite($data)
    {
        $mainURL = $this->baseUrl . 'messages/groupInvitationBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkProduct($data)
    {
        $mainURL = $this->baseUrl . 'messages/productBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }

    public function sendBulkCatalog($data)
    {
        $mainURL = $this->baseUrl . 'messages/catalogBulk';
        return Http::withHeaders([
            'CHANNELID' => $this->instanceId,
            'CHANNELTOKEN' => $this->token,
        ])->post($mainURL, $data);
    }
}