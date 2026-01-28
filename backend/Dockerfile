# Imagen base del contenedor
FROM php:8.2-apache

# Instalar extensiones de PHP para MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

#Añadir el contenido de TSTSWEB

COPY *.php /var/www/html