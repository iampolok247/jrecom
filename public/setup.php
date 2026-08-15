<?php
/**
 * 1-Click Automated Server Setup & Installer Script
 * Access via: https://your-domain.com/setup.php
 */

define('LARAVEL_START', microtime(true));
$baseDir = dirname(__DIR__);

header('Content-Type: text/html; charset=utf-8');

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JR-Ecom 1-Click Server Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; padding: 40px 20px; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; color: #f8fafc; }
        .log-box { background: #090d16; border-radius: 8px; padding: 15px; font-family: monospace; font-size: 13px; color: #38bdf8; max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body>
<div class="container max-w-lg mx-auto" style="max-width: 650px;">
    <div class="card p-4 shadow-lg mb-4">
        <h3 class="fw-bold text-white mb-2">🚀 JR-Ecom Server Installer</h3>
        <p class="text-secondary small mb-4">Automated 1-Click Setup & Migration System</p>
';

$logs = [];

function logMsg($msg) {
    echo "<div class='text-info mb-1'>➔ " . htmlspecialchars($msg) . "</div>";
    flush();
}

// 1. Unzip release.zip or vendor.zip if present
$possibleZips = [
    __DIR__ . '/release.zip',
    $baseDir . '/release.zip',
    __DIR__ . '/vendor.zip',
    $baseDir . '/vendor.zip'
];

foreach ($possibleZips as $zipFile) {
    if (file_exists($zipFile)) {
        $targetDir = (strpos(basename($zipFile), 'release') !== false && file_exists($baseDir . '/bootstrap')) ? $baseDir : __DIR__;
        logMsg("Extracting " . basename($zipFile) . " to " . $targetDir . "...");
        $zip = new ZipArchive();
        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($targetDir);
            $zip->close();
            @unlink($zipFile); // remove zip after extraction
            logMsg(basename($zipFile) . " extracted successfully!");
        } else {
            logMsg("ERROR: Failed to open " . basename($zipFile));
        }
    }
}

// Check autoload
if (!file_exists($baseDir . '/vendor/autoload.php')) {
    echo '<div class="alert alert-danger">Error: vendor/autoload.php not found. Please wait for CI/CD deployment to finish.</div>';
    echo '</div></div></body></html>';
    exit;
}

require $baseDir . '/vendor/autoload.php';
$app = require_once $baseDir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\SiteSetting;

try {
    logMsg("Running Database Migrations & Seeders...");
    Artisan::call('migrate', ['--force' => true]);
    logMsg(Artisan::output());

    Artisan::call('db:seed', ['--force' => true]);
    logMsg(Artisan::output());

    logMsg("Generating App Key & Clearing Caches...");
    Artisan::call('key:generate', ['--force' => true]);
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');

    // Create default admin if none exists
    $siteName = env('APP_NAME', 'Ravelis');
    $adminEmail = env('ADMIN_EMAIL', 'admin@ravelis.online');
    
    $user = User::where('role', 'admin')->first();
    if (!$user) {
        User::create([
            'name' => $siteName . ' Admin',
            'email' => $adminEmail,
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
        ]);
        logMsg("Created Admin User: $adminEmail (Password: admin123)");
    } else {
        logMsg("Admin User Ready: " . $user->email);
    }

    echo '<div class="alert alert-success mt-4">
        <h5 class="fw-bold mb-1">🎉 Setup Completed Successfully!</h5>
        <p class="mb-2">Your site database, migrations, and settings are fully ready.</p>
        <div class="mt-3">
            <a href="/" class="btn btn-primary fw-bold me-2">Visit Website</a>
            <a href="/login" class="btn btn-outline-light">Admin Login</a>
        </div>
    </div>';

} catch (\Exception $e) {
    echo '<div class="alert alert-danger mt-3">Setup Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

echo '</div></div></body></html>';
