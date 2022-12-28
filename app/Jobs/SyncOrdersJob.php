<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Variable;

class SyncOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $orders;
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Variable::insert([
            'var_key' => 'SYNCING',
            'var_value' => 1,
        ]);
        if(!empty($this->orders)){
            foreach ($this->orders as $order) {
                $orderObj = Order::where('order_id',$order['id'])->first();
                if(!$orderObj){
                    Order::create([
                        'order_id' => $order['id'],
                        'token' => $order['token'],
                        'title' => $order['title'],
                        'sellerJid' => str_replace('@s.whatsapp.net','@c.us',$order['sellerJid']),
                        'itemCount' => $order['itemCount'],
                        'price' => $order['price'],
                        'currency' => $order['currency'],
                        'time' => $order['time'],
                        'chatId' => $order['chatId'],
                        'products' => json_encode($order['products']),
                    ]);
                }else{
                    Order::where('order_id',$order['id'])->update([
                        'token' => $order['token'],
                        'title' => $order['title'],
                        'sellerJid' => str_replace('@s.whatsapp.net','@c.us',$order['sellerJid']),
                        'itemCount' => $order['itemCount'],
                        'price' => $order['price'],
                        'currency' => $order['currency'],
                        'time' => $order['time'],
                        'chatId' => $order['chatId'],
                        'products' => json_encode($order['products']),
                    ]);
                }
            }
        }
        Variable::where('var_key','SYNCING')->delete();
    }
}
