curl \
-X POST 'https://whatskey.net/engine/messages/sendDisappearingMessage' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
-d 'phone=966xxxxxxxxx' -d 'body=Hello there!' -d 'expiration=3600'