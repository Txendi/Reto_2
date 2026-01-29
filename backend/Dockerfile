FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

# Copiar todos los archivos PHP y el .htaccess
COPY *.php /var/www/html/
COPY .htaccess /var/www/html/

# Asegurar permisos correctos
RUN chown -R www-data:www-data /var/www/html