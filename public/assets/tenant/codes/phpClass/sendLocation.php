<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx";
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx";
    $whatsLoopObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => "966xxxxxxxxx",
        'lat' => 'Latitude', // Latitude
        'lng' => 'Longitude', // Longitude
        'address' => 'Address', // نص العنوان
    ];
    $whatsLoopObj->sendLocation($data);