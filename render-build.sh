#!/usr/bin/env bash
set -o errexit

# Instalar dependencias
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Generar key de Laravel
php artisan key:generate

# Migraciones
php artisan migrate --force
