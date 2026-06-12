#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# update.sh — Deploy & refresh script for SIMONAS (shared hosting)
# Usage: bash update.sh
# ─────────────────────────────────────────────────────────────────────────────

set -e  # stop on first error

BRANCH="upgrade/laravel-10"

# ── Detect PHP 8.x binary ────────────────────────────────────────────────────
PHP=""
for candidate in \
    /opt/cpanel/ea-php83/root/usr/bin/php \
    /opt/cpanel/ea-php82/root/usr/bin/php \
    /usr/local/lsws/lsphp83/bin/php \
    /usr/local/php83/bin/php \
    php8.3 php8.2 php8.1 php; do
    if [ -x "$candidate" ] || command -v "$candidate" &>/dev/null; then
        version=$("$candidate" -r "echo PHP_MAJOR_VERSION;" 2>/dev/null)
        if [ "$version" -ge 8 ] 2>/dev/null; then
            PHP="$candidate"
            break
        fi
    fi
done

if [ -z "$PHP" ]; then
    echo "❌  Tidak menemukan PHP 8+. Set manual: PHP=/path/to/php8.3 bash update.sh"
    exit 1
fi

echo "✅  Menggunakan PHP: $PHP ($($PHP -r 'echo phpversion();'))"
echo ""

# ── 1. Pull latest code ───────────────────────────────────────────────────────
echo "📦  [1/6] Git pull origin $BRANCH..."
git pull origin "$BRANCH"
echo ""

# ── 2. Composer (skip jika tidak ada perubahan pada composer.lock) ──────────
if git diff HEAD@{1} --name-only 2>/dev/null | grep -q "composer.lock"; then
    echo "📦  [2/6] composer install --no-dev --optimize-autoloader..."
    $PHP $(command -v composer) install --no-dev --optimize-autoloader --no-interaction
else
    echo "⏭️   [2/6] composer.lock tidak berubah, skip install."
fi
echo ""

# ── 3. Migrate ────────────────────────────────────────────────────────────────
echo "🗄️   [3/6] php artisan migrate --force..."
$PHP artisan migrate --force
echo ""

# ── 4. Clear all cache ────────────────────────────────────────────────────────
echo "🧹  [4/6] Clear cache..."
$PHP artisan cache:clear
$PHP artisan config:clear
$PHP artisan route:clear
$PHP artisan view:clear
echo ""

# ── 5. Re-cache config & routes (production optimisation) ───────────────────
echo "⚡  [5/6] Cache config & routes..."
$PHP artisan config:cache
$PHP artisan route:cache
echo ""

# ── 6. Storage link (idempotent) ─────────────────────────────────────────────
echo "🔗  [6/6] Storage link..."
$PHP artisan storage:link --quiet 2>/dev/null || true
echo ""

echo "✅  Deploy selesai!"
