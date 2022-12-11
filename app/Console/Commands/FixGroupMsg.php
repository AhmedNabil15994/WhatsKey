<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
Use App\Models\ContactReport;
Use App\Models\GroupMsg;
Use App\Models\User;
Use App\Models\Contact;
Use App\Jobs\GroupMessageJob;

class FixGroupMsg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:groupMsg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $tenantUser = User::first();
        $tenantObj = \DB::connection('main')->table('tenant_users')->where('global_user_id', $tenantUser->global_id)->first();
        $tenantId = $tenantObj->tenant_id;
        
        $groupMsgs = GroupMsg::NotDeleted()->where('later',0)->where('publish_at','>=','2022-08-10 18:00:00')->orderBy('id','desc')->get();
        $chunks = 100;
        foreach($groupMsgs as $oneMsg){
            $sentContacts = ContactReport::where('group_message_id',$oneMsg->id)->where('message_id','!=',null)->pluck('contact');
            $sentContacts = reset($sentContacts);
            
            $notSentContacts = ContactReport::where('group_message_id',$oneMsg->id)->where('status',0)->pluck('contact');
            $notSentContacts = reset($notSentContacts);
            
            $allContacts =  Contact::NotDeleted()->where('group_id',$oneMsg->group_id)->pluck('phone');
            $allContacts = reset($allContacts);
            
            $allContacts = array_diff( $allContacts, $sentContacts );
            $oldContacts = array_unique(array_merge( $allContacts, $notSentContacts ));

            $dataObj = GroupMsg::getData($oneMsg,$tenantId);
            $contacts = Contact::NotDeleted()->where('group_id',$oneMsg->group_id)->whereIn('phone',$oldContacts)->chunk($chunks,function($data) use ($dataObj){
                try {
                    dispatch(new GroupMessageJob(reset($data),$dataObj));
                } catch (Exception $e) {
                    
                }
            });
        }
        return 1;
    }
}
