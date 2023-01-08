<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'url' => 'https://d27jswm5an3efw.cloudfront.net/app/uploads/2019/07/how-to-make-a-url-for-a-picture-on-your-computer-4.jpg', // رابط الصورة
        'caption' => 'hello', // محتوي الرسالة ( اختياري )
    ];
    $whatsObj->sendImage($data);