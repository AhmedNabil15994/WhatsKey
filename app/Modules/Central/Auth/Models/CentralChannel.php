<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class CentralChannel extends Model{

    use \TraitsFunc;

    protected $table = 'channels';
    protected $primaryKey = 'id';
    protected $connection = 'main';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'token',
        'instanceId',
        'instanceToken',
        'start_date',
        'end_date',
        'tenant_id',
        'global_user_id',
    ];

    static function getOneByID($id){
        $channelObj = self::NotDeleted()->where('instanceId',$id)->first();
        return $channelObj ? self::getData($channelObj) : null;
    }

    static function getOne($id) {
        $channelObj = self::find($id);
        return $channelObj ? $channelObj : self::where('instanceId',$id)->first();
    }

    static function getUserChannels() {
        $data = self::NotDeleted()->orderBy('end_date','DESC');
        return self::getObj($data);
    }

    static function dataList($id=null) {
        $input = \Request::all();

        $source = self::NotDeleted()->where(function ($query) use ($input) {
                    if (isset($input['id']) && !empty($input['id'])) {
                        $query->where('id',$input['id']);
                    }
                    if (isset($input['token']) && !empty($input['token'])) {
                        $query->where('token',$input['token']);
                    } 
                    if (isset($input['name']) && !empty($input['name'])) {
                        $query->where('name', 'LIKE', '%' . $input['name'] . '%');
                    } 
                });
        if(\Session::has('user_id') && !IS_ADMIN){
            $source->where('id',Session::get('channel'));
        }
        if($id != null){
            $source->where('id',$id)->orWhere('instanceId',$id);
        }
        $source->orderBy('end_date','DESC');
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

    static function generateNewKey($token){
        $dataObj = self::NotDeleted()->orderBy('instanceId','DESC')->first();
        if($dataObj == null || $dataObj->instanceId == null ){
            $newKey = 10001;
        }
        $newKey = (int) $dataObj->instanceId + 1;
        $hashedToken = md5($token);
        return [$newKey , $hashedToken];
    }

    static function getData($source){
        $dataObj = new \stdClass();
        $dataObj->id = $source->id;
        $dataObj->name = $source->name;
        $dataObj->title = $source->name;
        $dataObj->token = $source->token;
        $dataObj->global_user_id = $source->global_user_id;
        $dataObj->tenant_id = $source->tenant_id;
        $dataObj->start_date = $source->start_date;
        $dataObj->end_date = $source->end_date;
        $dataObj->instanceId = $source->instanceId;
        $dataObj->instanceToken = $source->instanceToken;
        $dataObj->days = (strtotime($source->end_date) - strtotime($source->start_date)) / (60 * 60 * 24);
        $dataObj->usedDays = (strtotime(date('Y-m-d')) - strtotime($source->start_date)) / (60 * 60 * 24);
        $dataObj->leftDays = $dataObj->days - $dataObj->usedDays;
        $dataObj->rate = $dataObj->days ? ($dataObj->leftDays / $dataObj->days) * 100 : 0;
        $dataObj->myId = 1;
        $dataObj->name2 = trans('main.channel').' #'.$source->instanceId;
        return $dataObj;
    }

}
