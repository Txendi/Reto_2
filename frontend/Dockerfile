# FROM httpd:2.4
# COPY public-html/ /usr/local/apache2/htdocs/

# Paso 1: Usa una imagen base de Node.js para construir la app
FROM node:20-alpine as build

# Paso 2: Establece el directorio de trabajo
WORKDIR /app

# Paso 3: Copia los archivos de configuración de npm
COPY package*.json ./

# Paso 4: Instala las dependencias
RUN npm install

# Paso 5: Copia el resto de los archivos del proyecto
COPY . .

# Paso 6: Ejecuta el build de la aplicación
RUN npm run build

# Paso 7: Usa una imagen de Apache para servir los archivos estáticos
FROM httpd:2.4

# Paso 8: Copia los archivos generados en el build (dist o build según el framework)
COPY --from=build /app/dist/ /usr/local/apache2/htdocs/

# Paso 9: Expón el puerto en el que se sirve la aplicación
EXPOSE 80
