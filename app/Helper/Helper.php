<?php

class Helper
{   
    public static function RedirectWithPostForm(array $data,$url) {
        $fullData = $data;
        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <script type="text/javascript">
                    function closethisasap() {
                        document.forms["redirectpost"].submit();
                    }
                </script>
            </head>
            <body onload="closethisasap();">
                <form name="redirectpost" method="post" action="<?PHP echo $url; ?>">
                    <?php
                    if (!is_null($fullData)) {
                        foreach ($fullData as $k => $v) {
                            if(is_object($v) || is_array($v)){
                                echo "<input type='hidden' name='".$k."' value='".json_encode((array)$v)."' >";
                            }else{
                                echo "<input type='hidden' name='".$k."' value='".$v."' >";
                            }
                        }
                    }
                   ?>
               </form>
            </body>
        </html>
        <?php
        exit;
    }

    static function reformMessage($msg){
        $dataObj = new \stdClass();
        $dataObj->id = $msg['messageId'];
        $dataObj->type = $msg['type'];
        $dataObj->status = $msg['status'];
        $dataObj->chatId = \App\Models\ChatMessage::reformChatId($msg['chatId']);
        $dataObj->last_try = $msg['created_at'];
        return $dataObj;
    }

    static function calcTax($mainPrice){
        $tax = 15/100;
        $estimatedTax = $mainPrice * (15/115);
        return round($estimatedTax,2);
    }

    static function formatDate($date, $formate = "Y-m-d h:i:s A", $unix = false){
        $date = str_replace("," , '' , $date);
        $FinalDate = $unix != false ? gmdate($formate, $date) : date($formate, strtotime($date));
        return $FinalDate != '1970-01-01 12:00:00' ? $FinalDate : null;
    }

    static function formatDateForDisplay($date, $withTime = false){
        if($date == null || $date == "0000-00-00 00:00:00" || $date == "0000-00-00"){
            return '';
        }

        return $withTime != false ? date("m/d/Y h:i:s A", strtotime($date)) : date("m/d/Y", strtotime($date));
    }

    static function formatDateCustom($date, $format = "Y-m-d H:i:s", $custom = false) {
        if($date == null || $date == "0000-00-00 00:00:00" || $date == "0000-00-00" || $date == ""){
            return '';
        }

        $date = str_replace("," , '' , $date);

        $FinalDate = date($format, strtotime($date));

        if ($format == '24') {
            $FinalDate = date('Y-m-d', strtotime($date)) . ' 24:00:00';
        }

        if ($custom != false) {
            $FinalDate = date($format, strtotime($custom, strtotime($date)));
        }

        return $FinalDate != '1970-01-01 12:00:00' ? $FinalDate : null;
    }

    static function getFolderSize($path){
        $file_size = 0;
        if(file_exists($path)){
            foreach( \File::allFiles($path) as $file)
            {
                $file_size += $file->getSize();
            }
            $file_size = $file_size/(1024 * 1024);
            $file_size = number_format($file_size,2);
        }
        return $file_size;
    }

    static function fixPaginate($url, $key) {
        if(strpos($key , $url) == false){
            $url = preg_replace('/(.*)(?)' . $key . '=[^&]+?(?)[0-9]{0,4}(.*)|[^&]+&(&)(.*)/i', '$1$2$3$4$5$6$7$8$9$10$11$12$13$14$15$16$17$18$19$20', $url . '&');
            $url = substr($url, 0, -1);
            return $url ;
        }else{
            return $url;
        }
    }

