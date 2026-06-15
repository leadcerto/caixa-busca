#!/bin/sh
set -e

cd /var/www/html

php artisan storage:link --force 2>/dev/null || true

# Enfileira geração de conteúdo IA para bairros pendentes (sem --force = só processa quem ainda não tem)
php artisan app:gerar-conteudo-bairro 2>/dev/null || true

exec /usr/bin/supervisord -c /etc/supervisord.conf
