# Imagen base de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_sqlite

# Copiar el proyecto a la carpeta pública de Apache
COPY . /var/www/html/

# Dar permisos a SQLite
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
