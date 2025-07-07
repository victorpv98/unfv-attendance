#!/bin/bash

PORT=${PORT:-8080}
echo "Configurando Apache para puerto $PORT"

echo "Listen $PORT" > /etc/apache2/ports.conf

cat > /etc/apache2/sites-available/000-default.conf << EOF
<VirtualHost *:$PORT>
    ServerName unfv-attendance-production.up.railway.app
    ServerAlias localhost
    DocumentRoot /var/www/html/public
    
    <Directory /var/www/html/public>
        DirectoryIndex index.php index.html
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks
    </Directory>
    
    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>
    
    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

echo "ServerName unfv-attendance-production.up.railway.app" >> /etc/apache2/apache2.conf

if [ ! -d "/var/www/html/public" ]; then
    echo "ERROR: Directorio /var/www/html/public no existe"
    exit 1
fi

if [ ! -f "/var/www/html/public/index.php" ]; then
    echo "ERROR: Archivo /var/www/html/public/index.php no existe"
    exit 1
fi

# Debug de variables de BD
echo "=== DEBUG BASE DE DATOS ==="
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"
echo "DB_PASSWORD: ${DB_PASSWORD:0:5}..."

echo "Ejecutando optimizaciones..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Test de conexión ANTES de migraciones
echo "=== PROBANDO CONEXIÓN A BD ==="
php artisan tinker --execute="
try {
    \$pdo = DB::connection()->getPdo();
    echo 'Conexión exitosa!' . PHP_EOL;
    echo 'Base de datos: ' . \$pdo->query('SELECT current_database()')->fetchColumn() . PHP_EOL;
} catch(Exception \$e) {
    echo 'Error de conexión: ' . \$e->getMessage() . PHP_EOL;
    exit(1);
}
"

echo "=== EJECUTANDO MIGRACIONES ==="
if php artisan migrate --force; then
    echo "✅ Migraciones ejecutadas exitosamente"
else
    echo "❌ Error en las migraciones - continuando sin seeders"
fi

# Solo ejecutar seeders si las migraciones funcionaron
echo "=== SALTANDO SEEDERS (OPCIONAL) ==="
echo "⚠️ Seeders deshabilitados temporalmente"

echo "Configuración de Apache:"
echo "Puerto: $PORT"
echo "DocumentRoot: /var/www/html/public"

echo "Iniciando servidor Apache en puerto $PORT..."
apache2-foreground