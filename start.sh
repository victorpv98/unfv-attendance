#!/bin/bash

echo "🚀 Iniciando UNFV Attendance..."

# Variables de entorno de Apache
export APACHE_RUN_USER=www-data
export APACHE_RUN_GROUP=www-data
export APACHE_LOG_DIR=/var/log/apache2
export APACHE_LOCK_DIR=/var/lock/apache2
export APACHE_PID_FILE=/var/run/apache2.pid

# Configurar puerto dinámico de Render
if [ -n "$PORT" ]; then
    echo "🔧 Configurando Apache para puerto $PORT..."
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf
fi

# Mostrar configuración para debug
echo "🔍 Variables de entorno:"
echo "APP_ENV: $APP_ENV"
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"
echo "Puerto Apache: $PORT"

# FORZAR configuración de base de datos si las variables no se aplican
if [ "$APP_ENV" = "production" ]; then
    echo "🔧 Aplicando configuración de producción..."
    
    # Crear archivo de configuración temporal
    cat > /tmp/database_config.php << 'EOF'
<?php
// Configuración forzada para producción
return [
    'default' => 'pgsql',
    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'dpg-d1ainiemcj7s73fj4ksg-a'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'unfv_attendance'),
            'username' => env('DB_USERNAME', 'unfv_user'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],
    ],
];
EOF

    # Backup del archivo original
    cp /var/www/html/config/database.php /var/www/html/config/database.php.backup
    
    # Aplicar configuración forzada
    cp /tmp/database_config.php /var/www/html/config/database.php
    
    echo "✅ Configuración de BD aplicada"
fi

# Limpiar cache de configuración
echo "🧹 Limpiando cache..."
php artisan config:clear

# Mostrar configuración final
echo "🔍 Configuración final de BD:"
php -r "
require '/var/www/html/vendor/autoload.php';
\$app = require_once '/var/www/html/bootstrap/app.php';
\$config = \$app->make('config');
echo 'DB_DEFAULT: ' . \$config->get('database.default') . PHP_EOL;
echo 'DB_HOST: ' . \$config->get('database.connections.pgsql.host') . PHP_EOL;
echo 'DB_DATABASE: ' . \$config->get('database.connections.pgsql.database') . PHP_EOL;
echo 'DB_USERNAME: ' . \$config->get('database.connections.pgsql.username') . PHP_EOL;
"

# Probar conexión directa
echo "🔌 Probando conexión directa a PostgreSQL..."
if timeout 10 pg_isready -h dpg-d1ainiemcj7s73fj4ksg-a -p 5432 -U unfv_user; then
    echo "✅ PostgreSQL responde en el host"
else
    echo "❌ PostgreSQL no responde directamente"
fi

# Esperar a PostgreSQL con timeout
echo "⏳ Esperando conexión Laravel a PostgreSQL..."
timeout=120
counter=0

while [ $counter -lt $timeout ]; do
    if php artisan migrate:status >/dev/null 2>&1; then
        echo "✅ Laravel conectado a PostgreSQL!"
        break
    fi
    
    if [ $((counter % 15)) -eq 0 ]; then
        echo "Esperando conexión Laravel... ($counter/$timeout segundos)"
    fi
    
    sleep 3
    counter=$((counter + 3))
done

if [ $counter -ge $timeout ]; then
    echo "❌ Error: Laravel no se pudo conectar a PostgreSQL"
    echo "🔍 Última verificación de configuración:"
    php artisan config:show database.default 2>/dev/null || echo "Error mostrando config"
    php artisan config:show database.connections.pgsql 2>/dev/null || echo "Error mostrando config pgsql"
    exit 1
fi

# Ejecutar migraciones
echo "📊 Ejecutando migraciones principales..."
if ! php artisan migrate --force; then
    echo "❌ Error ejecutando migraciones principales"
    exit 1
fi

# Crear tablas adicionales
echo "🔧 Creando tablas del sistema..."
php artisan session:table --force 2>/dev/null && echo "✓ Migración de sessions creada"
php artisan queue:table --force 2>/dev/null && echo "✓ Migración de jobs creada"  
php artisan cache:table --force 2>/dev/null && echo "✓ Migración de cache creada"

# Ejecutar migraciones adicionales
echo "📊 Ejecutando migraciones adicionales..."
if ! php artisan migrate --force; then
    echo "⚠️ Advertencia: Error en migraciones adicionales, continuando..."
fi

# Optimizar para producción
echo "⚡ Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Configurar permisos finales
echo "🔐 Configurando permisos..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "🎉 UNFV Attendance iniciado correctamente en puerto $PORT!"
echo "🌐 La aplicación debería estar disponible en breve..."

# Iniciar Apache
exec apache2-foreground