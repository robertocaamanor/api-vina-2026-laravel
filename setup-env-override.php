<?php

echo "=== Creating aggressive env() override ===\n";

// Create a helper file that will be loaded BEFORE anything else
$helperFile = __DIR__ . '/bootstrap/env-helper.php';

$helperContent = <<<'PHP'
<?php

// Override env() function to ALWAYS return strings
if (!function_exists('env_original')) {
    function env_original($key, $default = null) {
        static $repository = null;
        
        if ($repository === null) {
            $repository = Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
                ->addAdapter(Dotenv\Repository\Adapter\PutenvAdapter::class)
                ->addWriter(Dotenv\Repository\Adapter\PutenvAdapter::class)
                ->immutable()
                ->make();
        }
        
        return $repository->get($key) ?? $default;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = getenv($key);
        
        if ($value === false) {
            $value = $default;
        }
        
        // HOTFIX: Force all DB_ values to be strings
        if (strpos($key, 'DB_') === 0 || in_array($key, ['SESSION_DRIVER', 'CACHE_STORE', 'QUEUE_CONNECTION'])) {
            if (is_array($value)) {
                file_put_contents(__DIR__ . '/../debug-env-override.txt', "OVERRIDE: $key was array, converting\n", FILE_APPEND);
                $value = count($value) > 0 ? reset($value) : '';
            }
            $value = (string)$value;
        }
        
        return $value;
    }
}

PHP;

file_put_contents($helperFile, $helperContent);
echo "✓ Created: $helperFile\n";

// Modify bootstrap/app.php to load this helper FIRST
$appFile = __DIR__ . '/bootstrap/app.php';
$appContent = file_get_contents($appFile);

if (strpos($appContent, 'env-helper.php') === false) {
    // Add require at the very top, after <?php
    $appContent = str_replace(
        "<?php",
        "<?php\n\nrequire __DIR__.'/env-helper.php';",
        $appContent
    );
    
    file_put_contents($appFile, $appContent);
    echo "✓ Modified: bootstrap/app.php to load env-helper.php\n";
} else {
    echo "- bootstrap/app.php already includes env-helper.php\n";
}

echo "\n=== Clearing all caches ===\n";
exec("cd " . __DIR__ . " && php artisan config:clear 2>&1", $output);
echo implode("\n", $output) . "\n";

// Delete bootstrap cache files
$cacheFiles = [
    __DIR__ . '/bootstrap/cache/config.php',
    __DIR__ . '/bootstrap/cache/routes.php',
    __DIR__ . '/bootstrap/cache/packages.php',
    __DIR__ . '/bootstrap/cache/services.php',
];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "✓ Deleted: $file\n";
    }
}

echo "\nNow run: php artisan migrate --force\n";
