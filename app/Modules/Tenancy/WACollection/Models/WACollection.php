<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class  WACollection extends Model{

    use \TraitsFunc;

    protected $table = 'collections';
    protected $primaryKey = 'id';
    protected $fillable = ['id','collection_id','name','products','status','can_appeal'];    
    public $timestamps = false;

    static function getOne($id) {
        return self::find($id);
    }

    static function getUserChannels() {
        $data = self::orderBy('id','DESC');
        return self::getObj($data);
    }

    static function dataList() {
        $input = \Request::all();

        $source = self::where(function ($query) use ($input) {
                    if (isset($input['id']) && !empty($input['id'])) {
                        $query->where('id',$input['id']);
                    }
                    if (isset($input['name']) && !empty($input['name'])) {
                        $query->where('name', 'LIKE', '%' . $input['name'] . '%');
                    }
                    if (isset($input['products']) && !empty($input['products'])) {
                        $query->where('products',$input['products']);
                    }
                    if (isset($input['status']) && !empty($input['status'])) {
                        $query->where('status',$input['status']);
                    }
                    if (isset($input['can_appeal']) && !empty($input['can_appeal'])) {
                        $query->where('can_appeal',$input['can_appeal']);
                    }
                });
        $source->orderBy('id','DESC');
        return self::getObj($source);
    }

    static function getObj($source) {
        $sourceArr = $source->get();

        $list = [];
        foreach ($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        $data['data'] = $list;
        return $data;
    }

    static function getData($source){
        $dataObj = new \stdClass();
        $dataObj->id = $source->id;
        $dataObj->collection_id = $source->collection_id;
        $dataObj->name = $source->name;
        $dataObj->products = $source->products;
        $dataObj->productsIDArr = $source->products != null && $source->products != '' ? explode(',', $source->products) : [];
        $dataObj->productsCount =  $source->products != null && $source->products != '' ? substr_count($source->products, ',') : 0;
        $dataObj->status = $source->status;
        $dataObj->can_appeal = $source->can_appeal == 1 ? trans('main.yes') : trans('main.no');
        return $dataObj;
    }
}
