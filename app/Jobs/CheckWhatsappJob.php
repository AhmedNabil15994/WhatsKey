<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
Use App\Models\Contact;

class CheckWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $contacts;
    public $messageObj;
    public $tries = 10;
    
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
        foreach ($this->contacts as $contactArr) {
            try {
                $contact = (object) $contactArr;
                $contactObj = Contact::where('group_id',$contact->group_id)->where('phone',$contact->phone)->first();
                if($contactObj){
                    $contactObj->update((array)$contactArr);
                }else{
                    $contactObj = Contact::create((array)$contactArr);
                }
                $phone = str_replace('+', '', $contact->phone);
                // $phoneResult = substr($phone, 0, 3);
                // if($phoneResult != '966'){
                //     $phone = '966'.$phone;
                // }
                $result = $this->checkWhatsappAvailability($phone);
                Contact::where('group_id',$contact->group_id)->where('phone',$contact->phone)->update(['has_whatsapp' => $result]);                
            } catch (Exception $e) {
                // Logger('')   
            }
        }
        return 1;
    }

    public function checkWhatsappAvailability($contact){
        $checkData['phone'] = $contact;
        $mainWhatsLoopObj = new \OfficialHelper();

        $checkResult = $mainWhatsLoopObj->checkPhone($checkData);
        $result = $checkResult->json();
        $status = 0;
        if(isset($result) && isset($result['status']) && isset($result['status']['status']) && $result['status']['status'] != 1){
            $status = 0;
        }

        if(isset($result['data'])){
            $status = $result['data']['exists'] == true ? 1 : 0;
        }

        return $status;
    }
}
