<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'reaction' => '😂', // الرياكشن    
        // 'reaction' => 'unset', //  لالغاء الرياكشن من علي الرسالة
        'messageId' => 'true_xxxxxxxxxxx@c.us_BAE5E8B97C8BB33A' , // Message ID
    ];
    $whatsObj->sendReaction($data);