<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\UserExtraQuota;
use App\Models\NotificationTemplate;
use App\Models\CentralUser;
use DataTables;
use Storage;


class UsersControllers extends Controller {

    use \TraitsFunc;

    public function getData($type=false){
        $groups = Group::dataList(1,[1])['data'];
        
        $data['mainData'] = [
            'title' => trans('main.users'),
            'url' => 'users',
            'name' => 'users',
            'nameOne' => 'user',
            'modelName' => 'User',
            'icon' => 'fa fa-users',
            'sortName' => 'name',
            'addOne' => trans('main.newUser'),
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'name' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '1',
                'label' => trans('main.name'),
            ],
            'email' => [
                'type' => 'email',
                'class' => 'form-control datatable-input',
                'index' => '2',
                'label' => trans('main.email'),
            ],
            'phone' => [
                'type' => 'number',
                'class' => 'form-control datatable-input',
                'index' => '3',
                'label' => trans('main.phone'),
            ],
            'group_id' => [
                'type' => 'select',
                'class' => 'form-control',
                'index' => '',
                'options' => $groups,
                'label' => trans('main.group'),
            ],
        ];

        $data['tableData'] = [
            'id' => [
                'label' => trans('main.id'),
                'type' => '',
                'className' => '',
                'data-col' => '',
                'anchor-class' => '',
            ],
            'name' => [
                'label' => trans('main.name'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'name',
                'anchor-class' => 'editable',
            ],
            'email' => [
                'label' => trans('main.email'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'email',
                'anchor-class' => 'editable',
            ],
            'phone' => [
                'label' => trans('main.phone'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'phone',
                'anchor-class' => 'editable',
            ],
            'group' => [
                'label' => trans('main.group'),
                'type' => '',
                'className' => '',
                'data-col' => 'group_id',
                'anchor-class' => '',
            ],
            'actions' => [
                'label' => trans('main.actions'),
                'type' => '',
                'className' => '',
                'data-col' => '',
                'anchor-class' => '',
            ],
        ];

        $data['modelData'] = [
            'group_id' => [
                'type' => 'select',
                'class' => 'form-control',
                'options' => $groups,
                'label' => trans('main.group'),
                'specialAttr' => '',
                'required' => true,
            ],
            'name' => [
                'type' => 'text',
                'class' => 'form-control',
                'label' => trans('main.name'),
                'specialAttr' => '',
                'required' => true,
            ],
            'telephone' => [
                'type' => 'tel',
                'class' => 'form-control teles',
                'label' => trans('main.phone'),
                'specialAttr' => 'id=telephone',
                'required' => true,
            ],
            'password' => [
                'type' => 'password',
                'class' => 'form-control',
                'label' => trans('main.password'),
                'specialAttr' => '',
                'required' => $type ? false : true,
            ],
            'email' => [
                'type' => 'email',
                'class' => 'form-control',
                'label' => trans('main.email'),
                'specialAttr' => '',
            ],
            'image' => [
                'type' => 'image',
                'class' => 'form-control',
                'label' => trans('main.image'),
                'specialAttr' => '',
            ],
            
        ];
        return $data;
    }

    protected function validateInsertObject($input){
        $rules = [
            'group_id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'password' => 'required|min:6',
            // 'email' => 'required',
        ];

        $message = [
            'group_id.required' => trans('main.groupValidate'),
            'name.required' => trans('main.nameValidate'),
            'phone.required' => trans('main.phoneValidate'),
            'password.required' => trans('main.passwordValidate'),
            'password.min' => trans('main.passwordValidate2'),
            // 'email.required' => trans('main.emailValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    protected function validateUpdateObject($input){
        $rules = [
            'group_id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            // 'email' => 'required',
        ];

        $message = [
            'group_id.required' => trans('main.groupValidate'),
            'name.required' => trans('main.nameValidate'),
            'phone.required' => trans('main.phoneValidate'),
            // 'email.required' => trans('main.emailValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = User::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = User::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = User::getData($userObj);
        $data['designElems'] = $this->getData(true);
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.users') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['permissions'] = \Helper::getPermissions(true);
        $data['timelines'] = [];
        return view('Tenancy.Template.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        // dd($input);
        $dataObj = User::NotDeleted()->find($id);
        if($dataObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateUpdateObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        if(isset($input['email']) && !empty($input['email'])){
            $userObj = User::checkUserBy('email',$input['email'],$id);
            if($userObj){
                Session::flash('error', trans('main.emailError'));
                return redirect()->back()->withInput();
            }
        }
        
        if(isset($input['phone']) && !empty($input['phone'])){
            $userObj = User::checkUserBy('phone',$input['phone'],$id);
            if($userObj){
                Session::flash('error', trans('main.phoneError'));
                return redirect()->back()->withInput();
            }
        }

        if(isset($input['password']) && !empty($input['password'])){
            $rules = [
                'password' => 'required|min:6',
            ];

            $message = [
                'password.required' => trans('main.passwordValidate'),
                'password.min' => trans('main.passwordValidate2'),
            ];

            $validate = \Validator::make($input, $rules, $message);
            if($validate->fails()){
                Session::flash('error', $validate->messages()->first());
                return redirect()->back();
            }

            $dataObj->password = \Hash::make($input['password']);
        }

        $dataObj->name = $input['name'];
        if($dataObj->group_id != 1){
            $dataObj->group_id = $input['group_id'];
        }
        $dataObj->email = $input['email'];
        $dataObj->phone = $input['phone'];
        $dataObj->extra_rules = isset($input['permission']) && !empty($input['permission']) ? serialize($input['permission']) : '';
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();

        $photos_name = Session::get('photos');
        if($photos_name){
            $photos = Storage::files($photos_name);
            if(count($photos) > 0){
                $images = self::addImage($photos[0],$dataObj->id);
                if ($images == false) {
                    Session::flash('error', trans('main.uploadProb'));
                    return redirect()->back()->withInput();
                }
                $dataObj->image = $images;
                $dataObj->save();  
                if($dataObj->group_id == 1){
                    CentralUser::where('id',User::first()->id)->update([
                        'name' => $dataObj->name,
                        'email' => $dataObj->email,
                        'extra_rules' => $dataObj->extra_rules,
                        'updated_at' => $dataObj->updated_at,
                        'updated_by' => $dataObj->updated_by,
                        'image' => $dataObj->image,
                    ]);
                }
                
            }
        }

        Session::forget('photos');
        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $empsCount = User::NotDeleted()->where('group_id','!=',1)->count();
        $dailyCount = Session::get('employessCount');
        $extraQuotas = UserExtraQuota::getOneForUserByType(GLOBAL_ID,2);
        if($dailyCount + $extraQuotas <= $empsCount){
            Session::flash('error', trans('main.empQuotaError'));
            return redirect()->back()->withInput();
        }
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.users') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['timelines'] = [];
        $data['permissions'] = \Helper::getPermissions(true);
        return view('Tenancy.Template.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $empsCount = User::NotDeleted()->where('group_id','!=',1)->count();
        $dailyCount = Session::get('employessCount');
        $extraQuotas = UserExtraQuota::getOneForUserByType(GLOBAL_ID,2);
        if($dailyCount + $extraQuotas <= $empsCount){
            Session::flash('error', trans('main.empQuotaError'));
            return redirect()->back()->withInput();
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        
        if(isset($input['email']) && !empty($input['email'])){
            $userObj = User::checkUserBy('email',$input['email']);
            if($userObj){
                Session::flash('error', trans('main.emailError'));
                return redirect()->back()->withInput();
            }
        }
        
        if(isset($input['phone']) && !empty($input['phone'])){
            $userObj = User::checkUserBy('phone',$input['phone']);
            if($userObj){
                Session::flash('error', trans('main.phoneError'));
                return redirect()->back()->withInput();
            }
        }

        User::where('phone',$input['phone'])->where('deleted_at','!=',null)->delete();
        $mainUser = User::first();

        $dataObj = new User;
        $dataObj->name = $input['name'];
        $dataObj->group_id = $input['group_id'];
        $dataObj->email = $input['email'];
        $dataObj->channels = User::NotDeleted()->first()->channels;
        $dataObj->two_auth = 0;
        $dataObj->phone = $input['phone'];
        $dataObj->password = \Hash::make($input['password']);
        $dataObj->is_active = 1;
        $dataObj->is_approved = 1;
        $dataObj->notifications = 0;
        $dataObj->offers = 0;
        $dataObj->domain = $mainUser->domain;
        $dataObj->global_id = $mainUser->global_id;
        $dataObj->company = $mainUser->company;
        $dataObj->extra_rules = isset($input['permission']) && !empty($input['permission']) ? serialize($input['permission']) : '';
        $dataObj->sort = User::newSortIndex();
        $dataObj->status = 1;
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        $photos_name = Session::get('photos');
        if($photos_name){
            $photos = Storage::files($photos_name);
            if(count($photos) > 0){
                $images = self::addImage($photos[0],$dataObj->id);
                if ($images == false) {
                    Session::flash('error', trans('main.uploadProb'));
                    return redirect()->back()->withInput();
                }
                $dataObj->image = $images;
                $dataObj->save();  
            }
        }

        $myDomain = config('app.MY_DOMAIN');
        $tenantUrl = str_replace('myDomain', $mainUser->domain, $myDomain).'/';

        $notificationTemplateObj = NotificationTemplate::getOne(2,'newEmployee');
        $allData = [
            'name' => $dataObj->name,
            'subject' => $notificationTemplateObj->title_ar,
            'content' => $notificationTemplateObj->content_ar,
            'email' => $dataObj->email,
            'template' => 'tenant.emailUsers.default',
            'url' => $tenantUrl,
            'extras' => [
                'company' => $mainUser->company,
                'url' => $tenantUrl,
                'owner' => $mainUser->name,
                'employee_name' => $dataObj->name,
                'phone' => $dataObj->phone,
                'password' => $input['password'],
            ],
            'isBA' => 1,
        ];

        if($dataObj->email != null){
            \MailHelper::prepareEmail($allData);
        }

        $notificationTemplateObj = NotificationTemplate::getOne(1,'newEmployee');
        $phoneData = $allData;
        $phoneData['phone'] = $dataObj->phone;
        \MailHelper::prepareEmail($phoneData,1);

        Session::forget('photos');
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = User::getOne($id);
        if($dataObj->group_id == 1 && User::first()->id == $dataObj->id){
            return \TraitsFunc::ErrorMessage(trans('main.notDeleted'));
        }
        \ImagesHelper::deleteDirectory(public_path('/').'uploads/'.TENANT_ID.'/'.$this->getData()['mainData']['name'].'/'.$id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            if($col == 'email'){
                $userObj = User::checkUserBy('email',$item[2],$item[0]);
                if($userObj){
                    return \TraitsFunc::ErrorMessage(trans('main.emailFound',['email'=>$item[2]]));
                }
            }

            if($col == 'phone'){
                $userObj = User::checkUserBy('phone',$item[2],$item[0]);
                if($userObj){
                    return \TraitsFunc::ErrorMessage(trans('main.phoneFound',['phone'=>$item[2]]));
                }
            }

            $dataObj = User::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }

    public function uploadImage(Request $request,$id=false){
        $rand = rand() . date("YmdhisA");
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

            Storage::put($rand,$files);
            Session::put('photos',$rand);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images,$nextID=false){
        $fileName = \ImagesHelper::UploadFile($this->getData()['mainData']['name'], $images, $nextID);
        if($fileName == false){
            return false;
        }
        return $fileName;        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = User::find($id);
        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage(trans('main.userNotFound'));
        }

        \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id.'/'.$menuObj->image);
        $menuObj->image = '';
        $menuObj->save();
        return \TraitsFunc::SuccessResponse(trans('main.imgDeleted'));
    }

}
