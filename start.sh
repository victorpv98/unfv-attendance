#!/bin/bash

# Ejecutar optimizaciones después de que las variables de entorno estén disponibles
echo "Ejecutando optimizaciones..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders si es necesario
echo "Ejecutando seeders..."
php artisan db:seed --force

# Iniciar el servidor
echo "Iniciando servidor..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}