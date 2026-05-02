FROM node:22-bookworm-slim AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./

RUN npm run build

FROM php:8.2-cli-bookworm

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring intl zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --no-scripts

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

RUN php artisan package:discover --ansi

EXPOSE 8080

CMD ["sh", "-lc", "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
