# Imagen base con PHP + Apache
FROM php:8.2-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git unzip libsqlite3-dev libpng-dev libonig-dev libxml2-dev \
    zip curl nodejs npm \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Configuración de Apache para permitir acceso
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Copiar proyecto a la carpeta de Apache
COPY . /var/www/html

# Dar permisos a Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependencias PHP de Laravel
RUN composer install --optimize-autoloader --no-dev

# Compilar frontend con npm
RUN npm install && npm run build

# Cachear configuración de Laravel
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Exponer puerto
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
