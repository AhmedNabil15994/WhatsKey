<?php namespace App\Http\Middleware;

use Closure;

class EngineInstance
{

    public function handle($request, Closure $next){
        $statusesArr = [
            // Instances
            'status','qr_code','logout','screenshot','takeover','expiry','retry','reboot','settings','updateSettings','outputIP','me','updateName','updateStatus','repeatHook','labelsList','createLabel','updateLabel','removeLabel','clearInstance',

            // Messages
            'sendMessage','sendImage','sendVideo','sendAudio','sendFile','sendSticker','sendGif','sendLink','sendLocation','sendReaction','sendContact','sendDisappearingMessage','sendMention','sendButtons','sendTemplates','sendList','sendGroupInvitation','sendPoll','sendCatalog','sendProduct',

            'textBulk','imageBulk','videoBulk','audioBulk','fileBulk','stickerBulk','gifBulk','locationBulk','contactBulk','disappearingBulk','mentionBulk','buttonsBulk','templateBulk','listBulk','linkBulk','groupInvitationBulk','productBulk','catalogBulk','pollBulk',


            
            // Webhooks
            'webhook',

            // Dialogs
            'allDialogs','dialog','group','pinChat','unpinChat','readChat','unreadChat','archiveChat','unarchiveChat','disappearingChat','clearChat','removeChat','joinGroup','leaveGroup','addGroupParticipant','removeGroupParticipant','promoteGroupParticipant','demoteGroupParticipant','typing','recording','labelChat','unlabelChat',

            // Queues
            'showMessagesQueue','clearMessagesQueue','showActionsQueue','clearActionsQueue',

            // Ban
            'banSettings','updateBanSettings','banTest',

            // Testing
            'instanceStatuses','webhookStatus','checkPhone',

            // Users
            'userStatus',

            // Products
            'getProducts','getProduct','sendProduct','getOrder',


        ];

        if(!in_array($request->segment(3), $statusesArr)){
            return \TraitsFunc::ErrorMessage("Not Found");
        }

        define('STATUS',$request->segment(3));
        return $next($request);
    }
}
