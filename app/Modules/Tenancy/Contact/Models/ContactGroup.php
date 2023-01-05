<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class ContactGroup extends Model{

    use \TraitsFunc;

    protected $table = 'contact_groups';
    protected $primaryKey = 'id';
    protected $fillable = ['id','contact','group_id','created_at'];    
    public $timestamps = false;
   
    public function Group(){
        return $this->belongsTo('App\Models\GroupMsg','group_id');
    }

    static function getOne($id){
        return self::where('id', $id)
            ->first();
    }

    static function newRecord($contact,$group_id,$date=null){
        $dataObj = self::where('contact',$contact)->where('group_id',$group_id)->first();
        if($dataObj == null){
            $dataObj = new self;
            $dataObj->contact = $contact;
            $dataObj->group_id = $group_id;
            $dataObj->created_at = $date != null ? $date : date('Y-m-d H:i:s');
            $dataObj->save();
        }
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
        $data->group_id = $source->group_id;
        $data->contact = $source->contact;
        $data->created_at = \Helper::formatDate($source->created_at);
        return $data;
    }

}
