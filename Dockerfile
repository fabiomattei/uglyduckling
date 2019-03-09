FROM php:7.3.3-apache

LABEL maintainer="Fabio Mattei"
# COPY index.php /var/www/html
COPY docker/php/php.ini /usr/local/etc/php/
COPY . /srv/app
COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev libzip-dev
RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite negotiation \
