curl --location --request POST 'https://wloop.net/engine/messages/sendTemplates' --header 'CHANNELID: xxxxx' --header 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' --data-raw '{
    "phone": "966xxxxxxxxx",
    "title": "Hello Title",
    "body": "Hello Body",
    "footer": "Bye Bye",
    "//image": "Image URL" ,
    "buttPHP - cURLons": [
        {
            "id": 1,
            "title": "URL Button",
            "type": 1,
            "extra_data": "https://wloop.net/login"
        },
        {
            "id": 2,
            "title": "Call Button",
            "type": 2,
            "extra_data": "+966xxxxxxxxx"
        },
        {
            "id": 3,
            "title": "Normal Button",
            "type": 3,
            "extra_data": ""
        }
    ]
}'