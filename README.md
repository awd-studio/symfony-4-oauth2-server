# Symfony 4 Boilerplate with OAuth2 server



## Install

Set up database in .env (MySQL is supposed to be used)

```sh
# Install dependencies
composer install

# Install and initialize keys, migrations, etc.
make init

# Generate a client
php bin/console app:client:add 'Client name' 'Client secret'
# Or, use fixtures
make fixtures
```

## Usage

Start the server: 
```bash
php bin/console server:start 127.0.0.1:8080
```

#### Get access token (example): 

Use the swagger UI (at http://127.0.0.1:8080/api/doc), or do a request manually:
```
POST http://127.0.0.1:8080/api/v1/oauth/token
Accept: */*
Cache-Control: no-cache
Content-Type: application/x-www-form-urlencoded

grant_type=password&client_id=0c83fbec-179e-11e9-9065-0242f7b4a583&client_secret=test&scope=*&username=user@test.mail&password=user
```

It'll return a response (example):
```json
{
  "token_type": "Bearer",
  "expires_in": 3600,
  "access_token": "[ACCESS_TOKEN]",
  "refresh_token": "[REFRESH_TOKEN]"
}
```
