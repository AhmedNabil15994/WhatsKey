<?php
namespace App\Handler;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;
use \Spatie\WebhookClient\ProcessWebhookJob;
use \Spatie\WebhookServer\WebhookCall;

use App\Models\User;
use App\Models\Variable;
use App\Models\Category;
use App\Models\Reply;
use App\Models\Order;

class BusinessWebhook extends ProcessWebhookJob
{

    public function handle()
    {
        $data = json_decode($this->webhookCall, true);
        $allData = $data['payload'];
        $tenantUser = User::first();

        if (isset($allData['event']) && str_contains($allData['event'], 'labels')) {
            $this->handleLabels($tenantUser->domain, $allData['data'], $allData['event']);
            $this->fireWebhook($allData['data']);
        } else if (isset($allData['event']) && str_contains($allData['event'], 'replies')) {
            $this->handleReplies($tenantUser->domain, $allData['data'], $allData['event']);
            $this->fireWebhook($allData['data']);
        } else if (isset($allData['event']) && str_contains($allData['event'], 'orders')) {
            $this->handleOrders($tenantUser->domain, $allData['data'], $allData['event']);
            $this->fireWebhook($allData['data']);
        } 
        
        // Fire Webhook For Client
        return 1;
    }

    public function handleLabels($domain, $data, $event)
    {   
        if(isset($data['deleted']) && $data['deleted']){
            Category::where('labelId',$data['id'])->delete();
        }else{
            foreach ($data as $key => $value) {
                Category::newCategory($value);
            }
        }
        return 1;
    }

    public function handleReplies($domain, $data, $event)
    {
        if(isset($data['deleted']) && $data['deleted']){
            Reply::where('reply_id',$data['id'])->delete();
        }else{
            foreach ($data as $key => $value) {
                Reply::newReply($value);
            }
        }
        return 1;
    }

    public function handleOrders($domain, $order, $event)
    {
        if(isset($order['id']) && isset($order['token'])){
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
        }
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
}
