<?php 

    include 'MainWhatsKey.php';
    $instanceId = "xxxxxx"; // رقم القناة
    $instanceToken = "xxxxxxxxxxxxxxxxxxxxxxxxxx"; // رمز المصادقة ( Token )
    $whatsObj = new MainWhatsKey($instanceId, $instanceToken);
    $data = [
        'phone' => '966xxxxxxxxx', // هاتف المستقبل
        'title' => 'hello',
        'body' => 'اهلا بك فى واتس كي تجربة ارسال قائمة',
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
    ];
    $whatsObj->sendList($data);