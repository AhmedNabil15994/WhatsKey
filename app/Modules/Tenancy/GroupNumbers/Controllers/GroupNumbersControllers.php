<?php namespace App\Http\Controllers;

use App\Models\GroupNumber;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\UserExtraQuota;
use App\Models\Variable;
use App\Models\UserAddon;
use App\Jobs\CheckWhatsappJob;
use App\Exports\ContactImport;
use DataTables;
use Storage;
use Excel;


class GroupNumbersControllers extends Controller {

    use \TraitsFunc;

    public function getData(){        
        $data['mainData'] = [
            'title' => trans('main.groupNumbers'),
            'url' => 'groupNumbers',
            'name' => 'groupNumbers',
            'nameOne' => 'group-number',
            'modelName' => 'GroupNumber',
            'icon' => 'fas fa-users',
            'sortName' => 'name_'.LANGUAGE_PREF,
            'addOne' => trans('main.newGroupNumber'),
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'name_ar' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '1',
                'label' => trans('main.titleAr'),
            ],
            'name_en' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '2',
                'label' => trans('main.titleEn'),
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
            'name_ar' => [
                'label' => trans('main.titleAr'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'name_ar',
                'anchor-class' => 'editable',
            ],
            'name_en' => [
                'label' => trans('main.titleEn'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'name_en',
                'anchor-class' => 'editable',
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
            'name_ar' => [
                'type' => 'text',
                'class' => 'form-control',
                'label' => trans('main.titleAr'),
                'specialAttr' => '',
                'required' => true,
            ],
            'name_en' => [
                'type' => 'text',
                'class' => 'form-control',
                'label' => trans('main.titleEn'),
                'specialAttr' => '',
                'required' => true,
            ],
            'description_ar' => [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => trans('main.descriptionAr'),
                'specialAttr' => '',
            ],
            'description_en' => [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => trans('main.descriptionEn'),
                'specialAttr' => '',
            ],

        ];
        return $data;
    }

    protected function validateInsertObject($input){
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
        ];

        $message = [
            'name_ar.required' => trans('main.titleArValidate'),
            'name_en.required' => trans('main.titleEnValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = GroupNumber::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = GroupNumber::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = GroupNumber::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.groupNumbers') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        return view('Tenancy.Template.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        // dd($input);
        $dataObj = GroupNumber::NotDeleted()->find($id);
        if($dataObj == null || $id == 1) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $dataObj->channel = Session::get('channelCode');
        $dataObj->name_ar = $input['name_ar'];
        $dataObj->name_en = $input['name_en'];
        $dataObj->description_ar = $input['description_ar'];
        $dataObj->description_en = $input['description_en'];
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.groupNumbers') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        return view('Tenancy.Template.Views.add')->with('data', (object) $data);
    }

    public function create(Request $request) {
        $input = \Request::all();

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            if($request->ajax()){
                return \TraitsFunc::ErrorMessage($validate->messages()->first());
            }
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $dataObj = new GroupNumber;
        $dataObj->channel = Session::get('channelCode');
        $dataObj->name_ar = $input['name_ar'];
        $dataObj->name_en = isset($input['name_en']) && !empty($input['name_en']) ? $input['name_en'] : '';
        if($request->ajax()){
            $input['status'] = 1;
            $input['description_ar'] = '';
            $input['description_en'] = '';
        }

        $dataObj->description_ar = $input['description_ar'];
        $dataObj->description_en = $input['description_en'];
        $dataObj->sort = GroupNumber::newSortIndex();
        $dataObj->status = 1;
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        if($request->ajax()){
            return \Response::json((object) GroupNumber::getData($dataObj));
        }
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = GroupNumber::getOne($id);
        if($dataObj == null || $id == 1) {
            return \TraitsFunc::ErrorMessage(trans('main.notDeleted'));
        }
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = GroupNumber::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }
        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }

    public function addGroupNumbers(){
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.addGroupNumbers') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['groups'] = GroupNumber::dataList(1,[1])['data'];
        $data['channels'] =[];
        $data['channels'][0] = Session::get('channelCode');
        $data['checkGroupMsg'] = UserAddon::checkUserAvailability('GroupMsgs');

        $data['modelProps'] = ['name'=>trans('main.name'),'email'=>trans('main.email'),'country'=>trans('main.country'),'city'=>trans('main.city'),'phone'=>trans('main.whats')];
        return view('Tenancy.GroupNumbers.Views.add')->with('data', (object) $data);
    }

    public function checkFile(Request $request){
        if ($request->hasFile('file')) {
            $rows = Excel::toArray(new ContactImport, $request->file('file'));
            $headers = $rows[0][0];
            $data = array_slice($rows[0], 1, 10); 
            $time = time();
            $fileName = \ImagesHelper::uploadFileFromRequest('excels', $request->file('file'), $time);
            \Session::put('excel',$fileName);
            \Session::put('excelFolder',$time);
            return response()->json(["headers"=>$headers,'data'=>$data,'files'=>json_encode([])]);
        }
    }

    public function postAddGroupNumbers(){
        $input = \Request::all();
        if(!isset($input['group_id']) && empty($input['group_id'])){
            Session::flash('error', trans('main.groupValidate'));
            return redirect()->back();
        }
        
        $groupObj = GroupNumber::getOne($input['group_id']);
        if($groupObj == null) {
            return Redirect('404');
        }

        if(!isset($input['files']) && empty($input['files'])){
            Session::flash('error', trans('main.pleaseAttachExcel'));
            return redirect()->back();
        }
        $fileName = \Session::get('excel');
        $folder = \Session::get('excelFolder');        
        $rows = Excel::toArray(new ContactImport, public_path('uploads/excels/'.$folder.'/'.$fileName));
        $mainData = $rows[0];

        $modelProps = ['name','email','country','city','phone','Phone','الاسم','الرقم_المرسل'];
        $userInputs = $input;
        unset($userInputs['status']);
        unset($userInputs['group_id']);
        unset($userInputs['_token']);
        unset($userInputs['file']);
        unset($userInputs['files']);

        $dateTime = DATE_TIME;
        $storeData = [];
        $consForQueue = [];
        $myData = [];
        foreach ($userInputs as $key=> $userInput) {
            $key = strtolower($key);
            if(in_array($key, $modelProps)){
                $myData[$key] = $userInputs[$key];
            }
        }

        $rows =  array_slice($rows[0], 1);
        // dd($mainData);
        for ($i = 1; $i < count($mainData); $i++) {
            $header = $mainData[0];
            for ($x = 0; $x < count($header); $x++) {
                foreach ($myData as $key=> $userInput) {
                    if(!isset($storeData[$i])){
                        $storeData[$i] = [];
                    }
                    $myKey = $key;
                    if($key == 'الرقم_المرسل' || $key == 'الرقم المرسل'){
                        $myKey = 'phone';
                    }else if($key == 'الاسم'){
                        $myKey = 'name';
                    }
                    if(!isset($storeData[$i][$myKey])){
                        $storeData[$i][$myKey] = '';
                    }
                    if($key == strtolower($header[$x]) || str_replace('_', ' ', $key) == strtolower($header[$x])){
                        $myKey = $key;
                        if($key == 'الرقم_المرسل' || $key == 'الرقم المرسل'){
                            $myKey = 'phone';
                        }else if($key == 'الاسم'){
                            $myKey = 'name';
                        }
                        $storeData[$i][$myKey] = $mainData[$i][$x];
                    }
                }
            }
            $storeData[$i]['status'] = 1;
            $storeData[$i]['group_id'] = $input['group_id'];
            $storeData[$i]['created_at'] = $dateTime;
            $storeData[$i]['created_by'] = USER_ID;
        }

        $contsArr = [];
        $phones = [];
        foreach ($storeData as $value) {
            if(isset($value['phone']) && $value['phone'] != null){
                $phone = str_replace('+','',$value['phone']);
                $phone = str_replace('\r', '', $phone);
                if(!isset($value['name']) || empty($value['name'])){
                    $value['name'] = $phone;
                }
                if(isset($value['email']) && !empty($value['email'])){
                    $value['email'] = $input['email'];
                }
                if(isset($value['country']) && !empty($value['country'])){
                    $value['country'] = $input['country'];
                }
                if(isset($value['city']) && !empty($value['city'])){
                    $value['city'] = $input['city'];
                }
                $value['phone'] = trim(str_replace('\r', '', $phone));
                $value['status'] = 1;
                $phones[] = $value['phone'];
                $item = [];
                foreach($value as $attr => $val){
                    $item[$attr]= $val;
                }
                $contsArr[] = $item;
                $consForQueue[] = $item;
            }
        }

        $totals = count(array_unique($phones));
        $varObj = Variable::where('var_key','check_'.$input['group_id'].'_'.$dateTime)->first();
        if(!$varObj){
            Variable::create([
                'var_key' => 'check_'.$input['group_id'].'_'.$dateTime,
                'var_value' => $totals,
            ]);
        }else{
            $varObj->update([
                'var_key' => 'check_'.$input['group_id'].'_'.$dateTime,
                'var_value' => $totals,
            ]);
        }
        
        $chunks = 1000;
        $contacts = array_chunk($consForQueue,$chunks);
        foreach ($contacts as $contact) {
            try {
                dispatch(new CheckWhatsappJob($contact))->onConnection('open');
            } catch (Exception $e) {
                
            }
        }

        \Session::forget('rows');
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to('/groupNumbers/reports');
    }

     public function report(Request $request) {
        if($request->ajax()){
            $data = Contact::getContactsReports();
            return Datatables::of($data)->rawColumns(['contacts','hasWhatsapp','hasNoWhatsapp'])->make(true);
        }

        $data['designElems']['mainData'] = [
            'title' => trans('main.groupNumberRepors'),
            'url' => 'groupNumbers/reports',
            'name' => 'groupNumberReports',
            'nameOne' => 'groupNumberReports',
            'modelName' => 'groupNumberReports',
            'icon' => 'mdi mdi-file-account-outline',
        ];
        $data['designElems']['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'group_id' => [
                'type' => 'select',
                'class' => 'form-control datatable-input',
                'index' => '1',
                'options' => GroupNumber::dataList()['data'],
                'label' => trans('main.group'),
            ],
            'from' => [
                'type' => 'text',
                'class' => 'form-control datatable-input datepicker',
                'index' => '',
                'id' => 'datepicker1',
                'label' => trans('main.dateFrom'),
            ],
            'to' => [
                'type' => 'text',
                'class' => 'form-control datatable-input datepicker',
                'index' => '',
                'id' => 'datepicker2',
                'label' => trans('main.dateTo'),
            ],
        ];
        $data['designElems']['tableData'] = [
            'id' => [
                'label' => trans('main.id'),
                'type' => '',
                'className' => '',
                'data-col' => '',
                'anchor-class' => '',
            ],
            'group_name' => [
                'label' => trans('main.group'),
                'type' => '',
                'className' => '',
                'data-col' => 'group_name',
                'anchor-class' => 'badge badge-primary',
            ],
            'status' => [
                'label' => trans('main.addType'),
                'type' => '',
                'className' => '',
                'data-col' => 'status',
                'anchor-class' => 'badge badge-success',
            ],
            'total' => [
                'label' => trans('main.addNos'),
                'type' => '',
                'className' => '',
                'data-col' => 'total',
                'anchor-class' => '',
            ],
            'hasWhatsapp' => [
                'label' => trans('main.hasWhats'),
                'type' => '',
                'className' => '',
                'data-col' => 'hasWhatsapp',
                'anchor-class' => '',
            ],
            'hasNoWhatsapp' => [
                'label' => trans('main.hasNotWhats'),
                'type' => '',
                'className' => '',
                'data-col' => 'hasNoWhatsapp',
                'anchor-class' => '',
            ],
            'created_at' => [
                'label' => trans('main.addDate'),
                'type' => '',
                'className' => '',
                'data-col' => 'created_at',
                'anchor-class' => '',
            ],
        ];
        $data['dis'] = true;
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }
}
