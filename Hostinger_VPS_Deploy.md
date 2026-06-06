# Guia de Deploy — Hostinger VPS + Coolify (Laravel + Vite)

> Lições aprendidas ao deployar `caixa-busca` no VPS `148.230.74.237` em junho/2026.
> Use como template para novos projetos Laravel no mesmo servidor.

---

## Stack testada e aprovada

- PHP 8.4 via Alpine 3.21 (pacotes `apk`, sem compilação)
- Laravel 13 + Livewire 4 + Tailwind CSS v4 + Vite 8
- Coolify v4.1.2 — Build Pack: **Dockerfile**
- MySQL 8.4 como serviço separado no Coolify
- Nginx + PHP-FPM + Supervisor dentro do mesmo container

---

## 1. Estrutura de arquivos obrigatória no projeto

```
projeto/
├── Dockerfile
└── docker/
    ├── nginx.conf
    ├── php-fpm.conf
    ├── php.ini
    ├── supervisord.conf
    └── entrypoint.sh
```

---

## 2. Dockerfile (3 estágios — Alpine)

**Por que 3 estágios e por que Alpine?**

- `docker-php-ext-install` (compilação de extensões) leva 8–15 min e causa timeout/OOM no Coolify
- Alpine instala PHP 8.4 com todas as extensões como pacotes pré-compilados em ~12 segundos
- Separar Node.js e Composer em estágios próprios evita conflito de dependências e memória

```dockerfile
# Stage 1: Node.js — Vite build (sem PHP)
FROM node:20-slim AS node-builder

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Composer — sem scripts para evitar bootstrap do Laravel no build
FROM composer:2 AS composer-builder

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Stage 3: Production — Alpine + PHP 8.4 pré-compilado
FROM alpine:3.21

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
    && ln -sf /usr/bin/php84 /usr/local/bin/php
    # ⚠️ NÃO tente criar symlink em /usr/local/sbin/ — esse diretório não existe no Alpine

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
RUN chmod +x /entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]
```

---

## 3. docker/nginx.conf

> ⚠️ No Alpine, o caminho é `/etc/nginx/http.d/` — NÃO `/etc/nginx/sites-available/`

```nginx
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php;
    charset utf-8;
    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # ⚠️ OBRIGATÓRIO: Livewire serve JS via PHP com hash no prefixo (/livewire-{hash}/)
    # Sem este bloco o nginx intercepta *.js e retorna 404, quebrando toda interatividade
    location ^~ /livewire {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~* \.(css|js|woff2?|ttf|eot|svg|png|jpg|jpeg|gif|ico)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }
}
```

---

## 4. docker/php-fpm.conf

> ⚠️ `clear_env = no` é CRÍTICO — sem ele o Laravel não recebe as variáveis de ambiente do Docker (APP_KEY, DB_*, etc.)
> ⚠️ User deve ser `nginx` no Alpine (não `www-data` como no Debian/Ubuntu)

```ini
[www]
user = nginx
group = nginx
listen = 127.0.0.1:9000
pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
clear_env = no
```

---

## 5. docker/php.ini

```ini
upload_max_filesize = 20M
post_max_size = 20M
max_execution_time = 120
memory_limit = 256M

opcache.enable = 1
opcache.revalidate_freq = 0
opcache.validate_timestamps = 0
opcache.max_accelerated_files = 10000
opcache.memory_consumption = 128
```

---

## 6. docker/supervisord.conf

> ⚠️ O binário do PHP-FPM no Alpine é `/usr/sbin/php-fpm84` (com versão no nome)
> ⚠️ A flag `-R` permite rodar como root quando necessário

```ini
[supervisord]
nodaemon=true
logfile=/dev/null
logfile_maxbytes=0
pidfile=/run/supervisord.pid

[program:php-fpm]
command=/usr/sbin/php-fpm84 -F -R
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
```

---

## 7. docker/entrypoint.sh

```bash
#!/bin/sh
set -e

cd /var/www/html

php artisan storage:link --force 2>/dev/null || true

exec /usr/bin/supervisord -c /etc/supervisord.conf
```

---

## 8. Configuração no Coolify

### Build Pack
Selecionar **"Dockerfile"** explicitamente em Configuration → General.
Nunca usar Nixpacks para PHP 8.4 — o nixpkgs do Coolify está pinado no PHP 8.3.

### Deploy Key SSH (GitHub privado)

1. No Coolify: **Keys & Tokens → New SSH Key** → gerar nova chave
2. Copiar a chave privada exibida
3. Extrair a chave pública correta com PowerShell (evita confusão de caracteres):

