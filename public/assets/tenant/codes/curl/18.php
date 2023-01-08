curl \
-X POST 'https://whatskey.net/engine/messages/sendPoll' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phone": "966xxxxxxxxx",
    "body": "how are you ?!",
    "options": ["Good","Not Good","Not","Fine","Not Fine"],
}'