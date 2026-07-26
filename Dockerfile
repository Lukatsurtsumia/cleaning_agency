# syntax=docker/dockerfile:1
#
# Production image for Coolify (Hetzner). Multi-stage:
#   1. composer  -> PHP dependencies (no dev)
#   2. node      -> compiled Vite/Tailwind assets
#   3. runtime   -> serversideup nginx + php-fpm, serving public/ on :8080
#
# Boot-time Laravel tasks (migrate, storage:link, config/route/view cache) are
# handled by serversideup's autorun — set these env vars in Coolify:
#   AUTORUN_ENABLED=true
#   AUTORUN_LARAVEL_MIGRATION=true
#   AUTORUN_LARAVEL_STORAGE_LINK=true
#   AUTORUN_LARAVEL_OPTIMIZE=true
# See DEPLOY.md for the full Coolify checklist.

# ---------- 1) PHP dependencies ----------
FROM composer:2 AS vendor
WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress \
        --prefer-dist --no-scripts --optimize-autoloader

# ---------- 2) Front-end assets ----------
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY resources/ resources/
COPY public/ public/
COPY vite.config.js tailwind.config.js postcss.config.js ./
RUN npm run build

# ---------- 3) Runtime ----------
FROM serversideup/php:8.3-fpm-nginx
WORKDIR /var/www/html
USER www-data

# App source (respects .dockerignore), then deps and built assets on top.
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=vendor /app/vendor ./vendor
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build

# Rebuild the package manifest (composer install ran with --no-scripts).
RUN php artisan package:discover --ansi
