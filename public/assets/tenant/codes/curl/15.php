curl \
-X POST 'https://whatskey.net/engine/messages/sendTemplates' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phone" : "966xxxxxxxxx",
    "body" : "Hello Bodysad",
    "footer": "Bye Bye",
    "//image":"https://d27jswm5an3efw.cloudfront.net/app/uploads/2019/07/how-to-make-a-url-for-a-picture-on-your-computer-4.jpg",
    "buttons":[
        {
            "id":1,
            "title": "Button 1",
            "type": 1, 
            "extra_data" : "url"
        },
        {
            "id":2,
            "title": "Button 2",
            "type": 2,
            "extra_data" : "+966xxxxxxxx"
        },
        {
            "id":3,
            "title": "Button 3",
            "type": 3,
            "extra_data" : "id3"
        },
        {
            "id":4,
            "title": "Button 4",
            "type": 3,
            "extra_data" : "id4"
        },
        {
            "id":5,
            "title": "Button 5",
            "type": 3,
            "extra_data" : "id5"
        }
    ]
}'