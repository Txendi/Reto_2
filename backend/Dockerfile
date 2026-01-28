# Imagen base del contenedor
FROM php:8.2-apache

# Instalar extensiones de PHP para MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite
#Añadir el contenido de TSTSWEB

COPY *.php /var/www/html
COPY .htacces /var/www/html