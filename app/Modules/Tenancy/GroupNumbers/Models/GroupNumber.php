<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class GroupNumber extends Model{

    use \TraitsFunc;

    protected $table = 'group_numbers';
    protected $primaryKey = 'id';
    protected $fillable = ['id','channel','name_ar','name_en','status','created_by','created_at'];    
    public $timestamps = false;

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function dataList($status=null,$id=null) {
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
        if($id != null){
            $source->whereNotIn('id',$id);
        }
        $source->orderBy('sort','DESC');
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
        $data->title = $source->{'name_'.LANGUAGE_PREF};
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
