Basic PHP Docker
===

A basic PHP/Docker/NGINX Image. The NGINX and MySql images are both trusted images and for the PHP container the php:7.3-fpm-alpine with some changes for composer and file permissions.

## Useful commands

* `docker-compose up -d `- Starts the containers in docker-compose. Visit site at localhost
* `docker-compose up -d --build` - Builds and starts containers.
* `docker-compose stop` - Stops running containers
* `docker-compose exec php sh` - Gives you an interactive shell for the PHP container.
* `docker-compose exec web sh` - Give you an interactive shell for the NGINX container.
* `docker-compose exec db sh` - Give you an interactive shell for the MySql container.