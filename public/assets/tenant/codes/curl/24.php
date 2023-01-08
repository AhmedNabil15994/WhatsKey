curl \
-X POST 'https://whatskey.net/engine/messages/videoBulk' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phones" : ["966xxxxxxxxx","966xxxxxxxxx"],
    "interval" : 60,
    "caption": "test reply ",
    "url": "video url"
}'