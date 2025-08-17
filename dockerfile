FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar directorio de la app
WORKDIR /var/www/html

# Copiar composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar archivos de Laravel
COPY . .

# Permitir Composer como root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias PHP
RUN composer install --optimize-autoloader --no-dev

# Generar clave de Laravel
RUN php artisan key:generate --force || true

# Instalar dependencias frontend
RUN npm install && npm run build

# Dar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto
EXPOSE 80

CMD ["apache2-foreground"]
