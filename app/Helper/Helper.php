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
            $file_size = number_format($file_size,2) . " MB ";
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
        $addons = Session::has('addons') ? Session::get('addons') : [];//\DB::connection('main')->table('addons')->whereIn('id',)->get(['module','id']);
        $externalPermissions = [];
        foreach ($addons as $addon) {
            // if(!in_array($addon,Session::get('deactivatedAddons')) || !in_array($addon,Session::get('disabledAddons'))){
            //     if($addon == 1){
            //         $externalPermissions = [
            //             'BotControllers@index' => 'list-bots',
            //             'BotControllers@edit' => 'edit-bot',
            //             'BotControllers@update' => 'edit-bot',
            //             'BotControllers@fastEdit' => 'edit-bot',
            //             'BotControllers@changeStatus' => 'edit-bot',
            //             'BotControllers@add' => 'add-bot',
            //             'BotControllers@addBotReply' => 'add-bot',
            //             'BotControllers@create' => 'add-bot',
            //             'BotControllers@copy' => 'copy-bot',
            //             'BotControllers@delete' => 'delete-bot',
            //             'BotControllers@sort' => 'sort-bot',
            //             'BotControllers@arrange' => 'sort-bot',
            //             'BotControllers@charts' => 'charts-bot',
            //             'BotControllers@uploadImage' => 'uploadImage-bot',
            //             'BotControllers@deleteImage' => 'deleteImage-bot',
            //         ];
            //     }elseif($addon == 2){
            //         $externalPermissions = [
            //             'LiveChatControllers@index' => 'list-livechat',
            //             'LiveChatControllers@dialogs' => 'list-dialogs',
            //             'LiveChatControllers@pinChat' => 'pin-chat',
            //             'LiveChatControllers@unpinChat' => 'unpin-chat',
            //             'LiveChatControllers@readChat' => 'read-chat',
            //             'LiveChatControllers@unreadChat' => 'unread-chat',
            //             'LiveChatControllers@messages' => 'list-messages',
            //             'LiveChatControllers@sendMessage' => 'sendMessage',
            //             'LiveChatControllers@deleteMessage' => 'deleteMessage',
            //             'LiveChatControllers@labels' => 'list-labels',
            //             'LiveChatControllers@labelChat' => 'label-chat',
            //             'LiveChatControllers@unlabelChat' => 'unlabel-chat',
            //             'LiveChatControllers@contact' => 'list-contact-details',
            //             'LiveChatControllers@updateContact' => 'update-contact-details',
            //             'LiveChatControllers@quickReplies' => 'list-quickReplies',
            //             'LiveChatControllers@moderators' => 'list-moderators',
            //             'LiveChatControllers@assignMod' => 'assign-moderator',
            //             'LiveChatControllers@removeMod' => 'remove-moderator',
            //             'LiveChatControllers@liveChatLogout' => 'list-livechat',
            //         ];
            //     }elseif($addon == 3){
            //         $externalPermissions = [
            //             'GroupMsgsControllers@index' => 'list-group-messages',
            //             'GroupMsgsControllers@add' => 'add-group-message' ,
            //             'GroupMsgsControllers@create' => 'add-group-message',
            //             'GroupMsgsControllers@resend' => 'add-group-message',
            //             'GroupMsgsControllers@view' => 'view-group-message',
            //             'GroupMsgsControllers@refresh' => 'view-group-message',
            //             'GroupMsgsControllers@charts' => 'charts-group-message',
            //             'GroupMsgsControllers@uploadImage' => 'uploadImage-group-message',
            //         ];
            //     }elseif($addon == 4){
            //         $externalPermissions = [
            //             'ZidControllers@customers' => 'zid-customers',
            //             'ZidControllers@products' => 'zid-products',
            //             'ZidControllers@orders' => 'zid-orders',
            //             'ZidControllers@abandonedCarts' => 'zid-abandoned-carts',
            //             'ZidControllers@getEvent' => 'zid-abandoned-carts',
            //             'ZidControllers@updateEvent' => 'zid-abandoned-carts',
            //             'ZidControllers@sendAbandoned' => 'zid-send-abandoned',
            //             'ZidControllers@resendCarts' => 'zid-send-abandoned',
            //             'ZidControllers@uploadImage' => 'zid-send-abandoned',
            //             'ZidControllers@reports' => 'zid-reports',
            //             'ZidControllers@templates' => 'zid-templates',
            //             'ZidControllers@templatesEdit' => 'edit-zid-template',
            //             'ZidControllers@templatesUpdate' => 'edit-zid-template',
            //             'ZidControllers@templatesAdd' => 'add-zid-template',
            //             'ZidControllers@templatesCreate' => 'add-zid-template',
            //             'ZidControllers@settings' => 'updateZid',
            //             'ZidControllers@postSettings' => 'updateZid',
            //             // 'ZidControllers@templatesDelete' => 'delete-zid-template',
            //             'ProfileControllers@updateZid' => 'updateZid',
            //         ];
            //     }elseif($addon == 5){
            //         $externalPermissions = [
            //             'SallaControllers@customers' => 'salla-customers',
            //             'SallaControllers@products' => 'salla-products',
            //             'SallaControllers@abandonedCarts' => 'salla-abandoned-carts',
            //             'SallaControllers@getEvent' => 'salla-abandoned-carts',
            //             'SallaControllers@updateEvent' => 'salla-abandoned-carts',
            //             'SallaControllers@sendAbandoned' => 'salla-send-abandoned',
            //             'SallaControllers@resendCarts' => 'salla-send-abandoned',
            //             'SallaControllers@uploadImage' => 'salla-send-abandoned',
            //             'SallaControllers@orders' => 'salla-orders',
            //             'SallaControllers@reports' => 'salla-reports',
            //             'SallaControllers@templates' => 'salla-templates',
            //             'SallaControllers@templatesEdit' => 'edit-salla-template',
            //             'SallaControllers@templatesUpdate' => 'edit-salla-template',
            //             'SallaControllers@templatesAdd' => 'add-salla-template',
            //             'SallaControllers@templatesCreate' => 'add-salla-template',
            //             // 'SallaControllers@templatesDelete' => 'delete-salla-template',
            //             'ProfileControllers@updateSalla' => 'updateSalla',
            //         ];
            //     }elseif($addon == 6){
            //         $externalPermissions = [];
            //     }elseif($addon == 7){
            //         $externalPermissions = [];
            //     }elseif($addon == 8){
            //         $externalPermissions = [];
            //     }elseif($addon == 9){
            //         $externalPermissions = [
            //             'WhatsappOrdersControllers@settings' => 'whatsapp-settings',
            //             'WhatsappOrdersControllers@postSettings' => 'whatsapp-settings',
            //             'WhatsappOrdersControllers@bankTransfers' => 'whatsapp-bankTransfers',
            //             'WhatsappOrdersControllers@viewTransfer' => 'view-whatsapp-bankTransfer',
            //             'WhatsappOrdersControllers@updateTransfer' => 'edit-whatsapp-bankTransfer',
            //             'WhatsappOrdersControllers@deleteTransfer' => 'delete-whatsapp-bankTransfer',

            //             'WhatsappOrdersControllers@products' => 'whatsapp-products',
            //             'WhatsappOrdersControllers@assignCategory' => 'whatsapp-assignCategory',
            //             'WhatsappOrdersControllers@orders' => 'whatsapp-orders',
            //             'WhatsappOrdersControllers@sendLink' => 'whatsapp-orders-sendLink',
            //             'WhatsAppCouponControllers@index' => 'list-coupons',
            //             'WhatsAppCouponControllers@edit' => 'edit-coupon',
            //             'WhatsAppCouponControllers@update' => 'edit-coupon',
            //             'WhatsAppCouponControllers@fastEdit' => 'edit-coupon',
            //             'WhatsAppCouponControllers@add' => 'add-coupon',
            //             'WhatsAppCouponControllers@create' => 'add-coupon',
            //             'WhatsAppCouponControllers@delete' => 'delete-coupon',
            //             'WhatsAppCouponControllers@arrange' => 'sort-coupon',
            //             'WhatsAppCouponControllers@sort' => 'sort-coupon',
            //             'WhatsAppCouponControllers@charts' => 'charts-coupon',
            //         ];
            //     }elseif($addon == 10){
            //         $externalPermissions = [
            //             'BotPlusControllers@index' => 'list-bots-plus',
            //             'BotPlusControllers@edit' => 'edit-bot-plus',
            //             'BotPlusControllers@update' => 'edit-bot-plus',
            //             'BotPlusControllers@changeStatus' => 'edit-bot-plus',
            //             'BotPlusControllers@fastEdit' => 'edit-bot-plus',
            //             'BotPlusControllers@add' => 'add-bot-plus',
            //             'BotPlusControllers@create' => 'add-bot-plus',
            //             'BotPlusControllers@copy' => 'copy-bot-plus',
            //             'BotPlusControllers@delete' => 'delete-bot-plus',
            //             'BotPlusControllers@sort' => 'sort-bot-plus',
            //             'BotPlusControllers@arrange' => 'sort-bot-plus',
            //             'BotPlusControllers@charts' => 'charts-bot-plus',
            //             'BotPlusControllers@uploadImage' => 'uploadImage-bot-plus',
            //             'BotPlusControllers@deleteImage' => 'deleteImage-bot-plus',
            //         ];
            //     }elseif($addon == 11){
            //         $externalPermissions = [
            //             'ProfileControllers@apiSetting' => 'apiSetting',
            //             'ProfileControllers@apiGuide' => 'apiGuide',
            //             'ProfileControllers@webhookSetting' => 'webhookSetting',
            //             'ProfileControllers@postWebhookSetting' => 'webhookSetting',
            //         ];
            //     }elseif($addon == 13){
            //         $externalPermissions = [
            //             'TemplateMsgControllers@index' => 'list-templates-messages',
            //             'TemplateMsgControllers@edit' => 'edit-template-message',
            //             'TemplateMsgControllers@deleteImage' => 'deleteImage-template-message',
            //             'TemplateMsgControllers@update' => 'edit-template-message',
            //             'TemplateMsgControllers@changeStatus' => 'edit-template-message',
            //             'TemplateMsgControllers@fastEdit' => 'edit-template-message',
            //             'TemplateMsgControllers@add' => 'add-template-message',
            //             'TemplateMsgControllers@create' => 'add-template-message',
            //             'TemplateMsgControllers@uploadImage' => 'add-template-message',
            //             'TemplateMsgControllers@copy' => 'copy-template-message',
            //             'TemplateMsgControllers@delete' => 'delete-template-message',
            //             'TemplateMsgControllers@sort' => 'sort-template-message',
            //             'TemplateMsgControllers@arrange' => 'sort-template-message',
            //             'TemplateMsgControllers@charts' => 'charts-template-message',
            //         ];
            //     }elseif($addon == 14){
            //         $externalPermissions = [
            //             'ListMsgControllers@index' => 'list-list-messages',
            //             'ListMsgControllers@edit' => 'edit-list-message',
            //             'ListMsgControllers@update' => 'edit-list-message',
            //             'ListMsgControllers@changeStatus' => 'edit-list-message',
            //             'ListMsgControllers@fastEdit' => 'edit-list-message',
            //             'ListMsgControllers@add' => 'add-list-message',
            //             'ListMsgControllers@create' => 'add-list-message',
            //             'ListMsgControllers@copy' => 'copy-list-message',
            //             'ListMsgControllers@delete' => 'delete-list-message',
            //             'ListMsgControllers@sort' => 'sort-list-message',
            //             'ListMsgControllers@arrange' => 'sort-list-message',
            //             'ListMsgControllers@charts' => 'charts-list-message',
            //         ];
            //     }
            // }

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
