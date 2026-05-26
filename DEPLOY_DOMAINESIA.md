# Deploy ke v3.simonas.id — Domainesia Shared Hosting

## Langkah-langkah Deploy

### 1. Clone / Upload Repo ke Hosting

Di File Manager Domainesia atau via SSH:

```bash
# Masuk ke folder public_html atau buat subdomain dulu
cd ~/public_html

# Clone branch upgrade/laravel-10
git clone -b upgrade/laravel-10 git@github.com:Adi-Sumardi/simonas-v3.git simonas-v3
```

> Atau upload via ZIP: download dari GitHub → Extract ke folder `simonas-v3/`

---

### 2. Setup Subdomain di cPanel Domainesia

1. Login cPanel → **Subdomains**
2. Subdomain: `v3` → Domain: `simonas.id`
3. Document Root: `public_html/simonas-v3/public`
4. Klik **Create**

---

### 3. Buat Database MySQL

1. cPanel → **MySQL Databases**
2. Buat database: `simonas_v3`
3. Buat user: `simonas_v3_user` + password
4. Assign user ke database: **All Privileges**

---

### 4. Setup `.env`

Upload file `.env` ke root project (`simonas-v3/.env`):

```env
APP_NAME="SIMONAS v3"
APP_ENV=production
APP_KEY=                        # diisi setelah php artisan key:generate
APP_DEBUG=false
APP_URL=https://v3.simonas.id

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=simonas_v3
DB_USERNAME=simonas_v3_user
DB_PASSWORD=your_db_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_DOMAIN=v3.simonas.id

MAIL_MAILER=smtp
MAIL_HOST=mail.simonas.id
MAIL_PORT=465
MAIL_USERNAME=noreply@simonas.id
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@simonas.id
MAIL_FROM_NAME="SIMONAS"

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URL=https://v3.simonas.id/auth/google/callback
GOOGLE_ALLOWED_DOMAINS=

VITE_APP_NAME="SIMONAS v3"
```

---

### 5. Jalankan via SSH / Terminal Hosting

```bash
cd ~/public_html/simonas-v3

# Install Composer dependencies (tanpa dev)
php artisan --version   # pastikan PHP 8.3
composer install --no-dev --optimize-autoloader

# Generate app key
php artisan key:generate

# Storage symlink
php artisan storage:link

# Run migrations
php artisan migrate --force

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> **Catatan:** Build frontend (`npm run build`) sudah dilakukan di lokal dan hasilnya
> sudah di-commit ke `public/build/`. Tidak perlu Node.js di server.

---

### 6. Permission Folder

```bash
chmod -R 755 storage bootstrap/cache
chown -R nobody:nobody storage bootstrap/cache   # sesuai user hosting
```

---

### 7. Setup SSL

Di cPanel → **SSL/TLS** → **Let's Encrypt** → pilih `v3.simonas.id` → Install

---

### 8. Verifikasi

Buka `https://v3.simonas.id` — harus tampil halaman landing SIMONAS.

Checklist:
- [ ] Landing page tampil
- [ ] Login berhasil
- [ ] Upload avatar berhasil
- [ ] Hafalan data tersimpan
- [ ] Storage symlink OK (`/storage/` bisa diakses)
- [ ] PWA dapat diinstall

---

## Update Berikutnya

Setiap ada update code:

```bash
cd ~/public_html/simonas-v3
git pull origin upgrade/laravel-10
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```
