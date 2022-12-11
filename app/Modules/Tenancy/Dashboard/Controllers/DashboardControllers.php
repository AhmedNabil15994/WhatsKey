<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Variable;
use App\Models\CentralUser;
use App\Models\FAQ;
use App\Models\CentralVariable;
use App\Models\Changelog;
use App\Models\CentralCategory;
use App\Models\CentralDepartment;
use App\Models\Rate;
use App\Models\UserStatus;
use App\Models\UserChannels;
use App\Models\CentralChannel;
use App\Models\Contact;
use App\Models\ChatMessage;
use App\Models\ChatEmpLog;
use App\Models\ChatDialog;

class DashboardControllers extends Controller {

    use \TraitsFunc;

    public function Dashboard(){   
        $varObj = Variable::getVar('QRIMAGE');
        if($varObj){
            $sendStatus = 0;
        }else{
            $sendStatus = 100;
        }
        $userStatusObj = UserStatus::orderBy('id','DESC')->first();
        if($userStatusObj!= null && in_array($userStatusObj->status,[2,3,4])){
            $sendStatus = 0;
        }else{
            $sendStatus = 100;
        }

        $messages = (object) ChatMessage::lastMessages();
        
        $data['allDialogs'] = ChatDialog::count();
        $data['data'] = $messages->data;
        $data['pagination'] = $messages->pagination;
        $data['sentMessages'] = ChatMessage::where('fromMe',1)->count();
        $data['incomingMessages'] = ChatMessage::count() - $data['sentMessages'];
        $data['contactsCount'] = Contact::NotDeleted()->count();
        $data['sendStatus'] = $sendStatus;
        $data['serverStatus'] = 100;
        $data['lastContacts'] = Contact::lastContacts()['data'];
        $data['logs'] = ChatEmpLog::dataList()['data'];
        return view('Tenancy.Dashboard.Views.dashboard')->with('data',(object) $data);
    } 

    public function qrIndex()
    {
        $varObj = Variable::getVar('QRIMAGE');
        $data['dis'] = 1;
        if ($varObj) {
            $data['qrImage'] = mb_convert_encoding($varObj, 'UTF-8', 'UTF-8');
            $data['dis'] = 1;
        }
        
        $data['data'] = [];
        $data['dataNames'] = [];
        $data['channelName'] = UserChannels::first() ? UserChannels::first()->name : '';
        return view('Tenancy.Dashboard.Views.qrData')->with('data', (object) $data);
    }

    public function updateName()
    {
        $input = \Request::all();
        if (!isset($input['name']) || empty($input['name'])) {
            return \TraitsFunc::ErrorMessage(trans('main.channelNameValidate'));
        }

        $channelObj = UserChannels::first();
        $channelObj->name = $input['name'];
        $channelObj->save();
        CentralChannel::where('id', $channelObj->id)->update(['name' => $input['name']]);

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }

    public function getQR()
    {
        $mainWhatsLoopObj = new \OfficialHelper();
        $result = $mainWhatsLoopObj->status();
        $result = $result->json();
        $channelObj = UserChannels::first();

        if (isset($result['data'])) {
            if ($result['data']['accountStatus'] == 'got qr code') {
                if (isset($result['data']['qrCode'])) {
                    $image = '/uploads/instance' . $channelObj->id . 'Image' . time() . '.png';
                    $destinationPath = public_path() . $image;
                    $qrCode = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $result['data']['qrCode']));
                    $succ = file_put_contents($destinationPath, $qrCode);
                    $statusObj['data']['qrImage'] = mb_convert_encoding($result['data']['qrCode'], 'UTF-8', 'UTF-8');
                    $statusObj['status'] = \TraitsFunc::SuccessMessage();
                    return \Response::json((object) $statusObj);
                }
            } else if ($result['data']['accountStatus'] == 'authenticated') {
                $statusObj['data']['qrImage'] = 'auth';
                $statusObj['status'] = \TraitsFunc::SuccessMessage();
                return \Response::json((object) $statusObj);
            }
        }

    }

    public function changeLang(Request $request){
        if(!\Session::has('locale')){
            \Session::put('locale', 'ar');
        }else{
            $old = \Session::get('locale');
            \Session::forget('locale');
            \Session::put('locale', $old == 'ar' ? 'en' : 'ar');
        }
        return redirect()->back();
    }

    public function helpCenter(){   
        $data = FAQ::dataList(1);
        $data['changeLogs'] = Changelog::dataList(1)['data'];
        $data['categories'] = CentralCategory::dataList(1)['data'];
        $data['email'] = CentralVariable::getVar('TECH_EMAIL');
        $data['phone'] = CentralVariable::getVar('TECH_PHONE');
        $data['pin_code'] = $this->genNewPinCode(IS_ADMIN ? USER_ID : User::first()->id);
        $data['clients'] = CentralUser::NotDeleted()->where('status',1)->where('global_id',GLOBAL_ID)->where('group_id',0)->get();
        $data['departments'] = CentralDepartment::dataList(1)['data'];
        return view('Tenancy.Dashboard.Views.helpCenter')->with('data',(object) $data);
    }

    public function faqs(){   
        $data = FAQ::dataList(1)['data'];
        return view('Tenancy.Dashboard.Views.faqs')->with('data',(object) $data);
    }

    public function genNewPinCode($user_id){
        $newCode = rand(1,10000);
        $userObj = User::getOne($user_id);
        $userObj->pin_code = $newCode;
        $userObj->save();

        $userObj = CentralUser::getOne($user_id);
        $userObj->pin_code = $newCode;
        $userObj->save();
        return $newCode;
    }

    public function changeLogs(){   
        $data = FAQ::dataList(1);
        $data['data'] = Changelog::dataList(1)['data'];
        $data['categories'] = CentralCategory::dataList(1)['data'];
        return view('Tenancy.Dashboard.Views.changeLogs')->with('data',(object) $data);
    }

    public function addRate(){
        $input = \Request::all();

        $rateObj = Rate::NotDeleted()->where('user_id',USER_ID)->where('changelog_id',$input['id'])->first();
        if($rateObj){
            return \TraitsFunc::ErrorMessage(trans('main.youRated'));
        }
        $rateObj = new Rate();
        $rateObj->user_id = USER_ID;
        $rateObj->tenant_id = TENANT_ID;
        $rateObj->changelog_id = (int) $input['id'];
        $rateObj->comment = (string) $input['comment'];
        $rateObj->rate = 3;
        $rateObj->created_by = USER_ID;
        $rateObj->created_at = DATE_TIME;
        $rateObj->save();

        return \TraitsFunc::SuccessResponse(trans('main.addSuccess'));
    }

}
