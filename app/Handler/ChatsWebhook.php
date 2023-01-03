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
class ChatsWebhook extends ProcessWebhookJob
{

    public function handle()
    {
        $data = json_decode($this->webhookCall, true);
        $allData = $data['payload'];
        $tenantUser = User::first();

        if (isset($allData['event']) && $allData['event'] == 'group-new') {
            $actions = $allData['data'];
            $this->handleNewGroup($tenantUser->domain, $actions);
        }else if (isset($allData['event']) && $allData['event'] == 'group-update') {
            $actions = $allData['data'];
            $this->handleGroupUpdates($tenantUser->domain, $actions);
        } else if (isset($allData['event']) && $allData['event'] == 'dialog-update') {
            $actions = $allData['data'];
            $this->handleChatsUpdates($tenantUser->domain, $actions);
        } 
        // Fire Webhook For Client
        $this->fireWebhook($actions);
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

    public function handleNewGroup($domain, $actions)
    {
        ChatDialog::newDialog($actions);
        // $actions['chatId'] = $actions['id'];
        // broadcast(new DialogUpdateStatus(strtolower($domain), $actions));
        return 1;
    }

    public function handleGroupUpdates($domain, $actions)
    {
        if($actions['id']){
            $dialogObj = ChatDialog::find($actions['id']);
            if($dialogObj){
                if(isset($actions['action'])){
                    $groupAction = $actions['action'];
                    $oldParticipants = $dialogObj->participants != null && $dialogObj->participants != '' ? json_decode($dialogObj->participants) : [];
                    if($groupAction == 'add'){
                        foreach($actions['participants'] as $addedOne){
                            $oldParticipants[] = (object) [
                                'id' => $addedOne,
                                'admin' => 'null',
                            ];
                        }
                        $dialogObj->participants = json_encode($oldParticipants);
                        $dialogObj->save();
                    }else {
                        $newParticipants = [];
                        foreach ($oldParticipants as $oldOne) {
                            if($groupAction == 'remove'){
                                if($oldOne->id != $actions['participants'][0]){
                                    $newParticipants[] = $oldOne;
                                }
                            }else if($groupAction == 'promote'){
                                if($oldOne->id == $actions['participants'][0]){
                                    $oldOne->admin = 'admin';
                                }
                                $newParticipants[] = $oldOne;                                 
                            }else if($groupAction == 'demote'){
                                if($oldOne->id == $actions['participants'][0]){
                                    $oldOne->admin = 'null';
                                }
                                $newParticipants[] = $oldOne;   
                            }       
                        }
                        $dialogObj->participants = json_encode($newParticipants);
                        $dialogObj->save();
                    }
                }

                if(isset($actions['name']) && !empty($actions['name'])){
                    $dialogObj->name = $actions['name'];
                    $dialogObj->save();
                }
                if(isset($actions['description']) && !empty($actions['description'])){
                    $dialogObj->group_description = $actions['description'];
                    $dialogObj->save();
                }
                if(isset($actions['announce'])){
                    $dialogObj->announce = $actions['announce'] == 'true' ? 1 : 0;
                    $dialogObj->save();
                }
                if(isset($actions['restrict'])){
                    $dialogObj->group_restrict = $actions['restrict'] == 'true' ? 1 : 0;
                    $dialogObj->save();
                }
            }
        }
        // ChatDialog::newDialog($actions);
        // $actions['chatId'] = $actions['id'];
        // broadcast(new DialogUpdateStatus(strtolower($domain), $actions));
        return 1;
    }

    public function handleChatsUpdates($domain, $actions)
    {
        $actions = (array) $actions;
        $chatObj = ChatDialog::where('id',$actions['chatId'])->first();

        if($chatObj){
            if(isset($actions['pinned'])){
                $chatObj->update(['pinned' => (int) $actions['pinned']]);
            }
            if(isset($actions['archived'])){
                $chatObj->update(['archived' =>  $actions['archived'] == 'true' ? 1 : 0]);
            }
            if(isset($actions['muted'])){
                if(!$actions['muted']){
                    $chatObj->update(['muted' =>  0 , 'muted_until' => null]);
                }else{
                    $chatObj->update(['muted' =>  1 , 'muted_until' => date('Y-m-d H:i:s',$actions['muted'] / 1000)]);
                }
            }
            if(isset($actions['labelled'])){
                $oldLabels = $chatObj->labels;
                $newLabels = $actions['labelled'] == true ? $oldLabels.= $actions['label_id'].',' : str_replace($actions['label_id'].',','',$oldLabels);
                $chatObj->update(['labels' =>  $newLabels]);
            }
            broadcast(new DialogUpdateStatus(strtolower($domain), $actions));
        }
        return 1;
    }
}
