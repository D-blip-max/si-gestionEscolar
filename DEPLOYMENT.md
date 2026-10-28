# Guía Completa de Despliegue en Producción - SisLaravel6 CEI

Esta guía detalla los pasos para desplegar el **Sistema de Gestión Académica CEI (Centro de Extensión de Idiomas)** en entornos de producción, ya sea usando contenedores Docker (Railway, Render, Cloud Run, VPS) o en un servidor dedicado VPS / Cloud (Ubuntu con Nginx y PHP-FPM).

---

## 1. Variables de Entorno Críticas (.env)

En producción, asegúrate de configurar las siguientes variables de entorno:

```env
APP_NAME="Instituto CEI"
APP_ENV=production
APP_KEY=base64:... (generado con php artisan key:generate)
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# Forzar HTTPS en assets y redirecciones (si se usa SSL reverse proxy)
FORCE_HTTPS=true

# Conexión a la base de datos (MySQL 8.0 o MariaDB)
DB_CONNECTION=mysql
DB_HOST=tu-host-mysql.com
DB_PORT=3306
DB_DATABASE=nombre_de_tu_bd
DB_USERNAME=usuario_bd
DB_PASSWORD=contraseña_segura

# Almacenamiento y Sesiones
FILESYSTEM_DISK=public
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=sync

# Configuración de Correo SMTP (para notificaciones y recuperación)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_correo@gmail.com
MAIL_PASSWORD=tu_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=contacto@institutocei.edu
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 2. Opción A: Despliegue con Docker / Railway / Render (Recomendado)

El repositorio incluye un [`Dockerfile`](file:///c:/wamp64/www/SisLaravel6/Dockerfile), [`.dockerignore`](file:///c:/wamp64/www/SisLaravel6/.dockerignore), [`nginx.conf`](file:///c:/wamp64/www/SisLaravel6/nginx.conf) y [`docker-entrypoint.sh`](file:///c:/wamp64/www/SisLaravel6/docker-entrypoint.sh) listos para producción.

### Pasos en Railway:
1. Conecta tu repositorio de GitHub en el panel de Railway.
2. Agrega un servicio de **MySQL Database** en el proyecto de Railway.
3. En el servicio web de la aplicación (Web Service):
   - Ve a **Variables** y agrega las variables del entorno (`APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, etc.).
   - Vincula las variables de la base de datos de Railway:
     - `DB_HOST`: `${{MySQL.MYSQLHOST}}`
     - `DB_PORT`: `${{MySQL.MYSQLPORT}}`
     - `DB_DATABASE`: `${{MySQL.MYSQLDATABASE}}`
     - `DB_USERNAME`: `${{MySQL.MYSQLUSER}}`
     - `DB_PASSWORD`: `${{MySQL.MYSQLPASSWORD}}`
4. Al hacer **Deploy**, el contenedor ejecutará automáticamente:
   - Compilación de recursos CSS/JS con Vite (`npm run build`).
   - Detección automática del puerto asignado por la plataforma (`$PORT`).
   - Ejecución de migraciones pendientes (`php artisan migrate --force`).
   - Sembrado idempotente de roles CEI, permisos y catálogos (`php artisan db:seed --force`).
   - Enlace simbólico de almacenamiento (`php artisan storage:link`).
   - Caché de rutas y plantillas Blade (`php artisan route:cache`, `php artisan view:cache`).

---

## 3. Opción B: Despliegue en Servidor VPS (Ubuntu 22.04 / 24.04 LTS)

### Requisitos del Servidor:
- PHP 8.2 o superior (`php8.2-fpm`, `php8.2-mysql`, `php8.2-gd`, `php8.2-zip`, `php8.2-mbstring`, `php8.2-xml`, `php8.2-curl`).
- Servidor Web Nginx.
- MySQL 8.0 o MariaDB 10.6+.
- Composer y Node.js 20+.
- Certbot para SSL gratuito Let's Encrypt.

### Comandos de Instalación:

```bash
# 1. Clonar el repositorio
cd /var/www
git clone https://github.com/tu-usuario/SisLaravel6.git cei-app
cd /var/www/cei-app

# 2. Instalar dependencias de PHP y compilar Frontend
composer install --optimize-autoloader --no-dev
npm ci
npm run build

# 3. Configurar archivo de entorno
cp .env.example .env
php artisan key:generate

# Edita .env con tus credenciales de base de datos
nano .env

# 4. Migraciones y Datos Iniciales
php artisan migrate --force
php artisan db:seed --force

# 5. Permisos de carpetas y Symlink de Storage
php artisan storage:link
sudo chown -R www-data:www-data /var/www/cei-app/storage /var/www/cei-app/bootstrap/cache
sudo chmod -R 775 /var/www/cei-app/storage /var/www/cei-app/bootstrap/cache

# 6. Optimizar Laravel para Producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Configuración de Nginx (`/etc/nginx/sites-available/cei.conf`):

```nginx
server {
    listen 80;
    server_name tu-dominio.com www.tu-dominio.com;
    root /var/www/cei-app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;
    charset utf-8;

    client_max_body_size 25M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Habilita el sitio y obtén el certificado SSL:
```bash
sudo ln -s /etc/nginx/sites-available/cei.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
sudo certbot --nginx -d tu-dominio.com -d www.tu-dominio.com
```

---

## 4. Tareas Programadas y Queue Worker (Opcional para background tasks)

Agrega el cron de Laravel en el servidor para tareas automáticas:
```bash
crontab -e
```
Agrega la siguiente línea al final:
```cron
* * * * * cd /var/www/cei-app && php artisan schedule:run >> /dev/null 2>&1
```

---

## 5. Verificación Post-Despliegue

Una vez completado el despliegue, realiza la prueba de humo inicial:
1. Accede a `https://tu-dominio.com/login`.
2. Inicia sesión con las credenciales maestras:
   - **Correo:** `admin@admin.com`
   - **Contraseña:** `12345678`
3. Verifica la visibilidad de los 8 paquetes temáticos en el menú lateral izquierdo de AdminLTE.
4. Cambia la contraseña del administrador desde la vista de perfil / usuarios para asegurar el entorno.
