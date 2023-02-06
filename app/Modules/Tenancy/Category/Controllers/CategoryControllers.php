<?php namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Variable;
use App\Models\UserAddon;
use App\Jobs\SyncLabelsJob;
use DataTables;
use Storage;


class CategoryControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.categories'),
            'url' => 'categories',
            'name' => 'categories',
            'nameOne' => 'category',
            'modelName' => 'Category',
            'icon' => ' fas fa-tags',
            'sortName' => 'name_'.LANGUAGE_PREF,
            'addOne' => trans('main.newCategory'),
        ];
        
        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'color_id' => [
                'type' => 'select',
                'class' => 'form-control datatable-input',
                'index' => '',
                'options' => Category::getColors(),
                'label' => trans('main.color'),
            ],
            'name_ar' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '3',
                'label' => trans('main.titleAr'),
            ],
            'name_en' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '4',
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
            'color' => [
                'label' => trans('main.color'),
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
            'color_id' => [
                'type' => 'select',
                'class' => 'form-control',
                'options' => Category::getColors(),
                'label' => trans('main.color'),
                'specialAttr' => '',
            ],
            'name_ar' => [
                'type' => 'text',
                'class' => 'form-control',
                'label' => trans('main.titleAr'),
                'specialAttr' => '',
                'required' => LANGUAGE_PREF == 'ar' ? true : '',
            ],
            'name_en' => [
                'type' => 'text',
                'class' => 'form-control',
                'label' => trans('main.titleEn'),
                'specialAttr' => '',
                'required' => LANGUAGE_PREF == 'en' ? true : '',
            ],
        ];
        return $data;
    }

    protected function validateInsertObject($input){
        $rules = [
            'name_'.LANGUAGE_PREF => 'required',
        ];

        $message = [
            'name_'.LANGUAGE_PREF.'.required' => trans('main.title'.ucfirst(LANGUAGE_PREF).'Validate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        $checkAvail = UserAddon::checkUserAvailability('BusinessProfile');
        if(!$checkAvail) {
            return Redirect('404');
        }
        if($request->ajax()){
            $data = Category::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        $data['disAdd'] = 1;
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $checkAvail = UserAddon::checkUserAvailability('BusinessProfile');
        $userObj = Category::NotDeleted()->find($id);
        if($userObj == null || !$checkAvail) {
            return Redirect('404');
        }

        $data['data'] = Category::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.categories') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['colors'] = Category::getColors();
        return view('Tenancy.Category.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $dataObj = Category::NotDeleted()->find($id);
        if($dataObj == null || $dataObj->labelId != 0) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $dataObj->color_id = $input['color_id'];
        $dataObj->name_ar = $input['name_ar'];
        $dataObj->name_en = $input['name_en'];
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $checkAvail = UserAddon::checkUserAvailability('BusinessProfile');
        if($userObj == null || !$checkAvail) {
            return Redirect('404');
        }
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.categories') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['colors'] = Category::getColors();
        return view('Tenancy.Category.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $dataObj = new Category;
        $dataObj->color_id = $input['color_id'];
        $dataObj->name_ar = $input['name_ar'];
        $dataObj->name_en = $input['name_en'];
        $dataObj->labelId = 0;
        $dataObj->sort = Category::newSortIndex();
        $dataObj->status = 1;
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function syncLabels(){
        $checkAvail = UserAddon::checkUserAvailability('BusinessProfile');
        if($userObj == null || !$checkAvail) {
            return Redirect('404');
        }
        $data = ['page'=>1,'page_size'=>1000000];
        $varObj = Variable::getVar('ME');
        if($varObj && json_decode($varObj)->isBussines){
            $mainWhatsLoopObj = new \OfficialHelper();
            $updateResult = $mainWhatsLoopObj->labels($data);
            $updateResult = $updateResult->json();
            if (isset($updateResult['data']) && !empty($updateResult['data'])) {
                try {
                    dispatch(new SyncLabelsJob($updateResult['data']))->onConnection('syncdata');
                } catch (Exception $e) {

                }
            }
        }
        
        Session::flash('success', trans('main.inPrgo'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = Category::getOne($id);
        if($dataObj->labelId == 0){
            return \Helper::globalDelete($dataObj);
        }
        $data['status'] = \TraitsFunc::ErrorMessage(trans('main.notExits'));
        return response()->json($data);
    }

    public function fastEdit() {
        $input = \Request::all();
        
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = Category::find($item[0]);
            if($dataObj->labelId == 0){
                $dataObj->$col = $item[2];
                $dataObj->updated_at = DATE_TIME;
                $dataObj->updated_by = USER_ID;
                $dataObj->save();
            }
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }

}
