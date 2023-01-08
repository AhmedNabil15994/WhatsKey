<?php 

    $curl = curl_init();
    // رابط التوجيه لارسال قالب
    $url = 'https://whatskey.net/engine/messages/sendTemplates';

    $headers = array(
        'CHANNELID: xxxxx',  // رقم القناة
        'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',  // رمز المصادقة ( Token )
    );

    $data = array(
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