#!/bin/bash

# Configurar puerto para Apache
PORT=${PORT:-8080}
echo "Configurando Apache para puerto $PORT"

# Crear configuración de puertos
echo "Listen $PORT" > /etc/apache2/ports.conf

# Crear configuración del virtual host con puerto dinámico
cat > /etc/apache2/sites-available/000-default.conf << EOF
<VirtualHost *:$PORT>
    ServerName localhost
    DocumentRoot /var/www/html/public
    
    <Directory /var/www/html/public>
        DirectoryIndex index.php index.html
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks
    </Directory>
    
    # Configuración para archivos PHP
    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>
    
    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# Verificar que el directorio public existe
if [ ! -d "/var/www/html/public" ]; then
    echo "ERROR: Directorio /var/www/html/public no existe"
    exit 1
fi

# Verificar que index.php existe
if [ ! -f "/var/www/html/public/index.php" ]; then
    echo "ERROR: Archivo /var/www/html/public/index.php no existe"
    exit 1
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

# Mostrar configuración para debug
echo "Configuración de Apache:"
echo "Puerto: $PORT"
echo "DocumentRoot: /var/www/html/public"

# Iniciar Apache
echo "Iniciando servidor Apache en puerto $PORT..."
apache2-foreground
