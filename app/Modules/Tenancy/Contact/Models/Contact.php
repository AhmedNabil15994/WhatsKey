<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Contact extends Model{

    use \TraitsFunc;

    protected $table = 'contacts';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'id',
        'has_whatsapp',
        'email',
        'city',
        'country',
        'phone',
        'group_id',
        'lang',
        'notes',
        'sort',
        'status',
        'created_at',
        'created_by',
    ];
    public function Group(){
        return $this->belongsTo('App\Models\GroupNumber','group_id');
    }

    public function NotDeletedGroup(){
        return $this->belongsTo(GroupNumber::class,'group_id','id')->where('deleted_by',  null);
    }


    public function Reports(){
        return $this->hasMany('App\Models\ContactReport','contact','phone');
    }

    public function LastReport(){
        return $this->hasOne(ContactReport::class,'contact','phone')->ofMany([
            'created_at' => 'max',
        ], function ($query) {
            $query->where('created_at', '!=', null);
        });
    }

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function newPhone($phone,$name){
        $phone = str_replace('@c.us', '', $phone);
        $contactObj = self::where('phone',$phone)->first();
        if($contactObj == null){
            $contactObj = new self;
            $contactObj->name = $name;
            $contactObj->phone = str_replace('+', '', $phone);
            $contactObj->group_id = 1;
            $contactObj->sort = self::newSortIndex();
        }
        $contactObj->has_whatsapp = 1;
        $contactObj->status = 1;
        $contactObj->created_at = date('Y-m-d H:i:s');
        $contactObj->save();
    }

    static function reformChatId($chatId){
        $chatId = str_replace('@c.us','',$chatId);
        $chatId = str_replace('@g.us','',$chatId);
        return $chatId;
    }

    static function getOneByPhone($phone){
        $contactObj = self::NotDeleted()->where('phone','+'.$phone)->orderBy('id','DESC')->first();
        if($contactObj != null){
            return self::getData($contactObj,null,null,true);
        }
    }

    static function lastContacts(){
        $contactObj = self::NotDeleted()->whereHas('NotDeletedGroup')->with('Group')->orderBy('id','DESC')->take(20)->inRandomOrder();
        if($contactObj != null){
            return self::generateObj($contactObj);
        }
    }

    static function dataList($status=null,$id=null,$group_id=null,$withMessageStatus=null) {
        $input = \Request::all();

        $source = self::NotDeleted()->whereHas('NotDeletedGroup')->with(['Group'])->where('phone','NOT LIKE','%@g.us')->where(function ($query) use ($input) {
            if (isset($input['id']) && !empty($input['id'])) {
                $query->where('id', $input['id']);
            } 
            if (isset($input['name']) && !empty($input['name'])) {
                $query->where('name', 'LIKE', '%' . $input['name'] . '%');
            } 
            if (isset($input['email']) && !empty($input['email'])) {
                $query->where('email', 'LIKE', '%' . $input['email'] . '%');
            } 
            if (isset($input['city']) && !empty($input['city'])) {
                $query->where('city', 'LIKE', '%' . $input['city'] . '%');
            } 
            if (isset($input['country']) && !empty($input['country'])) {
                $query->where('country', 'LIKE', '%' . $input['country'] . '%');
            } 
            if (isset($input['group_id']) && !empty($input['group_id'])) {
                $query->where('group_id', $input['group_id']);
            } 
            if (isset($input['whats']) && !empty($input['whats'])) {
                $query->where('phone', 'LIKE', '%' . $input['whats'] . '%');
            } 
            if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
                $query->where('created_at','>=', $input['from'].' 00:00:00')->where('created_at','<=',$input['to']. ' 23:59:59');
            }
        });
        if($status != null){
            $source->where('status',$status);
        }
        if($id != null){
            $source->whereNotIn('id',$id);
        }
        if($group_id != null){
            $source->where('group_id',$group_id);
        }
        $source->orderBy('sort','ASC');
        return self::generateObj($source,$withMessageStatus);
    }

    static function getFullContactsInfo($group_id,$group_message_id){
        $source = self::NotDeleted()->with(['Group','LastReport'])->where('group_id',$group_id);       
        $source->orderBy('sort','ASC');
        return self::generateReportObj($source,$group_message_id);
    }

    static function generateReportObj($source,$group_message_id){
        $sourceArr = $source->get();
        $groupMsgObj = GroupMsg::getOne($group_message_id);
        $groupMsgObj = $groupMsgObj ? GroupMsg::getData($groupMsgObj) : null;
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getReportData($value,$groupMsgObj);
        }
        $data['data'] = $list;
        // $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        return $data;
    }

    static function getContactsReports(){
        $input = \Request::all();
        $source = self::NotDeleted()->whereHas('NotDeletedGroup')->with('Group');
        if(isset($input['group_id']) && !empty($input['group_id'])){
            $source->where('group_id',$input['group_id']);
        }

        if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
            $source->where('created_at','>=', $input['from'].' 00:00:00')->where('created_at','<=',$input['to']. ' 23:59:59');
        }
        $source = $source->select('*',\DB::raw('count(*) as total'),\DB::raw('sum(has_whatsapp) as found'))->groupBy(\DB::raw('Date(created_at)'),'group_id')->orderBy('created_at','DESC')->get();

        $list = [];
        $i = 1;
        $hasWhatsapp = 0;
        $hasNoWhatsapp = 0;
        foreach ($source as $key => $contact) {
            $hasWhatsapp = (int) $contact->found; 
            $hasNoWhatsapp = (int) $contact->total - $contact->found; 
            $totals = Variable::getVar('check_'.$contact->group_id.'_'.$contact->created_at);
            
            $list[$key] = new \stdClass();
            $list[$key]->id = $i;
            $list[$key]->group_id = $contact->group_id;
            $list[$key]->group_name = $contact->Group->{'name_'.LANGUAGE_PREF};
            $status = trans('main.done');
            if($totals != null){
                if($hasWhatsapp + $hasNoWhatsapp >= $totals){
                    $status = trans('main.done');
                }else{
                    $status = trans('main.inPrgo');
                }
            }
            $list[$key]->status = $status; 
            $list[$key]->total = $contact->total;
            $list[$key]->hasWhatsapp = $hasWhatsapp == null ? 0 : $hasWhatsapp;
            $list[$key]->hasNoWhatsapp = $hasNoWhatsapp == null ? 0 : $hasNoWhatsapp;
            $list[$key]->contacts = $contact->total;
            $list[$key]->created_at = $contact->created_at != null ? $contact->created_at : '';
            $i++;
        }
        return $list;
    }

    static function generateObj($source,$withMessageStatus=null,$group_message_id=null){
        $sourceArr = $source->get();
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value,$withMessageStatus,$group_message_id);
        }
        $data['data'] = $list;
        return $data;
    }

    static function getData($source,$withMessageStatus=null,$group_message_id=null,$dets=false) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->group_id = $source->group_id;
        $data->group = $source->Group != null ? $source->Group->{'name_'.(\Session::has('group_id') ? LANGUAGE_PREF : 'ar')} : '';
        $data->phone = $source->phone;
        $data->phone2 = str_replace('+', '', str_replace('@c.us','',$source->phone));
        $data->name = $source->name != null ? self::reformChatId($source->name) : self::reformChatId($data->phone2);
        $data->lang = $source->lang;
        $data->langText = $source->lang == 0 ? trans('main.arabic') : trans('main.english');
        $data->notes = $source->notes;
        $data->has_whatsapp = $source->has_whatsapp;
        $data->has_whatsapp_text = $source->has_whatsapp == 1 ? trans('main.yes') : trans('main.no');
        $data->email = $source->email != null ? $source->email : '';
        $data->city = $source->city != null ? $source->city : '';
        $data->country = $source->country != null ? $source->country : '';
        $data->status = $source->status;
        $data->sort = $source->sort;
        $data->created_at = \Helper::formatDate($source->created_at);
        $data->created_at2 = self::reformDate(strtotime($source->created_at));
        if($withMessageStatus != null){
            $status = [];
            $groupMsgObj = GroupMsg::getOne($group_message_id);
            $groupMsgObj = $groupMsgObj ? GroupMsg::getData($groupMsgObj) : [];
            if($groupMsgObj && $groupMsgObj->sent_type == trans('main.publishSoon')){
                $status= ['dark',trans('main.publishSoon')];
                $data->reportStatus = $status;
                return $data;
            }

            $reportObj = $source->Reports()->where('group_message_id',$group_message_id)->where('group_id',$source->group_id)->orderBy('id','DESC')->first();
            if($reportObj == null){
                $status= ['info',trans('main.inPrgo')];
            }else{
                if($reportObj->status == 0){
                    $status = ['danger',trans('main.notSent')];
                }else if($reportObj->status == 1){
                    $status = ['success',trans('main.sent')];
                }else if($reportObj->status == 2){
                    $status = ['info',trans('main.received')];
                }else if($reportObj->status == 3){
                    $status = ['primary',trans('main.seen')];
                }
            }
            $data->reportStatus = $status;

        }
        return $data;
    }

    static function getReportData($source,$groupMsgObj) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->group_id = $source->group_id;
        $data->group = $source->Group != null ? $source->Group->{'name_'.(\Session::has('group_id') ? LANGUAGE_PREF : 'ar')} : '';
        $data->phone = $source->phone;
        $data->phone2 = str_replace('+', '', str_replace('@c.us','',$source->phone));
        $data->name = $source->name != null ? $source->name : $data->phone2;
        $data->lang = $source->lang;
        $data->langText = $source->lang == 0 ? trans('main.arabic') : trans('main.english');
        $data->notes = $source->notes;
        $data->has_whatsapp = $source->has_whatsapp;
        $data->email = $source->email != null ? $source->email : '';
        $data->city = $source->city != null ? $source->city : '';
        $data->country = $source->country != null ? $source->country : '';
        $data->sort = $source->sort;
        $data->created_at = \Helper::formatDate($source->created_at);
        $data->created_at2 = self::reformDate(strtotime($source->created_at));

        if($groupMsgObj != null){
            if($groupMsgObj && $groupMsgObj->sent_type == trans('main.publishSoon')){
                $data->color = 'dark';
                $data->status = trans('main.publishSoon');
                $data->date = '';
                return $data;
            }

            $reportObj = $source->LastReport;

            if($reportObj == null){
                $data->color = 'info';
                $data->status = trans('main.inPrgo');
                $data->date = date('Y-m-d H:i:s');
            }else{
                if($reportObj->status == 0){
                    $data->color = 'danger';
                    $data->status = trans('main.notSent');
                }else if($reportObj->status == 1){
                    $data->color = 'success';
                    $data->status = trans('main.sent');
                }else if($reportObj->status == 2){
                    $data->color = 'info';
                    $data->status = trans('main.received');
                }else if($reportObj->status == 3){
                    $data->color = 'primary';
                    $data->status = trans('main.seen');
                }
                $data->date = $reportObj->created_at;
            }
        }
        return $data;
    }


    static function newSortIndex(){
        return self::count() + 1;
    }

    static function reformDate($time){
        $diff = (time() - $time ) / (3600 * 24);
        $date = \Carbon\Carbon::parse(date('Y-m-d H:i:s'));
        if(round($diff) == 0){
            return [date('Y-m-d',$time),date('h:i A',$time)];
        }else if($diff>0 && $diff<=1){
            return [trans('main.yesterday'), date('h:i A',$time)];
        }else if($diff > 1 && $diff < 7){
            return [$date->locale((\Session::has('group_id') ? LANGUAGE_PREF : 'ar'))->dayName,date('h:i A',$time)];
        }else{
            return [date('Y-m-d',$time),date('h:i A',$time)];
        }
    }
}
