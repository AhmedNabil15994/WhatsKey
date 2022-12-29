<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model{

    use \TraitsFunc;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = ['id','order_id','token','title','sellerJid','itemCount','price','currency','time','chatId','products'];    
    public $timestamps = false;

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function dataList($productId=null) {
        $input = \Request::all();

        $source = self::where('id','!=',null);
        if(isset($input['id']) && !empty($input['id'])){
            $source->where('id',$input['id']);
        }
        if(isset($input['order_id']) && !empty($input['order_id'])){
            $source->where('order_id',$input['order_id']);
        }
        if(isset($input['order_token']) && !empty($input['order_token'])){
            $source->where('token',$input['order_token']);
        }
        if(isset($input['title']) && !empty($input['title'])){
            $source->where('title',$input['title']);
        }
        if(isset($input['itemCount']) && !empty($input['itemCount'])){
            $source->where('itemCount',$input['itemCount']);
        }
        if(isset($input['price']) && !empty($input['price'])){
            $source->where('price',$input['price']);
        }
        if(isset($input['chatId']) && !empty($input['chatId'])){
            $source->where('chatId',$input['chatId']);
        }
        if($productId != null){
            $source->where('products','LIKE','%'.$productId.'%');
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
        $data->order_id = $source->order_id;
        $data->token = $source->token;
        $data->title = $source->title;
        $data->sellerJid = $source->sellerJid;
        $data->itemCount = $source->itemCount;
        $data->products = $source->products != null && $source->products != '' ? json_decode($source->products) : [];
        $data->price = $source->price;
        $data->currency = $source->currency;
        $data->time = $source->time;
        $data->chatId = $source->chatId;
        $data->created_at = date('Y-m-d H:i:s',strtotime($source->time));
        return $data;
    }  

}
