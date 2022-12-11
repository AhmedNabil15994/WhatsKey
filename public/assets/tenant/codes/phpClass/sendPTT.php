<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx";
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx";
    $whatsLoopObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'audio' => 'https://url/file.ogg', // رابط الملف
    ];
    $whatsLoopObj->sendPTT($data);