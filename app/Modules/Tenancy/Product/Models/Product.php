<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model{

    use \TraitsFunc;

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = ['id','product_id','name','currency','price','collection_id','description','availability','review_status','is_hidden','images'];    
    public $timestamps = false;

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    public function WACollection(){
        return $this->belongsTo('App\Models\WACollection','collection_id');
    }

    static function dataList() {
        $input = \Request::all();

        $source = self::where('id','!=',null);
        if(isset($input['id']) && !empty($input['id'])){
            $source->where('id',$input['id']);
        }
        if(isset($input['name']) && !empty($input['name'])){
            $source->where('name',$input['name']);
        }
        if(isset($input['product_id']) && !empty($input['product_id'])){
            $source->where('product_id',$input['product_id']);
        }
        if(isset($input['price']) && !empty($input['price'])){
            $source->where('price',$input['price']);
        }
        if(isset($input['availability']) && !empty($input['availability'])){
            $source->where('availability',$input['availability']);
        }
        if(isset($input['review_status']) && !empty($input['review_status'])){
            $source->where('review_status',$input['review_status']);
        }
        if(isset($input['is_hidden']) && $input['is_hidden'] != null){
            $source->where('is_hidden',$input['is_hidden']);
        }
        if(isset($input['collection_id']) && $input['collection_id'] != null){
            $source->where('collection_id',$input['collection_id']);
        }

        $source->orderBy('id', 'DESC');
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
        $data->product_id = $source->product_id;
        $data->name = $source->name;
        $data->currency = $source->currency;
        $data->price = $source->price;
        $data->collection_id = $source->collection_id;
        $data->collection = $source->WACollection != null ? $source->WACollection->name : '';
        $data->description = $source->description;
        $data->availability = $source->availability;
        $data->review_status = $source->review_status;
        $data->is_hidden = $source->is_hidden == 1 ? trans('main.yes') : trans('main.no');
        $data->images = $source->images;
        return $data;
    }  

}
