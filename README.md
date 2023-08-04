# Requirements
 - Docker
 - enter `docker` Directory 
 - copy file `.env` from `.env.dist` it should be also located in `docker` directory
 - in your console run  `id $USER` to get your user **PUID** and **GUID**
 - update   **PUID** and **GUID** in .env file
# Running

- to start application run command 
`make up` it will build and start docker container
- enter container using command `make app_bash`
- run script `php add.php input.txt`
- to run tests enter `vendor/bin/phpunit` 