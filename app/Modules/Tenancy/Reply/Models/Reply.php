<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Reply extends Model{

    use \TraitsFunc;

    protected $table = 'quick_replies';
    protected $fillable = ['id','channel','name_ar','name_en','description_ar','description_en','reply_id','status','created_by','created_at'];    
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function dataList($status=null,$noUserReplies=0) {
        $input = \Request::all();

        $source = self::NotDeleted();
        if (isset($input['channel']) && !empty($input['channel'])) {
            $source->where('channel',$input['channel']);
        } 
        if (isset($input['name_ar']) && !empty($input['name_ar'])) {
            $source->where('name_ar', 'LIKE', '%' . $input['name_ar'] . '%');
        } 
        if (isset($input['name_en']) && !empty($input['name_en'])) {
            $source->where('name_en', 'LIKE', '%' . $input['name_en'] . '%');
        } 
        if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
            $source->where('created_at','>=', $input['from'].' 00:00:00')->where('created_at','<=',$input['to']. ' 23:59:59');
        }

        if($noUserReplies == 1){
            $source->where('reply_id',null);
        }else if($noUserReplies == 2){
            $source->where('reply_id','!=',null);
        }else if($noUserReplies == 3){
            $source->where('id','!=',null);
        }
        
        if($status != null){
            $source->where('status',$status);
        }
        if(isset($input['channel']) && !empty($input['channel'])){
            $source->where('channel',$input['channel']);
        }
        
        $source->orderBy('sort','ASC');
        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->get();

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        $data['data'] = $list;

        return $data;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->name_ar = $source->name_ar;
        $data->name_en = $source->name_en;
        $data->title = $source->{'name_'.(defined(LANGUAGE_PREF) ? LANGUAGE_PREF : 'ar')};
        $data->channel = $source->channel;
        $data->description_ar = $source->description_ar;
        $data->description_en = $source->description_en;
        $data->description = $source->{'description_'.(defined(LANGUAGE_PREF) ? LANGUAGE_PREF : 'ar')};
        $data->reply_id = $source->reply_id;
        $data->status = $source->status;
        $data->sort = $source->sort;
        $data->created_at = $source->created_at != null ? \Helper::formatDate($source->created_at) : \Helper::formatDate($source->updated_at);
        return $data;
    }

    static function newSortIndex(){
        return self::count() + 1;
    }

    static function newReply($replyObj){
        $replyObj = (object) $replyObj;
        $contactObj = self::where('reply_id',$replyObj->id)->first();
        if($contactObj == null){
            $contactObj = new self;
            $contactObj->name_ar = $replyObj->shortcut;
            $contactObj->name_en = $replyObj->shortcut;
            $contactObj->description_ar = $replyObj->message;
            $contactObj->description_en = $replyObj->message;
            $contactObj->reply_id = $replyObj->id;
            $contactObj->sort = self::newSortIndex();
            $contactObj->created_at = date('Y-m-d H:i:s');
        }
        $contactObj->status = 1;
        return $contactObj->save();
    }

}
