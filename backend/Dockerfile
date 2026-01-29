FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite
#Añadir el contenido de TSTSWEB

COPY *.php /var/www/html
COPY .htaccess /var/www/html
