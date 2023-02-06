<?php namespace App\Http\Controllers;

use App\Events\ChatLabelStatus;
use App\Events\ChatReadStatus;
use App\Events\DialogPinStatus;
use App\Events\SentMessage;
use App\Jobs\NewDialogJob;
use App\Models\Category;
use App\Models\CentralUser;
use App\Models\ChatDialog;
use App\Models\ChatEmpLog;
use App\Models\ChatMessage;
use App\Models\Contact;
use App\Models\ContactLabel;
use App\Models\Reply;
use App\Models\User;
use App\Models\UserAddon;
use App\Models\UserExtraQuota;
use App\Models\Variable;
use App\Events\DialogUpdate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use TraitsFunc;

class LiveChatControllers extends Controller
{

    use TraitsFunc;

    public function upload(Request $request){
        if($request->hasFile('audio-blob')){
            $image = $request->file('audio-blob');

            $file_size = $image->getSize();
            $file_size = $file_size / (1024 * 1024);
            $file_size = number_format($file_size, 2);

            $uploadedSize = \Helper::getFolderSize(public_path() . '/uploads/' . Session::get('tenant_id') . '/');
            $totalStorage = Session::get('storageSize');
            $extraQuotas = UserExtraQuota::getOneForUserByType(Session::get('global_id'), 3);
            if ($totalStorage + $extraQuotas < (doubleval($uploadedSize) + $file_size) / 1024) {
                return Session::put('errorMsg',trans('main.storageQuotaError'));
            }

            $fileName = \ImagesHelper::uploadFileFromRequest('chats', $image, 50,'sounds');
            if($fileName == false){
                return false;
            }
            $dataUrl = config('app.BASE_URL')  . '/uploads/' . Session::get('tenant_id') . '/chats/' . $fileName;
            $originalName = $image->getClientOriginalName();
            Session::put('audioName',$originalName);
            Session::put('audioDataURL',$dataUrl);

            return 1;
        }
    }


    public function index()
    {   
        $checkAvail = UserAddon::checkUserAvailability('Livechat');
        if(!$checkAvail){
            return redirect(404);
        }

        $varObj = Variable::getVar('ME');
        $business = 0;
        if($varObj && isset(json_decode($varObj)->isBussines) && json_decode($varObj)->isBussines){
            $business = 1;
        }
        \Session::forget('selected_chat_id');
        \Session::put('BUSINESS',$business);

        $is_admin = IS_ADMIN;
        $user_id = USER_ID;
        if(!$is_admin){
            $lastObj = ChatEmpLog::where('user_id',$user_id)->where('type','!=',3)->orderBy('id','DESC')->first();
            if($lastObj != null && $lastObj->ended == 0 && $lastObj->type == 1){
                $lastObj->ended = 1;
                $lastObj->ended_at = DATE_TIME;
                $lastObj->save();
                ChatEmpLog::newRecord($lastObj->chatId,2,$user_id,date('Y-m-d H:i:s'),1);
            }
        }

        $data['contacts'] = Contact::NotDeleted()->where('group_id',1)->get(['id','name','phone']);
        return view('Tenancy.LiveChat.Views.index')->with('data',(object)$data);
    }

    public function updateContact(Request $request)
    {
        $input = \Request::all();
        $domain = User::first()->domain;
        $input['chatId'] = Session::get('selected_chat_id');
        $checkAvail = UserAddon::checkUserAvailability('Livechat');
        if(!$checkAvail){
            return \TraitsFunc::ErrorMessage('Please Re-activate LiveChat Addon');
        }

        if (!IS_ADMIN && !\Helper::checkRules('update-livechat-contact-details')) {
            return \TraitsFunc::ErrorMessage("Please Add (update-livechat-contact-details) Privilege To User's Group");
        }

        if (!isset($input['chatId']) || empty($input['chatId'])) {
            return \TraitsFunc::ErrorMessage("Chat ID Is Required");
        }

        $contactObj = Contact::NotDeleted()->where('phone', str_replace('@c.us', '', $input['chatId']))->orWhere('phone', str_replace('@g.us', '', $input['chatId']))->first();
        if (!$contactObj) {
            return \TraitsFunc::ErrorMessage("Invalid Contact");
        }

        if (isset($input['email']) && !empty($input['email'])) {
            $contactObj->email = $input['email'];
        }
        if (isset($input['city']) && !empty($input['city'])) {
            $contactObj->city = $input['city'];
        }
        if (isset($input['country']) && !empty($input['country'])) {
            $contactObj->country = $input['country'];
        }
        if (isset($input['notes']) && !empty($input['notes'])) {
            $contactObj->notes = $input['notes'];
        }
        $contactObj->save();

        if (isset($input['name']) && !empty($input['name'])) {

            $chatObj = ChatDialog::where('id', $input['chatId'])->first();
            $chatObj->name = $input['name'];
            $chatObj->save();

            if (isset($input['disable_read']) && $input['disable_read'] != null) {
                $chatObj->disable_read = $input['disable_read'];
                $chatObj->save();
            }

            if (isset($input['mods']) && !empty($input['mods'])) {
                $input['mods'] = json_decode($input['mods']);
                $modArrs = $chatObj->modsArr;
                if ($modArrs == null) {
                    $chatObj->modsArr = serialize($input['mods']);
                    $chatObj->save();
                } else {
                    $oldArr = unserialize($chatObj->modsArr);
                    $newArr = array_unique(array_merge($oldArr,$input['mods']));
                    $chatObj->modsArr = serialize($input['mods']);
                    $chatObj->save();
                }
            }

            if($request->hasFile('background')){
                $image = $request->file('background');

                $file_size = $image->getSize();
                $file_size = $file_size / (1024 * 1024);
                $file_size = number_format($file_size, 2);

                $uploadedSize = \Helper::getFolderSize(public_path() . '/uploads/' . Session::get('tenant_id') . '/');
                $totalStorage = Session::get('storageSize');
                $extraQuotas = UserExtraQuota::getOneForUserByType(Session::get('global_id'), 3);
                if ($totalStorage + $extraQuotas < (doubleval($uploadedSize) + $file_size) / 1024) {
                    return \TraitsFunc::ErrorMessage(trans('main.storageQuotaError'));
                }


                $fileName = \ImagesHelper::uploadFileFromRequest('chats', $image, 50);
                if($fileName == false){
                    return false;
                }
                $dataUrl = config('app.BASE_URL')  . '/uploads/' . Session::get('tenant_id') . '/chats/' . $fileName;
                
                $chatObj->background = $dataUrl;
                $chatObj->save();
            }
        }

        broadcast(new DialogUpdate(strtolower($domain), $input['chatId']));

        $dataList['status'] = \TraitsFunc::SuccessMessage('Data Updated Successfully.');
        return \Response::json((object) $dataList);
    }



    public function liveChatLogout()
    {
        $is_admin = IS_ADMIN;
        $user_id = USER_ID;
        if (!$is_admin) {
            $lastObj = ChatEmpLog::where('user_id', $user_id)->where('type', '!=', 3)->orderBy('id', 'DESC')->first();
            if ($lastObj != null && $lastObj->ended == 0 && $lastObj->type == 1) {
                $lastObj->ended = 1;
                $lastObj->ended_at = DATE_TIME;
                $lastObj->save();
                ChatEmpLog::newRecord($lastObj->chatId, 2, $user_id, date('Y-m-d H:i:s'), 1);
            }
        }
        return redirect()->to('/dashboard');
    }
}
