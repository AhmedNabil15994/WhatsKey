<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'lat' => 'Latitude', // Latitude
        'lng' => 'Longitude', // Longitude
        'address' => 'Address', // نص العنوان ( اختياري )
    ];
    $whatsObj->sendLocation($data);