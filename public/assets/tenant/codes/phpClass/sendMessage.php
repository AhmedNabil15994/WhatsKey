<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx";
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx";
    $whatsLoopObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => "966xxxxxxxxx",
        'body' => "اهلا بك فى واتس لوب تجربة ارسال رسالة",
    ];
    $whatsLoopObj->sendMessage($data);