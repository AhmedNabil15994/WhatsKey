<?php

namespace App\Console\Commands;

use App\Models\CentralUser;
use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Console\Command;

class InstanceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instance:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Instance Status Every 5 Minutes';

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

        $userObj = CentralUser::find(User::first()->id);
        $mainWhatsLoopObj = new \OfficialHelper();
        $result = $mainWhatsLoopObj->status();
        $result = $result->json();

        $statusInt = 4;

        if (isset($result['data']) && !empty($result['data'])) {
            $status = $result['data']['status'];
            if ($status == 'connected') {
                $statusInt = 1;
            } else if ($status == 'disconnected') {
                $statusInt = 3;
            } else if ($status == 'got QR and ready to scan') {
                $statusInt = 4;
            }
        }

        if (isset($result['status']) && !empty($result['status'])) {
            if ($result['status']['status'] == 1) {
                $userStatusObj = new UserStatus;
                $userStatusObj->status = $statusInt;
                $userStatusObj->created_at = date('Y-m-d H:i:s');
                $userStatusObj->save();
            }
        }
      
        // // TODO: send whatsapp message when channel is down ( Need Test )
        if (in_array($statusInt, [3, 4])) {
            $channelObj = \DB::connection('main')->table('channels')->where('deleted_by', null)->orderBy('id', 'ASC')->first();
            $whatsLoopObj = new \OfficialHelper($channelObj->id, $channelObj->token);
            $phone = User::first()->emergency_number ? User::first()->emergency_number : User::first()->phone;
            $data['phone'] = str_replace('+', '', $phone);
            $data['body'] = 'Connection Closed and you got a new QR Code , please go and scan it!';
            $test = $whatsLoopObj->sendMessage($data);
            if (!isset($result['status']) || $result['status']['status'] != 1) {
                $userStatusObj = new UserStatus;
                $userStatusObj->status = $statusInt;
                $userStatusObj->created_at = date('Y-m-d H:i:s');
                $userStatusObj->save();
            }
        }
        return 1;
    }
}
