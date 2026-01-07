<?php

echo "=== Clearing All Laravel Caches ===\n\n";

// Delete bootstrap/cache files
$cacheFiles = [
    __DIR__ . '/bootstrap/cache/config.php',
    __DIR__ . '/bootstrap/cache/routes.php',
    __DIR__ . '/bootstrap/cache/events.php',
    __DIR__ . '/bootstrap/cache/packages.php',
    __DIR__ . '/bootstrap/cache/services.php',
];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "✓ Deleted: $file\n";
    } else {
        echo "- Not found: $file\n";
    }
}

// Delete storage/framework/cache files
$cacheDirs = [
    __DIR__ . '/storage/framework/cache/data',
    __DIR__ . '/storage/framework/views',
    __DIR__ . '/storage/framework/sessions',
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        $count = 0;
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                unlink($file);
                $count++;
            }
        }
        echo "✓ Deleted $count files from: $dir\n";
    }
}

echo "\n=== Regenerating config cache ===\n";
// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Clear config cache via Artisan
try {
    $kernel->call('config:clear');
    echo "✓ Config cache cleared via Artisan\n";
} catch (\Exception $e) {
    echo "✗ Error clearing config: " . $e->getMessage() . "\n";
}

echo "\n=== Testing migration after cache clear ===\n";
try {
    $kernel->call('migrate', ['--force' => true, '--pretend' => true]);
    echo "✓ Migration pretend succeeded!\n";
} catch (\Exception $e) {
    echo "✗ Migration still fails: " . $e->getMessage() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nDone!\n";
