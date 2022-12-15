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
            foreach ($this->contacts as $contact) {
                $contactName = isset($contact['notify']) ? $contact['notify'] : $contact['name'];
                Contact::newPhone($contact['id'], $contactName);
                ChatDialog::where([
                    ['id' , '=' , $contact['id']],
                    ['name' , '=' , ''],
                ])->orWhere([
                    ['id' , '=' , $contact['id']],
                    ['name' , '=' , null],
                ])->update(['name' => $contactName]);
            }
        }

        Variable::where('var_key','QRSYNCING')->delete();
        Variable::where('var_key','SYNCING')->delete();
    }
}
