<?php namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\CentralDepartment;
use App\Models\CentralUser;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DataTables;
use Storage;


class TicketControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.tickets'),
            'url' => 'tickets',
            'name' => 'tickets',
            'nameOne' => 'ticket',
            'modelName' => 'Ticket',
            'icon' => ' dripicons-ticket',
            'sortName' => 'title',
            'addOne' => trans('main.newTicket'),
        ];
        $departments = CentralDepartment::dataList(1)['data'];
        $statuses = [
            ['id' => '1', 'title' => trans('main.open')],
            ['id' => '2', 'title' => trans('main.answered')],
            ['id' => '3', 'title' => trans('main.customerReply')],
            ['id' => '4', 'title' => trans('main.onHold')],
            ['id' => '5', 'title' => trans('main.inProgress')],
            ['id' => '6', 'title' => trans('main.closed')],
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'subject' => [
                'type' => 'text',
                'class' => 'form-control datatable-input',
                'index' => '2',
                'label' => trans('main.subject'),
            ],
            'department_id' => [
                'type' => 'select',
                'class' => 'form-control datatable-input select2',
                'options' => $departments,
                'label' => trans('main.department'),
            ],
            'status' => [
                'type' => 'select',
                'class' => 'form-control datatable-input select2',
                'options' => $statuses,
                'label' => trans('main.status'),
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
            'department' => [
                'label' => trans('main.department'),
                'type' => '',
                'className' => '',
                'data-col' => 'department_id',
                'anchor-class' => '',
            ],
            'subject' => [
                'label' => trans('main.subject'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'subject',
                'anchor-class' => 'editable',
            ],
            'client' => [
                'label' => trans('main.client'),
                'type' => '',
                'className' => '',
                'data-col' => 'user_id',
                'anchor-class' => '',
            ],
            'created_at' => [
                'label' => trans('main.date'),
                'type' => '',
                'className' => '',
                'data-col' => 'created_at',
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

        return $data;
    }

    protected function validateInsertObject($input){
        $rules = [
            'subject' => 'required',
            'department_id' => 'required',
            'description' => 'required',
        ];

        $message = [
            'subject.required' => trans('main.subjectValidate'),
            'department_id.required' => trans('main.departmentValidate'),
            'description.required' => trans('main.descriptionValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Ticket::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Tenancy.Template.Views.index')->with('data', (object) $data);
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.tickets') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['clients'] = CentralUser::NotDeleted()->where('status',1)->where('global_id',GLOBAL_ID)->where('group_id',0)->get();
        $data['departments'] = CentralDepartment::dataList(1)['data'];
        Session::forget('photos');
        return view('Tenancy.Ticket.Views.add')->with('data', (object) $data);
    }

    public function create(Request $request) {
        $input = \Request::all();

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $dataObj = new Ticket;
        $dataObj->subject = $input['subject'];
        $dataObj->user_id = USER_ID;
        $dataObj->global_id = User::find(ROOT_ID)->global_id;
        $dataObj->priority_id = isset($input['priority_id']) && !empty($input['priority_id']) ? $input['priority_id'] : 1;
        $dataObj->department_id = $input['department_id'];
        $dataObj->description = $input['description'];
        $dataObj->sort = Ticket::newSortIndex();
        $dataObj->status = 1;
        $dataObj->created_at = DATE_TIME;
        $dataObj->created_by = USER_ID;
        $dataObj->save();

        $photos_name = Session::get('photos');
        if($photos_name && count($photos_name) > 0){
            foreach ($photos_name as $photo_name) {
                $photo = Storage::files($photo_name);
                $photo = $photo[0];
                $images = self::addImage($photo,$dataObj->id);
                if ($images == false) {
                    Session::flash('error', trans('main.uploadProb'));
                    return redirect()->back()->withInput();
                }
                $imagesArr[] = $images;
            }
            $dataObj->files = serialize($imagesArr);
            $dataObj->save();  
        }else{
            if($request->hasFile('files')){
                $images =  \ImagesHelper::uploadFileFromRequest($this->getData()['mainData']['name'], $request->file('files'), $dataObj->id);
                if ($images == false) {
                    Session::flash('error', trans('main.uploadProb'));
                    return redirect()->back()->withInput();
                }
                $imagesArr[] = $images;
                $dataObj->files = serialize($imagesArr);
                $dataObj->save();  
            }
        }

        Session::forget('photos');
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = Ticket::getOne($id);
        \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id);
        return \Helper::globalDelete($dataObj);
    }

    public function view($id) {
        $id = (int) $id;
        Session::forget('attachs');
        $dataObj = Ticket::NotDeleted()->find($id);
        if($dataObj == null || User::first()->global_id != $dataObj->global_id ) {
            return Redirect('404');
        }

        $data['data'] = Ticket::getData($dataObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.view') . ' '.trans('main.tickets') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-eye';
        $data['clients'] = CentralUser::NotDeleted()->where('status',1)->where('global_id',GLOBAL_ID)->where('group_id',0)->get();
        $data['assigns'] = CentralUser::NotDeleted()->where('status',1)->whereNotIn('group_id',[0,1])->get();
        $data['userObj'] = User::getData(\App\Models\User::getOne(USER_ID));
        $data['comments'] = Comment::dataList($id);
        $data['commentsCount'] = Comment::NotDeleted()->where('status',1)->where('ticket_id',$id)->count();
        return view('Tenancy.Ticket.Views.view')->with('data', (object) $data);      
    }

    public function uploadImage(Request $request,$id=false){
        $rand = rand() . date("YmdhisA");
        $imageArr = Session::has('photos') ? Session::get('photos') : [];
        $attachArr = Session::has('attachs') ? Session::get('attachs') : '';
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            Storage::put($rand,$files);
            $imageArr[] = $rand;
            Session::put('photos',$imageArr);
            return \TraitsFunc::SuccessResponse('sc');
        }elseif ($request->hasFile('attachs')) {
            $files = $request->file('attachs');
            Storage::put($rand,$files);
            $attachArr = $rand;
            Session::put('attachs',$attachArr);
            return \TraitsFunc::SuccessResponse('sc');
        }
    }

    public function addImage($images,$nextID=false,$modelType=null){
        $type = \ImagesHelper::checkFileExtension($images);
        if($modelType == 'attachs'){
            $fileName = \ImagesHelper::UploadFile('comments', $images, $nextID,$type);
        }else{
            $fileName = \ImagesHelper::UploadFile($this->getData()['mainData']['name'], $images, $nextID,$type);
        }
        if($fileName == false){
            return false;
        }
        return $fileName;        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = Ticket::find($id);
        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage(trans('main.userNotFound'));
        }


        $imagesArr = unserialize($menuObj->files);
        if (($key = array_search($input['name'], $imagesArr)) !== false) {
            unset($imagesArr[$key]);
            \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id.'/'.$input['name']);
        }
        $menuObj->files = serialize($imagesArr);
        $menuObj->save();

        return \TraitsFunc::SuccessResponse(trans('main.imgDeleted'));
    }

    public function addComment($id){
        $input = \Request::all();
        $rules = [
            'comment' => 'required',
        ];

        $message = [
            'comment.required' => trans('main.commentValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);
        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }   

        $videoObj = Ticket::getOne($id);
        if($videoObj == null){
            return \TraitsFunc::ErrorMessage(trans('main.ticketNotFound'), 400);
        }

        $commentObj = new Comment;
        $commentObj->comment = $input['comment'];
        $commentObj->name = FULL_NAME;
        $commentObj->reply_on = isset($input['reply']) && !empty($input['reply']) ? $input['reply'] : 0;
        $commentObj->ticket_id = $id;
        $commentObj->status = 1;
        $commentObj->admin = ROOT_ID == USER_ID ? 1 : 0;
        $commentObj->created_by = USER_ID;
        $commentObj->created_at = date('Y-m-d H:i:s');
        $commentObj->save();

        $photo_name = Session::get('attachs');
        if($photo_name && $photo_name != ''){
            $photo = Storage::files($photo_name);
            $photo = $photo[0];

            $images = self::addImage($photo,$commentObj->id,'attachs');
            if ($images == false) {
                Session::flash('error', trans('main.uploadProb'));
                return redirect()->back()->withInput();
            }
            $commentObj->file_name = $images;
            $commentObj->save();  
        }

        return \TraitsFunc::SuccessResponse(trans('main.commentSaved'));
    }

    public function removeComment($id,$comment_id){
        $commentObj = Comment::getOne($comment_id);
        Comment::where('reply_on',$comment_id)->update(['deleted_by'=> USER_ID,'deleted_at' => DATE_TIME]);
        if($commentObj == null){
            return \TraitsFunc::ErrorMessage(trans('main.commentNotFound'), 400);
        }
        return \Helper::globalDelete($commentObj);
    }
}
