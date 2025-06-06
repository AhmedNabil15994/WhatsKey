<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Template extends Model{

    use \TraitsFunc;

    protected $table = 'templates';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id','channel','name_ar','name_en','description_ar','description_en','status','created_by','created_at'];    
    
    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function dataList($status=null,$ids=null) {
        $input = \Request::all();

        $source = self::NotDeleted()->where(function ($query) use ($input) {
                    if (isset($input['name_ar']) && !empty($input['name_ar'])) {
                        $query->where('name_ar', 'LIKE', '%' . $input['name_ar'] . '%');
                    } 
                    if (isset($input['name_en']) && !empty($input['name_en'])) {
                        $query->where('name_en', 'LIKE', '%' . $input['name_en'] . '%');
                    } 
                    if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
                        $query->where('created_at','>=', $input['from'].' 00:00:00')->where('created_at','<=',$input['to']. ' 23:59:59');
                    }
                });
        if($status != null){
            $source->where('status',$status);
        }
        if($ids != null){
            $source->whereIn('id',$ids);
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
        
        if(!defined('LANGUAGE_PREF')){
            define('LANGUAGE_PREF','ar');
        }

        $data = new  \stdClass();
        $data->id = $source->id;
        $data->name_ar = $source->name_ar;
        $data->name_en = $source->name_en;
        $data->title = $source->{'name_'.(@defined(LANGUAGE_PREF) ? LANGUAGE_PREF : 'ar')};
        $data->description = $source->{'description_'.(@defined(LANGUAGE_PREF) ? LANGUAGE_PREF : 'ar')};
        $data->channel = $source->channel;
        $data->description_ar = $source->description_ar;
        $data->description_en = $source->description_en;
        $data->status = $source->status;
        $data->sort = $source->sort;
        $data->created_at = \Helper::formatDate($source->created_at);
        return $data;
    }

    static function newSortIndex(){
        return self::count() + 1;
    }

}
