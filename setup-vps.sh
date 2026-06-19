#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# setup-vps.sh — Provisioning awal VPS untuk SIMONAS
#                Ubuntu 22.04 / 24.04 · PHP 8.3 · Nginx · PostgreSQL · SSL
#
# Jalankan SEKALI di VPS yang masih kosong, sebagai root:
#     sudo bash setup-vps.sh
#
# Bisa diisi lewat env (kalau kosong akan ditanya interaktif):
#     DOMAIN=simonas.contoh.com EMAIL=admin@contoh.com \
#     DB_PASS='rahasia' sudo -E bash setup-vps.sh
#
# Setelah skrip ini selesai → isi .env → jalankan deploy.sh
# ─────────────────────────────────────────────────────────────────────────────

set -euo pipefail

[ "$(id -u)" -eq 0 ] || { echo "❌  Jalankan sebagai root: sudo bash setup-vps.sh"; exit 1; }

# ── Konfigurasi (default; bisa di-override lewat env) ────────────────────────
PHP_VER="${PHP_VER:-8.3}"
APP_DIR="${APP_DIR:-/var/www/simonas}"   # lokasi project di VPS
DB_NAME="${DB_NAME:-simonas}"
DB_USER="${DB_USER:-simonas}"
NODE_MAJOR="${NODE_MAJOR:-20}"
WEB_USER="www-data"

# ── Input wajib (tanya kalau belum diset) ────────────────────────────────────
DOMAIN="${DOMAIN:-}"
EMAIL="${EMAIL:-}"
DB_PASS="${DB_PASS:-}"
[ -z "$DOMAIN" ]  && read -rp "Domain (mis. simonas.contoh.com, kosongkan jika belum ada): " DOMAIN
[ -z "$DB_PASS" ] && read -rsp "Password DB untuk user '$DB_USER': " DB_PASS && echo ""
[ -z "$DB_PASS" ] && { echo "❌  Password DB tidak boleh kosong."; exit 1; }
if [ -n "$DOMAIN" ] && [ -z "$EMAIL" ]; then
    read -rp "Email untuk SSL/Let's Encrypt: " EMAIL
fi

echo ""
echo "▶  Domain   : ${DOMAIN:-(belum, SSL dilewati)}"
echo "▶  App dir  : $APP_DIR"
echo "▶  Database : $DB_NAME (user: $DB_USER)"
echo "▶  PHP      : $PHP_VER · Node: $NODE_MAJOR"
echo ""

export DEBIAN_FRONTEND=noninteractive

# ── 1. Paket dasar ───────────────────────────────────────────────────────────
echo "📦  [1/8] Update & paket dasar..."
apt-get update -y
apt-get install -y software-properties-common curl git unzip ca-certificates gnupg lsb-release

# ── 2. PHP 8.3 + ekstensi (repo ondrej) ──────────────────────────────────────
echo "🐘  [2/8] Install PHP $PHP_VER + ekstensi..."
add-apt-repository -y ppa:ondrej/php
apt-get update -y
apt-get install -y \
    "php${PHP_VER}-fpm" "php${PHP_VER}-cli" "php${PHP_VER}-pgsql" \
    "php${PHP_VER}-mbstring" "php${PHP_VER}-xml" "php${PHP_VER}-curl" \
    "php${PHP_VER}-zip" "php${PHP_VER}-bcmath" "php${PHP_VER}-gd" \
    "php${PHP_VER}-intl" "php${PHP_VER}-redis"

# ── 3. Composer ──────────────────────────────────────────────────────────────
echo "🎼  [3/8] Install Composer..."
if ! command -v composer >/dev/null 2>&1; then
    curl -sS https://getcomposer.org/installer | "php${PHP_VER}" -- --install-dir=/usr/local/bin --filename=composer
fi

# ── 4. Node.js (NodeSource) ──────────────────────────────────────────────────
echo "🟢  [4/8] Install Node.js $NODE_MAJOR..."
if ! command -v node >/dev/null 2>&1; then
    curl -fsSL "https://deb.nodesource.com/setup_${NODE_MAJOR}.x" | bash -
    apt-get install -y nodejs
fi

# ── 5. Nginx + PostgreSQL ────────────────────────────────────────────────────
echo "🌐  [5/8] Install Nginx & PostgreSQL..."
apt-get install -y nginx postgresql postgresql-contrib
systemctl enable --now nginx postgresql

