<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatDialog extends Model{

    protected $table = 'dialogs';
    protected $primaryKey = 'id';
    protected $fillable = ['id','name','image','metadata','pinned','archived','unreadCount','unreadMentionCount','notSpam','readOnly','modsArr'];    
    public $timestamps = false;
    public $incrementing = false;

    static function getOne($id){
        return self::where('id', $id)->first();
    }

    public function LastMessage(){
        return $this->hasOne(ChatMessage::class,'chatId','id')->ofMany([
            'time' => 'max',
        ], function ($query) {
            $query->where('time', '!=', null);
        });
    }

    public function SenderLastMessage(){
        return $this->hasOne(ChatMessage::class,'chatId','id')->ofMany([
            'time' => 'max',
        ], function ($query) {
            $query->where('fromMe',0)->where('time', '!=', null);
        });
    }

    public function Messages(){
        return $this->hasMany('App\Models\ChatMessage','chatId','id');
    }

    static function dataList($limit=null,$name=null,$contacts=null) {
        $input = \Request::all();
        // if($name != null){
        //     $limit = 0;
        //     $source = self::whereHas('Messages',function($whereHasQuery) use ($name){
        //         $whereHasQuery->where('senderName','LIKE','%'.$name.'%');
        //     })->with(['Messages','SenderLastMessage'])->orWhere('name','LIKE','%'.$name.'%')->orWhere('id','LIKE','%'. str_replace('+','',$name).'%')->orderByDesc(ChatMessage::select('time')
        //         ->whereColumn('messages.chatId', 'dialogs.id')
        //         ->orderBy('time','DESC')
        //         ->take(1)
        //     );
        // }else{
        //     $source =  self::whereHas('Messages')->with(['LastMessage','SenderLastMessage'])->orderByDesc(ChatMessage::select('time')
        //         ->whereColumn('messages.chatId', 'dialogs.id')
        //         ->orderBy('time','DESC')
        //         ->take(1)
        //     );
        // }
        
        $source = self::whereHas('Messages')->with(['LastMessage','SenderLastMessage']);

        if((isset($input['mine']) && !empty($input['mine']))){
            $source->where('modsArr','LIKE','%'.USER_ID.'%');
        }

        if($name != null){
            $source = self::whereHas('Messages',function($whereHasQuery) use ($name){
                $whereHasQuery->where('senderName','LIKE','%'.$name.'%');
            })->with(['Messages','SenderLastMessage'])->orWhere('name','LIKE','%'.$name.'%')->orWhere('id','LIKE','%'. str_replace('+','',$name).'%')->orderBy('pinned','DESC')->orderByDesc(ChatMessage::select('time')
                ->whereColumn('messages.chatId', 'dialogs.id')
                ->orderBy('time','DESC')
                ->take(1)
            );
        }else{
            $source->orderBy('pinned','DESC')->orderByDesc(ChatMessage::select('time')
                ->whereColumn('messages.chatId', 'dialogs.id')
                ->orderBy('time','DESC')
                ->take(1)
            );
        }
        // if($contacts != null){
        //     $source = self::with('Messages');
        //     if(isset($input['name']) && !empty($input['name'])){
        //         $source->whereHas('Messages',function($whereHasQuery) use ($input){
        //             $whereHasQuery->where('senderName','LIKE','%'.$input['name'].'%');
        //         })->orWhere('name','LIKE','%'.$input['name'].'%')->orWhere('id','LIKE','%' . str_replace('+','',$input['name']). '%');
        //     }
        //     return self::generateObj2($source,$limit);
        // }
        return self::generateObj($source,$limit,);
    }
    
    static function getPinned(){
        $source = self::whereHas('Messages')->with(['LastMessage','SenderLastMessage'])->where('pinned','>',0)->orderBy('last_time','DESC');  
        return self::generateObj($source);
    }

    static function generateObj($source,$limit=null){
        if($limit != null && $limit != 0){
            $sourceArr = $source->paginate($limit);
        }else{
            $sourceArr = $source->get();
        }
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value,null);
        }
        if($limit !=  null){
            $data['data'] = $list;
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        }else if($limit == 0){
            $data['data'] = $list;
        }else{
            $data = $list;
        }
        return $data;
    }

    static function generateObj2($source,$limit=null){
        if($limit != null && $limit != 0){
            $sourceArr = $source->paginate($limit);
        }else{
            $sourceArr = $source->get();
        }
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData2($value);
        }
        if($limit !=  null){
            $data['data'] = $list;
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        }else if($limit == 0){
            $data['data'] = $list;
        }else{
            $data = $list;
        }
        return $data;
    }

    static function newDialog($source){
        $source = (object) $source;
        $dataObj = self::where('id',$source->id)->first();
        if($dataObj == null){
            $dataObj = new  self;
        }
        
        $dataObj->id = $source->id;
        if( isset($source->name) && $source->name != null){
            $dataObj->name = $source->name;
        }
        if(isset($source->image) && !empty($source->image)){
            $path = $source->image;
            $type = pathinfo(
                parse_url($path, PHP_URL_PATH), 
                PATHINFO_EXTENSION
            );
            $data = @file_get_contents($path);
            $dataObj->image = $data != null ?  'data:image/' . $type . ';base64,' . base64_encode($data) : "";
        }
        $dataObj->metadata = isset($source->metadata) ? serialize($source->metadata) : '';
        $dataObj->last_time = isset($source->last_time) ? $source->last_time : '';
        $dataObj->pinned = isset($source->pinned) ? (int)$source->pinned : 0;
        $dataObj->archived = isset($source->archived) && $source->archived == 'true' ? 1 : 0;
        $dataObj->unreadCount = isset($source->unreadCount) ? $source->unreadCount : 0;
        $dataObj->unreadMentionCount = isset($source->unreadMentionCount) ? $source->unreadMentionCount : 0;
        $dataObj->notSpam = isset($source->notSpam) ? $source->notSpam : 0;
        $dataObj->readOnly = isset($source->readOnly) ? $source->readOnly : 0;
        $dataObj->save();
        return $dataObj;
    }

    static function getData($source,$metaData=false){
        $dataObj = new \stdClass();
        if($source){
            $source = (object) $source;
            $dataObj->id = $source->id;
            $dataObj->phone = str_replace('@c.us','',$source->id);
            $dataObj->name = $source->name != "" ? ( strpos($source->name, '@c.us') !== false && $source->SenderLastMessage != null ? $source->SenderLastMessage->senderName : $source->name ) : self::reformChatId($source->id,"");
            $dataObj->chatName = self::reformChatId($source->id,$dataObj->name);
            $dataObj->image = isset($source->image) ? mb_convert_encoding($source->image, 'UTF-8', 'UTF-8') : asset('assets/tenant/images/def_user.svg');
            $dataObj->metadata = isset($source->metadata) ? unserialize($source->metadata) : [];
            $dataObj->last_time = isset($source->LastMessage) && !empty($source->LastMessage)  ? self::reformDate($source->LastMessage->time) : self::reformDate($source->last_time); 
            $dataObj->pinned = $source->pinned;
            $dataObj->archived = $source->archived;
            $dataObj->unreadCount = $source->unreadCount;
            $dataObj->unreadMentionCount = $source->unreadMentionCount;
            $dataObj->notSpam = $source->notSpam;
            $dataObj->readOnly = $source->readOnly;

            $dataObj->modsArr = $source->modsArr != null ? unserialize($source->modsArr) : [];
            if($metaData == false){
                // $cats = ContactLabel::where('contact',str_replace('@c.us', '', $source->id))->pluck('category_id');
                // $cats = reset($cats);
                // $cats = empty($cats) ? [0] : $cats;
                // $dataObj->labels = Category::dataList(null,$cats)['data'];
                // $dataObj->labelsArr = $cats;
                // $dataObj->moderators =!empty($dataObj->modsArr)  ? User::dataList(null,$dataObj->modsArr,'ar')['data'] : [];
                // $dataObj->unreadCount = $source->Messages()->where('fromMe',0)->where('sending_status','!=',3)->count();
                $last = isset($source->LastMessage) && !empty($source->LastMessage) ? $source->LastMessage : ''; 
                if($last != ''){
                    $lastMessage = ChatMessage::getData($last,null,null,'notNull');   
                    $dataObj->lastMessage = $lastMessage;
                    $dataObj->last_time =  self::reformDate($source->LastMessage->time);
                }
                // if(isset($source->metadata) && isset($dataObj->metadata['labels']) && isset($dataObj->metadata['labels'][0])){
                //     $dataObj->label = Category::dataList($dataObj->metadata['labels'])['data'];
                // }
            }

            return $dataObj;
        }
    }

    static function getData2($source,$metaData=false){
        $dataObj = new \stdClass();
        if($source){
            $source = (object) $source;
            $dataObj->id = $source->id;
            $dataObj->phone = explode('@', $dataObj->id)[0];
            $dataObj->name = $source->name != "" ? ( strpos($source->name, '@c.us') !== false && $source->SenderLastMessage != null ? $source->SenderLastMessage->senderName : $source->name ) : self::reformChatId($source->id,"");
            $dataObj->image = isset($source->image) ? mb_convert_encoding($source->image, 'UTF-8', 'UTF-8') : '';
            return $dataObj;
        }
    }

    static function reformName($name){
        if(strpos($name, '+') !== false){
            $newName = str_replace('+', '', str_replace(' ', '', $name));
        }else{
            $newName = $name;
        }
        return $newName;
    }

    static function reformChatId($chatId,$name){
        $chatId = str_replace('@c.us','',$chatId);
        $chatId = str_replace('@g.us','',$chatId);
        $name = str_replace('@c.us','',$name);
        $name = str_replace('@g.us','',$name);
        if($name != null && $name != ''){
            return $name;
        }
        $chatId = '+'.$chatId;
        $parts=sscanf($chatId,'%4c%2c%3c%4c');
        return $parts[0].' '.$parts[1].' '.$parts[2].' '.$parts[3];
    }

    static function reformDate($time){
        $diff = 0;
        if($time != ''){
            $diff = (time() - $time ) / (3600 * 24);
        }else{
            return '';
        }
        $date = \Carbon\Carbon::parse(date('Y-m-d H:i:s'));
        if(round($diff) == 0 && round($diff) < 1){
            return date('h:i A',$time);
        }else if($diff>0 && $diff<=1){
            return trans('main.yesterday');
        }else if($diff > 1 && $diff < 7){
            $myDate = \Carbon\Carbon::parse(date('Y-m-d H:i:s',$time));
            return $myDate->locale(@defined(LANGUAGE_PREF) ? LANGUAGE_PREF : 'ar')->dayName;
        }else{
            return date('Y-m-d',$time);
        }
    }
}