```powershell
# Salve a chave privada num arquivo temporário
$key = @'
-----BEGIN OPENSSH PRIVATE KEY-----
... (cole aqui)
-----END OPENSSH PRIVATE KEY-----
'@
$key | Out-File -FilePath "$env:TEMP\deploy_key" -Encoding ascii -NoNewline
ssh-keygen -y -f "$env:TEMP\deploy_key"
# Copie o output (ssh-ed25519 AAAA...) e adicione como Deploy Key no GitHub
Remove-Item "$env:TEMP\deploy_key"
```

> ⚠️ NUNCA copie a chave pública digitando manualmente — o base64 tem `l` (L minúsculo) e `I` (i maiúsculo) visualmente idênticos que causam "Permission denied (publickey)"

4. No GitHub: **Settings → Deploy Keys → Add deploy key** — cole o output do comando acima

### Variáveis de ambiente no Coolify

Configurar em **Configuration → Environment Variables**:

```env
APP_NAME="Nome do Projeto"
APP_ENV=production
APP_KEY=base64:GERAR_COM_php_artisan_key_generate
APP_DEBUG=false
APP_URL=https://seu-dominio.com.br

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=HOSTNAME_DO_CONTAINER_MYSQL_NO_COOLIFY
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha_segura

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

# APIs específicas do projeto
# OPENROUTER_API_KEY=
# CRM_WEBHOOK_URL=
# etc.
```

> ⚠️ O `DB_HOST` no Coolify é o **nome do container** do MySQL (ex: `k14ghasx9c82f3o63zlli6dp`), não `127.0.0.1` nem `localhost`. Encontre em **Services → MySQL → Container Name**.

> ⚠️ `APP_KEY` deve ser gerado com `php artisan key:generate --show` rodado localmente ou via Terminal do Coolify após o primeiro deploy.

---

## 9. Pós-deploy (primeira vez)

Após o primeiro deploy bem-sucedido, acesse **Terminal** no Coolify e execute:

```bash
# Criar todas as tabelas do banco
php artisan migrate --force

# Se APP_KEY não foi gerado antes:
php artisan key:generate --force

# Limpar caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 10. Erros comuns e soluções

| Erro | Causa | Solução |
|------|-------|---------|
| `Permission denied (publickey)` | Chave SSH pública copiada com `l`/`I` trocados | Extrair com `ssh-keygen -y -f` via PowerShell |
| `composer install` exit 2 | Nixpacks instala PHP 8.3, lock file exige 8.4 | Usar Dockerfile com Alpine, não Nixpacks |
| `npm ci` ignora devDeps | `NODE_ENV=production` herdado de `APP_ENV` | Usar stage `node:20-slim` separado no Dockerfile |
| `DeploymentException` code 0 | SSH do Coolify cai por timeout (build > 4 min) | Alpine com apk (build < 2 min) em vez de `docker-php-ext-install` |
| `ln: /usr/local/sbin/php-fpm84: No such file or directory` | Diretório não existe no Alpine | Remover o symlink; supervisord.conf usa caminho absoluto |
| Laravel não lê variáveis de ambiente | `clear_env` não configurado no PHP-FPM | Adicionar `clear_env = no` em php-fpm.conf |
| CSS/JS não carregam (ViteManifestException) | Vite não rodou ou `public/build/` ausente | Checar stage `node-builder` no log do build |
| Banco não conecta | `DB_HOST` aponta para `127.0.0.1` | Usar hostname do container MySQL do Coolify |
| Livewire dropdowns não reagem (interatividade quebrada) | nginx intercepta `/livewire-{hash}/*.js` como asset estático → 404 | Adicionar `location ^~ /livewire { try_files $uri $uri/ /index.php?$query_string; }` **antes** do bloco de assets estáticos |

---

## 11. Checklist para novo projeto

- [ ] Copiar pasta `docker/` deste projeto
- [ ] Copiar `Dockerfile` deste projeto
- [ ] Ajustar extensões PHP se necessário (adicionar/remover `php84-*`)
- [ ] Criar banco MySQL no Coolify e anotar o hostname do container
- [ ] Gerar deploy key com `ssh-keygen -y -f` e adicionar ao GitHub
- [ ] Configurar todas as variáveis de ambiente no Coolify
- [ ] Build Pack: **Dockerfile** (não Nixpacks)
- [ ] Após deploy: rodar `php artisan migrate --force` pelo Terminal
- [ ] Configurar domínio + SSL (Coolify gera Let's Encrypt automaticamente)
