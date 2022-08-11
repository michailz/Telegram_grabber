FROM php:8.1.1-apache

RUN  apt-get update && apt-get install -y ca-certificates gnupg
RUN apt-get update && apt-get upgrade -y && apt-get install -y libpq-dev postgresql-client git
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pgsql pdo pdo_pgsql
RUN /usr/sbin/a2enmod rewrite && /usr/sbin/a2enmod headers && /usr/sbin/a2enmod expires
RUN pecl install xdebug && docker-php-ext-enable xdebug
