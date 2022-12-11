<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use DataTables;


class ContactUsControllers extends Controller {

    use \TraitsFunc;

    public function getData(){
        $data['mainData'] = [
            'title' => trans('main.contactUs'),
            'url' => 'contactUs',
            'name' => 'contactUs',
            'nameOne' => 'contactUs',
            'modelName' => 'ContactUs',
            'icon' => 'dripicons-blog',
            'sortName' => 'name',
        ];

        $data['searchData'] = [
            'id' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '0',
                'label' => trans('main.id'),
            ],
            'name' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '1',
                'label' => trans('main.name'),
            ],
            'email' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '2',
                'label' => trans('main.email'),
            ],
            'phone' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '3',
                'label' => trans('main.phone'),
            ],
            'message' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '4',
                'label' => trans('main.message'),
            ],
            'reply' => [
                'type' => 'text',
                'class' => 'form-control m-input',
                'index' => '5',
                'label' => trans('main.reply'),
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
            'name' => [
                'label' => trans('main.name'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'name',
                'anchor-class' => 'editable',
            ],
            'email' => [
                'label' => trans('main.email'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'email',
                'anchor-class' => 'editable',
            ],
            'phone' => [
                'label' => trans('main.phone'),
                'type' => '',
                'className' => 'edits',
                'data-col' => 'phone',
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

    protected function validateObject($input){
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',//|regex:/(01)[0-9]{9}/',
            'message' => 'required',
        ];

        $message = [
            'name.required' => "يرجي ادخال الاسم بالكامل",
            'email.required' => "يرجي ادخال البريد الالكتروني",
            'email.email' => "يرجي ادخال بريد الكتروني متاح",
            'message.required' => "يرجي ادخال مضمون الرسالة",
            // 'phone.regex' => "يرجي ادخال رقم التليفون",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request)
    {   
        if($request->ajax()){
            $data = ContactUs::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['designElems'] = $this->getData();
        return view('Central.User.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $menuObj = ContactUs::NotDeleted()->find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $data['data'] = ContactUs::getData($menuObj);
        $data['designElems'] = $this->getData();
        return view('Central.ContactUs.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = ContactUs::NotDeleted()->find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateObject($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $menuObj->name = $input['name'];
        $menuObj->email = $input['email'];
        $menuObj->phone = $input['phone'];
        $menuObj->address = $input['address'];
        $menuObj->message = $input['message'];
        $menuObj->status = $input['status'];
        $menuObj->updated_at = DATE_TIME;
        $menuObj->updated_by = USER_ID;
        $menuObj->save();

        \Session::flash('success', "تنبيه! تم التعديل بنجاح");
        return \Redirect::back()->withInput();
    }

    public function delete($id) {
        $id = (int) $id;
        $menuObj = ContactUs::getOne($id);
        return \Helper::globalDelete($menuObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $menuObj = ContactUs::NotDeleted()->find($item[0]);
            $menuObj->$col = $item[2];
            $menuObj->updated_at = DATE_TIME;
            $menuObj->updated_by = USER_ID;
            $menuObj->save();
        }

        return \TraitsFunc::SuccessResponse('تم التعديل بنجاح');
    }

    public function reply($id){
        $id = (int) $id;

        $menuObj = ContactUs::NotDeleted()->find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $data['data'] = ContactUs::getData($menuObj);
        $data['designElems'] = $this->getData();
        return view('Central.ContactUs.Views.reply')->with('data', (object) $data);
    }

    public function postReply($id){
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = ContactUs::NotDeleted()->find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $menuObj->reply = $input['reply'];
        $menuObj->status = $input['status'];
        $menuObj->updated_at = DATE_TIME;
        $menuObj->updated_by = USER_ID;
        $menuObj->save();

        \MailHelper::prepareContactEmail([
            'to' => $menuObj->email,
            'subject' => 'Reply On Contact Us',
            'content' => $menuObj->reply,
            'firstName' => $menuObj->name,
        ]);        

        \Session::flash('success', "تنبيه! تم الرد بنجاح");
        return \Redirect::back()->withInput();
    }

    public function charts() {
        $input = \Request::all();
        $now = date('Y-m-d');
        $start = $now;
        $end = $now;
        $date = null;
        if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
            $start = $input['from'].' 00:00:00';
            $end = $input['to'].' 23:59:59';
            $date = 1;
        }

        $addCount = WebActions::getByDate($date,$start,$end,1,'ContactUs')['count'];
        $editCount = WebActions::getByDate($date,$start,$end,2,'ContactUs')['count'];
        $deleteCount = WebActions::getByDate($date,$start,$end,3,'ContactUs')['count'];
        $fastEditCount = WebActions::getByDate($date,$start,$end,4,'ContactUs')['count'];

        $data['chartData1'] = $this->getChartData($start,$end,1,'ContactUs');
        $data['chartData2'] = $this->getChartData($start,$end,2,'ContactUs');
        $data['chartData3'] = $this->getChartData($start,$end,4,'ContactUs');
        $data['chartData4'] = $this->getChartData($start,$end,3,'ContactUs');
        $data['counts'] = [$addCount , $editCount , $deleteCount , $fastEditCount];
        $data['title'] = 'الاتصال بنا';
        $data['miniTitle'] = 'الاتصال بنا';
        $data['url'] = 'contactUs';

        return view('TopMenu.Views.charts')->with('data',(object) $data);
    }

    public function getChartData($start=null,$end=null,$type,$moduleName){
        $input = \Request::all();
        
        if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
            $start = $input['from'];
            $end = $input['to'];
        }

        $datediff = strtotime($end) - strtotime($start);
        $daysCount = round($datediff / (60 * 60 * 24));
        $datesArray = [];
        $datesArray[0] = $start;

        if($daysCount > 2){
            for($i=0;$i<$daysCount;$i++){
                $datesArray[$i] = date('Y-m-d',strtotime($start.'+'.$i."day") );
            }
            $datesArray[$daysCount] = $end;  
        }else{
            for($i=1;$i<24;$i++){
                $datesArray[$i] = date('Y-m-d H:i:s',strtotime($start.'+'.$i." hour") );
            }
        }

        $chartData = [];
        $dataCount = count($datesArray);

        for($i=0;$i<$dataCount;$i++){
            if($dataCount == 1){
                $count = WebActions::where('type',$type)->where('module_name',$moduleName)->where('created_at','>=',$datesArray[0].' 00:00:00')->where('created_at','<=',$datesArray[0].' 23:59:59')->count();
            }else{
                if($i < count($datesArray)){
                    $count = WebActions::where('type',$type)->where('module_name',$moduleName)->where('created_at','>=',$datesArray[$i].' 00:00:00')->where('created_at','<=',$datesArray[$i].' 23:59:59')->count();
                }
            }
            $chartData[0][$i] = $datesArray[$i];
            $chartData[1][$i] = $count;
        }
        return $chartData;
    }


}
