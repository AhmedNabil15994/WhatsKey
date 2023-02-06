<?php namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WACollection;
use App\Models\Order;
use App\Models\Contact;
use App\Models\UserExtraQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DataTables;
use Storage;


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
                'label' => trans('main.isHidden'),
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
            'name' => 'required',
            'price' => 'required',
            'currency' => 'required',
        ];

        $message = [
            'name.required' => trans('main.nameValidate'),
            'price.required' => trans('main.pirceValidate'),
            'currency.required' => trans('main.currencyValidate'),
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
            $data = Product::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        $collections = WACollection::get();
        $colls = [];
        $data['disFastEdit'] = 1;
        foreach($collections as $one){
            $colls[] = ['id'=>$one->id,'title'=>$one->name];
        }
        $data['designElems']['searchData']['collection_id']['options'] = $colls;
        $data['designElems']['collection_id']['collection_id']['options'] = $colls;
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function add() {
        $checkAvail = UserAddon::checkUserAvailability('BusinessProfile');
        if(!$checkAvail) {
            return Redirect('404');
        }

        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.products') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        return view('Tenancy.Product.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $photos_name = Session::get('photo');
        $image = '';
        if($photos_name){
            $photos = Storage::files($photos_name);
            if(count($photos) > 0){
                $images = self::addImage($photos[0],0);
                if ($images == false) {
                    Session::flash('error', trans('main.uploadProb'));
                    return redirect()->back()->withInput();
                }
                $image = config('app.BASE_URL')  . '/uploads/' . \Session::get('tenant_id') . '/products/0/' . $images;
            }
        }
       
        $mainWhatsLoopObj = new \OfficialHelper();
        $updateResult = $mainWhatsLoopObj->productCreate([
            'name' => $input['name'],
            'description' => $input['description'],
            'price' => $input['price'],
            'currency' => $input['currency'],
            'isHidden' => $input['is_hidden'] == 1 ? true : false,
            'image' => $image,
        ]);
        $updateResult = $updateResult->json();
        if(!isset($updateResult) || !isset($updateResult['data']) || !isset($updateResult['data']['id'])){
            Session::flash('error', $updateResult['status']['message']);
            return \Redirect::back()->withInput();
        }

        $dataObj = new Product;
        $dataObj->name = $input['name'];
        $dataObj->product_id = $updateResult['data']['id'];
        $dataObj->description = $input['description'];
        $dataObj->price = $input['price'];
        $dataObj->availability = $updateResult['data']['availability'];
        $dataObj->review_status = isset($updateResult['data']['reviewStatus']) && isset($updateResult['data']['reviewStatus']['whatsapp']) ? $updateResult['data']['reviewStatus']['whatsapp'] : '';
        $dataObj->currency = $input['currency'];
        $dataObj->is_hidden = $input['is_hidden'];
        $dataObj->images = $image;
        $dataObj->save();

        Session::forget('photo');
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function view($id) {

        $checkAvail = UserAddon::checkUserAvailability('BusinessProfile');
        if(!$checkAvail) {
            return Redirect('404');
        }

        $id = (int) $id;

        $userObj = Product::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Product::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.products') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['latest'] = Product::dataList($id)['data'];
        $data['lastOrders'] = Order::dataList($userObj->product_id)['data'];
        $data['contacts'] = Contact::dataList(1)['data'];
        return view('Tenancy.Product.Views.view')->with('data', (object) $data);      
    }

    public function sendProduct($id){
        $checkAvail = UserAddon::checkUserAvailability('BusinessProfile');
        if(!$checkAvail) {
            return Redirect('404');
        }

        $input = \Request::all();
        $id = (int) $id;

        $userObj = Product::NotDeleted()->find($id);
        if($userObj == null && $userObj->product_id != null) {
            return Redirect('404');
        }

        if(!isset($input['phones']) || empty($input['phones'])){
            return \TraitsFunc::ErrorMessage(trans('main.editSuccess'));
        }

        $phones = $input['phones'];
        if($input['type'] == 2){
            $newPhones = [];
            $phones = trim($input['phones']);
            
            $numbersArr = explode(PHP_EOL, $phones);
            for ($i = 0; $i < count($numbersArr) ; $i++) {
                $phone = str_replace('\r', '', $numbersArr[$i]);
                $newPhones[] = $phone;
            }
            $phones = $newPhones;
        }
        
        $mainWhatsLoopObj = new \OfficialHelper();
        $updateResult = $mainWhatsLoopObj->sendBulkProduct([
            'phones' => $phones,
            'productId' => $userObj->product_id,
            'interval' => 3,
        ]);
        $updateResult = $updateResult->json();
        
        $dataList['status'] = \TraitsFunc::SuccessMessage(trans('main.inPrgo'));
        return \Response::json((object) $dataList);     
    }

    public function edit($id) {
        $checkAvail = UserAddon::checkUserAvailability('BusinessProfile');
        if(!$checkAvail) {
            return Redirect('404');
        }

        $id = (int) $id;

        $userObj = Product::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Product::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.products') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        return view('Tenancy.Product.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        $dataObj = Product::NotDeleted()->find($id);
        if($dataObj == null || $id == 1) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $photos_name = Session::get('photo');
        $image = '';
        if($photos_name){
            $photos = Storage::files($photos_name);
            if(count($photos) > 0){
                $images = self::addImage($photos[0],0);
                if ($images == false) {
                    Session::flash('error', trans('main.uploadProb'));
                    return redirect()->back()->withInput();
                }
                $image = config('app.BASE_URL')  . '/uploads/' . \Session::get('tenant_id') . '/products/0/' . $images;
            }
        }

        $updateArr = [
            'productId' => $dataObj->product_id,
        ];
        
        if(isset($input['name']) && $input['name'] != '' && $input['name'] != $dataObj->name){
            $updateArr['name'] = $input['name'];
        }
        if(isset($input['description']) && $input['description'] != '' && $input['description'] != $dataObj->description){
            $updateArr['description'] = $input['description'];
        }
        if(isset($input['price']) && $input['price'] != '' && $input['price'] != $dataObj->price){
            $updateArr['price'] = $input['price'];
        }
        if(isset($input['currency']) && $input['currency'] != '' && $input['currency'] != $dataObj->currency){
            $updateArr['currency'] = $input['currency'];
        }
        if(isset($input['is_hidden']) && $input['is_hidden'] != '' && $input['is_hidden'] != $dataObj->is_hidden){
            $updateArr['isHidden'] = $input['is_hidden'];
        }
        if(isset($image) && $image != '' && $image != $dataObj->images){
            $updateArr['image'] = $image;
        }

        $mainWhatsLoopObj = new \OfficialHelper();
        $updateResult = $mainWhatsLoopObj->productUpdate($updateArr);
        $updateResult = $updateResult->json();
        if(!isset($updateResult) || !isset($updateResult['data']) || !isset($updateResult['data']['id'])){
            Session::flash('error', $updateResult['status']['message']);
            return \Redirect::back()->withInput();
        }

        $dataObj->name = $input['name'];
        $dataObj->description = $input['description'];
        $dataObj->price = $input['price'];
        $dataObj->availability = $updateResult['data']['availability'];
        $dataObj->review_status = isset($updateResult['data']['reviewStatus']) && isset($updateResult['data']['reviewStatus']['whatsapp']) ? $updateResult['data']['reviewStatus']['whatsapp'] : '';
        $dataObj->currency = $input['currency'];
        $dataObj->is_hidden = $input['is_hidden'];
        $dataObj->images = $image;
        $dataObj->save();

        Session::forget('photo');
        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function delete($id) {

        $checkAvail = UserAddon::checkUserAvailability('BusinessProfile');
        if(!$checkAvail) {
            return Redirect('404');
        }
        
        $id = (int) $id;
        $dataObj = Product::getOne($id);
        if(!$dataObj){
            return \TraitsFunc::ErrorMessage(trans('main.notDeleted'));
        }
        if($dataObj->product_id != null){
            $mainWhatsLoopObj = new \OfficialHelper();
            $updateResult = $mainWhatsLoopObj->productDelete(['productId'=>$dataObj->product_id]);
            $updateResult = $updateResult->json();
        }
        return \Helper::globalDelete($dataObj);
    }

    public function uploadImage(Request $request){
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

            $type = \ImagesHelper::checkFileExtension($files->getClientOriginalName());
            
            if( $type != 'photo' ){
                return \TraitsFunc::ErrorMessage(trans('main.selectFile'));
            }

            Storage::put($rand,$files);
            Session::put('photo',$rand);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images,$nextID=false){
        $fileName = \ImagesHelper::UploadFile('products', $images, $nextID);
        if($fileName == false){
            return false;
        }
        return $fileName;        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();
        $menuObj = Product::find($id);
        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage(trans('main.notFound'));
        }

        \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id.'/'.$menuObj->image);
        $menuObj->images = null;
        $menuObj->save();
        return \TraitsFunc::SuccessResponse(trans('main.imgDeleted'));
    }
}