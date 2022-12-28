<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DataTables;
use Storage;
use App\Models\WACollection;

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
      
        $data['dis'] = true;
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

}
