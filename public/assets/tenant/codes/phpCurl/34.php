<?php 

    $curl = curl_init();
    // رابط التوجيه لارسال ازرار تفاعلية
    $url = 'https://whatskey.net/engine/messages/buttonsBulk';

    $headers = array(
        'CHANNELID: xxxxx',  // رقم القناة
        'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',  // رمز المصادقة ( Token )
    );

    $data = array(
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