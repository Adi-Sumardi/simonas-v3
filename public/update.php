<?php
// Deploy helper — migrate + clear all cache
// Access: https://v3.simonas.id/update.php
// DELETE or move out of public/ after use in production

define('TOKEN', 'simonas2026');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SIMONAS — Deploy Update</title>
<style>
    body { font-family: system-ui, sans-serif; max-width: 480px; margin: 60px auto; padding: 0 20px; background: #f8fafc; }
    h2 { color: #1e3a5f; margin-bottom: 4px; }
    p { color: #64748b; font-size: 14px; margin-bottom: 24px; }
    .card { background: white; border-radius: 16px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
    input[type=password] { width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; box-sizing: border-box; margin-bottom: 16px; }
    .actions { display: flex; gap: 10px; flex-wrap: wrap; }
    button { flex: 1; padding: 12px; border: none; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; }
    .btn-all  { background: #2563eb; color: white; }
    .btn-mig  { background: #0891b2; color: white; }
    .btn-cache{ background: #7c3aed; color: white; }
    .btn-seed { background: #059669; color: white; }
    button:hover { opacity: .88; }
</style>
</head>
<body>
<div class="card">
    <h2>🚀 SIMONAS Deploy</h2>
    <p>Jalankan migrate dan/atau clear cache via browser.</p>
    <form method="POST">
        <input type="password" name="token" placeholder="Token..." required>
        <div class="actions">
            <button class="btn-all"   name="action" value="all">   Migrate + Clear Cache</button>
            <button class="btn-mig"   name="action" value="migrate">Migrate saja</button>
            <button class="btn-cache" name="action" value="cache">  Clear Cache saja</button>
            <button class="btn-seed"  name="action" value="seed">   Role &amp; Permission Seeder</button>
        </div>
    </form>
</div>
</body>
</html>
<?php
    exit;
}

// ── POST handler ─────────────────────────────────────────────────────────────
if ($_POST['token'] !== TOKEN) {
    http_response_code(403);
    exit('403 Forbidden');
}

$action = $_POST['action'] ?? 'all';

define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app    = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$log = [];

if ($action === 'migrate' || $action === 'all') {
    Artisan::call('migrate', ['--force' => true]);
    $log[] = ['cmd' => 'migrate --force', 'out' => Artisan::output()];
}

if ($action === 'cache' || $action === 'all') {
    foreach (['cache:clear', 'config:clear', 'route:clear', 'view:clear'] as $cmd) {
        Artisan::call($cmd);
        $log[] = ['cmd' => $cmd, 'out' => Artisan::output()];
    }
}

if ($action === 'seed') {
    Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder', '--force' => true]);
    $log[] = ['cmd' => 'db:seed --class=RolePermissionSeeder', 'out' => Artisan::output()];
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SIMONAS — Deploy Result</title>
<style>
    body { font-family: system-ui, sans-serif; max-width: 640px; margin: 40px auto; padding: 0 20px; background: #f8fafc; }
    h2 { color: #1e3a5f; }
    .item { background: white; border-radius: 12px; padding: 16px 20px; margin-bottom: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .cmd { font-size: 12px; font-weight: 700; color: #2563eb; background: #eff6ff; display: inline-block; padding: 3px 10px; border-radius: 20px; margin-bottom: 8px; }
    pre { margin: 0; font-size: 13px; color: #334155; white-space: pre-wrap; }
    .back { display: inline-block; margin-top: 16px; color: #2563eb; text-decoration: none; font-size: 14px; }
</style>
</head>
<body>
<h2>✅ Deploy selesai</h2>
<?php foreach ($log as $entry): ?>
<div class="item">
    <span class="cmd">php artisan <?= htmlspecialchars($entry['cmd']) ?></span>
    <pre><?= htmlspecialchars(trim($entry['out'])) ?: '(no output)' ?></pre>
</div>
<?php endforeach; ?>
<a class="back" href="update.php">← Kembali</a>
</body>
</html>
