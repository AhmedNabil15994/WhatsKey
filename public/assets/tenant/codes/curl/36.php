curl \
-X POST 'https://whatskey.net/engine/messages/listBulk' \
-H 'CHANNELID: xxxxx' -H 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxx' -H 'Content-Type: application/json' \
--data-raw '{
    "phones" : ["966xxxxxxxxx","966xxxxxxxxx"],
    "interval" : 3,
    "body": "This is Body",
    "footer": "Bye Bye",
    "title": "Hello Title",
    "buttonText": "Required, text on the button to view the list",
    "sections": [
        {
            "title": "Section 1 Text",
            "rows": [
                {
                    "title": "Option 1",
                    "rowId": "option1",
                    "description": "This is description"
                },
                {
                    "title": "Option 2",
                    "rowId": "option2"
                }
            ]
        },
        {
            "title": "Section 2 Text",
            "rows": [
                {
                    "title": "Option 3",
                    "rowId": "option3"
                },
                {
                    "title": "Option 4",
                    "rowId": "option4",
                    "description": "This is description"
                }
            ]
        }
    ]
}'