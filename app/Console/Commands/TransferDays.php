<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CentralUser;
use App\Models\UserAddon;
use App\Models\CentralChannel;
use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Variable;

class TransferDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer  Days for every Channel';

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

        $channelObj = CentralChannel::NotDeleted()->orderBy('id','ASC')->first();
        $mainWhatsLoopObj = new \OfficialHelper($channelObj->id,$channelObj->token);
        
        $channels = CentralChannel::dataList()['data'];
        $activeChannels = [];
        foreach ($channels as $key => $value) {
            $centralUserObj = CentralUser::where('global_id',$value->global_user_id)->first();
            if($key > 0 && $value->leftDays > 0){
                $activeChannels[] = $value->instanceId; 
            }
        }

        foreach($channels as $key => $channel){
            if($channel->id != $channelObj->id){
                if(in_array($channel->instanceId,$activeChannels) && $channel->leftDays >= 1){
                    $transferDaysData = [
                        'receiver' => $channel->instanceId,
                        'days' => 1,
                        'sender' => $channelObj->instanceId,
                    ];
                    $updateResult = $mainWhatsLoopObj->transferDays($transferDaysData);
                    $result = $updateResult->json();
                }
            }
        }
        return 1;
    }
}
