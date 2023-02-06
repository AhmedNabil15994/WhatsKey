<?php namespace App\Http\Controllers;

use App\Jobs\ReadChatsJob;
use App\Jobs\SyncDialogsJob;
use App\Jobs\SyncLabelsJob;
use App\Jobs\SyncContactsJob;
use App\Jobs\SyncMessagesJob;
use App\Jobs\SyncRepliesJob;
use App\Jobs\SyncOrdersJob;
use App\Jobs\SyncProductsJob;
use App\Jobs\SyncCollectionsJob;

use App\Models\Category;
use App\Models\CentralChannel;
use App\Models\CentralUser;
use App\Models\ChatDialog;
use App\Models\ChatMessage;
use App\Models\Contact;
use App\Models\Membership;
use App\Models\Order;
use App\Models\Product;
use App\Models\WACollection;
use App\Models\Reply;
use App\Models\Addons;

use App\Models\User;
use App\Models\UserAddon;
use App\Models\UserExtraQuota;
use App\Models\UserStatus;
use App\Models\Variable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Storage;
use Validator;

class WAAccountController extends Controller
{

    use \TraitsFunc;

    public function subscription()
    {
        $userObj = User::authenticatedUser();
        $data['designElems']['mainData'] = [
            'title' => trans('main.subscriptionManage'),
            'icon' => 'fa fa-cogs',
        ];

        // Perform Whatsapp Integration
        $mainWhatsLoopObj = new \OfficialHelper();
        
        // Fetch Subscription Data
        $membershipObj = Session::get('membership') != null ? Membership::getData(Membership::getOne(Session::get('membership'))) : [];
        $channelObj = Session::get('channel') != null ? CentralChannel::getData(CentralChannel::getOne(Session::get('channel'))) : null;
        if ($channelObj) {
            $channelStatus = ($channelObj->leftDays >= 0 && date('Y-m-d') <= $channelObj->end_date) ? 1 : 0;
        }

        $data['subscription'] = (object) [
            'package_id' => $channelObj ? $membershipObj->id : '',
            'package_name' => $channelObj ? $membershipObj->title : '',
            'channelStatus' => $channelObj ? $channelStatus : '',
            'start_date' => $channelObj ? $channelObj->start_date : '',
            'end_date' => $channelObj ? $channelObj->end_date : '',
            'leftDays' => $channelObj ? $channelObj->leftDays : '',
            'membership_addons' => $channelObj ? Addons::dataList(1,Session::get('membershipAddonsID'))['data'] : [],
            'addons' =>  $channelObj ? UserAddon::dataList(Session::get('addonsID'),ROOT_ID)['data'] : [],
            'extra_quotas' => $channelObj ? UserExtraQuota::getForUser(GLOBAL_ID)[1] : [],
            'disableAddonAutoInvoice' => Variable::getVar('disableAddonAutoInvoice'),
            'disableExtraQuotaAutoInvoice' => Variable::getVar('disableExtraQuotaAutoInvoice'),
        ];
        $meVar = Variable::getVar('ME');

        $data['data'] = $userObj;
        $data['me'] = $meVar != null ? json_decode($meVar) : null;
        $userStatusObj = UserStatus::orderBy('id', 'DESC')->first();
        if ($userStatusObj) {
            $data['status'] = $channelObj ? UserStatus::getData($userStatusObj) : '';
        } else {
            $data['status'] = [];
        }

        $msgQueue = $mainWhatsLoopObj->getMessagesQueue();
        $queueResult = $msgQueue->json();
        if ($queueResult && isset($queueResult['data'])) {
            $data['totalQueueMessages'] = $queueResult['data']['count'];
            $data['queuedMessages'] = $queueResult['data']['messages'];
        }

        $list = [];
        if($meVar){
            $blockedList = $mainWhatsLoopObj->blockList();
            $blockResult = $blockedList->json();
            if ($blockResult && isset($blockResult['data'])) {
                $list = $blockResult['data'];
            }
        }

        $data['allDialogs'] = ChatDialog::count();
        $data['sentMessages'] = ChatMessage::where('fromMe',1)->where([
            ['time' , '>=', strtotime(date('Y-m-d'). ' 00:00:00')],
            ['time' , '<=', strtotime(date('Y-m-d'). ' 23:59:59')],
        ])->count();
        $data['messages'] = ChatMessage::count();
        $data['blockList'] = $list;
        $data['contactsCount'] = Contact::NotDeleted()->whereHas('NotDeletedGroup')->count();
        $data['channel'] = $channelObj ? CentralChannel::getData(CentralChannel::getOne(Session::get('channel'))) : null;
        $data['contactsCount'] = Contact::NotDeleted()->count();
        $data['channelSettings'] = [
            'contactsNameType' => Variable::getVar('contactsNameType'),
            'disableGroupsReply' => Variable::getVar('disableGroupsReply'),
            'disableDialogsArchive' => Variable::getVar('disableDialogsArchive'),
            'disableReceivingCalls' => Variable::getVar('disableReceivingCalls'),
        ];
        return view('Tenancy.Profile.Views.subscription')->with('data', (object) $data);
    }

