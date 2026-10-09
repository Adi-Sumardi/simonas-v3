#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# update.sh — Deploy & refresh script for SIMONAS
# Usage (di folder app, mis. /var/www/simonas):  bash update.sh
#
# Catatan penting:
# - public/build dan vendor/ ikut ter-commit tapi juga di-generate ulang di
#   server, jadi selalu "kotor". Kalau tidak dibuang dulu, `git pull` gagal dan
#   (karena set -e) seluruh deploy berhenti diam-diam di langkah 1.
# - npm run build WAJIB setiap deploy: manifest.json di git bisa tidak cocok
#   dengan file hash di disk → halaman stuck di splash (404 JS).
# - php-fpm di-RELOAD, jangan restart (restart pernah bikin socket 502).
# ─────────────────────────────────────────────────────────────────────────────

set -e  # stop on first error

BRANCH="upgrade/laravel-10"
cd "$(dirname "$0")"

# ── Detect PHP 8.x binary ────────────────────────────────────────────────────
PHP="${PHP:-}"
if [ -z "$PHP" ]; then
    for candidate in \
        /opt/cpanel/ea-php83/root/usr/bin/php \
        /opt/cpanel/ea-php82/root/usr/bin/php \
        /usr/local/lsws/lsphp83/bin/php \
        /usr/local/php83/bin/php \
        php8.3 php8.2 php8.1 php; do
        if "$candidate" -r "exit(PHP_MAJOR_VERSION >= 8 ? 0 : 1);" &>/dev/null; then
            PHP="$candidate"
            break
        fi
    done
fi

if [ -z "$PHP" ]; then
    echo "❌  Tidak menemukan PHP 8+. Set manual: PHP=/path/to/php8.3 bash update.sh"
    exit 1
fi

echo "✅  Menggunakan PHP: $PHP ($($PHP -r 'echo phpversion();'))"
echo ""

# ── 1. Pastikan branch benar & buang artefak build lokal ────────────────────
echo "📦  [1/7] Sinkronisasi kode ($BRANCH)..."
CURRENT_BRANCH="$(git branch --show-current)"
if [ "$CURRENT_BRANCH" != "$BRANCH" ]; then
    echo "    Pindah branch: $CURRENT_BRANCH → $BRANCH"
    git checkout -- public/build vendor 2>/dev/null || true
    git checkout "$BRANCH"
fi

# Artefak yang di-generate ulang di server: aman dibuang.
git checkout -- public/build vendor 2>/dev/null || true
git clean -fdq public/build

# Perubahan lain (kode) jangan pernah dibuang otomatis — berhenti dan laporkan.
OTHER_CHANGES="$(git status --porcelain --untracked-files=no -- . ':!public/build' ':!vendor')"
if [ -n "$OTHER_CHANGES" ]; then
    echo "❌  Ada perubahan lokal di server (bukan build/vendor), deploy dibatalkan:"
    echo "$OTHER_CHANGES"
    echo "    Periksa dulu (git diff), lalu commit/stash/buang sebelum jalankan ulang."
    exit 1
fi

OLD_HEAD="$(git rev-parse HEAD)"
git pull --ff-only origin "$BRANCH"
NEW_HEAD="$(git rev-parse HEAD)"
echo "    $(git log -1 --oneline)"
echo ""

# ── 2. Composer ─────────────────────────────────────────────────────────────
# Selalu dijalankan: cepat kalau tidak ada perubahan, dan sekaligus
# membuang dev-dependency yang ikut ter-commit di vendor/.
echo "📦  [2/7] composer install --no-dev --optimize-autoloader..."
$PHP "$(command -v composer)" install --no-dev --optimize-autoloader --no-interaction --quiet
echo ""

# ── 3. Build frontend (WAJIB setiap deploy) ─────────────────────────────────
echo "🎨  [3/7] npm run build..."
if ! command -v npm >/dev/null 2>&1; then
    echo "❌  npm tidak ditemukan — build frontend wajib, deploy dibatalkan."
    exit 1
fi
if [ "$OLD_HEAD" != "$NEW_HEAD" ] && git diff --name-only "$OLD_HEAD" "$NEW_HEAD" | grep -qE '^package(-lock)?\.json$'; then
    echo "    package.json berubah → npm ci"
    npm ci --no-audit --no-fund
fi
npm run build
echo ""

# ── 4. Migrate ───────────────────────────────────────────────────────────────
echo "🗄️   [4/7] php artisan migrate --force..."
$PHP artisan migrate --force
echo ""

# ── 5. Clear & rebuild cache ─────────────────────────────────────────────────
echo "🧹  [5/7] Clear & cache config/routes/views..."
$PHP artisan optimize:clear
$PHP artisan config:cache
$PHP artisan route:cache
echo ""

# ── 6. Storage link (idempotent) ─────────────────────────────────────────────
echo "🔗  [6/7] Storage link..."
$PHP artisan storage:link --quiet 2>/dev/null || true
echo ""

# ── 7. Reload PHP-FPM (bersihkan opcache) — RELOAD, bukan restart ───────────
echo "♻️   [7/7] Reload PHP-FPM..."
FPM_SERVICE="$(systemctl list-units --type=service --no-legend 2>/dev/null | awk '{print $1}' | grep -E '^php[0-9.]+-fpm\.service$' | head -1)"
if [ -n "$FPM_SERVICE" ] && sudo -n systemctl reload "$FPM_SERVICE" 2>/dev/null; then
    echo "    $FPM_SERVICE di-reload."
else
    echo "⚠️   Tidak bisa reload PHP-FPM otomatis. Jalankan manual:"
    echo "    sudo systemctl reload ${FPM_SERVICE:-php8.3-fpm}"
fi
echo ""

# ── Health check ────────────────────────────────────────────────────────────
APP_URL="$(grep -E '^APP_URL=' .env | cut -d= -f2- | tr -d '"'"'"' ')"
if [ -n "$APP_URL" ] && command -v curl >/dev/null 2>&1; then
    CODE="$(curl -s -o /dev/null -w '%{http_code}' "$APP_URL/login" || true)"
    echo "🩺  $APP_URL/login → HTTP $CODE"
    [ "$CODE" = "200" ] || echo "⚠️   Cek situs! Login tidak mengembalikan 200."
fi

echo "✅  Deploy selesai! ($(git log -1 --oneline))"
