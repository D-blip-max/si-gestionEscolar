#!/bin/sh
set -e

echo "==> Iniciando despliegue de SisLaravel6 CEI..."

# 1. Configurar puerto dinámico en Nginx (Railway / Cloud Run / VPS / Local)
PORT="${PORT:-8080}"
echo "==> Configurando Nginx en el puerto: $PORT"
sed -i "s/listen .*/listen $PORT;/g" /etc/nginx/sites-available/default

# 2. Asegurar directorios de almacenamiento y permisos
echo "==> Asegurando estructura de carpetas storage..."
mkdir -p storage/framework/sessions \
         storage/framework/views \
         storage/framework/cache \
         storage/logs \
         storage/app/public \
         bootstrap/cache

chmod -R 777 storage bootstrap/cache

# 3. Limpiar cachés anteriores
echo "==> Limpiando caché temporal..."
php artisan config:clear
php artisan cache:clear || true

# 4. Migración y Seeding de la base de datos
echo "==> Ejecutando migraciones de base de datos..."
php artisan migrate --force

echo "==> Sincronizando catálogo CEI, roles y administrador..."
php artisan db:seed --force

# 5. Generar symlink de almacenamiento si no existe
echo "==> Verificando storage:link..."
php artisan storage:link || true

# 6. Cachear configuración, rutas y vistas para máximo rendimiento en producción
echo "==> Optimizando para producción (config, rutas y vistas)..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Iniciar PHP-FPM y Nginx
echo "==> SisLaravel6 CEI listo y escuchando en puerto $PORT"
php-fpm -D
exec nginx -g "daemon off;"
