# PATH
Please follow all the steps below, it will take 7 commands to get the system running

p.s. it should be more convinient, hence added a TODO

## Building the project: 

First build:

`sudo docker-compose up -d --build`

SSH to php:

`docker exec -it php /bin/bash`

install dependencies:

`composer install`

## Configuring DB:

1) Create database: `php bin/console doctrine:database:create`

2) Migrate `php bin/console doctrine:migrations:migrate`

3) Add fixtures `php bin/console doctrine:fixtures:load`

## Configuring keypair for JWT

`php bin/console lexik:jwt:generate-keypair`

## Usage:

Host: `localhost`

Port: `8080`

Endpoints:

* POST /user/login (get jwt)
* POST /order/new (create a new order)
* POST /order/{id}/edit (edit an order)
* GET /order/all (list all orders of the jwt owner)
* GET /order/{id} (get specific order)



## TODO
1) Optimize model calls
2) Return meaningful error messages
3) Prepare swagger (latest versions of NelmioApiDocBundle has some problems with Symfony 4.4, older versions should be used)
4) Write unit tests
5) Add entrypoint to reduce instruction of this readme.
