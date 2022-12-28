<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Reply;
use App\Models\Variable;

class SyncRepliesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $replies;
    public function __construct($replies)
    {
        $this->replies = $replies;
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
        if(!empty($this->replies)){
            foreach ($this->replies as $reply) {
                $replyObj = Reply::where('reply_id',$reply['id'])->first();
                if(!$replyObj){
                    Reply::create([
                        'name_ar' => $reply['shortcut'],
                        'name_en' => $reply['shortcut'],
                        'description_ar' => $reply['message'],
                        'description_en' => $reply['message'],
                        'reply_id' => $reply['id'],
                    ]);
                }else{
                    Reply::where('reply_id',$reply['id'])->update([
                        'name_ar' => $reply['shortcut'],
                        'name_en' => $reply['shortcut'],
                        'description_ar' => $reply['message'],
                        'description_en' => $reply['message'],
                    ]);
                }
            }
        }
        Variable::where('var_key','SYNCING')->delete();
    }
}
