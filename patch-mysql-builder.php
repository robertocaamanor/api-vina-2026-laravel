<?php

// Patch MySqlBuilder to debug the exact issue
$builderFile = __DIR__ . '/vendor/laravel/framework/src/Illuminate/Database/Schema/MySqlBuilder.php';
$builderCode = file_get_contents($builderFile);

// Add debug output before line 41
$original = "    public function hasTable(\$table)\n    {\n        \$table = \$this->connection->getTablePrefix().\$table;";

$patched = "    public function hasTable(\$table)\n    {\n        \$prefix = \$this->connection->getTablePrefix();\n        file_put_contents(__DIR__.'/../../../../../../debug-prefix.txt', \"Prefix type: \" . gettype(\$prefix) . \"\\n\" . \"Prefix value: \" . var_export(\$prefix, true) . \"\\n\" . \"Stack: \" . print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true), FILE_APPEND);\n        \$table = \$prefix.\$table;";

if (strpos($builderCode, 'file_put_contents') === false) {
    $builderCode = str_replace($original, $patched, $builderCode);
    file_put_contents($builderFile, $builderCode);
    echo "✓ MySqlBuilder.php patched with debug code\n";
} else {
    echo "- MySqlBuilder.php already patched\n";
}

echo "\nNow run: php artisan migrate --force\n";
echo "Then check: cat debug-prefix.txt\n";
