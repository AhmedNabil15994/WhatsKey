<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phones' => ['966xxxxxxxxx','966xxxxxxxxx'], // هواتف المستقبلين
        'interval' => 3, // الفترة الزمنية بين كل رسالة والاخري بالثانية
        'title' => 'hello', // العنوان اجباري في حالة عدم وجود صورة,
        // "image" => "URL" // الصورة اجباري في حالة عدم وجود العنوان,
        'body' => 'اهلا بك فى واتس كي تجربة ارسال ازرار تفاعلية',
        'footer' => 'bye bye',
        'buttons' => [
            [
                'id': 1,
                'title': 'Option 1',
            ],
            [
                'id': 2,
                'title': 'Option 2',
            ],
            [
                'id': 3,
                'title': 'Option 3',
            ],
        ],
    ];
    $whatsObj->sendBulkButtons($data);