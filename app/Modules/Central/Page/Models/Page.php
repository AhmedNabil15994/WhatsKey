<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model{

    use \TraitsFunc;

    protected $table = 'pages';
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function dataList($status=null,$ids=null) {
        $input = \Request::all();

        $source = self::NotDeleted()->where(function ($query) use ($input,$status,$ids) {
                    if (isset($input['title']) && !empty($input['title'])) {
                        $query->where('title', 'LIKE', '%' . $input['title'] . '%');
                    } 
                    if (isset($input['icon']) && !empty($input['icon'])) {
                        $query->where('icon', 'LIKE', '%' . $input['icon'] . '%');
                    } 
                    if (isset($input['id']) && !empty($input['id'])) {
                        $query->where('id',  $input['id']);
                    } 
                    if($status != null){
                        $query->where('status',$status);
                    }
                    if($ids != null){
                        $query->whereIn('id',$ids);
                    }
                })->orderBy('sort','ASC');

        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->get();

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        // $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

        return $data;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->title = $source->{'title_'.LANGUAGE_PREF};
        $data->title_ar = $source->title_ar;
        $data->title_en = $source->title_en;
        $data->sort = $source->sort;
        $data->status = $source->status;
        $data->statusText = $source->status == 0 ? 'مسودة' : 'مفعلة';
        $data->created_at = \Helper::formatDateForDisplay($source->created_at,true);
        return $data;
    }


    static function newSortIndex(){
        return self::count() + 1;
    }

}
