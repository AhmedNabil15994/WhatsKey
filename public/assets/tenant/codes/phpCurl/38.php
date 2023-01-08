<?php 

    $curl = curl_init();
    // رابط التوجيه لارسال قائمة
    $url = 'https://whatskey.net/engine/messages/pollBulk';

    $headers = array(
        'CHANNELID: xxxxx',  // رقم القناة
        'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',  // رمز المصادقة ( Token )
    );

    $data = array(
        'phones' => ['966xxxxxxxxx','966xxxxxxxxx'], // هواتف المستقبلين
        'interval' => 3, // الفترة الزمنية بين كل رسالة والاخري بالثانية
        'body' => ' Poll Content ', // محتوي استطلاع الرأي
        'options' =>  ["Good","Not Good","Not","Fine","Not Fine"], // اختيارات استطلاع الرأي
    );

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
    ));

    // تقديم طلب POST
    $response = curl_exec($curl);

    curl_close($curl);