    Static function GeneratePagination($source){
        $uri = \Request::getUri();
        $count = count($source);
        $total = $source->total();
        $lastPage = $source->lastPage();
        $currentPage = $source->currentPage();

        $data = new \stdClass();
        $data->count = $count;
        $data->total_count = $total;
        $data->current_page = $currentPage;
        $data->last_page = $lastPage;
        $next = $currentPage + 1;
        $prev = $currentPage - 1;

        $newUrl = self::fixPaginate($uri, "page");

        if(preg_match('/(&)/' , $newUrl) != 0 || strpos($newUrl , '?') != false ){
            $separator = '&';
        }else{
            $separator = '?';
        }

        if($currentPage !=  $lastPage ){
            $link = str_replace('&&' , '&' , $newUrl . $separator. "page=". $next);
            $link = str_replace('?&' , '?' , $link);
            $data->next = $link;
            if($currentPage == 1){
                $data->prev = "";
            }else{
                $link = str_replace('&&' , '&' , $newUrl . $separator. "page=". $prev);
                $link = str_replace('?&' , '?' , $link);
                $data->prev = $link ;
            }
        }else{
            $data->next = "";
            if($currentPage == 1){
                $data->prev = "";
            }else{
                $link = str_replace('&&' , '&' , $newUrl . $separator. "page=". $prev);
                $link = str_replace('?&' , '?' , $link);
                $data->prev = $link ;
            }
        }

        return $data;
    }

    static function getCountryNameByPhone($phone){
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $clinetNumberProto = $phoneUtil->parse($phone);
        $geocoder = \libphonenumber\geocoding\PhoneNumberOfflineGeocoder::getInstance();
        $langPref = LANGUAGE_PREF == 'en' ? 'en_US' : 'ar_EG';
        $country = $geocoder->getDescriptionForNumber($clinetNumberProto, $langPref);
        return $country;
    }

    static function getCountryCode() {
        $ip = \Request::ip();
        if($ip == "127.0.0.1" || $ip == "::1"){
            $ip = '197.40.252.75';
        }
        $location = \Location::get($ip);
        return $location;
    }

    static function checkRules($rule){
        if(IS_ADMIN == 1 && \Session::has('central') && \Session::get('central') == 1){
            return true;
        }
        $explodeRule = explode(',', $rule);
        // dd(PERMISSIONS);
        $containsSearch = count(array_intersect($explodeRule, (array) PERMISSIONS)) > 0;
        if($containsSearch == true){
            return true;
        }
        return false;
    }

    static function globalDelete($dataObj) {
        if ($dataObj == null) {
            return response()->json(\TraitsFunc::ErrorMessage(trans('main.notExist')));
        }

        $dataObj->deleted_by = USER_ID;
        $dataObj->deleted_at = date('Y-m-d H:i:s');
        $dataObj->save();

        $data['status'] = \TraitsFunc::SuccessResponse(trans('main.deleteSuccess'));
        return response()->json($data);
    }

    static function getCentralPermissions($withTitles = null){
        $data = [];
        $perms = config('central_permissions');
        foreach ($perms as $key => $perm) {
            if($perm != 'general'){
                $controller = explode('@', $key)[0];
                $data[$controller][$perm] = [
                    'perm_name' => $perm,
                    'perm_title' => trans('permission.'.$perm),
                ];
            }
        }
        return $data;
    }
    
