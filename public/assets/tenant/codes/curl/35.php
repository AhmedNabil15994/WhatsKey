curl \
-X POST 'https://whatskey.net/engine/messages/templateBulk' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phones" : ["966xxxxxxxxx"],
    "interval" : 3,
    "body": "Hello Body",
    "footer": "Bye Bye",
    "//image": "https://d27jswm5an3efw.cloudfront.net/app/uploads/2019/07/how-to-make-a-url-for-a-picture-on-your-computer-4.jpg",
    "buttons": [
        {
            "id": 1,
            "title": "⭐ Star Baileys on GitHub!",
            "type": 1,
            "extra_data": "url"
        },
        {
            "id": 2,
            "title": "Call me Big Boss!",
            "type": 2,
            "extra_data": "+966xxxxxxxxx"
        },
        {
            "id": 3,
            "title": "This is a reply, just like normal buttons!",
            "type": 3,
            "extra_data": "id-like-buttons-message"
        }
    ]
}'