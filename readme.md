# Email sender Api

## Installation 
1. `docker-compose up --build from ./`

2. `cd app/ && composer install`

## How to send email:

POST http://localhost:8080/email/create

```json
{
  "fromEmail": "xxxx@abv.bg",
  "fromName": "Smith",
  "subject": "test subject",
  "recipients": [
      {"email": "xxxx@gmail.com", "name": "John Doe"}
  ],
  "textPart": "Test Body Mail",
  "htmlPart": ""
}
```

##### To send an email via console run:

`./bin/console app:create-email --help`