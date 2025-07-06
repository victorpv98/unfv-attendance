#!/bin/bash

# Configurar puerto para Apache
if [ -n "$PORT" ]; then
    echo "Listen $PORT" > /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf
fi

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

# Iniciar Apache
echo "Iniciando servidor Apache en puerto $PORT..."
apache2-foreground
