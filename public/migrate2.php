<?php
// One-time migration runner - DELETE AFTER USE
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<form method="POST"><input type="hidden" name="token" value="simonas2026"><button type="submit">Run Migrate</button></form>';
    exit;
}
if ($_POST['token'] !== 'simonas2026') { http_response_code(403); exit('Forbidden'); }

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$result = Artisan::call('migrate', ['--force' => true]);
echo '<pre>';
echo Artisan::output();
echo "\nExit code: $result\n";
echo '</pre>';
