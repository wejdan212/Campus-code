FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && a2dismod mpm_worker \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

COPY . /var/www/html

EXPOSE 80