    public function unBlock($chatId)
    {
        $chatObj = ChatDialog::getOne($chatId);
        if($chatObj){
            $newChats = [];
            $mainWhatsLoopObj = new \OfficialHelper();
            if($chatObj->blocked == 1){
                $result = $mainWhatsLoopObj->unblockUser(['phone'=>$chatId]);
                $chatObj->blocked = 0;
                $chatObj->save();
            }
        }
        Session::flash('success', trans('main.syncInProgress'));
        return redirect()->back();
    }

    public function screenshot()
    {
        // Perform Whatsapp Integration
        $mainWhatsLoopObj = new \OfficialHelper();
        $updateResult = $mainWhatsLoopObj->screenshot();
        $result =  $updateResult->json();

        if (!$result) {
            return \TraitsFunc::ErrorMessage(trans('main.loading'));
        }
        $dataList['image'] = $result['html'];
        $dataList['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $dataList);
    }

    public function syncAll()
    {
        $data = ['page'=>1,'page_size'=>1000000];
        $mainWhatsLoopObj = new \OfficialHelper();
        $updateResult = $mainWhatsLoopObj->messages($data);
        $result = $updateResult->json();
        if ($result != null && $result['status']['status'] != 1) {
            Session::flash('error', $result['status']['message']);
            return redirect()->back();
        }

        $messages = [];
        if ($result != null && $result['data'] != null) {
            $messages = $result['data'];
        }
        try {
            dispatch(new SyncMessagesJob($messages))->onConnection('syncdata');
        } catch (Exception $e) {

        }
        Session::flash('success', trans('main.syncInProgress'));
        return redirect()->back();
    }

    public function closeConn()
    {
        $mainWhatsLoopObj = new \OfficialHelper();
        $updateResult = $mainWhatsLoopObj->disconnect();
        $result = $updateResult->json();

        if ($result != null && $result['status']['status'] != 1) {
            Session::flash('error', $result['status']['message']);
            return redirect()->back();
        }
        UserStatus::latest('id')->first()->update(['status' => 4]);
        Session::flash('success', trans('main.logoutDone'));
        return redirect()->back();
    }

    public function read($status)
    {
        $status = (int) $status;
        if (!in_array($status, [0, 1])) {
            return redirect('404');
        }

        $sending_status_text = 2;
        if ($status == 1) {
            $sending_status_text = 3;
        }

        $messages = ChatMessage::where('fromMe', 0)->groupBy('chatId')->pluck('chatId');
        try {
            dispatch(new ReadChatsJob(reset($messages), $status))->onConnection('syncdata');
        } catch (Exception $e) {

        }

        Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
    }

    public function syncDialogs()
    {
        $data = ['page'=>1,'page_size'=>1000000];
        $mainWhatsLoopObj = new \OfficialHelper();
        $updateResult = $mainWhatsLoopObj->dialogs($data);
        $result = $updateResult->json();
        $dialogs = [];
        if ($result != null && $result['status']['status'] != 1) {
            Session::flash('error', $result['status']['message']);
            return redirect()->back();
        }

        if ($result != null && $result['data'] != null) {
            $dialogs = $result['data'];
        }
        try {
            dispatch(new SyncDialogsJob($dialogs))->onConnection('syncdata');
        } catch (Exception $e) {

        }
        Session::flash('success', trans('main.syncInProgress'));
        return redirect()->back();
    }

    public function syncContacts()
    {
        $data = ['page'=>1,'page_size'=>1000000];
        $mainWhatsLoopObj = new \OfficialHelper();
        $updateResult = $mainWhatsLoopObj->contacts($data);
        $result = $updateResult->json();
        $contacts = [];
        if ($result != null && $result['status']['status'] != 1) {
            Session::flash('error', $result['status']['message']);
            return redirect()->back();
        }

        if ($result != null && $result['data'] != null) {
            $contacts = $result['data'];
        }
        try {
            dispatch(new SyncContactsJob($contacts))->onConnection('syncdata');
        } catch (Exception $e) {

        }
        Session::flash('success', trans('main.syncInProgress'));
        return redirect()->back();
    }

    public function syncLabels()
    {
        $varObj = Variable::getVar('ME');
        if($varObj && isset(json_decode($varObj)->isBussines) && json_decode($varObj)->isBussines){
            $mainWhatsLoopObj = new \OfficialHelper();
            $updateResult = $mainWhatsLoopObj->labels();
            $updateResult = $updateResult->json();
            if (isset($updateResult['data']) && !empty($updateResult['data'])) {
                try {
                    dispatch(new SyncLabelsJob($updateResult['data']))->onConnection('syncdata');
                } catch (Exception $e) {

                }
            }
        }

        Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
    }

    public function syncReplies()
    {
        $varObj = Variable::getVar('ME');
        if($varObj && isset(json_decode($varObj)->isBussines) && json_decode($varObj)->isBussines){
            $mainWhatsLoopObj = new \OfficialHelper();
            $updateResult = $mainWhatsLoopObj->replies();
            $updateResult = $updateResult->json();
            if (isset($updateResult['data']) && !empty($updateResult['data'])) {
                try {
                    dispatch(new SyncRepliesJob($updateResult['data']))->onConnection('syncdata');
                } catch (Exception $e) {

                }
            }
        }

        Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
    }

    public function syncOrders()
    {
        $varObj = Variable::getVar('ME');
        if($varObj && isset(json_decode($varObj)->isBussines) && json_decode($varObj)->isBussines){
            $mainWhatsLoopObj = new \OfficialHelper();
            $updateResult = $mainWhatsLoopObj->orders();
            $updateResult = $updateResult->json();
            if (isset($updateResult['data']) && !empty($updateResult['data'])) {
                try {
                    dispatch(new SyncOrdersJob($updateResult['data']))->onConnection('syncdata');
                } catch (Exception $e) {

                }
            }
        }

        Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
    }

    public function syncProducts()
    {
        $varObj = Variable::getVar('ME');
        if($varObj && isset(json_decode($varObj)->isBussines) && json_decode($varObj)->isBussines){
            $mainWhatsLoopObj = new \OfficialHelper();
            $updateResult = $mainWhatsLoopObj->products();
            $updateResult = $updateResult->json();
            if (isset($updateResult['data']) && !empty($updateResult['data'])) {
                try {
                    dispatch(new SyncProductsJob($updateResult['data']))->onConnection('syncdata');
                } catch (Exception $e) {

                }
            }
        }

        Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
    }

    public function syncCollections()
    {
        $varObj = Variable::getVar('ME');
        if($varObj && isset(json_decode($varObj)->isBussines) && json_decode($varObj)->isBussines){
            $mainWhatsLoopObj = new \OfficialHelper();
            $updateResult = $mainWhatsLoopObj->collections();
            $updateResult = $updateResult->json();
            if (isset($updateResult['data']) && !empty($updateResult['data'])) {
                try {
                    dispatch(new SyncCollectionsJob($updateResult['data']))->onConnection('syncdata');
                } catch (Exception $e) {

                }
            }
        }

        Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
    }
    
    public function resyncAll(){
        $mainWhatsLoopObj = new \OfficialHelper();
        $me = $mainWhatsLoopObj->me();
        $meResult = $me->json();
        if($meResult != null && isset($meResult['data']) && !empty($meResult['data'])){
            Variable::where('var_key','ME')->delete();
            Variable::create(['var_key'=>'ME','var_value'=> json_encode($meResult['data'])]);
        }

        Contact::where('group_id',1)->delete();
        Category::where('id', '!=', null)->delete();
        ChatMessage::where('id', '!=', null)->delete();
        ChatDialog::where('id', '!=', null)->delete();
        Product::where('id', '!=', null)->delete();
        Order::where('id', '!=', null)->delete();
        WACollection::where('id', '!=', null)->delete();
        Reply::where('reply_id', '!=', null)->delete();

        $this->syncAll();
        $this->syncDialogs();
        $this->syncContacts();
        $this->syncLabels();
        $this->syncReplies();
        $this->syncOrders();
        $this->syncProducts();
        $this->syncCollections();

        Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
    }

    public function restoreAccountSettings()
    {
        $userObj = User::first();
        $domain = $userObj->domain;
        $mainWhatsLoopObj = new \OfficialHelper();
        // // Update User With Settings For Whatsapp Based On His Domain
        $myData = [
            'sendDelay' => '0',
            'webhooks' => [
                'messageNotifications' => str_replace('://', '://'.$domain.'.', config('app.BASE_URL')).'/services/webhooks/messages-webhook',
                'ackNotifications' => str_replace('://', '://'.$domain.'.', config('app.BASE_URL')).'/services/webhooks/acks-webhook',
                'chatNotifications' => str_replace('://', '://'.$domain.'.', config('app.BASE_URL')).'/services/webhooks/chats-webhook',
                'businessNotifications' => str_replace('://', '://'.$domain.'.', config('app.BASE_URL')).'/services/webhooks/business-webhook',
            ],
            'ignoreOldMessages' => 1,
        ];
        $updateResult = $mainWhatsLoopObj->updateChannelSetting($myData);
        $result = $updateResult->json();

        $updateResult2 = $mainWhatsLoopObj->clearInstanceData();
        $result2 = $updateResult2->json();

        $updateResult1 = $mainWhatsLoopObj->disconnect();
        $result1 = $updateResult1->json();

        $userObj = User::first();
        $centralUser = CentralUser::getOne($userObj->id);

        $userObj->setting_pushed = 1;
        $userObj->save();

        $centralUser->setting_pushed = 1;
        $centralUser->save();

        Contact::where('group_id',1)->delete();
        Category::where('id', '!=', null)->delete();
        Reply::where('reply_id', '!=', null)->delete();
        ChatMessage::where('id', '!=', null)->delete();
        ChatDialog::where('id', '!=', null)->delete();
        UserStatus::where('id', '!=', null)->delete();
        Product::where('id', '!=', null)->delete();
        Order::where('id', '!=', null)->delete();
        WACollection::where('id', '!=', null)->delete();
        Variable::where('var_key', 'ME')->delete();
        Session::flash('success', trans('main.logoutDone'));
        return redirect()->back();
    }

    public function clearMessagesQueue()
    {
        $mainWhatsLoopObj = new \OfficialHelper();
        $msgQueue = $mainWhatsLoopObj->clearMessagesQueue();
        $queueResult = $msgQueue->json();
        Session::flash('success', trans('main.inPrgo'));
        return redirect()->back();
    }

    public function updateChannelSetting()
    {
        $input = \Request::all();
        if(!isset($input['setting']) || empty($input['setting'])){
            return \TraitsFunc::ErrorMessage(trans('main.settingNotFound'));
        }
        if(!isset($input['value']) || $input['value'] == null){
            return \TraitsFunc::ErrorMessage(trans('main.valueNotFound'));
        }

        $varObj = Variable::where('var_key',$input['setting'])->first();
        if($varObj){
            $varObj->var_value = $input['value'];
            $varObj->save();
        }else{
            Variable::create(['var_key'=> $input['setting'],'var_value'=>$input['value']]);
        }

        if($input['setting'] == 'contactsNameType'){
            $data = ['page'=>1,'page_size'=>1000000];
            $mainWhatsLoopObj = new \OfficialHelper();
            $updateResult = $mainWhatsLoopObj->contacts($data);
            $result = $updateResult->json();
            $contacts = [];
            if ($result != null && $result['data'] != null) {
                $contacts = $result['data'];
            }
            try {
                dispatch(new SyncContactsJob($contacts))->onConnection('syncdata');
            } catch (Exception $e) {

            }
        }
        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
}
