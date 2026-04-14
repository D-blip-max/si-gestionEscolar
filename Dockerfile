FROM php:8.2-cli

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js 20 (La 22 es muy nueva y a veces da problemas con algunas distros, la 20 es LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app

# Copiar archivos de dependencias
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Instalar dependencias
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction
RUN npm ci

# Copiar el resto de la aplicación
COPY . .

# Build de assets
RUN npm run build

# Crear directorios y permisos
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# NOTA: No uses EXPOSE o úsalo solo como documentación, Railway ignora esto.

# Comando de inicio CORREGIDO
# Usamos "sh -c" para que pueda interpretar la variable $PORT de Railway
CMD sh -c "php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan route:cache && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
