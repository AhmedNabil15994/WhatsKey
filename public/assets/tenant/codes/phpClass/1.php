<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => "966xxxxxxxxx", // هاتف المستقبل
        'body' => "اهلا بك فى واتس كي تجربة ارسال رسالة", // محتوي الرسالة
    ];
    $whatsObj->sendMessage($data);