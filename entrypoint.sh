#!/bin/sh

# Hacemos que cualquier error detenga la ejecución
set -e

echo "👉 Ejecutando migraciones..."
php artisan migrate --force

echo "👉 Limpiando cachés de Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "👉 Listo. Iniciando aplicación..."
exec "$@"
