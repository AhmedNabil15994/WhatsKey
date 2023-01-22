<?php namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DataTables;
use Storage;


class BankAccountControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.bankAccounts'),
            'url' => 'bankAccounts',
            'name' => 'bankAccounts',
            'nameOne' => 'bankAccount',
            'modelName' => 'BankAccount',
            'icon' => ' fas fa-credit-card',
            'sortName' => 'bank_name',
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'bank_name' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '1',
                'label' => trans('main.bank_name'),
            ],
            'account_name' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '2',
                'label' => trans('main.account_name'),
            ],
            'account_number' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '3',
                'label' => trans('main.account_number'),
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
            'bank_name' => [
                'label' => trans('main.bank_name'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'bank_name',
                'anchor-class' => 'editable',
            ],
            'account_name' => [
                'label' => trans('main.account_name'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'account_name',
                'anchor-class' => 'editable',
            ],
            'account_number' => [
                'label' => trans('main.account_number'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'account_number',
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

        return $data;
    }

    protected function validateInsertObject($input){
        $rules = [
            'bank_name' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
        ];

        $message = [
            'bank_name.required' => trans('main.bankNameValidate'),
            'account_name.required' => trans('main.accountNameValidate'),
            'account_number.required' => trans('main.accountNumberValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = BankAccount::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.User.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = BankAccount::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = BankAccount::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.bankAccounts') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        return view('Central.BankAccount.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        // dd($input);
        $dataObj = BankAccount::NotDeleted()->find($id);
        if($dataObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }


        $dataObj->bank_name = $input['bank_name'];
        $dataObj->account_name = $input['account_name'];
        $dataObj->account_number = $input['account_number'];
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
            }
        }

        Session::forget('photos');
        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.bankAccounts') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        return view('Central.BankAccount.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        
        $dataObj = new BankAccount;
        $dataObj->bank_name = $input['bank_name'];
        $dataObj->account_name = $input['account_name'];
        $dataObj->account_number = $input['account_number'];
        $dataObj->sort = BankAccount::newSortIndex();
        $dataObj->status = $input['status'];
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

        Session::forget('photos');
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = BankAccount::getOne($id);
        \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = BankAccount::find($item[0]);
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
            Storage::put($rand,$files);
            Session::put('photos',$rand);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images,$nextID=false){
        $fileName = \ImagesHelper::UploadFiles($this->getData()['mainData']['name'], $images, $nextID);
        if($fileName == false){
            return false;
        }
        return $fileName;        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = BankAccount::find($id);
        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage(trans('main.userNotFound'));
        }

        \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id.'/'.$menuObj->image);
        $menuObj->image = '';
        $menuObj->save();
        return \TraitsFunc::SuccessResponse(trans('main.imgDeleted'));
    }

}
