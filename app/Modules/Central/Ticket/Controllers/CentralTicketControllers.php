<?php namespace App\Http\Controllers;

use App\Models\CentralTicket;
use App\Models\CentralDepartment;
use App\Models\CentralUser;
use App\Models\CentralComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DataTables;
use Storage;


class CentralTicketControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.tickets'),
            'url' => 'tickets',
            'name' => 'tickets',
            'nameOne' => 'ticket',
            'modelName' => 'CentralTicket',
            'icon' => ' dripicons-ticket',
            'sortName' => 'title',
        ];
        $clients = CentralUser::NotDeleted()->where('status',1)->where('group_id',0)->get();
        $departments = CentralDepartment::dataList(1)['data'];
        $priorities = [
            ['id' => '1', 'title' => trans('main.low')],
            ['id' => '2', 'title' => trans('main.medium')],
            ['id' => '3', 'title' => trans('main.high')],
        ];
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
                'class' => 'form-control m-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'subject' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '2',
                'label' => trans('main.subject'),
            ],
            'user_id' => [
                'type' => 'select',
                'class' => 'form-control m-input select2',
                'options' => reset($clients),
                'label' => trans('main.client'),
            ],
            'department_id' => [
                'type' => 'select',
                'class' => 'form-control m-input select2',
                'options' => $departments,
                'label' => trans('main.department'),
            ],
            'priority_id' => [
                'type' => 'select',
                'class' => 'form-control m-input select2',
                'options' => $priorities,
                'label' => trans('main.priority'),
            ],
            'status' => [
                'type' => 'select',
                'class' => 'form-control m-input select2',
                'options' => $statuses,
                'label' => trans('main.status'),
            ],
            'from' => [
                'type' => 'text',
                'class' => 'form-control m-input datepicker',
                'index' => '',
                'id' => 'datepicker1',
                'label' => trans('main.dateFrom'),
            ],
            'to' => [
                'type' => 'text',
                'class' => 'form-control m-input datepicker',
                'index' => '',
                'id' => 'datepicker2',
                'label' => trans('main.dateTo'),
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
                'className' => 'edits selects',
                'data-col' => 'department_id',
                'anchor-class' => 'editable',
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
                'className' => 'edits selects',
                'data-col' => 'user_id',
                'anchor-class' => 'editable',
            ],
            'priority' => [
                'label' => trans('main.priority'),
                'type' => '',
                'className' => 'edits selects',
                'data-col' => 'priority_id',
                'anchor-class' => 'editable',
            ],
            'created_at' => [
                'label' => trans('main.date'),
                'type' => '',
                'className' => '',
                'data-col' => 'created_at',
                'anchor-class' => '',
            ],
            'last_comment' => [
                'label' => trans('main.comment_owner'),
                'type' => '',
                'className' => '',
                'data-col' => 'last_comment',
                'anchor-class' => '',
            ],
            'last_comment_date' => [
                'label' => trans('main.last_comment_date'),
                'type' => '',
                'className' => '',
                'data-col' => 'last_comment_date',
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
            'user_id' => 'required',
            'department_id' => 'required',
            'description' => 'required',
        ];

        $message = [
            'subject.required' => trans('main.subjectValidate'),
            'user_id.required' => trans('main.clientValidate'),
            'department_id.required' => trans('main.departmentValidate'),
            'description.required' => trans('main.descriptionValidate'),
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = CentralTicket::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.User.Views.index')->with('data', (object) $data);
    }

    public function view($id) {
        $id = (int) $id;

        $dataObj = CentralTicket::NotDeleted()->find($id);
        $assigns = !empty($dataObj->assignment) ? unserialize($dataObj->assignment) : [];
        $emps = !empty($dataObj->Department->emps) ? unserialize($dataObj->Department->emps) : [];
        $assigns = array_unique(array_merge($assigns,$emps));
        if($dataObj == null || (!IS_ADMIN && !in_array(USER_ID, $assigns)) ) {
            return Redirect('404');
        }

        $data['data'] = CentralTicket::getData($dataObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.view') . ' '.trans('main.tickets') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['clients'] = CentralUser::NotDeleted()->where('status',1)->where('group_id',0)->get();
        $data['assigns'] = CentralUser::NotDeleted()->where('status',1)->whereNotIn('group_id',[0,1])->get();
        $data['comments'] = CentralComment::dataList($id);
        $data['commentsCount'] = CentralComment::NotDeleted()->where('status',1)->where('ticket_id',$id)->count();
        return view('Central.Ticket.Views.view')->with('data', (object) $data);      
    }

    public function edit($id) {
        $id = (int) $id;

        $dataObj = CentralTicket::NotDeleted()->find($id);
        $assigns = !empty($dataObj->assignment) ? unserialize($dataObj->assignment) : [];
        $emps = !empty($dataObj->Department->emps) ? unserialize($dataObj->Department->emps) : [];
        $assigns = array_unique(array_merge($assigns,$emps));
        if($dataObj == null || (!IS_ADMIN && !in_array(USER_ID, $assigns)) ) {
            return Redirect('404');
        }

        $data['data'] = CentralTicket::getData($dataObj);
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.edit') . ' '.trans('main.tickets') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-pencil-alt';
        $data['clients'] = CentralUser::NotDeleted()->where('status',1)->where('group_id',0)->get();
        $data['assigns'] = CentralUser::NotDeleted()->where('status',1)->whereNotIn('group_id',[0,1])->get();
        $data['departments'] = CentralDepartment::dataList(1)['data'];
        $data['userObj'] = CentralUser::getData(\App\Models\CentralUser::getOne(USER_ID));
        return view('Central.Ticket.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();
        // dd($input);
        $dataObj = CentralTicket::NotDeleted()->find($id);
        $assigns = !empty($dataObj->assignment) ? unserialize($dataObj->assignment) : [];
        $emps = !empty($dataObj->Department->emps) ? unserialize($dataObj->Department->emps) : [];
        $assigns = array_unique(array_merge($assigns,$emps));
        if($dataObj == null || (!IS_ADMIN && !in_array(USER_ID, $assigns)) ) {
            return Redirect('404');
        }

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }


        $dataObj->subject = $input['subject'];
        $dataObj->user_id = $input['user_id'];
        $dataObj->global_id = CentralUser::find($input['user_id'])->global_id;
        $dataObj->priority_id = $input['priority_id'];
        $dataObj->department_id = $input['department_id'];
        $dataObj->description = $input['description'];
        if(isset($input['assignment']) && !empty($input['assignment'])){
            $dataObj->assignment = serialize($input['assignment']);
        }
        $dataObj->status = $input['status'];
        $dataObj->updated_at = DATE_TIME;
        $dataObj->updated_by = USER_ID;
        $dataObj->save();

        $imagesArr = unserialize($dataObj->files);
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
        }

        Session::forget('photos');
        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['designElems'] = $this->getData();
        $data['designElems']['mainData']['title'] = trans('main.add') . ' '.trans('main.tickets') ;
        $data['designElems']['mainData']['icon'] = 'fa fa-plus';
        $data['clients'] = CentralUser::NotDeleted()->where('status',1)->where('group_id',0)->get();
        $data['departments'] = CentralDepartment::dataList(1)['data'];
        Session::forget('photos');
        return view('Central.Ticket.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();

        $validate = $this->validateInsertObject($input);
        if($validate->fails()){
            Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        
        $dataObj = new CentralTicket;
        $dataObj->subject = $input['subject'];
        $dataObj->user_id = $input['user_id'];
        $dataObj->global_id = CentralUser::find($input['user_id'])->global_id;
        $dataObj->priority_id = isset($input['priority_id']) && !empty($input['priority_id']) ? $input['priority_id'] : 1;
        $dataObj->department_id = $input['department_id'];
        $dataObj->description = $input['description'];
        $dataObj->sort = CentralTicket::newSortIndex();
        $dataObj->status = $input['status'];
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
        }

        Session::forget('photos');
        Session::flash('success', trans('main.addSuccess'));
        return redirect()->to($this->getData()['mainData']['url'].'/');
    }

    public function delete($id) {
        $id = (int) $id;
        $dataObj = CentralTicket::getOne($id);
        \ImagesHelper::deleteDirectory(public_path('/').'/uploads/'.$this->getData()['mainData']['name'].'/'.$id);
        return \Helper::globalDelete($dataObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $dataObj = CentralTicket::find($item[0]);
            $dataObj->$col = $item[2];
            $dataObj->updated_at = DATE_TIME;
            $dataObj->updated_by = USER_ID;
            $dataObj->save();
        }

        return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
    }
    
    public function uploadImage(Request $request,$id=false){
        $rand = rand() . date("YmdhisA");
        $imageArr = Session::has('photos') ? Session::get('photos') : [];
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            Storage::put($rand,$files);
            $imageArr[] = $rand;
            Session::put('photos',$imageArr);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images,$nextID=false){
        $type = \ImagesHelper::checkFileExtension($images);
        $fileName = \ImagesHelper::UploadFile($this->getData()['mainData']['name'], $images, $nextID,$type);
        if($fileName == false){
            return false;
        }
        return $fileName;        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = CentralTicket::find($id);
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

        $videoObj = CentralTicket::getOne($id);
        if($videoObj == null){
            return \TraitsFunc::ErrorMessage(trans('main.ticketNotFound'), 400);
        }

        if($input['reply'] != 0){
            $commentObj = CentralComment::getOne($input['reply']);
            if($commentObj == null){
                return \TraitsFunc::ErrorMessage(trans('main.commentNotFound'), 400);
            }
            $input['reply'] = $commentObj->reply_on != 0 ? $commentObj->reply_on : $input['reply'];
            if($commentObj->reply_on == 0 ){
                if($commentObj->created_by == USER_ID){
                    return \TraitsFunc::ErrorMessage(trans('main.cantReply'), 400);
                }
            }
            $replier = CentralUser::getData(CentralUser::getOne(USER_ID));
            // $msg = $replier->name.' replied on your comment';
            // $tokens = Devices::getDevicesBy($commentObj->created_by,true);
            // $fireBase = new \FireBase();
            // $metaData = ['title' => "New Comment", 'body' => $msg,];
            // $myData = ['type' => 3 , 'id' => $commentObj->video_id];
            // $fireBase->send_android_notification($tokens[0],$metaData,$myData);
        }

        $commentObj = new CentralComment;
        $commentObj->comment = $input['comment'];
        $commentObj->name = FULL_NAME;
        $commentObj->reply_on = $input['reply'];
        $commentObj->ticket_id = $id;
        $commentObj->status = 1;
        $commentObj->created_by = USER_ID;
        $commentObj->created_at = date('Y-m-d H:i:s');
        $commentObj->save();

        if($videoObj->user_id == USER_ID){
            $videoObj->status = 3;
        }else{
            $videoObj->status = 2;
        }
        $videoObj->save();

        return \TraitsFunc::SuccessResponse(trans('main.commentSaved'));
    }

    public function updateComment($comment_id){
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

        $videoObj = CentralComment::getOne($comment_id);
        if($videoObj == null){
            return \TraitsFunc::ErrorMessage(trans('main.commentNotFound'), 400);
        }

        $videoObj->comment = $input['comment'];
        $videoObj->updated_by = USER_ID;
        $videoObj->updated_at = date('Y-m-d H:i:s');
        $videoObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse(trans('main.commentUpdated'));
        return $statusObj;
    }

    public function removeComment($id,$comment_id){
        $commentObj = CentralComment::getOne($comment_id);
        CentralComment::where('reply_on',$comment_id)->update(['deleted_by'=> USER_ID,'deleted_at' => DATE_TIME]);
        if($commentObj == null){
            return \TraitsFunc::ErrorMessage(trans('main.commentNotFound'), 400);
        }
        return \Helper::globalDelete($commentObj);
    }

}
