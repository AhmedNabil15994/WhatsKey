<?php namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DataTables;
use Storage;


class SectionControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.sections'),
            'url' => 'sections',
            'name' => 'sections',
            'nameOne' => 'section',
            'modelName' => 'Section',
            'icon' => 'dripicons-blog',
            'sortName' => 'title_'.LANGUAGE_PREF,
        ];
        $categories = Page::dataList(1)['data'];
        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'title_ar' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '1',
                'label' => trans('main.name_ar'),
            ],
            'title_en' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '2',
                'label' => trans('main.name_en'),
            ],
            'page_id' => [
                'type' => 'select',
                'class' => 'form-control',
                'index' => '3',
                'options' => $categories,
                'label' => trans('main.page'),
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
            'title_ar' => [
                'label' => trans('main.name_ar'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'title_ar',
                'anchor-class' => 'editable',
            ],
            'title_en' => [
                'label' => trans('main.name_en'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'title_en',
                'anchor-class' => 'editable',
            ],
            'page' => [
                'label' => trans('main.page'),
                'type' => '',
                'className' => 'edits selects',
                'data-col' => 'page_id',
                'anchor-class' => 'editable',
            ],
            'created_at' => [
                'label' => trans('main.date'),
                'type' => '',
                'className' => 'edits dates',
                'data-col' => 'created_at',
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
            'title_ar' => 'required',
            'title_en' => 'required',
            'page_id' => 'required',
        ];

        $message = [
            'title_ar.required' => trans('main.titleArValidate'),
            'title_en.required' => trans('main.titleEnValidate'),
            'page_id.required' => trans('main.pageValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Section::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.User.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Section::NotDeleted()->find($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Section::getData($userObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.sections') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['pages'] = Page::dataList(1)['data'];
        return view('Central.Section.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        // dd($input);
        $dataObj = Section::NotDeleted()->find($id);
        if($dataObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }


        $dataObj->title_ar = $input['title_ar'];
        $dataObj->title_en = $input['title_en'];
        $dataObj->description_ar = $input['description_ar'];
        $dataObj->description_en = $input['description_en'];
        $dataObj->page_id = $input['page_id'];
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
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.sections') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['pages'] = Page::dataList(1)['data'];
        return view('Central.Section.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        
        $dataObj = new Section;
        $dataObj->title_ar = $input['title_ar'];
        $dataObj->title_en = $input['title_en'];
        $dataObj->description_ar = $input['description_ar'];
        $dataObj->description_en = $input['description_en'];
        $dataObj->page_id = $input['page_id'];
        $dataObj->sort = Section::newSortIndex();
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
        $dataObj = Section::getOne($id);
        \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = Section::find($item[0]);
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
        $fileName = \ImagesHelper::UploadFile($this->getData()['mainData']['name'], $images, $nextID);
        if($fileName == false){
            return false;
        }
        return $fileName;        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = Section::find($id);
        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage(trans('main.userNotFound'));
        }

        \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id.'/'.$menuObj->image);
        $menuObj->image = '';
        $menuObj->save();
        return \TraitsFunc::SuccessResponse(trans('main.imgDeleted'));
    }

}
