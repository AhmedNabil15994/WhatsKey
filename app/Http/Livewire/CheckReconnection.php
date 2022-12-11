<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\UserAddon;
use App\Models\UserStatus;
use App\Models\Variable;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class CheckReconnection extends Component
{
    protected $haveImage = '';
    protected $tutorials = '';
    protected $seconds = 2;
    public $requestSemgent;
    public $addons;
    public $tenant_id;

    public function mount($requestSemgent, $addons, $tenant_id)
    {
        $this->requestSemgent = $requestSemgent;
        $this->addons = [];
        $this->tenant_id = $tenant_id;
    }

    public function render()
    {
        Artisan::call('tenants:run instance:status --tenants=' . $this->tenant_id);
        $userStatusObj = UserStatus::orderBy('id', 'DESC')->first();
        
        $data = [];
        $data['haveImage'] = 0;
        $data['dis'] = 0;
        $data['seconds'] = 2;
        $varObj = Variable::getVar('QRIMAGE');

        if ((isset($userStatusObj) && in_array($userStatusObj->status, [3,4]))) {
            $data['haveImage'] = 1;
            $data['dis'] = 1;
        }

        $userAddonsTutorial = [];
        $userAddons = array_unique($this->addons);
        $addonsTutorial = [1, 2, 4, 5];
        $userObj = User::first();

        $data['tutorials'] = array_values($userAddonsTutorial);
        return view('livewire.check-reconnection')->with('data', (object) $data);
    }
}
