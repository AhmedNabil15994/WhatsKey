<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phones' => ['966xxxxxxxxx','966xxxxxxxxx'], // هواتف المستقبلين
        'interval' => 3, // الفترة الزمنية بين كل رسالة والاخري بالثانية
        'contact' => '966xxxxxxxxx', // جهة الاتصال المرسلة
        "organization" => "testOrganization" // ( اختياري ),
        "name" => "test name" // اسم جهة الاتصال
    ];
    $whatsObj->sendBulkContact($data);