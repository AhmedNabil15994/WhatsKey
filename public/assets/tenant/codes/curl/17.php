curl \
-X POST 'https://whatskey.net/engine/messages/sendGroupInvitation' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phone" : "966xxxxxxxxx", 
    "groupId" : "120363043746069702"
}'