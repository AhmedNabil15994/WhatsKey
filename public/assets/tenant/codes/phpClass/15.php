<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'body' => 'اهلا بك فى واتس كي تجربة ارسال قالب',
        'footer' => 'bye bye',
        "buttons"=> [
            [
                "id" => 1,
                "title" => "URL Button",
                "type" => 1, // زر برابط
                "extra_data" => "https:/whatskey.net/login"
            ],
            [
                "id" => 2,
                "title" => "CALL Button",
                "type" => 2, // زر اتصال
                "extra_data" => "+966xxxxxxxxx"
            ],
            [
                "id" => 3,
                "title" => "NORMAL Button",
                "type" => 3, // زر عادي
                "extra_data" => ""
            ],
        ],
    ];
    $whatsObj->sendTemplates($data);