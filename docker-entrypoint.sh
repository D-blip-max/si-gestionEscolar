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

# 3. Esperar disponibilidad de base de datos y ejecutar migraciones en un solo paso
echo "==> Ejecutando migraciones de base de datos..."
for i in $(seq 1 15); do
    if php artisan migrate --force; then
        echo "==> Migraciones completadas exitosamente."
        break
    fi
    echo "Esperando conexión a la base de datos ($i/15)..."
    sleep 1
done

# 4. Sincronizar catálogo CEI, roles y seeder
echo "==> Sincronizando catálogo CEI, roles y datos del semestre..."
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