    static function getAllPerms(){
        $controllers = config('permissions');
        $membershipAddons = Session::has('membershipAddons') ? Session::get('membershipAddons') : [];
        $addons = array_merge($membershipAddons,(Session::has('addons') ? Session::get('addons') : []));
        $externalPermissions = [];
        foreach ($addons as $key => $value) {
            if($value == 'api'){
                $externalPermissions = [
                    'ApiSettingController@apiSetting' => 'apiSetting',
                    'ApiSettingController@apiGuide' => 'apiGuide',
                    'ApiSettingController@webhookSetting' => 'webhookSetting',
                    'ApiSettingController@postWebhookSetting' => 'webhookSetting',
                ];
            }else if($value == 'Bot'){
                $externalPermissions = [
                    'BotControllers@index' => 'list-bots',
                    'BotControllers@edit' => 'edit-bot',
                    'BotControllers@update' => 'edit-bot',
                    'BotControllers@fastEdit' => 'edit-bot',
                    'BotControllers@changeStatus' => 'edit-bot',
                    'BotControllers@add' => 'add-bot',
                    'BotControllers@addBotReply' => 'add-bot',
                    'BotControllers@create' => 'add-bot',
                    'BotControllers@copy' => 'copy-bot',
                    'BotControllers@delete' => 'delete-bot',
                    'BotControllers@uploadImage' => 'uploadImage-bot',
                    'BotControllers@deleteImage' => 'deleteImage-bot',
                ];
            }else if($value == 'BotPlus'){
                $externalPermissions = [
                    'BotPlusControllers@index' => 'list-bots-plus',
                    'BotPlusControllers@edit' => 'edit-bot-plus',
                    'BotPlusControllers@update' => 'edit-bot-plus',
                    'BotPlusControllers@changeStatus' => 'edit-bot-plus',
                    'BotPlusControllers@fastEdit' => 'edit-bot-plus',
                    'BotPlusControllers@add' => 'add-bot-plus',
                    'BotPlusControllers@create' => 'add-bot-plus',
                    'BotPlusControllers@copy' => 'copy-bot-plus',
                    'BotPlusControllers@delete' => 'delete-bot-plus',
                    'BotPlusControllers@uploadImage' => 'uploadImage-bot-plus',
                    'BotPlusControllers@deleteImage' => 'deleteImage-bot-plus',
                ];
            }else if($value == 'GroupMsgs'){
                $externalPermissions = [
                    'GroupMsgsControllers@index' => 'list-group-messages',
                    'GroupMsgsControllers@add' => 'add-group-message' ,
                    'GroupMsgsControllers@create' => 'add-group-message',
                    'GroupMsgsControllers@resend' => 'add-group-message',
                    'GroupMsgsControllers@view' => 'view-group-message',
                    'GroupMsgsControllers@charts' => 'charts-group-message',
                    'GroupMsgsControllers@uploadImage' => 'uploadImage-group-message',
                ];
            }else if($value == 'Livechat'){
                $externalPermissions = [
                    'LiveChatControllers@index' => 'list-livechat',
                    'LiveChatControllers@upload' => 'list-livechat',
                    'LiveChatControllers@updateContact' => 'list-livechat',
                ];
            }else if($value == 'Polls'){
                $externalPermissions = [
                    'ListMsgControllers@index' => 'list-lists',
                    'ListMsgControllers@edit' => 'edit-list',
                    'ListMsgControllers@update' => 'edit-list',
                    'ListMsgControllers@changeStatus' => 'edit-list',
                    'ListMsgControllers@fastEdit' => 'edit-list',
                    'ListMsgControllers@add' => 'add-list',
                    'ListMsgControllers@create' => 'add-list',
                    'ListMsgControllers@copy' => 'copy-list',
                    'ListMsgControllers@delete' => 'delete-list',

                    'PollsControllers@index' => 'list-polls',
                    'PollsControllers@edit' => 'edit-poll',
                    'PollsControllers@update' => 'edit-poll',
                    'PollsControllers@changeStatus' => 'edit-poll',
                    'PollsControllers@fastEdit' => 'edit-poll',
                    'PollsControllers@add' => 'add-poll',
                    'PollsControllers@create' => 'add-poll',
                    'PollsControllers@copy' => 'copy-poll',
                    'PollsControllers@delete' => 'delete-poll',

                    'TemplateMsgControllers@index' => 'list-templates-messages',
                    'TemplateMsgControllers@edit' => 'edit-template-message',
                    'TemplateMsgControllers@deleteImage' => 'deleteImage-template-message',
                    'TemplateMsgControllers@update' => 'edit-template-message',
                    'TemplateMsgControllers@changeStatus' => 'edit-template-message',
                    'TemplateMsgControllers@fastEdit' => 'edit-template-message',
                    'TemplateMsgControllers@add' => 'add-template-message',
                    'TemplateMsgControllers@create' => 'add-template-message',
                    'TemplateMsgControllers@uploadImage' => 'add-template-message',
                    'TemplateMsgControllers@copy' => 'copy-template-message',
                    'TemplateMsgControllers@delete' => 'delete-template-message',

                ];
            }
            $controllers = array_merge($controllers,$externalPermissions);
        }

        return $controllers;
    }

    static function getPermissions($withTitles = null,$type='tenant'){
        $data = [];
        $perms = $type == 'tenant' ? self::getAllPerms() : config('central_permissions');
        foreach ($perms as $key => $perm) {
            if($perm != 'general'){
                $controller = explode('@', $key)[0];
                $data[$controller][$perm] = [
                    'perm_name' => $perm,
                    'perm_title' => trans('permission.'.$perm),
                ];
            }
        }
        return $data;
    }
    
}
