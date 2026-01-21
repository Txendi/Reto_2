# Imagen base del contenedor
FROM php:8.2-apache

# Instalar extensiones de PHP para MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

#Añadir el contenido de TSTSWEB

COPY ../TSTWEB/*.html /var/www/html
COPY ../TSTWEB/*.css /var/www/html
COPY ../TSTWEB/*.js /var/www/html

COPY ../TSTWEB/*.xsd /var/www/html
COPY ../TSTWEB/*.xsl /var/www/html
COPY ../TSTWEB/*.php /var/www/html
COPY ../TSTWEB/*.vue /var/www/html