<?php 

    $curl = curl_init();
    // رابط التوجيه لارسال الرسائل
    $url = 'https://whatskey.net/engine/messages/sendMention';

    $headers = array(
        'CHANNELID: xxxxxx',  // رقم القناة
        'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx',  // رمز المصادقة ( Token )
    );

    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'mention' => '966xxxxxxxxx', // الرقم المشار اليه
    ];

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