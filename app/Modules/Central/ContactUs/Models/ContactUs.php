<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model{

    use \TraitsFunc;

    protected $table = 'contact_us';
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function getOne($id) {
        return self::NotDeleted()
            ->find($id);
    }

    static function getByDate($date=null,$start=null,$end=null){
        $source = self::where(function($whereQuery) use ($date,$start,$end){
            if($date == null){
                $whereQuery->where('created_at','>=',now()->format('Y-m-d').' 00:00:00')->where('created_at','<=',now()->format('Y-m-d').' 23:59:59');
            }else{
                $whereQuery->where('created_at','>=',$start)->where('created_at','<=',$end);
            }
        })->orderBy('id','DESC');
        return self::getObj($source);
    }

    static function dataList() {        
        $input = \Request::all();
        $source = self::NotDeleted()->where(function ($query) use ($input) {
                if (isset($input['name']) && !empty($input['name'])) {
                    $query->where('name', 'LIKE', '%' . $input['name'] . '%');
                }
                if (isset($input['id']) && !empty($input['id'])) {
                    $query->where('id', $input['id']);
                }

                if (isset($input['email']) && !empty($input['email'])) {
                    $query->where('email', 'LIKE', '%' . $input['email'] . '%');
                }

                if (isset($input['message']) && !empty($input['message'])) {
                    $query->where('message', 'LIKE', '%' . $input['message'] . '%');
                }

                if (isset($input['reply']) && !empty($input['reply'])) {
                    $query->where('reply', 'LIKE', '%' . $input['reply'] . '%');
                }

                if (isset($input['phone']) && !empty($input['phone'])) {
                    $query->where('phone', 'LIKE', '%' . $input['phone'] . '%');
                }

                if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
                    $query->where('created_at','>=', $input['from'].' 00:00:00')->where('created_at','<=',$input['to']. ' 23:59:59');
                }
            });

        return self::getObj($source);
    }

    static function getObj($source) {
        $sourceArr = $source->get();
        $count = $source->count();

        $list = [];
        foreach ($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        $data['data'] = $list;
        $data['count'] = $count;
        return $data;
    }

    static function getData($source) {
        $faqObj = new \stdClass();
        $faqObj->id = $source->id;
        $faqObj->name = $source->name;
        $faqObj->email = $source->email;
        $faqObj->phone = $source->phone;
        $faqObj->address = $source->address;
        $faqObj->message = $source->message;
        $faqObj->reply = $source->reply != null ? $source->reply : '';
        $faqObj->status = $source->status;
        $faqObj->statusText = $source->status == 0 ? 'مسودة' : 'مفعلة';
        $faqObj->created_at = \Helper::formatDate($source->created_at,'Y-m-d H:i');

        return $faqObj;
    }

    static function newSortIndex(){
        return self::count() + 1;
    }
}
