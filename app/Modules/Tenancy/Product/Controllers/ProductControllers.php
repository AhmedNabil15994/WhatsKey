<?php namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WACollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DataTables;


class ProductControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.products'),
            'url' => 'products',
            'name' => 'products',
            'nameOne' => 'product',
            'modelName' => 'Product',
            'icon' => 'la la-product-hunt',
            'addOne' => trans('main.addNewProduct'),
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
                'specialAttr' => '',
            ],
            'name' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '2',
                'label' => trans('main.name'),
                'specialAttr' => '',
            ],
            'price' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '3',
                'label' => trans('main.price'),
                'specialAttr' => '',
            ],
            'product_id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '1',
                'label' => trans('main.productId'),
                'specialAttr' => '',
            ],
            'availability' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '4',
                'label' => trans('main.availability'),
                'specialAttr' => '',
            ],
            'review_status' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '5',
                'label' => trans('main.review_status'),
                'specialAttr' => '',
            ],
            'is_hidden' => [
                'type' => 'select',
                'class' => 'form-control datatable-input',
                'index' => '6',
                'label' => trans('main.isHidden'),
                'specialAttr' => '',
                'options' => [
                    ['id' => 0 , 'title' => trans('main.no')],
                    ['id' => 1 , 'title' => trans('main.yes')],
                ],
            ],
            'collection_id' => [
                'type' => 'select',
                'class' => 'form-control datatable-input',
                'index' => '7',
                'label' => trans('main.collection'),
                'options' => [],
                'specialAttr' => '',
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
            'product_id' => [
                'label' => trans('main.productId'),
                'type' => '',
                'className' => '',
                'data-col' => 'product_id',
                'anchor-class' => '',
            ],
            'name' => [
                'label' => trans('main.name'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'name',
                'anchor-class' => 'editable',
            ],
            'price' => [
                'label' => trans('main.price'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'price',
                'anchor-class' => 'editable',
            ],
            'availability' => [
                'label' => trans('main.availability'),
                'type' => '',
                'className' => '',
                'data-col' => 'availability',
                'anchor-class' => '',
            ],
            'review_status' => [
                'label' => trans('main.review_status'),
                'type' => '',
                'className' => '',
                'data-col' => 'review_status',
                'anchor-class' => '',
            ],
            'is_hidden' => [
                'label' => trans('main.availability'),
                'type' => 'select',
                'className' => 'edits selects',
                'data-col' => 'isHidden',
                'anchor-class' => 'editable',
                'options' => [
                    ['id' => 0 , 'title' => trans('main.no')],
                    ['id' => 1 , 'title' => trans('main.yes')],
                ],
            ],
            'collection' => [
                'label' => trans('main.collection'),
                'type' => 'select',
                'className' => 'edits selects',
                'data-col' => 'collection_id',
                'anchor-class' => 'editable',
                'options' => []
            ],
            'actions' => [
                'label' => trans('main.actions'),
                'type' => '',
                'className' => '',
                'data-col' => '',
                'anchor-class' => '',
            ],
        ];

        $data['modelData'] = [];
        return $data;
    }

    protected function validateInsertObject($input){
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
        ];

        $message = [
            'name_ar.required' => trans('main.nameArValidate'),
            'name_en.required' => trans('main.nameEnValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Product::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        $collections = WACollection::get();
        $colls = [];
        foreach($collections as $one){
            $colls[] = ['id'=>$one->id,'title'=>$one->name];
        }
        $data['designElems']['searchData']['collection_id']['options'] = $colls;
        $data['designElems']['collection_id']['collection_id']['options'] = $colls;
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.products') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        return view('Tenancy.Template.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        

        $dataObj = new Group;
        $dataObj->name_ar = $input['name_ar'];
        $dataObj->name_en = $input['name_en'];
        $dataObj->rules = isset($input['permission']) && !empty($input['permission']) ? serialize($input['permission']) : '';
        $dataObj->sort = Group::newSortIndex();
        $dataObj->status = isset($input['status']) && !empty($input['status']) ? $input['status'] : 1;
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function view($id) {
        $id = (int) $id;

        $userObj = Product::find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Product::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.groups') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        return view('Tenancy.Template.Views.edit')->with('data', (object) $data);      
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Product::find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Product::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.groups') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        return view('Tenancy.Template.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $dataObj = Product::find($id);
        if($dataObj == null || $id == 1) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $dataObj->name_ar = $input['name_ar'];
        $dataObj->name_en = $input['name_en'];
        $dataObj->rules = isset($input['permission']) && !empty($input['permission']) ? serialize($input['permission']) : '';
        $dataObj->status = $input['status'];
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();

        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = Group::getOne($id);
        if($id == 1){
            return \TraitsFunc::ErrorMessage(trans('main.notDeleted'));
        }
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = Group::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
}