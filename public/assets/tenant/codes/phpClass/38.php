<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phones' => ['966xxxxxxxxx','966xxxxxxxxx'], // هواتف المستقبلين
        'interval' => 3, // الفترة الزمنية بين كل رسالة والاخري بالثانية
        'body' => ' Poll Content ', // محتوي استطلاع الرأي
        'options' =>  ["Good","Not Good","Not","Fine","Not Fine"], // اختيارات استطلاع الرأي
    ];
    $whatsObj->sendBulkPoll($data);