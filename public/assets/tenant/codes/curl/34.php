curl \
-X POST 'https://whatskey.net/engine/messages/buttonsBulk' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phones" : ["966xxxxxxxxx","966xxxxxxxxx"],
    "interval" : 3,
    "body": "Hello Body",
    "footer": "Bye Bye",
    "//image": "https://d27jswm5an3efw.cloudfront.net/app/uploads/2019/07/how-to-make-a-url-for-a-picture-on-your-computer-4.jpg",
    "buttons": [
        {
            "id": 1,
            "title": "Button 1 Text"
        },
        {
            "id": 2,
            "title": "Button 2 Text"
        }
    ]
}'