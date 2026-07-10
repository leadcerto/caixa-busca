#!/bin/sh
set -e

cd /var/www/html

mkdir -p storage/app/public storage/app/private
chown -R nginx:nginx storage
chmod -R 775 storage

php artisan storage:link --force 2>/dev/null || true

php artisan migrate --force 2>/dev/null || true

php artisan config:cache 2>/dev/null || true
php artisan route:cache  2>/dev/null || true
php artisan view:cache   2>/dev/null || true

# Enfileira geração de conteúdo IA para bairros pendentes (sem --force = só processa quem ainda não tem)
php artisan app:gerar-conteudo-bairro 2>/dev/null || true

exec /usr/bin/supervisord -c /etc/supervisord.conf
