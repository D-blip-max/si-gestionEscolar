FROM php:8.2-fpm

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
    nginx \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_pgsql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app

# Copiar dependencias primero (cache de Docker)
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction
RUN npm ci

# Copiar toda la app
COPY . .

# Build de assets Vite
RUN npm run build

# Dump autoload con la app completa
RUN composer dump-autoload --optimize

# Permisos
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Configuración de Nginx
RUN echo 'server { \n\
    listen 8080; \n\
    root /app/public; \n\
    index index.php; \n\
    location / { \n\
        try_files $uri $uri/ /index.php?$query_string; \n\
    } \n\
    location ~ \.php$ { \n\
        fastcgi_pass 127.0.0.1:9000; \n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \n\
        include fastcgi_params; \n\
    } \n\
    location ~* \.(css|js|png|jpg|ico|woff2?)$ { \n\
        expires max; \n\
        add_header Cache-Control "public"; \n\
    } \n\
}' > /etc/nginx/sites-available/default

CMD sh -c "php artisan config:clear && \
    php artisan migrate --force && \
    php artisan storage:link && \
    php artisan route:cache && \
    php artisan view:cache && \
    php-fpm -D && \
    nginx -g 'daemon off;'"