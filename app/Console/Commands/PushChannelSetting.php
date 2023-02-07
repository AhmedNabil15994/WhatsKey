<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CentralUser;
use App\Models\CentralChannel;
use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;

class PushChannelSetting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:channelSetting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Channel Settings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   

        $users = CentralUser::NotDeleted()->where('group_id',0)->where('setting_pushed',0)->where('status',1)->get();
        foreach($users as $user){
            $domain = CentralUser::getDomain($user);
            $channelObj = CentralChannel::where('global_user_id',$user->global_id)->first();
            if($channelObj){
                $mainWhatsLoopObj = new \OfficialHelper($channelObj->id,$channelObj->token);
                $myData = [
                    'sendDelay' => '0',
                    'webhooks' => [
                        'messageNotifications' => str_replace('://', '://'.$domain.'.', config('app.BASE_URL')).'/services/webhooks/messages-webhook',
                        'ackNotifications' => str_replace('://', '://'.$domain.'.', config('app.BASE_URL')).'/services/webhooks/acks-webhook',
                        'chatNotifications' => str_replace('://', '://'.$domain.'.', config('app.BASE_URL')).'/services/webhooks/chats-webhook',
                        'businessNotifications' => str_replace('://', '://'.$domain.'.', config('app.BASE_URL')).'/services/webhooks/business-webhook',
                    ],
                    'ignoreOldMessages' => 1,
                ];
                $updateResult = $mainWhatsLoopObj->updateChannelSetting($myData);
                
                $result = $updateResult->json();

                if(isset($result) && isset($result['status']) && $result['status']['status'] == 1){
                    $user->setting_pushed = 1;
                    $user->save();
                    
                    $domainObj = Domain::where('domain',$domain)->first();                    
                    tenancy()->initialize($domainObj->tenant_id);

                    $tenantUserObj = User::first();
                    $tenantUserObj->setting_pushed = 1;
                    $tenantUserObj->save();
                    
                    tenancy()->end($domainObj->tenant_id);
                }
            }
        }
        return 1;
    }
}
