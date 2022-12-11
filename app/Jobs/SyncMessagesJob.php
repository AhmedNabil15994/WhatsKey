<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatMessage;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Addons;
use App\Models\Variable;
use App\Models\UserAddon;
use App\Models\CentralUser;

class SyncMessagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $messages;
    public function __construct($messages)
    {
        // ini_set('memory_limit', '-1');
        $this->messages = $messages;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $whatsProductsArr = [];
        $whatsOrdersArr = [];
        if(!empty($this->messages)){
            foreach ($this->messages as $message) {
                if(isset($message['status'])){
                    $message['sending_status'] = $message['status'];
                    unset($message['status']);
                }
                ChatMessage::newMessage($message);
            }
        }
    }
}
