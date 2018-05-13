First Step Core module
======================

This is the set of classes that I use for every project.
I am trying to keep them all together in order to avoid confusion.




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

