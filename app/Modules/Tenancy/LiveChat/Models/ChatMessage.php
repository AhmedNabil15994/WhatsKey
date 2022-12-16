<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserChannels;
use App\Jobs\SyncProducts;
use Nicolaslopezj\Searchable\SearchableTrait;

class ChatMessage extends Model{
    use \TraitsFunc,SearchableTrait;

    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $fillable = ['id','body','fromMe','isForwarded','author','time','chatId','messageNumber','type','message_type','status','senderName','chatName','caption','sending_status','deleted_by','deleted_at'];    
    public $timestamps = false;
    public $incrementing = false;

    protected $searchable = [
        'columns' => [
            'body' => 255,
            'author' => 255,
            'chatId' => 255,
            'senderName' => 255,
            'chatName' => 255,
            'caption' => 255,
        ],
    ];

    public function Order(){
        return $this->belongsTo('App\Models\Order','id','message_id');
    }

    static function getOne($id){
        return self::where('id', $id)->first();
    }

    static function dataList($chatId=null,$limit=null,$disDetails=null,$start=null) {
        $input = \Request::all();
        $source = self::where('id','!=',null);
        if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
            $source->where('time','>=', strtotime($input['from'].' 00:00:00'))->where('time','<=',strtotime($input['to'].' 23:59:59'));
        }
        if(isset($input['fromMe']) && $input['fromMe'] != null){
            $source->where('fromMe',$input['fromMe']);
        }

        if(isset($input['sending_status']) && $input['sending_status'] != null){
            $source->where('sending_status',$input['sending_status']);
        }

        if(isset($input['chatId']) && !empty($input['chatId'])){
            $source->where('chatId','LIKE','%'.$input['chatId'].'%');
        }

        if(isset($input['message_type']) && !empty($input['message_type'])){
            $source->where('type','LIKE',$input['message_type']);
        }

        if(isset($input['message']) && !empty($input['message'])){
            $source->where([
                ['body','LIKE','%'.$input['message'].'%'],
                ['body','LIKE',$input['message'].'%'],
                ['body','LIKE','%'.$input['message']]
            ])->orWhere([
                ['caption','LIKE','%'.$input['message'].'%'],
                ['caption','LIKE',$input['message'].'%'],
                ['caption','LIKE','%'.$input['message']]
            ]);
        }
        if(isset($input['id']) && !empty($input['id'])){
            $source->where('id',$input['id'])->orderBy('time','DESC');
        }

