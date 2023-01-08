<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'body' => ' Poll Content ', // محتوي استطلاع الرأي
        'options' =>  ["Good","Not Good","Not","Fine","Not Fine"], // اختيارات استطلاع الرأي
    ];
    $whatsObj->sendPoll($data);