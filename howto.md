

## docker commands

To build:
* docker-compose up --build

To stop:
* docker-compose stop

To stop everything:
* docker-compose down

To run:
* docker-compose up -d

To run a single service: (in /var/apps)
* docker-compose up -d db
* docker-compose up -d phpmyadmin
* docker-compose up -d server

To restart I can do:
* docker-compose stop
* docker-compose up -d

If first restart does not work, I can do: 
* docker-compose down
* docker-compose up -d

## phan commands

./vendor/bin/phan --allow-polyfill-parser

https://github.com/phan/phan/wiki/Issue-Types-Caught-by-Phan
https://github.com/phan/phan/wiki/Annotating-Your-Source-Code#property

## phpunit commands

./vendor/bin/phpunit
