FROM php:8.2-apache
RUN docker-php-ext-install mysqli
RUN a2dismod mpm_event mpm_worker && a2enmod mpm_prefork
COPY . /var/www/html/
FROM php:8.2-cli

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /app

WORKDIR /app

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080"]
