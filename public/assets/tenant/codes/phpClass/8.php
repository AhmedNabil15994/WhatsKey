<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'url' => 'https://whatskey.net', // الرابط المرسل
        'title' => 'title', // عنوان الرابط المرسل
        'description' => 'Link Description', // وصف الرابط المرسل
    ];
    $whatsObj->sendLink($data);