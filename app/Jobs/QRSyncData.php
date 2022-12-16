<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SyncDialogsJob;
use App\Jobs\SyncContactsJob;
use App\Jobs\SyncMessagesJob;
use App\Models\Variable;

class QRSyncData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $domain;
    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $domain = $this->domain;

        $mainWhatsLoopObj = new \OfficialHelper();

        $myData = [
            'sendDelay' => '0',
            'webhooks' => [
                'messageNotifications' => str_replace('://', '://'.$domain.'.', config('app.BASE_URL')).'/services/webhooks/messages-webhook',
                'ackNotifications' => str_replace('://', '://'.$domain.'.', config('app.BASE_URL')).'/services/webhooks/acks-webhook',
            ],
            'ignoreOldMessages' => 1,
        ];
        $updateResult = $mainWhatsLoopObj->updateChannelSetting($myData);
        $result = $updateResult->json();
        
        Variable::insert([
            'var_key' => 'SYNCING',
            'var_value' => 1,
        ]);

        $diags = $mainWhatsLoopObj->dialogs(['page'=>'1','page_size'=>1000000]);
        $diags = $diags->json();
        if($diags != null && isset($diags['data']) && !empty($diags['data'])){
            try {
                // dispatch(new SyncDialogsJob($diags['data']))->onConnection('cjobs');
                dispatch(new SyncDialogsJob($diags['data']))->onConnection('database');
            } catch (Exception $e) {
                
            }   
        }

        $msgResult = $mainWhatsLoopObj->messages(['page'=>'1','page_size'=>1000000]);
        $msgRespone = $msgResult->json();
        if($msgRespone != null && isset($msgRespone['data']) && !empty($msgRespone['data'])){
            try {
                // dispatch(new SyncMessagesJob($msgRespone['data']))->onConnection('cjobs');
                dispatch(new SyncMessagesJob($msgRespone['data']))->onConnection('database');
            } catch (Exception $e) {
                
            }
        }

        $contactResult = $mainWhatsLoopObj->contacts(['page'=>'1','page_size'=>1000000]);
        $contactResponse = $contactResult->json();
        if($contactResponse != null && isset($contactResponse['data']) && !empty($contactResponse['data'])){
            try {
                // dispatch(new SyncMessagesJob($contactResponse['data']))->onConnection('cjobs');
                dispatch(new SyncContactsJob($contactResponse['data']))->onConnection('database');
            } catch (Exception $e) {
                
            }
        }

        $me = $mainWhatsLoopObj->me();
        $meResult = $me->json();
        if($meResult != null && isset($meResult['data']) && !empty($meResult['data'])){
            Variable::where('var_key','ME')->delete();
            Variable::create(['var_key'=>'ME','var_value'=> json_encode($meResult['data'])]);
        }
        return 1;
    }

}
