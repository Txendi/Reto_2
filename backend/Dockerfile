FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN a2enmod rewrite headers

COPY .htaccess /var/www/html
COPY *.php /var/www/html

