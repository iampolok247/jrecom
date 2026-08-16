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

$currentDir = __DIR__;
$baseDir = (basename($currentDir) === 'public' || basename($currentDir) === 'public_html') ? dirname($currentDir) : $currentDir;
$secretToken = 'ravelis_deploy_secret_987654321';

function copyRecursive($src, $dst) {
    if (!file_exists($src)) return;
    $dir = opendir($src);
    @mkdir($dst, 0755, true);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyRecursive($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function processDeployment($baseDir, $currentDir) {
    $artisanLog = [];

    // 1. Locate public assets source folder (either baseDir/public or currentDir/public)
    $publicSrc = null;
    if (file_exists($baseDir . '/public') && is_dir($baseDir . '/public') && realpath($baseDir . '/public') !== realpath($currentDir)) {
        $publicSrc = $baseDir . '/public';
    } elseif (file_exists($currentDir . '/public') && is_dir($currentDir . '/public') && realpath($currentDir . '/public') !== realpath($currentDir)) {
        $publicSrc = $currentDir . '/public';
    }

    // 2. Sync public folder contents to current web root (public_html)
    if ($publicSrc) {
        copyRecursive($publicSrc, $currentDir);
        $artisanLog[] = "Public assets synced to web root.";
    }

    // 3. Ensure index.php in web root points to valid vendor/autoload and bootstrap/app
    $indexPath = $currentDir . '/index.php';
    if (file_exists($indexPath)) {
        $vendorPath = file_exists($baseDir . '/vendor/autoload.php') ? "$baseDir/vendor/autoload.php" : (file_exists($currentDir . '/vendor/autoload.php') ? "$currentDir/vendor/autoload.php" : dirname($currentDir) . '/vendor/autoload.php');
        $appPath = file_exists($baseDir . '/bootstrap/app.php') ? "$baseDir/bootstrap/app.php" : (file_exists($currentDir . '/bootstrap/app.php') ? "$currentDir/bootstrap/app.php" : dirname($currentDir) . '/bootstrap/app.php');
        
        $indexContent = "<?php\n\nuse Illuminate\\Http\\Request;\n\ndefine('LARAVEL_START', microtime(true));\n\nif (file_exists(\$maintenance = '$baseDir/storage/framework/maintenance.php')) {\n    require \$maintenance;\n}\n\nrequire '$vendorPath';\n\n\$app = require_once '$appPath';\n\n\$app->handleRequest(Request::capture());\n";
        file_put_contents($indexPath, $indexContent);
        $artisanLog[] = "Web root index.php paths updated successfully.";
    }

    // 4. Bootstrap Laravel & Run Artisan commands
    $autoloadPath = file_exists($baseDir . '/vendor/autoload.php') ? $baseDir . '/vendor/autoload.php' : $currentDir . '/vendor/autoload.php';
    $appBootstrapPath = file_exists($baseDir . '/bootstrap/app.php') ? $baseDir . '/bootstrap/app.php' : $currentDir . '/bootstrap/app.php';

    if (file_exists($autoloadPath) && file_exists($appBootstrapPath)) {
        require_once $autoloadPath;
        $app = require_once $appBootstrapPath;
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $artisanLog[] = \Illuminate\Support\Facades\Artisan::output();
        } catch (\Throwable $me) {
            $artisanLog[] = "Migration status: " . $me->getMessage();
        }

        try {
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            $artisanLog[] = "Caches cleared successfully.";
        } catch (\Throwable $ce) {
            $artisanLog[] = "Cache status: " . $ce->getMessage();
        }
    }
    return $artisanLog;
}

// Handle Secure HTTPS POST Deployment from GitHub Actions
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

                try {
                    $log = processDeployment($baseDir, $currentDir);
                    echo json_encode(['status' => 'success', 'message' => 'Deployed, unzipped, synced public assets, and migrated successfully!', 'log' => $log]);
                } catch (\Throwable $e) {
                    echo json_encode(['status' => 'warning', 'message' => 'Unzipped, but notice: ' . $e->getMessage()]);
                }
                exit;
            } else {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Failed to open uploaded ZIP file']);
                exit;
            }
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file to target directory']);
            exit;
        }
    } else {
        http_response_code(400);
        $errCode = $_FILES['package']['error'] ?? 'No package file attached';
        $uploadErrors = [
            1 => 'Uploaded file exceeds upload_max_filesize directive in php.ini',
            2 => 'Uploaded file exceeds MAX_FILE_SIZE directive in HTML form',
            3 => 'The file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk',
            8 => 'A PHP extension stopped the file upload'
        ];
        $errMsg = $uploadErrors[$errCode] ?? ('Error code: ' . $errCode);
        echo json_encode(['status' => 'error', 'message' => 'Upload failed. ' . $errMsg]);
        exit;
    }
}

// Web UI View for Manual Browser Access
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
    $currentDir . '/release.zip',
    $baseDir . '/release.zip',
];

$extracted = false;
foreach ($possibleZips as $zipFile) {
    if (file_exists($zipFile)) {
        echo "<div class='alert alert-info'>Extracting " . basename($zipFile) . "...</div>";
        $zip = new ZipArchive();
        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($baseDir);
            $zip->close();
            @unlink($zipFile);
            echo "<div class='alert alert-success'>Extracted " . basename($zipFile) . " successfully!</div>";
            $extracted = true;
        }
    }
}

if ($extracted || file_exists($baseDir . '/vendor/autoload.php') || file_exists($currentDir . '/vendor/autoload.php')) {
    try {
        $log = processDeployment($baseDir, $currentDir);
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
