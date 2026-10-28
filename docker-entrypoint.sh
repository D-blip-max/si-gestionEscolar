#!/bin/sh
set -e

echo "==> Iniciando despliegue de SisLaravel6 CEI..."

# 1. Configurar puerto dinámico en Nginx (Railway / Cloud Run / VPS / Local)
PORT="${PORT:-8080}"
echo "==> Configurando Nginx en el puerto: $PORT"
sed -i "s/listen .*/listen $PORT;/g" /etc/nginx/sites-available/default

# 2. Asegurar directorios de almacenamiento y permisos
echo "==> Asegurando estructura de carpetas storage y uploads..."
mkdir -p storage/framework/sessions \
         storage/framework/views \
         storage/framework/cache \
         storage/logs \
         storage/app/public \
         bootstrap/cache \
         public/uploads/logos \
         public/uploads/fotos \
         public/uploads/formaciones

chmod -R 777 storage bootstrap/cache public/uploads

# 3. Limpiar cachés anteriores
echo "==> Limpiando caché temporal..."
php artisan config:clear
php artisan cache:clear || true

# 4. Esperar disponibilidad de base de datos antes de migrar
echo "==> Verificando conexión a la base de datos..."
for i in $(seq 1 30); do
    if php artisan migrate:status > /dev/null 2>&1; then
        echo "==> Conexión a la base de datos exitosa."
        break
    fi
    echo "Esperando a la base de datos ($i/30)..."
    sleep 2
done

# 5. Migración y Seeding de la base de datos
echo "==> Ejecutando migraciones de base de datos..."
php artisan migrate --force

echo "==> Sincronizando catálogo CEI, roles y administrador..."
php artisan db:seed --force

# 6. Generar symlink de almacenamiento si no existe
echo "==> Verificando storage:link..."
php artisan storage:link || true

# 7. Cachear configuración, rutas y vistas para máximo rendimiento en producción
echo "==> Optimizando para producción (config, rutas y vistas)..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Iniciar PHP-FPM y Nginx
echo "==> SisLaravel6 CEI listo y escuchando en puerto $PORT"
php-fpm -D
exec nginx -g "daemon off;"
