curl \
-X POST 'https://whatskey.net/engine/messages/textBulk' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phones" : ["966xxxxxxxxx","966xxxxxxxxx"],
    "interval" : 3,
    "messageData" : [
        {"body":"text reply message1"},
        {"body": "text reply message2"}
    ]
}'