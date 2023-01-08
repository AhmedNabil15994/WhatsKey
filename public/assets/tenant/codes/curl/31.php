curl \
-X POST 'https://whatskey.net/engine/messages/contactBulk' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phones" : ["966xxxxxxxxx","966xxxxxxxxx"],
    "interval" : 3,
    "name": "Ahmed Nabil",
    "contact": 966xxxxxxxxx,
    "organization":"Self"
}'