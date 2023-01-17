<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;
use App\Models\Variable;
use App\Models\ChatDialog;

class SyncContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $contacts;
    public function __construct($contacts)
    {
        $this->contacts = $contacts;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!empty($this->contacts)){
            $mainWhatsLoopObj = new \OfficialHelper();
            $blockResult = $mainWhatsLoopObj->blockList();
            $block = $blockResult->json();
            $blockChats = [];
            if(isset($block['data'])){
                $blockChats = $block['data'];
            }

            $varObj = Variable::where('var_key','contactsNameType')->first();

            foreach ($this->contacts as $contact) {
                $contactName = str_contains($contact['id'], '@g.us') ? $contact['name'] :  (isset($contact['notify']) ? $contact['notify'] : $contact['name']);
                if($varObj != null && $varObj->var_value == 2){
                    $contactName = str_contains($contact['id'], '@g.us') ? $contact['name'] : isset($contact['name']) ? $contact['name'] : $contact['id'];
                }else if($varObj != null && $varObj->var_value == 3){
                    $contactName = isset($contact['notify']) ? $contact['notify'] : $contact['name'];
                }
                Contact::newPhone($contact['id'], $contactName);
                
                if($varObj == null || $varObj->var_value == 1){
                    ChatDialog::where([
                        ['id' , '=' , $contact['id']],
                        ['name' , '=' , ''],
                    ])->orWhere([
                        ['id' , '=' , $contact['id']],
                        ['name' , '=' , null],
                    ])->update([
                        'name' => $contactName,
                    ]);
                }else{
                    ChatDialog::where('id',$contact['id'])->update(['name' => $contactName]);
                }

                ChatDialog::where('id',$contact['id'])->update(['blocked' => in_array($contact['id'],$blockChats) ? 1 : 0]);
            }
        }

        Variable::where('var_key','QRSYNCING')->delete();
        Variable::where('var_key','SYNCING')->delete();
    }
}
