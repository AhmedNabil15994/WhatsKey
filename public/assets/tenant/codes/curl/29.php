curl \
-X POST 'https://whatskey.net/engine/messages/linkBulk' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phones" : ["966xxxxxxxxx","966xxxxxxxxx"],
    "interval" : 3,
    "description": "text then google url",
    "title": "google",
    "url": "https://www.google.com/"
}'