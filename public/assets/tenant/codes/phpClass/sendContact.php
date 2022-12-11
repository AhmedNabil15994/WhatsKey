<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx";
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx";
    $whatsLoopObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'contact' => '966xxxxxxxxx', // جهة الاتصال المرسلة
        "organization"=> "testOrganization", 
        "name" => "test name"
    ];
    $whatsLoopObj->sendContact($data);