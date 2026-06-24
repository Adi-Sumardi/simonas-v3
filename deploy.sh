#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# deploy.sh — Deploy SIMONAS ke VPS (Biznet Gio · Ubuntu · Nginx + PHP-FPM)
#
# Jalankan DI VPS, dari dalam folder project:
#     bash deploy.sh
#
# Override opsional:
#     BRANCH=main bash deploy.sh           # deploy branch lain
#     SKIP_BUILD=1 bash deploy.sh          # lewati build asset (npm)
#     NO_RELOAD=1 bash deploy.sh           # jangan reload nginx/php-fpm
# ─────────────────────────────────────────────────────────────────────────────

set -euo pipefail

# ── Konfigurasi ──────────────────────────────────────────────────────────────
BRANCH="${BRANCH:-main}"
WEB_USER="${WEB_USER:-www-data}"        # user yang dipakai nginx/php-fpm
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# ── Helper sudo (pakai sudo hanya jika bukan root) ───────────────────────────
SUDO=""
if [ "$(id -u)" -ne 0 ]; then
    command -v sudo >/dev/null 2>&1 && SUDO="sudo"
fi

# ── Deteksi binary PHP 8.x ───────────────────────────────────────────────────
PHP="${PHP:-}"
if [ -z "$PHP" ]; then
    for candidate in php8.3 php8.2 php8.1 /usr/bin/php8.3 /usr/bin/php8.2 php; do
        if "$candidate" -r 'exit(PHP_MAJOR_VERSION >= 8 ? 0 : 1);' &>/dev/null; then
            PHP="$candidate"; break
        fi
    done
fi
[ -z "$PHP" ] && { echo "❌  Tidak menemukan PHP 8+. Set manual: PHP=/path/to/php8.3 bash deploy.sh"; exit 1; }

# ── Cari composer ────────────────────────────────────────────────────────────
COMPOSER="$(command -v composer || true)"
[ -z "$COMPOSER" ] && { echo "❌  composer tidak ditemukan di PATH."; exit 1; }

PHP_VER="$($PHP -r 'echo phpversion();')"
FPM_SERVICE="php${PHP_VER%.*}-fpm"      # mis. php8.3-fpm
echo "✅  PHP: $PHP ($PHP_VER) · FPM service: $FPM_SERVICE · Branch: $BRANCH"
echo ""

# ── Pastikan .env ada sebelum menjalankan artisan ───────────────────────────
if [ ! -f .env ]; then
    echo "❌  File .env tidak ditemukan!"
    echo "    Salin dan isi dulu: cp .env.example .env"
    echo "    Lalu jalankan: php artisan key:generate"
    exit 1
fi

# Catat commit sebelum pull untuk deteksi perubahan
PREV_REF="$(git rev-parse HEAD)"

# ── 1. Maintenance mode ON ───────────────────────────────────────────────────
echo "🚧  [1/9] Maintenance mode ON..."
$PHP artisan down --retry=15 || true
# Pastikan app kembali UP walau skrip gagal di tengah jalan
trap '$PHP artisan up >/dev/null 2>&1 || true' EXIT
echo ""

# ── 2. Pull kode terbaru ─────────────────────────────────────────────────────
echo "📦  [2/9] git pull origin $BRANCH..."
git fetch origin "$BRANCH"
git reset --hard "origin/$BRANCH"       # kode di VPS selalu identik dengan remote
echo ""

# Daftar file yang berubah sejak deploy terakhir
CHANGED="$(git diff --name-only "$PREV_REF" HEAD || true)"
changed() { echo "$CHANGED" | grep -q "$1"; }

# ── 3. Composer (skip jika composer.lock tidak berubah) ──────────────────────
if changed "composer.lock" || [ ! -d vendor ]; then
    echo "📦  [3/9] composer install --no-dev --optimize-autoloader..."
    "$COMPOSER" install --no-dev --optimize-autoloader --no-interaction
else
    echo "⏭️   [3/9] composer.lock tidak berubah, skip."
fi
echo ""

# ── 4. Build asset frontend (vite) ───────────────────────────────────────────
if [ "${SKIP_BUILD:-0}" = "1" ]; then
    echo "⏭️   [4/9] SKIP_BUILD=1, lewati build asset."
elif changed "package-lock.json" || changed "resources/" || changed "vite.config" || [ ! -d public/build ]; then
    echo "🎨  [4/9] Build asset (npm ci && npm run build)..."
    if changed "package-lock.json" || [ ! -d node_modules ]; then
        npm ci
    fi
    npm run build
else
    echo "⏭️   [4/9] Tidak ada perubahan frontend, skip build."
fi
echo ""

# ── 5. Migrasi database ──────────────────────────────────────────────────────
echo "🗄️   [5/9] php artisan migrate --force..."
$PHP artisan migrate --force
echo ""

# ── 6. Clear cache lama ──────────────────────────────────────────────────────
echo "🧹  [6/9] Clear cache..."
$PHP artisan optimize:clear
echo ""

# ── 7. Re-cache config, route, view (optimisasi produksi) ────────────────────
echo "⚡  [7/9] Cache config, route, view..."
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
echo ""

# ── 8. Storage link + perbaiki permission ────────────────────────────────────
echo "🔗  [8/9] Storage link & permission..."
$PHP artisan storage:link --quiet 2>/dev/null || true
if [ -n "$SUDO" ] || [ "$(id -u)" -eq 0 ]; then
    $SUDO chown -R "$WEB_USER:$WEB_USER" storage bootstrap/cache 2>/dev/null || true
fi
chmod -R ug+rwX storage bootstrap/cache 2>/dev/null || true
echo ""

# ── 9. Reload PHP-FPM & Nginx ────────────────────────────────────────────────
if [ "${NO_RELOAD:-0}" = "1" ]; then
    echo "⏭️   [9/9] NO_RELOAD=1, lewati reload service."
else
    echo "🔄  [9/9] Reload $FPM_SERVICE & nginx..."
    $SUDO systemctl reload "$FPM_SERVICE" 2>/dev/null || $SUDO systemctl restart "$FPM_SERVICE" 2>/dev/null || echo "   ⚠️  Gagal reload $FPM_SERVICE (cek nama service / izin sudo)."
    $SUDO nginx -t 2>/dev/null && $SUDO systemctl reload nginx 2>/dev/null || echo "   ⚠️  Gagal reload nginx (cek 'sudo nginx -t')."
fi
echo ""

# ── Maintenance mode OFF ─────────────────────────────────────────────────────
trap - EXIT
$PHP artisan up
echo "✅  Deploy selesai! Aplikasi sudah online."
