<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatMessage;

class ReadChatsJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $messages;
    public $status;
    public function __construct($messages,$status)
    {
        $this->messages = $messages;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $status = $this->status;
        foreach ($this->messages as $message) {
            $mainWhatsLoopObj = new \OfficialHelper();
            $data['phone'] = str_replace('@c.us', '', $message);
            
            if($status == 1){
                $updateResult = $mainWhatsLoopObj->readChat($data);
            }else{
                $updateResult = $mainWhatsLoopObj->unreadChat($data);
            }

            $result = $updateResult->json();
            Logger((array)$result);
        }
        ChatMessage::whereIn('chatId', $this->messages)->update(['sending_status' => $status == 1 ? 3 : 2]);
    }
}
