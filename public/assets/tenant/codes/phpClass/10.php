<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'contactMobile' => '966xxxxxxxxx', // جهة الاتصال المرسلة
        "organization" => "testOrganization" // ( اختياري ),
        "name" => "test name" // اسم جهة الاتصال
    ];
    $whatsObj->sendContact($data);