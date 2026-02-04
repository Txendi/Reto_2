FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN a2enmod rewrite headers

# 🔴 ESTA LÍNEA ES LA CLAVE
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf


COPY .htaccess /var/www/html
COPY *.php /var/www/html