        if($start!= null){
            $source->skip($start);
        }
        if($chatId != null){
            $source->where('chatId',$chatId)->orderBy('time','DESC')->orderBy('id','DESC');
        }else{
            $source->orderBy('time','DESC');
        }
        return self::generateObj($source,$limit);
    }

    static function lastMessages() {
        $source = self::NotDeleted();
        $source->orderBy('time','DESC');
        return self::generateObj($source,100);
    }

    static function generateObj($source,$limit=null){
        if($limit != null){
            $sourceArr = $source->paginate($limit);
        }else{
            $sourceArr = $source->get();
        }
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        $data['data'] = $list;
        if($limit != null){
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        }

        return $data;
    }

    static function newMessage($source){
        $source = (object) $source;
        $dataObj = self::where('id',$source->id)->first();
        if($dataObj == null){
            $dataObj = new  self;
            if(isset($source->type)){
                $dataObj->type = $source->type;
            }
        }
        
        $dataObj->id = $source->id;
        $dataObj->body = $source->body;
        $dataObj->fromMe = ((isset($source->fromMe)) && ($source->fromMe == 1|| $source->fromMe == 'true')) ? 1:0;
        $dataObj->isForwarded = isset($source->isForwarded) ? $source->isForwarded : 0;
        $dataObj->author = isset($source->author) ? $source->author : '';
        $dataObj->time = isset($source->time) ? $source->time  : $dataObj->time;
        $dataObj->chatId = isset($source->chatId) ? $source->chatId : '';
        $dataObj->senderName = isset($source->senderName) ? $source->senderName : '' ;
        $dataObj->caption = isset($source->caption) ? $source->caption : '' ;
        $dataObj->chatName = isset($source->chatName) ? $source->chatName : '' ;
        $dataObj->type = isset($source->type) ? $source->type : '' ;
        if(isset($source->status) && !$dataObj->status){
            $dataObj->status = $source->status;
        }
        
        if(isset($source->sending_status)){
            $dataObj->sending_status = $source->sending_status ;
        }
        if( isset($source->metadata)){
            $dataObj->metadata = !empty($dataObj->metadata) ? json_encode(array_merge((array)json_decode($dataObj->metadata),(array)$source->metadata)) : json_encode($source->metadata) ;
        }
        if( isset($source->module_id) && $source->module_id != '' && $source->module_id != null){
            $dataObj->module_id = $source->module_id;
        }
        if( isset($source->module_status) && $source->module_status != '' && $source->module_status != null){
            $dataObj->module_status = $source->module_status;
        }
        if( isset($source->module_order_id) && $source->module_order_id != '' && $source->module_order_id != null){
            $dataObj->module_order_id = $source->module_order_id;
        }
        $dataObj->save();

        return $dataObj;
    }

    static function getData($source){
        $dataObj = new \stdClass();
        $dates = self::reformDate($source->time);

        $source = (object) $source;
        $dataObj->id = $source->id;
        
        $dataObj->body = ( $source->deleted_by != null ? 'رسالة محذوفة أو غير مدعومة' : $source->body);
        $dataObj->fromMe = isset($source->fromMe) ? $source->fromMe : '';
        $dataObj->isForwarded = isset($source->isForwarded) ? $source->isForwarded : '';
        $dataObj->author = isset($source->author) ? $source->author : '';
        $dataObj->time = isset($source->time) ? $source->time : '';
        $dataObj->created_at_day = isset($source->time) ? $dates[0]  : ''; 
        $dataObj->created_at_time = isset($source->time) ? $dates[1]  : ''; 
        $dataObj->chatId = isset($source->chatId) ? $source->chatId : '';
        $dataObj->dialog = isset($source->chatId) ? self::reformChatId($source->chatId) : '';
        $dataObj->status = self::getSenderStatus($source);
        $dataObj->message_type = $source->type;
        $dataObj->senderName = isset($source->senderName) && $source->senderName != null ? $source->senderName : $source->chatName ;
        $dataObj->caption = isset($source->caption) ? $source->caption : '' ;
        $dataObj->chatName = isset($source->chatName) ? $source->chatName : '' ;
        $dataObj->sending_status = $source->sending_status;
        $dataObj->sending_status_text = self::getSendingStatus($source->sending_status);
        $dataObj->metadata = isset($source->metadata) ? json_decode($source->metadata) : '';
        if($dataObj->metadata != '' && isset($source->metadata['quotedMsgId'])){
            $dataObj->quotedMsgObj = self::getData(self::getOne($source->metadata['quotedMsgId']));
        }
        if(in_array($dataObj->message_type , ['document','video','audio','image'])){
            $dataObj->file_size = self::getPhotoSize($dataObj->body);
            $dataObj->file_name = $dataObj->message_type != 'image' ? ($source->caption != null ? $source->caption : self::getFileName($dataObj->body) ) : self::getFileName($dataObj->body);
        }
        if(isset($dataObj->message_type) && $dataObj->message_type == 'contact' ){
            $contactData = json_decode($source->metadata);
            $dataObj->contact_name = isset($contactData->name) ? $contactData->name : $contactData->phone;
            $dataObj->contact_number = $contactData->phone;
            $dataObj->body = $dataObj->contact_number;
        }
        $dataObj->messageContent = $source->body != null && (strpos(' https',ltrim($source->body)) !== false || strpos(' http',ltrim($source->body)) !== false ) ? 'ðŸ“·' : $source->body;
        if(in_array($dataObj->message_type , ['audio'])){
            $dataObj->messageContent = trans('main.sound');
        }
       
        $dataObj->icon = $source->fromMe ? '<i class="flaticon-reply text-success"></i>' : '<i class="flaticon-speech-bubble-1 text-danger"></i>';
        $dataObj->date_time = $dataObj->created_at_day . ' ' . $dataObj->created_at_time;
        // if($source->type == 'mention'){
        //     $dataObj->body = str_replace('@', '', $dataObj->body);
        // }
        $dataObj->chatId3 = str_replace('+','',$dataObj->dialog);
        $dataObj->deleted_by = $source->deleted_by;
        $dataObj->deleted_at = $source->deleted_at;
        $dataObj->module_id = $source->module_id;
        $dataObj->module_status = $source->module_status;
        $dataObj->module_order_id = $source->module_order_id;
        return $dataObj;
        
    }  

    static function getSendingStatus($status){
        if($status == 0){
            return trans('main.notSent');
        }else if($status == 1){
            return trans('main.sent');
        }else if($status == 2){
            return trans('main.received');
        }else if($status == 3){
            return trans('main.seen');
        }
    }

    static function getSenderStatus($source){
        if($source->status != null){
            return $source->status;
        }else{
            if($source->fromMe == 0){
                return $source->senderName;
            }else{
                return 'API';
            }
        }        
    }

    static function reformChatId($chatId){
        $chatId = str_replace('@s.whatsapp.net','@c.us',$chatId);
        $chatId = str_replace('@c.us','',$chatId);
        $chatId = str_replace('@g.us','',$chatId);
        return $chatId;
    }
    
    static function reformDate($time){

        if(!defined('LANGUAGE_PREF')){
            define('LANGUAGE_PREF','ar');
        }
        
        $diff = (time() - $time ) / (3600 * 24);
        $date = \Carbon\Carbon::parse(date('Y-m-d H:i:s'));
        if(round($diff) == 0 && round($diff) < 1){
            return [trans('main.today'),date('h:i A',$time)];
        }else if($diff>0 && $diff<=1){
            return [trans('main.yesterday'), date('h:i A',$time)];
        }else if($diff > 1 && $diff < 7){
            $myDate = \Carbon\Carbon::parse(date('Y-m-d H:i:s',$time));
            return [$myDate->locale(@defined(LANGUAGE_PREF) ? LANGUAGE_PREF : 'ar')->dayName,date('h:i A',$time)];
        }else{
            return [date('Y-m-d',$time),date('h:i A',$time)];
        }
    }

    static function getPhotoSize($url){
        if($url == ""){
            return '';
        }

        if (filter_var($url, FILTER_VALIDATE_URL)) { 
            $image = @get_headers($url, 1);
            $bytes = @$image["Content-Length"];
            $mb = $bytes/(1024 * 1024);
            return number_format($mb,2) . " MB ";
        }
    }

    static function getFileName($body){
        $names = explode('/',$body);
        return array_reverse($names)[0];
    }
    static function getExtension($body){
        $names = explode('.',$body);
        return array_reverse($names)[0];
    }
}
