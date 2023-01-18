<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatDialog extends Model{

    protected $table = 'dialogs';
    protected $primaryKey = 'id';
    protected $fillable = ['id','name','image','metadata','pinned','archived','unreadCount','unreadMentionCount','notSpam','readOnly','blocked','modsArr','muted','muted_until','labels','creation','owner','group_restrict','announce','participants','disable_read','background','group_description'];  


    public $timestamps = false;
    public $incrementing = false;

    static function getOne($id){
        $is_admin = \Session::get('is_admin');
        $user_id = \Session::get('user_id');
        if($is_admin){
            $source = self::where('id', $id)->first();
        }else{
            $source = self::where('id', $id)->where('modsArr','LIKE','%'.$user_id.'%')->first();
        }
        return $source;
    }

    public function labelColors(){
        if($this->labels != null && $this->labels != ''){
            $related = $this->hasMany(Category::class);
            $related->setQuery(
                Category::whereIn('labelId', array_unique(explode(',',$this->labels)))->getQuery()
            );
            return $related;
        }
        // return $this->labels;
    }

    public function moderatos(){
        if($this->modsArr != null && $this->modsArr != ''){
            $related = $this->hasMany(User::class);
            $related->setQuery(
                User::whereIn('id', array_unique(unserialize($this->modsArr)))->getQuery()
            );
            return $related;
        }
        // return $this->labels;
    }

    public function LastMessage(){
        return $this->hasOne(ChatMessage::class,'chatId','id')->ofMany([
            'time' => 'max',
        ], function ($query) {
            $query->where('time', '!=', null)->where('type','!=','reaction');
        });
    }

    public function SenderLastMessage(){
        return $this->hasOne(ChatMessage::class,'chatId','id')->ofMany([
            'time' => 'max',
        ], function ($query) {
            $query->where('fromMe',0)->where('time', '!=', null)->where('type','!=','reaction');
        });
    }

    public function Messages(){
        return $this->hasMany('App\Models\ChatMessage','chatId','id');
    }

    static function dataList($limit=null,$name=null,$contacts=null) {
        $input = \Request::all();
        $source = self::whereHas('Messages')->with(['LastMessage','SenderLastMessage']);

        if(!IS_ADMIN){
            $source->where('modsArr','LIKE','%'.USER_ID.'%');
        }

        $varObj = Variable::getVar('disableDialogsArchive');
        if($varObj == '1'){
            $source->where('archived',0)->orWhere('archived',null);
        }

        if($name != null){
            $source->where('name','LIKE','%'.$name.'%')->orWhere('id','LIKE','%'. str_replace('+','',$name).'%')->orderBy('pinned','DESC')->orderByDesc(ChatMessage::select('time')
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
        if( isset($source->subject) && $source->subject != null){
            $dataObj->name = $source->subject;
        }
        if( isset($source->creation) && $source->creation != null){
            $dataObj->creation = $source->creation;
        }
        if( isset($source->owner) && $source->owner != null){
            $dataObj->owner = str_replace('s.whatsapp.net','c.us',$source->owner);
        }
        if( isset($source->restrict) && $source->restrict != ''){
            $dataObj->group_restrict = $source->restrict;
        }
        if( isset($source->announce) && $source->announce != ''){
            $dataObj->announce = $source->announce;
        }
        if( isset($source->participants) && $source->participants != null){
            $dataObj->participants = json_encode($source->participants);
        }
        if( isset($source->labels) && $source->labels != null){
            $dataObj->labels = implode(',', $source->labels).',';
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
        $dataObj->blocked = isset($source->blocked) ? $source->blocked : 0;
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
            $dataObj->name = str_replace('+','',$dataObj->name);
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
            $dataObj->moderatos = $source->modsArr != null && $source->modsArr != '' ? $source->moderatos : [];
            $dataObj->blocked = $source->blocked;
            $dataObj->disable_read = $source->disable_read;
            $dataObj->background = $source->background;
            $dataObj->labels = $source->labels;
            $dataObj->labelsArr = $source->labels != null && $source->labels != '' ? $source->labelColors : [];
            $dataObj->muted = $source->muted;
            $dataObj->muted_until = $source->muted_until;
            $dataObj->creation = $source->creation;
            $dataObj->owner = $source->owner;
            $dataObj->group_restrict = $source->group_restrict;
            $dataObj->group_description = $source->group_description;
            $dataObj->announce = $source->announce;
            $dataObj->participants = $source->participants != null && $source->participants != '' ? json_decode($source->participants) : [];
            $dataObj->reformedPhone = str_contains($source->id, '@g.us') && $source->participants != null ? 'Group '. count(json_decode($source->participants)) .' participants' : self::reformNumber(str_replace('@c.us','',$source->id));

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

    static function reformNumber($chatId){
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
