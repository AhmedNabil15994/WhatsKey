<?php namespace App\Http\Controllers;

use App\Models\CentralChannel;
use App\Models\Variable;
use App\Models\User;
use App\Models\UserAddon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Storage;
use Validator;

class ApiSettingController extends Controller
{

    use \TraitsFunc;

    public function apiSetting(Request $request)
    {
        $checkAvail = UserAddon::checkUserAvailability('api');
        if(!$checkAvail) {
            return Redirect('404');
        }

        $data['designElems']['mainData'] = [
            'title' => trans('main.api_setting'),
            'icon' => 'fas fa-handshake',
            'url' => 'profile/apiSetting',
            'name' => 'UserChannels',
            'nameOne' => 'UserChannel',
            'modelName' => 'UserChannels',
        ];

        $data['designElems']['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
                'specialAttr' => '',
            ],
            'name' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '1',
                'label' => trans('main.name'),
                'specialAttr' => '',
            ],
            'token' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '2',
                'label' => trans('main.token'),
                'specialAttr' => '',
            ],
        ];

        $data['designElems']['tableData'] = [
            'myId' => [
                'label' => trans('main.id'),
                'type' => '',
                'className' => '',
                'data-col' => 'instanceId',
                'anchor-class' => '',
            ],
            'instanceId' => [
                'label' => trans('main.channel_no'),
                'type' => '',
                'className' => '',
                'data-col' => 'instanceId',
                'anchor-class' => '',
            ],
            'name2' => [
                'label' => trans('main.name'),
                'type' => '',
                'className' => '',
                'data-col' => 'name2',
                'anchor-class' => '',
            ],
            'instanceToken' => [
                'label' => trans('main.token'),
                'type' => '',
                'className' => '',
                'data-col' => 'token',
                'anchor-class' => '',
            ],
            'start_date' => [
                'label' => trans('main.start_date'),
                'type' => '',
                'className' => '',
                'data-col' => 'start_date',
                'anchor-class' => '',
            ],
            'end_date' => [
                'label' => trans('main.end_date'),
                'type' => '',
                'className' => '',
                'data-col' => 'end_date',
                'anchor-class' => '',
            ],
        ];
        if ($request->ajax()) {
            $data = CentralChannel::dataList(Session::get('channel'));
            return Datatables::of($data['data'])->make(true);
        }
        $data['dis'] = true;
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function webhookSetting()
    {
        $checkAvail = UserAddon::checkUserAvailability('api');
        if(!$checkAvail) {
            return Redirect('404');
        }

        $data['designElems']['mainData'] = [
            'title' => trans('main.webhook_setting'),
            'icon' => 'mdi mdi-webhook',
        ];
        $data['data'] = [];
        return view('Tenancy.Profile.Views.webhookSetting')->with('data', (object) $data);
    }

    public function postWebhookSetting()
    {
        $input = \Request::all();
        $varObj = Variable::NotDeleted()->where('var_key', 'WEBHOOK_ON')->first();
        if ($varObj == null) {
            $varObj = new Variable;
            $varObj->var_key = 'WEBHOOK_ON';
            $varObj->var_value = isset($input['webhook_on']) && !empty($input['webhook_on']) ? 1 : 0;
            $varObj->created_at = DATE_TIME;
            $varObj->created_by = USER_ID;
            $varObj->save();
        } else {
            $varObj->var_value = isset($input['webhook_on']) && !empty($input['webhook_on']) ? 1 : 0;
            $varObj->updated_at = DATE_TIME;
            $varObj->updated_by = USER_ID;
            $varObj->save();
        }

        $varObj = Variable::NotDeleted()->where('var_key', 'WEBHOOK_URL')->first();
        if ($varObj == null) {
            $varObj = new Variable;
            $varObj->var_key = 'WEBHOOK_URL';
            $varObj->var_value = $input['webhook_url'];
            $varObj->created_at = DATE_TIME;
            $varObj->created_by = USER_ID;
            $varObj->save();
        } else {
            $varObj->var_value = $input['webhook_url'];
            $varObj->updated_at = DATE_TIME;
            $varObj->updated_by = USER_ID;
            $varObj->save();
        }

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function apiGuide()
    {
        $checkAvail = UserAddon::checkUserAvailability('api');
        if(!$checkAvail) {
            return Redirect('404');
        }
        
        $userObj = User::authenticatedUser();
        $data['designElems']['mainData'] = [
            'title' => trans('main.api_guide'),
            'icon' => 'fas fa-code',
        ];
        $data['data'] = $userObj;
        return view('Tenancy.Profile.Views.apiGuide')->with('data', (object) $data);
    }

}
