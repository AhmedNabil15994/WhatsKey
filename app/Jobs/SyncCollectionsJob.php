<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\WACollection;
use App\Models\Variable;

class SyncCollectionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $catalogs;
    public function __construct($catalogs)
    {
        $this->catalogs = $catalogs;
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
        if(!empty($this->catalogs)){
            foreach ($this->catalogs as $catalog) {
                $catalogObj = WACollection::where('collection_id',$catalog['id'])->first();
                $productsString = '';
                foreach($catalog['products'] as $oneProduct){
                    if($oneProduct['id']){
                        $productsString.= $oneProduct['id'].',';
                    }
                }

                if(!$catalogObj){
                    WACollection::create([
                        'name' => $catalog['name'],
                        'status' => isset($catalog['status']) && isset($catalog['status']['status']) ? $catalog['status']['status'] : '',
                        'can_appeal' => isset($catalog['status']) && isset($catalog['status']['canAppeal']) ? $catalog['status']['canAppeal'] : 0,
                        'products' => $productsString,
                        'collection_id' => $catalog['id'],
                    ]);
                }else{
                    WACollection::where('collection_id',$catalog['id'])->update([
                        'name' => $catalog['name'],
                        'status' => isset($catalog['status']) && isset($catalog['status']['status']) ? $catalog['status']['status'] : '',
                        'can_appeal' => isset($catalog['status']) && isset($catalog['status']['canAppeal']) ? $catalog['status']['canAppeal'] : 0,
                        'products' => $productsString,
                    ]);
                }
            }
        }
        Variable::where('var_key','SYNCING')->delete();
    }
}
