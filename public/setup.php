<?php
/**
 * One-time setup script for shared hosting without PHP 8.x CLI.
 * DELETE THIS FILE immediately after setup is complete.
 */

// Basic security: require a secret token
$secret = $_GET['token'] ?? '';
if ($secret !== 'simonas-setup-2024') {
    http_response_code(403);
    die('Forbidden');
}

define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$results = [];

// 1. Generate APP_KEY if not set
$envPath = base_path('.env');
$envContent = file_get_contents($envPath);
if (str_contains($envContent, 'APP_KEY=') && !str_contains($envContent, 'APP_KEY=base64')) {
    $kernel->call('key:generate');
    $results[] = '✅ APP_KEY generated';
} else {
    $results[] = '⏭️ APP_KEY already set, skipping';
}

// 2. Run migrations
try {
    $kernel->call('migrate', ['--force' => true]);
    $results[] = '✅ Migrations ran successfully';
} catch (\Exception $e) {
    $results[] = '❌ Migration error: ' . $e->getMessage();
}

// 3. Storage link
try {
    // Create symlink manually (artisan storage:link may need CLI)
    $target = realpath(__DIR__ . '/../storage/app/public');
    $link   = __DIR__ . '/storage';
    if (!file_exists($link) && !is_link($link)) {
        symlink($target, $link);
        $results[] = '✅ Storage link created';
    } else {
        $results[] = '⏭️ Storage link already exists';
    }
} catch (\Exception $e) {
    $results[] = '❌ Storage link error: ' . $e->getMessage();
}

// 4. Cache config & routes
try {
    $kernel->call('config:cache');
    $results[] = '✅ Config cached';
    $kernel->call('route:cache');
    $results[] = '✅ Routes cached';
    $kernel->call('view:cache');
    $results[] = '✅ Views cached';
} catch (\Exception $e) {
    $results[] = '❌ Cache error: ' . $e->getMessage();
}

// Output
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head><title>SIMONAS Setup</title>
<style>body{font-family:monospace;padding:2rem;background:#0f172a;color:#e2e8f0}
h1{color:#38bdf8}.ok{color:#4ade80}.skip{color:#94a3b8}.err{color:#f87171}
.box{background:#1e293b;padding:1.5rem;border-radius:8px;margin-top:1rem}
.warn{background:#7c2d12;padding:1rem;border-radius:8px;margin-top:1.5rem;color:#fca5a5}
</style></head>
<body>
<h1>🚀 SIMONAS Setup</h1>
<p>PHP: <?= PHP_VERSION ?> | Laravel: <?= app()->version() ?></p>
<div class="box">
<?php foreach ($results as $r): ?>
    <p><?= htmlspecialchars($r) ?></p>
<?php endforeach; ?>
</div>
<div class="warn">
    ⚠️ <strong>PENTING:</strong> Hapus file <code>public/setup.php</code> sekarang via File Manager atau SSH!<br>
    <code>rm ~/v3.simonas.id/public/setup.php</code>
</div>
</body>
</html>
<?php
// Self-destruct option
if (isset($_GET['destroy'])) {
    unlink(__FILE__);
    echo '<script>document.body.innerHTML += "<p style=\'color:lime\'>✅ File setup.php sudah dihapus otomatis.</p>"</script>';
}
