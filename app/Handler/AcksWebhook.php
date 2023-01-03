<?php
namespace App\Handler;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;
use \Spatie\WebhookClient\ProcessWebhookJob;
use \Spatie\WebhookServer\WebhookCall;

use App\Events\MessageStatus;
use App\Events\DialogUpdateStatus;

use App\Models\User;
use App\Models\CentralVariable;
use App\Models\ChatDialog;
use App\Models\ChatMessage;
use App\Models\ContactReport;
use App\Models\Variable;
class AcksWebhook extends ProcessWebhookJob
{

    public function handle()
    {
        $data = json_decode($this->webhookCall, true);
        $allData = $data['payload'];
        $tenantUser = User::first();

        if (isset($allData['event']) && $allData['event'] == 'message-status') {
            $actions = $allData['ack'];
            $this->handleUpdates($tenantUser->domain, $actions);
            // Fire Webhook For Client
            $this->fireWebhook($actions);
        } 
        // else if (isset($allData['event']) && $allData['event'] == 'connectionStatus') {

        //     $varObj = Variable::where('var_key', 'QRIMAGE')->first();
        //     if ($allData['type'] == 'removed') {
        //         $image = 'QRIMAGE';
        //     } else {
        //         $image = '';
        //     }
        //     if (!$varObj) {
        //         $varObj = new Variable;
        //         $varObj->var_key = 'QRIMAGE';
        //         $varObj->var_value = $image;
        //         $varObj->save();
        //     } else {
        //         $varObj->var_value = $image;
        //         $varObj->save();
        //     }
        //     UserStatus::latest('id')->first()->update(['status' => 4]);
        // }
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

    public function handleUpdates($domain, $actions)
    {
        foreach ($actions as $action) {
            $action = (array) $action;
            $sender = $action['chatId'];
            $messageId = $action['id'];
            $messageObj = ChatMessage::where('id', $messageId)->first();
            $statusInt = 1;
            if($messageObj){
                if ($action['status'] == 'delivered') {
                    $statusInt = 2;
                    $contactObj = ContactReport::where('contact', str_replace('@c.us', '', $sender))->where('message_id', $messageId)->update(['status' => $statusInt]);
                } elseif ($action['status'] == 'viewed') {
                    $statusInt = 3;
                    $contactObj = ContactReport::where('contact', str_replace('@c.us', '', $sender))->where('message_id', $messageId)->update(['status' => $statusInt]);
                } elseif ($action['status'] == 'deleted') {
                    $statusInt = 6;
                }

                if (isset($messageObj) && $statusInt > $messageObj->sending_status) {
                    $messageObj->update(['sending_status' => $statusInt]);
                    if ($statusInt == 3) {
                        ChatMessage::where('fromMe', $messageObj->fromMe)->where('chatId', $sender)->update(['sending_status' => 3]);
                    }else if ($statusInt == 6) {
                        $messageObj->update(['deleted_at' => date('Y-m-d H:i:s')]);
                    }
                    broadcast(new MessageStatus(strtolower($domain), $sender, $messageId, $messageObj->sending_status));
                }

                if(in_array($action['status'],['starred','unstarred'])){
                    $messageObj->update(['starred' => $action['status'] == 'starred' ? 1 : 0]);
                    broadcast(new MessageStatus(strtolower($domain), $sender, $messageId, $action['status']));
                }else if(in_array($action['status'],['labelled','unlabelled'])){
                    $oldLabels = $messageObj->labelled;
                    $newLabels = $action['status'] == 'labelled' ? $oldLabels.= $action['label_id'].',' : str_replace($action['label_id'].',','',$oldLabels);
                    $messageObj->update(['labelled' => $newLabels]);
                    broadcast(new MessageStatus(strtolower($domain), $sender, $messageId, $action['status']));
                }
            }

        }
        return 1;
    }
}
