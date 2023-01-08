<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phones' => ['966xxxxxxxxx','966xxxxxxxxx'], // هواتف المستقبلين
        'interval' => 3, // الفترة الزمنية بين كل رسالة والاخري بالثانية
        'url' => 'https://whatskey.net', // الرابط المرسل
        'title' => 'title', // عنوان الرابط المرسل
        'description' => 'Link Description', // وصف الرابط المرسل
    ];
    $whatsObj->sendBulkLink($data);