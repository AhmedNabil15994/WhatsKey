<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DataTables;
use Storage;
use App\Models\Contact;
use App\Models\Variable;
use App\Models\UserAddon;
use App\Models\UserExtraQuota;

class BusinessProfileControllers extends Controller {

    use \TraitsFunc;

    public function index(Request $request) {
        $checkAvail = UserAddon::checkUserAvailability('BusinessProfile');
        if(!$checkAvail) {
            return Redirect('404');
        }
        $mainWhatsLoopObj = new \OfficialHelper();
        $me = $mainWhatsLoopObj->me();
        $meResult = $me->json();
        $profileArr = [];
        if($meResult != null && isset($meResult['data']) && !empty($meResult['data'])){
            Variable::where('var_key','ME')->delete();
            Variable::create(['var_key'=>'ME','var_value'=> json_encode($meResult['data'])]);
            $profileArr= $meResult['data'];
        }
        $data['data'] = $profileArr;       
        return view('Tenancy.BusinessProfile.Views.index')->with('data', (object) $data);
    }

    public function update(Request $request) {
        $input = \Request::all();
        $photos_name = Session::get('photo');
        
        $image = '';
        if($photos_name){
            $image = config('app.BASE_URL')  . '/uploads/users/0/' . $photos_name;
        }

        $mainWhatsLoopObj = new \OfficialHelper();
        if(isset($input['name']) && !empty($input['name'])){
            $mainWhatsLoopObj->updateName(['name'=>$input['name']]);
        }
        if(isset($input['status']) && !empty($input['status'])){
            $mainWhatsLoopObj->updateStatus(['status'=>$input['status']]);
        }
        if($image != ''){
            $mainWhatsLoopObj->updateProfilePicture(['phone'=>$input['phone'] , 'imageURL' => $image]);
        }
        
        Session::forget('photo');
        Session::flash('success', trans('main.editSuccess'));
        return redirect()->to('/businessProfile');
    }

    public function deleteImage(){
        $input = \Request::all();
        return \TraitsFunc::SuccessResponse(trans('main.imgDeleted'));
    }

    public function uploadImage(Request $request){
        if ($request->hasFile('file')) {
            $files = $request->file('file');

            $file_size = $files->getSize();
            $file_size = $file_size/(1024 * 1024);
            $file_size = number_format($file_size,2);
            $uploadedSize = \Helper::getFolderSize(public_path().'/uploads/'.TENANT_ID.'/');
            $totalStorage = Session::get('storageSize');
            $extraQuotas = UserExtraQuota::getOneForUserByType(GLOBAL_ID,3);
            if($totalStorage + $extraQuotas < (doubleval($uploadedSize) + $file_size) / 1024){
                return \TraitsFunc::ErrorMessage(trans('main.storageQuotaError'));
            }

            $type = \ImagesHelper::checkFileExtension($files->getClientOriginalName());
            if( $type != 'photo' ){
                return \TraitsFunc::ErrorMessage(trans('main.selectFile'));
            }
            $name = $this->addImage($files,0);
            Session::put('photo',$name);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images,$nextID=false){
        $fileName = \ImagesHelper::uploadFileFromRequest('users', $images, $nextID);
        if($fileName == false){
            return false;
        }
        return $fileName;        
    }

}
