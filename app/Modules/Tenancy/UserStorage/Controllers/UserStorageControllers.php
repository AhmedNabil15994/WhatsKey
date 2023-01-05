<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GroupMsg;
use App\Models\Bot;
use App\Models\ChatMessage;
use App\Models\UserExtraQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\WebActions;
use Storage;
use File;


class UserStorageControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.storage'),
            'url' => 'storage',
            'name' => 'storage',
            'nameOne' => 'storage',
            'icon' => 'mdi mdi-folder-star-outline',
        ];

        $file_size = \Helper::getFolderSize(public_path().'/uploads/'.TENANT_ID.'/');
        $data['totalSize'] = $file_size;

        $dailyCount = Session::get('storageSize');
        $extraQuotas = UserExtraQuota::getOneForUserByType(GLOBAL_ID,3);
        $totalStorage = $dailyCount + $extraQuotas;
        $totalStorage = $totalStorage * 1024;

        $data['totalStorage'] = $totalStorage;
        return $data;
    }

    public function index(Request $request) {
        $users = User::where('image','!=',null)->get(['id','created_at']);
        $dataArr = [];
        foreach ($users as $key => $value) {
            $dataArr[$key] = new \stdClass();
            $dataArr[$key]->id = $value->id;
            $dataArr[$key]->created_at = $value->created_at;
            $dataArr[$key]->folder_size = \Helper::getFolderSize(public_path().'/uploads/'.TENANT_ID.'/users/'.$value->id);
        }
        $data['designElems'] = $this->getData();
        $data['data'] = $dataArr;
        $data['type'] = 'users';
        $data['parent'] = 'main';
        $data['totalSize'] = $data['designElems']['totalSize'];
        $data['totalStorage'] = $data['designElems']['totalStorage'];
        return view('Tenancy.UserStorage.Views.index')->with('data', (object) $data);
    }

    public function bots(Request $request) {
        $users = Bot::where('file_name','!=',null)->get(['id','created_at']);
        $dataArr = [];
        foreach ($users as $key => $value) {
            $dataArr[$key] = new \stdClass();
            $dataArr[$key]->id = $value->id;
            $dataArr[$key]->created_at = $value->created_at;
            $dataArr[$key]->folder_size = \Helper::getFolderSize(public_path().'/uploads/'.TENANT_ID.'/bots/'.$value->id);
        }
        $data['designElems'] = $this->getData();
        $data['data'] = $dataArr;
        $data['type'] = 'bots';
        $data['parent'] = 'main';
        $data['totalSize'] = $data['designElems']['totalSize'];
        $data['totalStorage'] = $data['designElems']['totalStorage'];
        return view('Tenancy.UserStorage.Views.index')->with('data', (object) $data);
    }

    public function groupMsgs(Request $request) {
        $users = GroupMsg::where('file_name','!=',null)->get(['id','created_at']);
        $dataArr = [];
        foreach ($users as $key => $value) {
            $dataArr[$key] = new \stdClass();
            $dataArr[$key]->id = $value->id;
            $dataArr[$key]->created_at = $value->created_at;
            $dataArr[$key]->folder_size = \Helper::getFolderSize(public_path().'/uploads/'.TENANT_ID.'/groupMessages/'.$value->id);
        }
        $data['designElems'] = $this->getData();
        $data['data'] = $dataArr;
        $data['type'] = 'groupMessages';
        $data['parent'] = 'main';
        $data['totalSize'] = $data['designElems']['totalSize'];
        $data['totalStorage'] = $data['designElems']['totalStorage'];
        return view('Tenancy.UserStorage.Views.index')->with('data', (object) $data);
    }

    public function chats(Request $request) {
        $dataObj = [];
        $path = public_path().'/uploads/'.TENANT_ID.'/chats/';
        if(file_exists($path)){
            foreach (File::allFiles($path) as $key => $file) {
                $dataObj[$key] = new \stdClass();
                $file_size = $file->getSize();
                $file_size = $file_size/(1024 * 1024);
                $file_size = number_format($file_size,2);
                $dataObj[$key]->size = $file_size;
                $dataObj[$key]->name = $file->getFileName();
                $dataObj[$key]->extension = in_array($file->getExtension(),['png','jpg','jpeg']) ? 'jpg' : $file->getExtension();
                $dataObj[$key]->file = \URL::to('/').'/uploads/'.TENANT_ID.'/chats/'.$file->getFileName();
            }
        }
        $data['data'] = $dataObj;
        $data['designElems'] = $this->getData();
        $data['type'] = 'chats';
        $data['parent'] = 'child';
        $data['totalSize'] = $data['designElems']['totalSize'];
        $data['totalStorage'] = $data['designElems']['totalStorage'];
        return view('Tenancy.UserStorage.Views.index')->with('data', (object) $data);
    }

    public function getByTypeAndId($type,$id){
        // if($type == 'users'){
        //     $dataObj = User::getData(User::getOne($id));
        // }else if($type == 'bots'){
        //     $dataObj = Bot::getData(Bot::getOne($id));
        // }else if($type == 'groupMsgs'){
        //     $dataObj = GroupMsg::getData(GroupMsg::getOne($id));
        // }
        
        // if( isset($dataObj->photo_name) && $dataObj->photo_name == null){
        //     return redirect()->back();
        // }

        // if( isset($dataObj->file_name) && $dataObj->file_name == null){
        //     return redirect()->back();
        // }
        



        $dataObj = [];
        $path = public_path().'/uploads/'.TENANT_ID.'/'.$type.'/'.$id;
        if(file_exists($path)){
            foreach (File::allFiles($path) as $key => $file) {
                $dataObj[$key] = new \stdClass();
                $file_size = $file->getSize();
                $file_size = $file_size/(1024 * 1024);
                $file_size = number_format($file_size,2);
                $dataObj[$key]->size = $file_size;
                $dataObj[$key]->name = $file->getFileName();
                $dataObj[$key]->extension = in_array($file->getExtension(),['png','jpg','jpeg']) ? 'jpg' : $file->getExtension();
                $dataObj[$key]->file = \URL::to('/').'/uploads/'.TENANT_ID.'/'.$type.'/'.$id.'/'.$file->getFileName();
            }
        }
        $data['data'] = $dataObj;
        $data['designElems'] = $this->getData();
        $data['type'] = $type;
        $data['parent'] = 'child';
        $data['totalSize'] = $data['designElems']['totalSize'];
        $data['totalStorage'] = $data['designElems']['totalStorage'];
        return view('Tenancy.UserStorage.Views.index')->with('data', (object) $data);
    }

    public function removeByTypeAndId($type,$id){
        $input = \Request::all();

        if($type == 'users'){
            $dataObj = User::getOne($id);
        }else if($type == 'bots'){
            $dataObj = Bot::getOne($id);
        }else if($type == 'groupMessages'){
            $dataObj = GroupMsg::getOne($id);
        }

        $url = public_path('/').'uploads/'.TENANT_ID.'/'.$type.'/'.$id;
        if(isset($input['fileName']) && !empty($input['fileName'])){
            $url = public_path('/').'uploads/'.TENANT_ID.'/'.$type.'/'.$id.'/'.$input['fileName'];
            if(isset($dataObj->image) && $input['fileName'] == $dataObj->image){
                $dataObj->image = null;
                $dataObj->save();
            }

            if(isset($dataObj->file_name) && $input['fileName'] == $dataObj->file_name){
                $dataObj->file_name = null;
                $dataObj->save();
            } 

            if(isset($dataObj->file_name) && $input['fileName'] == $dataObj->file_name){
                $dataObj->file_name = null;
                $dataObj->save();
            } 
        }else{
            if(isset($dataObj->image)){
                $dataObj->image = null;
                $dataObj->save();
            }

            if(isset($dataObj->file_name)){
                $dataObj->file_name = null;
                $dataObj->save();
            } 

            if(isset($dataObj->file_name)){
                $dataObj->file_name = null;
                $dataObj->save();
            } 
        }
        
        \ImagesHelper::deleteDirectory($url);
        return \TraitsFunc::SuccessResponse(trans('main.deleteSuccess'));
    }

    public function removeChatFile($id){
        \ImagesHelper::deleteDirectory(public_path('/').'uploads/'.TENANT_ID.'/chats/'.$id);
        return \TraitsFunc::SuccessResponse(trans('main.deleteSuccess'));
    }

}
