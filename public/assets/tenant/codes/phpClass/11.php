<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'body' => '966xxxxxxxxx', // نص الرسالة
        "expiration" => 3600 // المدة المقررة لاختفاء الرسالة بالثواني,
    ];
    $whatsObj->disappearingText($data);