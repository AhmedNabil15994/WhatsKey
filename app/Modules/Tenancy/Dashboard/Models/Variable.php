<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Variable extends Model
{

    use \TraitsFunc;

    protected $table = 'variables';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'var_key',
        'var_value',
    ];

    public static function getOne($id)
    {
        return self::NotDeleted()
            ->find($id);
    }

    public static function variableList()
    {
        $input = Request::all();

        $source = self::NotDeleted()->where(function ($query) use ($input) {
            if (isset($input['key']) && !empty($input['key'])) {
                $query->where('var_key', 'LIKE', '%' . $input['key'] . '%');
            }
        });

        if (isset($input['value']) && !empty($input['value'])) {
            $source->where('var_value', 'LIKE', '%' . $input['value'] . '%');
        }

        return self::getObj($source);
    }

    public static function getObj($source)
    {
        $sourceArr = $source->get();

        $list = [];
        foreach ($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        $data['data'] = $list;
        return $data;
    }

    public static function getData($source)
    {
        $variableObj = new \stdClass();
        $variableObj->id = $source->id;
        $variableObj->key = $source->var_key;
        $variableObj->value = $source->var_value;
        $variableObj->created_at = $source->created_at;
        return $variableObj;
    }

    public static function getVar($key)
    {
        $variableObj = self::where('var_key', $key)->first();
        return $variableObj != null ? $variableObj->var_value : '';
    }

    public static function get_Webhook_url()
    {
        $url = self::where('var_key', 'MESSAGE_NOTIFICATIONS')->first();
        if (!$url) {
            $mainWhatsLoopObj = new \OfficialHelper();
            $me = $mainWhatsLoopObj->me()->json()['data']['channelSetting']['webhooks'];
            $url = $me['messageNotifications'];
            (isset($me['messageNotifications'])) ? self::create(['var_key' => 'MESSAGE_NOTIFICATIONS', 'var_value' => $me['messageNotifications']]) : "";
            (isset($me['ackNotifications'])) ? self::create(['var_key' => 'ACK_NOTIFICATIONS', 'var_value' => $me['ackNotifications']]) : '';
            (isset($me['chatNotifications'])) ? self::create(['var_key' => 'CHAT_NOTIFICATIONS', 'var_value' => $me['chatNotifications']]) : '';
            (isset($me['labelNotifications'])) ? self::create(['var_key' => 'LABEL_NOTIFICATIONS', 'var_value' => $me['labelNotifications']]) : '';
        } else {
            $url = $url->var_value;
        }
        return $url;
    }

}
