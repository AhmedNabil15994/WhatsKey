curl \
-X POST 'https://whatskey.net/engine/messages/locationBulk' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phones" : ["966xxxxxxxxx","966xxxxxxxxx"],
    "interval" : 3,
    "address": "Here",
    "latitude": 24.121231,
    "longitude":55.1121221
}'