# ── 6. Buat database & role ──────────────────────────────────────────────────
echo "🗄️   [6/8] Setup database '$DB_NAME'..."
# Buat role (idempotent) — set/refresh password
sudo -u postgres psql -v ON_ERROR_STOP=1 <<SQL
DO \$\$
BEGIN
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = '${DB_USER}') THEN
        CREATE ROLE "${DB_USER}" LOGIN PASSWORD '${DB_PASS}';
    ELSE
        ALTER ROLE "${DB_USER}" WITH LOGIN PASSWORD '${DB_PASS}';
    END IF;
END
\$\$;
SQL
# Buat database (CREATE DATABASE tidak bisa IF NOT EXISTS, jadi cek dulu)
if ! sudo -u postgres psql -tAc "SELECT 1 FROM pg_database WHERE datname='${DB_NAME}'" | grep -q 1; then
    sudo -u postgres createdb -O "${DB_USER}" -E UTF8 "${DB_NAME}"
fi
sudo -u postgres psql -v ON_ERROR_STOP=1 -c "GRANT ALL PRIVILEGES ON DATABASE \"${DB_NAME}\" TO \"${DB_USER}\";"
# Postgres 15+: beri hak penuh di schema public agar migrasi bisa CREATE TABLE
sudo -u postgres psql -d "${DB_NAME}" -v ON_ERROR_STOP=1 \
    -c "GRANT ALL ON SCHEMA public TO \"${DB_USER}\";" \
    -c "ALTER SCHEMA public OWNER TO \"${DB_USER}\";"

# ── 7. Vhost Nginx (docroot: public/) ────────────────────────────────────────
echo "⚙️   [7/8] Konfigurasi Nginx..."
SERVER_NAME="${DOMAIN:-_}"
FPM_SOCK="/run/php/php${PHP_VER}-fpm.sock"
cat > /etc/nginx/sites-available/simonas <<NGINX
server {
    listen 80;
    listen [::]:80;
    server_name ${SERVER_NAME};
    root ${APP_DIR}/public;

    index index.php;
    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php\$ {
        fastcgi_pass unix:${FPM_SOCK};
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }

    client_max_body_size 50M;
}
NGINX
ln -sf /etc/nginx/sites-available/simonas /etc/nginx/sites-enabled/simonas
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

# Siapkan folder project (kalau belum ada) supaya bisa git clone ke sini nanti
mkdir -p "$APP_DIR"
chown -R "$WEB_USER:$WEB_USER" "$(dirname "$APP_DIR")/$(basename "$APP_DIR")"

# ── 8. SSL (Certbot) — hanya jika domain diisi ───────────────────────────────
if [ -n "$DOMAIN" ]; then
    echo "🔒  [8/8] Setup SSL untuk $DOMAIN..."
    apt-get install -y certbot python3-certbot-nginx
    if certbot --nginx -d "$DOMAIN" --non-interactive --agree-tos -m "$EMAIL" --redirect; then
        echo "   ✅  SSL aktif."
    else
        echo "   ⚠️  Certbot gagal — pastikan DNS '$DOMAIN' sudah mengarah ke IP VPS, lalu ulangi:"
        echo "       sudo certbot --nginx -d $DOMAIN"
    fi
else
    echo "⏭️   [8/8] Domain kosong, SSL dilewati."
fi

# ── Selesai ──────────────────────────────────────────────────────────────────
SERVER_IP="$(curl -s ifconfig.me || echo 'IP-VPS')"
cat <<DONE

✅  Provisioning selesai!

Langkah berikutnya:
  1. Clone project ke folder app:
       sudo git clone <REPO_URL> ${APP_DIR}
       (atau pindahkan kode yang sudah ada ke ${APP_DIR})

  2. Buat & isi .env produksi:
       cd ${APP_DIR}
       cp .env.example .env
       Sesuaikan minimal:
         APP_ENV=production · APP_DEBUG=false
         APP_URL=https://${DOMAIN:-$SERVER_IP}
         DB_CONNECTION=pgsql · DB_HOST=127.0.0.1 · DB_PORT=5432
         DB_DATABASE=${DB_NAME} · DB_USERNAME=${DB_USER} · DB_PASSWORD=(password tadi)
         LIVEKIT_API_SECRET= (minimal 32 karakter!)
       php${PHP_VER} artisan key:generate

  3. Deploy:
       bash deploy.sh

Setelah itu cukup jalankan deploy.sh setiap ada update.
DONE
