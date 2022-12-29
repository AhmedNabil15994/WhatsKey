<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DataTables;
use Storage;
use App\Models\WACollection;
use App\Models\Contact;

class WACollectionControllers extends Controller {

    use \TraitsFunc;

    public function index(Request $request) {
        if($request->ajax()){
            $data = WACollection::dataList();
            return Datatables::of($data['data'])->make(true);
        }

        $data['designElems']['mainData'] = [
            'title' => trans('main.collections'),
            'url' => 'collections',
            'name' => 'collections',
            'nameOne' => 'collection',
            'modelName' => 'collection',
            'icon' => 'ki ki-menu',
        ];

       
        $data['designElems']['searchData'] = [
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
            'products' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '2',
                'label' => trans('main.productsCount'),
            ],
            'status' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '3',
                'label' => trans('main.status'),
            ],
            'can_appeal' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '4',
                'label' => trans('main.can_appeal'),
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
            'name' => [
                'label' => trans('main.name'),
                'type' => '',
                'className' => '',
                'data-col' => 'name',
                'anchor-class' => '',
            ],
            'productsCount' => [
                'label' => trans('main.productsCount'),
                'type' => '',
                'className' => '',
                'data-col' => 'productsCount',
                'anchor-class' => '',
            ],
            'status' => [
                'label' => trans('main.status'),
                'type' => '',
                'className' => '',
                'data-col' => 'status',
                'anchor-class' => '',
            ],
            'can_appeal' => [
                'label' => trans('main.can_appeal'),
                'type' => '',
                'className' => '',
                'data-col' => 'can_appeal',
                'anchor-class' => '',
            ],
        ];
      
        $data['contacts'] = Contact::dataList(1)['data'];
        return view('Tenancy.WACollection.Views.index')->with('data', (object) $data);
    }

    public function sendCatalog(){
        $input = \Request::all();

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
        $updateResult = $mainWhatsLoopObj->sendBulkCatalog([
            'phones' => $phones,
            'interval' => 3,
        ]);
        $updateResult = $updateResult->json();
        
        $dataList['status'] = \TraitsFunc::SuccessMessage(trans('main.inPrgo'));
        return \Response::json((object) $dataList);     
    }
}
