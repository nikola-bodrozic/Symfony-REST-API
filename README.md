Symfony REST API
================
## Install:

* `composer install`
* `php bin/console doctrine:schema:update --force` to create table user

## Features:

* POST endpoint `/login` get bearer token 
* performs CRUD on User entity
* GET endpoints are `/user`, `user/{id}`
* POST endpoint `/user` secured with bearer token
* PUT `/user/{id}` secured with bearer token
* DELETE `/user/{id}` secured with bearer token

## Testing:

* get bearer token `curl -X POST -H "Content-Type: application/x-www-form-urlencoded" -d 'name=test&password=123&csrf_token=abc' http://127.0.0.1:8000/login`
* add user `curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer e4d3" -d '{"name":"title1", "role":"role1"}' http://127.0.0.1:8000/user`
* list one user `curl -X GET http://127.0.0.1:8000/user/1`
* list users `curl -X GET http://127.0.0.1:8000/user`
* update user `curl -X PUT  -H "Authorization: Bearer e4d3" -H "Content-Type: application/json" -d '{"name":"other title1",  "role":"other role1"}' http://127.0.0.1:8000/user/1`
* delete user `curl -X DELETE  -H "Authorization: Bearer e4d3" http://127.0.0.1:8000/user/1`