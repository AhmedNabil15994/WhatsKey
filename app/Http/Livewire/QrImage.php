<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Jobs\SyncDialogsJob;
use App\Jobs\SyncMessagesJob;
use App\Models\ChatDialog;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Category;
use App\Models\CentralChannel;
use App\Models\UserChannels;
use App\Models\Variable;
use App\Models\CentralUser;
use App\Models\UserStatus;
use App\Jobs\QRSyncData;


class QrImage extends Component
{

    protected $url = '';
    protected $area = '';

    public $showLoadingQR = 0;

    public function statusChanged()
    {
        $this->showLoadingQR = 1;
    }

    public function render(){    
        $userObj = CentralUser::find(User::first()->id);
        $channelObj =  UserChannels::first();

        $mainWhatsLoopObj = new \OfficialHelper($channelObj->id,$channelObj->token);
        $result = $mainWhatsLoopObj->qr();
        $result = $result->json();

        $data['url'] = asset('assets/dashboard/assets/images/qr-load.png');
        $data['area'] = 1;
        
        if(isset($result['data'])){
            Variable::where('var_key','QRIMAGE')->delete();
            Variable::where('var_key','QRSYNCING')->delete();
           
            if($result['data']['qr'] != 'connected'){
                if(isset($result['data']['qr'])){
                    $data['url'] = $result['data']['qr'];
                    $data['area'] = 0;
                    Variable::insert([
                        'var_key' => 'QRIMAGE',
                        'var_value' => $result['data']['qr'],
                    ]);
                }
            }else if($result['data']['qr'] == 'connected'){
                $data['url'] = asset('assets/dashboard/assets/images/qr-load.png');
                $data['area'] = 1;
                
                Variable::insert([
                    'var_key' => 'QRSYNCING',
                    'var_value' => 1,
                ]);

                UserStatus::insert([
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                
                // // Update User With Settings For Whatsapp Based On His Domain
                $domain = User::first()->domain;
                // dispatch(new QRSyncData($domain))->onConnection('cjobs');
                dispatch(new QRSyncData($domain))->onConnection('database');
                $this->emit('statusChanged'); 
            }
            
        }        
        return view('livewire.qr-image')->with('data',(object) $data);
    }
}
