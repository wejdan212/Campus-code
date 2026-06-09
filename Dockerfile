FROM php:8.2-apache

RUN a2dismod mpm_event mpm_worker mpm_prefork && \
    a2enmod mpm_prefork && \
    a2enmod rewrite && \
    docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html

EXPOSE 80
