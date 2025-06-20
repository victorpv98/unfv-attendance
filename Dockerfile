# Usar PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache
RUN a2enmod rewrite
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de PHP
RUN composer install --optimize-autoloader --no-interaction

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Crear script de inicio mejorado
RUN echo '#!/bin/bash\n\
echo "Starting Apache in background..."\n\
apache2ctl start\n\
\n\
echo "Waiting for database (max 60 seconds)..."\n\
count=0\n\
until php artisan migrate:status > /dev/null 2>&1 || [ $count -eq 12 ]; do\n\
    echo "Database not ready, waiting... ($count/12)"\n\
    sleep 5\n\
    count=$((count + 1))\n\
done\n\
\n\
if [ $count -eq 12 ]; then\n\
    echo "Database timeout - starting Apache anyway"\n\
    apache2-foreground\n\
    exit 0\n\
fi\n\
\n\
echo "Database ready! Running setup..."\n\
php artisan migrate --force || echo "Migration failed"\n\
php artisan session:table --force 2>/dev/null || echo "Session table exists"\n\
php artisan queue:table --force 2>/dev/null || echo "Queue table exists"\n\
php artisan cache:table --force 2>/dev/null || echo "Cache table exists"\n\
php artisan migrate --force || echo "Additional migrations failed"\n\
\n\
echo "Optimizing application..."\n\
php artisan config:cache || echo "Config cache failed"\n\
php artisan route:cache || echo "Route cache failed"\n\
php artisan view:cache || echo "View cache failed"\n\
\n\
echo "Restarting Apache..."\n\
apache2ctl stop\n\
apache2-foreground' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Exponer puerto
EXPOSE 80

# Comando de inicio
CMD ["/usr/local/bin/start.sh"]