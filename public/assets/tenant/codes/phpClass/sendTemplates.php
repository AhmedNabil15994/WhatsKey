<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx";
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx";
    $whatsLoopObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx',
        'title' => 'hello', // العنوان اجباري في حالة عدم وجود صورة
        'body' => 'اهلا بك فى واتس لوب تجربة ارسال قالب',
        'footer' => 'bye bye',
        //"image" => "URL" // العنوان اجباري في حالة عدم وجود العنوان,
        "buttons"=> [
            [
                "id" => 1,
                "title" => "URL Button",
                "type" => 1, // زر برابط
                "extra_data" => "https:/wloop.net/login" // URL inside Button
            ],
            [
                "id" => 2,
                "title" => "CALL Button",
                "type" => 2, // زر اتصال
                "extra_data" => "+966xxxxxxxxx" // Phone Inside Button
            ],
            [
                "id" => 3,
                "title" => "NORMAL Button",
                "type" => 3, // زر عادي
                "extra_data" => ""
            ],
        ],
    ];
    $whatsLoopObj->sendTemplates($data);