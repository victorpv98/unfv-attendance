#!/bin/bash

echo "🚀 Iniciando UNFV Attendance..."

# Variables de entorno de Render
export APACHE_RUN_USER=www-data
export APACHE_RUN_GROUP=www-data
export APACHE_LOG_DIR=/var/log/apache2
export APACHE_LOCK_DIR=/var/lock/apache2
export APACHE_PID_FILE=/var/run/apache2.pid

# Configurar puerto dinámico de Render
if [ -n "$PORT" ]; then
    echo "🔧 Configurando puerto $PORT para Render..."
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf
fi

# Verificar variables de entorno críticas
echo "🔍 Verificando configuración..."
echo "DB_HOST: $DB_HOST"
echo "DB_DATABASE: $DB_DATABASE"
echo "APP_ENV: $APP_ENV"

# Esperar a que PostgreSQL esté disponible con timeout
echo "⏳ Esperando conexión a PostgreSQL..."
timeout=300  # 5 minutos
counter=0

until php artisan migrate:status >/dev/null 2>&1; do
  if [ $counter -ge $timeout ]; then
    echo "❌ Timeout: No se pudo conectar a PostgreSQL después de 5 minutos"
    echo "🔍 Verificando configuración de BD..."
    php artisan config:show database
    exit 1
  fi
  echo "Esperando PostgreSQL... ($counter/$timeout)"
  sleep 5
  counter=$((counter + 5))
done

echo "✅ PostgreSQL conectado!"

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

# Crear tablas adicionales si no existen
echo "🔧 Verificando tablas del sistema..."

# Crear migraciones para tablas adicionales
php artisan session:table --force 2>/dev/null && echo "Migración de sessions creada"
php artisan queue:table --force 2>/dev/null && echo "Migración de jobs creada"  
php artisan cache:table --force 2>/dev/null && echo "Migración de cache creada"

# Ejecutar todas las migraciones
php artisan migrate --force

# Optimizar para producción
echo "⚡ Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar permisos
echo "🔐 Configurando permisos..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "🎉 UNFV Attendance iniciado correctamente en puerto $PORT!"

# Iniciar Apache
exec apache2-foreground