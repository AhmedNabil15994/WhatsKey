<?php namespace App\Http\Controllers;

use App\Models\GroupNumber;
use App\Models\User;
use App\Models\Contact;
use App\Models\ChatDialog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Jobs\CheckWhatsappJob;
use DataTables;
use Storage;
use Excel;
use App\Exports\ContactExport;

class ContactsControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $groups = GroupNumber::dataList(1)['data'];
        $data['mainData'] = [
            'title' => trans('main.contacts'),
            'url' => 'contacts',
            'name' => 'contacts',
            'nameOne' => 'contact',
            'modelName' => 'Contact',
            'icon' => 'fas fa-users',
            'sortName' => 'name',
            'addOne' => trans('main.newContact'),
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'group_id' => [
                'type' => 'select',
                'class' => 'form-control datatable-input',
                'index' => '',
                'options' => $groups,
                'label' => trans('main.group'),
            ],
            'name' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '2',
                'label' => trans('main.name'),
            ],
            'email' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '3',
                'label' => trans('main.email'),
            ],
            'city' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '4',
                'label' => trans('main.city'),
            ],
            'country' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '5',
                'label' => trans('main.country'),
            ],
            'whats' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '6',
                'label' => trans('main.whats'),
            ],
            'from' => [
                'type' => 'text',
                'class' => 'form-control datatable-input datepicker',
                'index' => '7',
                'id' => 'datepicker1',
                'label' => trans('main.dateFrom'),
            ],
            'to' => [
                'type' => 'text',
                'class' => 'form-control datatable-input datepicker',
                'index' => '8',
                'id' => 'datepicker2',
                'label' => trans('main.dateTo'),
            ],
        ];

        $data['tableData'] = [
            'id' => [
                'label' => trans('main.id'),
                'index' => '0',
                'type' => '',
                'className' => '',
                'data-col' => '',
                'anchor-class' => '',
            ],
            'group' => [
                'label' => trans('main.group'),
                'index' => '1',
                'type' => '',
                'className' => '',
                'data-col' => 'group_id',
                'anchor-class' => 'badge badge-primary',
            ],
            'name' => [
                'label' => trans('main.name'),
                'index' => '2',
                'type' => '',
                'className' => 'edits',
                'data-col' => 'name',
                'anchor-class' => 'editable',
            ],
            'email' => [
                'label' => trans('main.email'),
                'index' => '3',
                'type' => '',
                'className' => 'edits',
                'data-col' => 'email',
                'anchor-class' => 'editable',
            ],
            'country' => [
                'label' => trans('main.country'),
                'index' => '4',
                'type' => '',
                'className' => 'edits',
                'data-col' => 'country',
                'anchor-class' => 'editable',
            ],
            'city' => [
                'label' => trans('main.city'),
                'index' => '5',
                'type' => '',
                'className' => 'edits',
                'data-col' => 'city',
                'anchor-class' => 'editable',
            ],
            'phone2' => [
                'label' => trans('main.whats'),
                'index' => '6',
                'type' => '',
                'className' => 'edits',
                'data-col' => 'phone',
                'anchor-class' => 'editable',
            ],
            'has_whatsapp_text' => [
                'label' => trans('main.hasWhats'),
                'index' => '7',
                'type' => '',
                'className' => '',
                'data-col' => 'has_whatsapp_text',
                'anchor-class' => '',
            ],
            'created_at' => [
                'label' => trans('main.date'),
                'index' => '7',
                'type' => 'date',
                'className' => 'edits dates',
                'data-col' => 'created_at',
                'anchor-class' => 'editable',
            ],
            'actions' => [
                'label' => trans('main.actions'),
                'index' => '8',
                'type' => '',
                'className' => '',
                'data-col' => '',
                'anchor-class' => '',
            ],
        ];
        return $data;
    }

    protected function validateInsertObject($input){
        
        return $validate;
    }

    public function index(Request $request) {
        $input = \Request::all();
        if($request->ajax()){
            $data = Contact::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $contactObj = Contact::NotDeleted()->find($id);
        if($contactObj == null) {
            return Redirect('404');
        }

        $data['data'] = Contact::getData($contactObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.contacts') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['groups'] = GroupNumber::dataList(1,[1])['data'];
        return view('Tenancy.Contact.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        // dd($input);
        $dataObj = Contact::NotDeleted()->find($id);
        if($dataObj == null) {
            return Redirect('404');
        }

        if(!isset($input['phone']) || empty($input['phone'])){
            Session::flash('error', trans('main.phoneValidate'));
            return redirect()->back()->withInput();
        }

        $phone = str_replace('+', '', $input['phone']);
        $contactObj = Contact::NotDeleted()->where('id','!=',$id)->where('group_id',$input['group_id'])->where('phone',$phone)->first();
        if($contactObj != null){
            Session::flash('error', trans('main.phoneError'));
            return redirect()->back();
        }

        $dataObj->name = !isset($input['client_name']) || empty($input['client_name']) ? $phone : $input['client_name'];
        $dataObj->phone = $phone;
        $dataObj->city = $input['city'];
        $dataObj->email = $input['email'];
        $dataObj->country = $input['country'];
        $dataObj->group_id = $input['group_id'];
        $dataObj->lang = $input['lang'];
        $dataObj->notes = $input['notes'];
        $dataObj->sort = Contact::newSortIndex();
        $dataObj->updated_by = USER_ID;
        $dataObj->updated_at = DATE_TIME;
        $dataObj->save();

        ChatDialog::where('id',str_replace('+', '', $dataObj->phone).'@c.us')->update(['name'=>$dataObj->name]);

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.contacts') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['groups'] = GroupNumber::dataList(1,[1])['data'];
        $data['modelProps'] = ['name'=>trans('main.name'),'email'=>trans('main.email'),'country'=>trans('main.country'),'city'=>trans('main.city'),'phone'=>trans('main.whats')];
        return view('Tenancy.Contact.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
       
        $modelProps = ['name','email','country','city','phone'];
        $userInputs = $input;
        $consForQueue = [];

        $type = $input['vType'];
        if(!isset($input['vType']) || empty($input['vType'])){
            Session::flash('error', trans('main.phoneValidate'));
            return redirect()->back()->withInput();
        }
        
        if($type == 2){
            $rules = ['group_id' => 'required',];
            $message = ['group_id.required' => trans('main.groupValidate'),];
            $validate = \Validator::make($input, $rules, $message);
            if($validate->fails()){
                Session::flash('error', $validate->messages()->first());
                return redirect()->back()->withInput();
            }
            $groupObj = GroupNumber::getOne($input['group_id']);
            if($groupObj == null) {
                return Redirect('404');
            }

            if(!isset($input['phone']) || empty($input['phone'])){
                Session::flash('error', trans('main.phoneValidate'));
                return redirect()->back()->withInput();
            }

            $contactObj = Contact::NotDeleted()->where('group_id',$input['group_id'])->where('phone',$input['phone'])->first();
            if(!$contactObj){
                $dataObj = new Contact;
                $dataObj->name = $input['client_name'];
                $dataObj->phone = str_replace('+', '', $input['phone']);
                $dataObj->city = $input['city'];
                $dataObj->email = $input['email'];
                $dataObj->country = $input['country'];
                $dataObj->group_id = $input['group_id'];
                $dataObj->lang = isset($input['lang']) && !empty($input['lang']) ? $input['lang'] : 0;
                $dataObj->notes = $input['notes'];
                $dataObj->has_whatsapp = 1;
                $dataObj->status = 1;
                $dataObj->sort = Contact::newSortIndex();
                $dataObj->created_by = USER_ID;
                $dataObj->created_at = DATE_TIME;
                $dataObj->save();
                $consForQueue[] = $dataObj;
            }else{
                Session::flash('error', trans('main.phoneError'));
                return redirect()->back()->withInput();
            }
        }elseif($type == 3){
            $rules = ['group_ids' => 'required',];
            $message = ['group_ids.required' => trans('main.groupValidate'),];
            $validate = \Validator::make($input, $rules, $message);
            if($validate->fails()){
                Session::flash('error', $validate->messages()->first());
                return redirect()->back()->withInput();
            }
            $groupObj = GroupNumber::getOne($input['group_ids']);
            if($groupObj == null) {
                return Redirect('404');
            }

            if(!isset($input['whatsappNos']) || empty($input['whatsappNos'])){
                Session::flash('error', trans('main.whatsappNosValidate'));
                return redirect()->back()->withInput();
            }
            $input['whatsappNos'] = trim($input['whatsappNos']);
            $numbersArr = explode(PHP_EOL, $input['whatsappNos']);
            if(count($numbersArr) > 100){
                Session::flash('error', trans('main.numberlimit',['number'=>100]));
                return redirect()->back()->withInput();
            }
            for ($i = 0; $i < count($numbersArr) ; $i++) {
                $phone = str_replace('\r', '', $numbersArr[$i]);
                $contactObj = Contact::NotDeleted()->where('group_id',$input['group_ids'])->where('phone',$phone)->first();
                if(!$contactObj){
                    $dataObj = new Contact;
                    $dataObj->phone = str_replace('+', '', trim($phone));
                    $dataObj->group_id = $input['group_ids'];
                    $dataObj->name = trim($phone);
                    $dataObj->status = 1;
                    $dataObj->has_whatsapp = 1;
                    $dataObj->sort = Contact::newSortIndex();
                    $dataObj->created_by = USER_ID;
                    $dataObj->created_at = DATE_TIME;
                    $dataObj->save();
                    $consForQueue[] = $dataObj;
                }else{
                    $foundData[] = $phone;
                }
            }
        }
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = Contact::getOne($id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = Contact::find($item[0]);
            if($col == 'phone'){
                $item[2] = str_replace('+', '', $item[2]);
            }elseif ($col == 'name') {
                ChatDialog::where('id',str_replace('+', '', $dataObj->phone).'@c.us')->update(['name'=>$item[2]]);
            }
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }

    public function downloadContacts($group_id){
        $group_id = (int) $group_id;
        $dataObj = GroupNumber::getOne($group_id);
        if($dataObj == null){
            \Session::flash('error',trans('main.notFound'));
            return redirect()->back();
        }
        $count = Contact::NotDeleted()->where('group_id',$group_id)->count();
        if($count>0){
            return Excel::download(new ContactExport($group_id), $dataObj->name_en.' contacts.xlsx');
        }else{
            return redirect()->back();
        }
    }

}
