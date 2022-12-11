<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncMessagesJob;
use App\Models\User;
use App\Models\ChatMessage;
use App\Models\CentralUser;

class SyncMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync User Messages Every Minute';

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
        $isBA = CentralUser::find(User::first()->id)->isBA;
        if(!$isBA){
            $mainWhatsLoopObj = new \MainWhatsLoop();
            $data['limit'] = 0;
            if(User::first()->setting_pushed == 1){
                $lastMessageObj = ChatMessage::orderBy('time','DESC')->first();
                if($lastMessageObj != null && $lastMessageObj->time != null){
                    $data['min_time'] = $lastMessageObj->time - 7200;
                }
            }
            $updateResult = $mainWhatsLoopObj->messages($data);
            $updateResult = $updateResult->json();
            if(isset($updateResult['data']) && !empty($updateResult['data'])){
                try {
                    dispatch(new SyncMessagesJob($result['data']['messages']))->onConnection('cjobs');
                } catch (Exception $e) {
                    
                }
            }     
        }else{
            $mainWhatsLoopObj = new \OfficialHelper();
            $data['lmts'] = 'all';
            if(User::first()->setting_pushed == 1){
                $lastMessageObj = ChatMessage::orderBy('time','DESC')->first();
                if($lastMessageObj != null && $lastMessageObj->time != null){
                    $data['min_time'] = $lastMessageObj->time - 7200;
                }
            }
            $updateResult = $mainWhatsLoopObj->messages($data);
            $updateResult = $updateResult->json();
            if(isset($updateResult['data']) && !empty($updateResult['data'])){
                try {
                    dispatch(new SyncMessagesJob($updateResult['data']))->onConnection('cjobs');
                } catch (Exception $e) {
                    
                }
            }
        }
        return 1;   
    }
}
