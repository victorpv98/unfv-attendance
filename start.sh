#!/bin/bash

echo "🚀 Iniciando UNFV Attendance..."

# Esperar a que PostgreSQL esté disponible
echo "⏳ Esperando conexión a PostgreSQL..."
until php artisan migrate:status >/dev/null 2>&1; do
  echo "Esperando PostgreSQL..."
  sleep 5
done

echo "✅ PostgreSQL conectado!"

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

# Crear tablas adicionales si no existen
echo "🔧 Verificando tablas del sistema..."

# Verificar si existen las tablas, si no las crea
php artisan session:table 2>/dev/null || echo "Tabla sessions ya existe"
php artisan queue:table 2>/dev/null || echo "Tabla jobs ya existe"  
php artisan cache:table 2>/dev/null || echo "Tabla cache ya existe"

# Ejecutar migraciones de las nuevas tablas
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

echo "🎉 UNFV Attendance iniciado correctamente!"

# Iniciar Apache
exec apache2-foreground