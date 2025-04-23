API Project
=================

How to run the project?
1) in the root directory run `make up`
2) after everything is up, run `make composer-install`
3) then `make db-migrate`

Project runs on http://localhost:8888, database is posgresql and runs on port `5432`

In `resources` folder, there is Postman collection.

Import given collection into Postman and test.

Login request automatically saves token into collection
and the token is added to header for endpoints that require authorization.

Out of the box, there are 3 users in database. For each there is login example request.

To run tests, just use `make run-tests`
