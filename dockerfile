# Imagen base con PHP 8.2 y Apache
FROM php:8.2-apache

# Instalar dependencias del sistema necesarias
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
    libxml2-dev zip nodejs npm \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Habilitar mod_rewrite en Apache
RUN a2enmod rewrite

# Configurar el DocumentRoot para Laravel (public)
WORKDIR /var/www/html

# Copiar archivos de Laravel
COPY . .

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Fix: permitir Composer como root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias PHP de Laravel
RUN composer install --optimize-autoloader --no-dev

# Generar key de Laravel (si no existe .env, crearlo antes en Render o local)
RUN if [ ! -f .env ]; then cp .env.example .env; fi \
    && php artisan key:generate --force

# Compilar frontend con npm
RUN npm install && npm run build

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuración de Apache para Laravel
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

EXPOSE 80
CMD ["apache2-foreground"]
