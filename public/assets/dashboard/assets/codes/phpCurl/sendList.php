<?php 

    $curl = curl_init();
    // رابط التوجيه لارسال قائمة
    $url = 'https://wloop.net/engine/messages/sendList';

    $headers = array(
        'CHANNELID: xxxxx',  // رقم القناة
        'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',  // رمز المصادقة ( Token )
    );

    $data = array(
        'phone' => '966xxxxxxxxx',
        'title' => 'hello',
        'body' => 'اهلا بك فى واتس لوب تجربة ارسال قائمة',
        'footer' => 'bye bye',
        "buttonText" => "اجباري, النص علي الزر لعرض القائمة"
        "sections"=> [
            [
                "title" => "Section 1 Text",
                "rows" => [
                    [
                        "title" => "Option 1",
                        "rowId" => "option1",
                        "description" => "This is description"
                    ],
                    [
                        "title" => "Option 2",
                        "rowId" => "option2"
                    ],
                ],
            ],
            [
                "title" => "Section 2 Text",
                "rows" => [
                    [
                        "title" => "Option 3",
                        "rowId" => "option3"
                    ],
                    [
                        "title" => "Option 4",
                        "rowId" => "option4",
                        "description" => "This is description"
                    ],
                ],
            ],
        ],
    );

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => $headers,
    ));

    // تقديم طلب POST
    $response = curl_exec($curl);

    curl_close($curl);