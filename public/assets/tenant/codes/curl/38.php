curl \
-X POST 'https://whatskey.net/engine/messages/pollBulk' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phones" : ["966xxxxxxxxx","966xxxxxxxxx"],
    "interval" : 3,
    "body": "how are you ?!",
    "options": ["Good","Not Good","Not","Fine","Not Fine"],
}'