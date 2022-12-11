<?php

use App\Models\UserProfile;
use App\Models\Users;
use App\Models\ApiAuth;

trait TraitsFunc
{

    public static function loader(){
        return new self();
    }

    public function scopeNotDeleted($query){
        return $query->whereRaw('(deleted_at IS NULL)');
    }

    public static function NotFound(){
        $statusObj['status'] = new \stdClass();
        $statusObj['status']->status = 0;
        $statusObj['status']->code = 204;
        $statusObj['status']->message = trans('main.notFound');
        return \Response::json((object) $statusObj);
    }

    public static function ValidationError($validator){
        $statusObj['status'] = new \stdClass();
        $statusObj['status']->status = 0;
        $statusObj['status']->code = 400;
        $statusObj['status']->message = $validator->messages()->first();
        return \Response::json((object) $statusObj);
    }

    public static function ErrorMessage($message = "Error in process, please try again later", $code = 400){
        $statusObj['status'] = new \stdClass();
        $statusObj['status']->status = 0;
        $statusObj['status']->code = $code;
        $statusObj['status']->message = $message;
        return \Response::json((object) $statusObj);
    }

    public static function SuccessMessage($message = 'Data Generated Successfully'){
        $statusObj['status'] = new stdClass();
        $statusObj['status']->status = 1;
        $statusObj['status']->code = 200;
        $statusObj['status']->message = $message;
        return (object) $statusObj['status'];
    }

    public static function SuccessResponse($message = 'Data Generated Successfully'){
        $statusObj['status'] = new stdClass();
        $statusObj['status']->status = 1;
        $statusObj['status']->code = 200;
        $statusObj['status']->message = $message;
        return \Response::json((object) $statusObj);
    }

    public static function LoginResponse($message = 'Data Generated Successfully'){
        $statusObj['status'] = new stdClass();
        $statusObj['status']->status = 1;
        $statusObj['status']->code = 205;
        $statusObj['status']->message = $message;
        return (object) $statusObj['status'];
    }
}
