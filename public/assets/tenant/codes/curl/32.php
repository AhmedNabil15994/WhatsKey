curl \
-X POST 'https://whatskey.net/engine/messages/disappearingBulk' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phones" : ["966xxxxxxxxx","966xxxxxxxxx"],
    "interval" : 3,
    "body": "test Disappearing reply",
    "expiration": 3600
}'