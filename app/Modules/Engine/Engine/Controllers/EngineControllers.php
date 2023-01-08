<?php namespace App\Http\Controllers;

use Request;
use Response;
use URL;
use Illuminate\Support\Facades\Http;

class EngineControllers extends Controller {

    use \TraitsFunc;

    public function formatResponse($result,$status=null){
        if(isset($result['error']) && !empty($result['error'])){
            return [0,str_replace('app.chat-api.com','whatskey.net',$result['error'])];
        }
        if(isset($result['status']) && isset($result['status']['status']) && $result['status']['status'] != 1){
            return [0,str_replace('app.chat-api.com','whatskey.net',$result['status']['message'])];
        }
        if(isset($result['result']) && $result['result'] == 'failed'){
            return [0,str_replace('@c.us', '', str_replace('app.chat-api.com','whatskey.net',$result['message']))];
        }
        if(is_array($result) && !in_array($status, ['labelsList','getProduct','showMessagesQueue','showActionsQueue','allMessages','messagesHistory'])){
            $extraResult = array_values($result);
            if(isset($extraResult[0]) && $extraResult[0] == false && !isset($result['sendDelay'])){
                return [0,str_replace('@c.us', '', str_replace('app.chat-api.com','whatskey.net',@$result['message']))];
            }                
        }

        if(isset($result['result']) && $result['result'] == "Couldn't delete chat or leaving group. Invalid number"){
            return [0,"Couldn't delete chat or leaving group. Invalid number"];
        }

       
        return [1,'success'];
    }

    public function index($status) {
        $input = Request::all();
        // Whatsapp Integration

        if($status == 'textBulk'){$status = 'sendBulkText';}
        else if($status == 'imageBulk'){$status = 'sendBulkImage';}
        else if($status == 'videoBulk'){$status = 'sendBulkVideo';}
        else if($status == 'audioBulk'){$status = 'sendBulkAudio';}
        else if($status == 'fileBulk'){$status = 'sendBulkFile';}
        else if($status == 'stickerBulk'){$status = 'sendBulkSticker';}
        else if($status == 'gifBulk'){$status = 'sendBulkGif';}
        else if($status == 'locationBulk'){$status = 'sendBulkLocation';}
        else if($status == 'linkBulk'){$status = 'sendBulkLink';}
        else if($status == 'contactBulk'){$status = 'sendBulkContact';}
        else if($status == 'mentionBulk'){$status = 'sendBulkMention';}
        else if($status == 'disappearingBulk'){$status = 'sendBulkDisappearing';}
        else if($status == 'buttonsBulk'){$status = 'sendBulkButtons';}
        else if($status == 'templateBulk'){$status = 'sendBulkTemplate';}
        else if($status == 'listBulk'){$status = 'sendBulkList';}
        else if($status == 'groupInvitationBulk'){$status = 'sendBulkGroupInvite';}
        else if($status == 'pollBulk'){$status = 'sendBulkPoll';}
        else if($status == 'productBulk'){$status = 'sendBulkProduct';}
        else if($status == 'catalogBulk'){$status = 'sendBulkCatalog';}
        
        $whatsLoopObj =  new \OfficialHelper(CHANNEL_ID,CHANNEL_TOKEN);
        $serverResult = $whatsLoopObj->$status($input);
        $serverResult = $serverResult->json();
        
        $formatResponeResult = $this->formatResponse($serverResult,$status);
        if($formatResponeResult[0] == 0){
            return \TraitsFunc::ErrorMessage($formatResponeResult[1]);
        }
        $dataArr = [];
        if($serverResult){
            $dataArr = $serverResult['data'];
        }

        $dataList['data'] = $dataArr;
        $dataList['status'] = \TraitsFunc::SuccessMessage("Data Generated Successfully");
        return \Response::json((object) $dataList);        
    }
}
