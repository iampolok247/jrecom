<?php
/**
 * 1-Click Automated Server Setup & Deployer Script
 * Access via: https://ravelis.online/setup.php
 */

@ini_set('upload_max_filesize', '128M');
@ini_set('post_max_size', '128M');
@ini_set('max_execution_time', '300');
@ini_set('memory_limit', '512M');

define('LARAVEL_START', microtime(true));
$baseDir = dirname(__DIR__);
$secretToken = 'ravelis_deploy_secret_987654321';

// 1. Handle Secure HTTPS POST Deployment from GitHub Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $providedToken = $_POST['token'] ?? $_SERVER['HTTP_X_DEPLOY_TOKEN'] ?? '';
    
    if ($providedToken !== $secretToken) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized: Invalid token']);
        exit;
    }

    if (isset($_FILES['package']) && $_FILES['package']['error'] === UPLOAD_ERR_OK) {
        $targetZip = $baseDir . '/release.zip';
        if (move_uploaded_file($_FILES['package']['tmp_name'], $targetZip)) {
            $zip = new ZipArchive();
            if ($zip->open($targetZip) === TRUE) {
                $zip->extractTo($baseDir);
                $zip->close();
                @unlink($targetZip);

                // Run Artisan tasks
                $artisanLog = [];
                try {
                    if (file_exists($baseDir . '/vendor/autoload.php')) {
                        require_once $baseDir . '/vendor/autoload.php';
                        $app = require_once $baseDir . '/bootstrap/app.php';
                        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
                        $kernel->bootstrap();

                        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                        $artisanLog[] = \Illuminate\Support\Facades\Artisan::output();

                        \Illuminate\Support\Facades\Artisan::call('config:clear');
                        \Illuminate\Support\Facades\Artisan::call('cache:clear');
                        \Illuminate\Support\Facades\Artisan::call('view:clear');
                        $artisanLog[] = "Caches cleared successfully.";
                    }
                    echo json_encode(['status' => 'success', 'message' => 'Deployed, unzipped, and migrated successfully!', 'log' => $artisanLog]);
                } catch (\Throwable $e) {
                    echo json_encode(['status' => 'warning', 'message' => 'Unzipped, but Artisan warning: ' . $e->getMessage()]);
                }
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to open uploaded ZIP file']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
            exit;
        }
    } else {
        $errCode = $_FILES['package']['error'] ?? 'No package file attached';
        echo json_encode(['status' => 'error', 'message' => 'Upload failed. Error code: ' . $errCode]);
        exit;
    }
}

// 2. Web UI View for Manual Browser Access
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JR-Ecom Automated Deployer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; padding: 40px 20px; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; color: #f8fafc; }
    </style>
</head>
<body>
<div class="container" style="max-width: 650px;">
    <div class="card p-4 shadow-lg mb-4">
        <h3 class="fw-bold text-white mb-2">🚀 JR-Ecom Deployer Endpoint</h3>
        <p class="text-secondary small mb-3">HTTPS Deployment Endpoint for GitHub Actions</p>
';

// Check for local zips if accessed directly via browser
$possibleZips = [
    __DIR__ . '/release.zip',
    $baseDir . '/release.zip',
];

$extracted = false;
foreach ($possibleZips as $zipFile) {
    if (file_exists($zipFile)) {
        $targetDir = file_exists($baseDir . '/bootstrap') ? $baseDir : __DIR__;
        echo "<div class='alert alert-info'>Extracting " . basename($zipFile) . "...</div>";
        $zip = new ZipArchive();
        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($targetDir);
            $zip->close();
            @unlink($zipFile);
            echo "<div class='alert alert-success'>Extracted " . basename($zipFile) . " successfully!</div>";
            $extracted = true;
        }
    }
}

if ($extracted || file_exists($baseDir . '/vendor/autoload.php')) {
    try {
        require_once $baseDir . '/vendor/autoload.php';
        $app = require_once $baseDir . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        echo '<div class="alert alert-success mt-3">
            <h5 class="fw-bold mb-1">🎉 System Ready & Migrated!</h5>
            <div class="mt-3">
                <a href="/" class="btn btn-primary fw-bold me-2">Visit Website</a>
                <a href="/login" class="btn btn-outline-light">Admin Login</a>
            </div>
        </div>';
    } catch (\Throwable $e) {
        echo '<div class="alert alert-warning mt-3">Status: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
} else {
    echo '<div class="alert alert-info mt-3">Ready to receive deployments from GitHub Actions.</div>';
}

echo '</div></div></body></html>';
