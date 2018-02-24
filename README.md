Symfony REST API
================

Features:
* performs CRUD on User entity
* GET endpoints are `/user`, `user/{id}`
* POST endpoint `/user`
* PUT `/user/{id}`
* DELETE `/user/{id}`

Testing:
* `php phpunit-6.4.phar`
or using cURL on empty user table
* `curl -X POST -H "Content-Type: application/json" -d '{"name":"title1", "role":"role1"}' http://127.0.0.1:8000/user`
* `curl -X GET http://127.0.0.1:8000/user/1`
* `curl -X GET http://127.0.0.1:8000/user`
* `curl -X PUT -H "Content-Type: application/json" -d '{"name":"other title1",  "role":"other role1"}' http://127.0.0.1:8000/user/1`
* `curl -X DELETE http://127.0.0.1:8000/user/1`