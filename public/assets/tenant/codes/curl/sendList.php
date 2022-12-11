curl --location --request POST 'https://whatskey.net/engine/messages/sendList' --header 'CHANNELID: xxxxx' --header 'CHANNELTOKEN: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' --data-raw '{
    "phone": "966xxxxxxxxx",
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