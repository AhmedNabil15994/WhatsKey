<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use App\Models\WACollection;
use App\Models\Variable;

class SyncProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $products;
    public function __construct($products)
    {
        $this->products = $products;
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
        if(!empty($this->products)){
            foreach ($this->products as $product) {
                $collectionObj = WACollection::where('products','LIKE','%'.$product['id'].'%')->first();
                $productObj = Product::where('product_id',$product['id'])->first();                
                if(!$productObj){
                    Product::create([
                        'product_id' => $product['id'],
                        'name' => $product['name'],
                        'availability' => $product['availability'],
                        'description' => $product['description'],
                        'price' => $product['price'],
                        'currency' => $product['currency'],
                        'is_hidden' => $product['isHidden'],
                        'review_status' => isset($product['reviewStatus']) && isset($product['reviewStatus']['whatsapp']) ? $product['reviewStatus']['whatsapp'] : '',
                        'images' => isset($product['imageUrls']) && isset($product['imageUrls']['original']) ? $product['imageUrls']['original'] : '',
                        'collection_id' => $collectionObj ? $collectionObj->id : null,
                    ]);
                }else{
                    Product::where('product_id',$product['id'])->update([
                        'name' => $product['name'],
                        'availability' => $product['availability'],
                        'description' => $product['description'],
                        'price' => $product['price'],
                        'currency' => $product['currency'],
                        'is_hidden' => $product['isHidden'],
                        'review_status' => isset($product['reviewStatus']) && isset($product['reviewStatus']['whatsapp']) ? $product['reviewStatus']['whatsapp'] : '',
                        'images' => isset($product['imageUrls']) && isset($product['imageUrls']['original']) ? $product['imageUrls']['original'] : '',
                        'collection_id' => $collectionObj ? $collectionObj->id : null,
                    ]);
                }
            }
        }
        Variable::where('var_key','SYNCING')->delete();
    }
}
