# =============================================================================
# Stage 1: Node.js — Vite build (segundos, sem PHP)
# =============================================================================
FROM node:20-slim AS node-builder

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# =============================================================================
# Stage 2: Composer — PHP deps (usa imagem pre-compilada)
# =============================================================================
FROM composer:2 AS composer-builder

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# =============================================================================
# Stage 3: Production — Alpine + PHP 8.4 pre-compilado (segundos!)
# =============================================================================
FROM alpine:3.21

# PHP 8.4 + extensões como pacotes Alpine (sem compilação!)
RUN apk add --no-cache \
    php84 \
    php84-fpm \
    php84-pdo \
    php84-pdo_mysql \
    php84-mbstring \
    php84-bcmath \
    php84-gd \
    php84-zip \
    php84-intl \
    php84-pcntl \
    php84-opcache \
    php84-dom \
    php84-xml \
    php84-xmlreader \
    php84-xmlwriter \
    php84-tokenizer \
    php84-curl \
    php84-ctype \
    php84-openssl \
    php84-session \
    php84-fileinfo \
    php84-simplexml \
    php84-sodium \
    nginx \
    supervisor \
    && ln -sf /usr/bin/php84 /usr/local/bin/php \
    && ln -sf /usr/sbin/php-fpm84 /usr/local/sbin/php-fpm84

WORKDIR /var/www/html

COPY . .
COPY --from=composer-builder /app/vendor ./vendor
COPY --from=node-builder /app/public/build ./public/build

RUN mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache \
    && chown -R nginx:nginx storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php-fpm.conf /etc/php84/php-fpm.d/www.conf
COPY docker/php.ini /etc/php84/conf.d/99-app.ini
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh \
    && rm -f /etc/nginx/http.d/default.conf.disabled 2>/dev/null || true

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
