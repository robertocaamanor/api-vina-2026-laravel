<?php

echo "=== Checking debug-prefix.txt ===\n";
if (file_exists(__DIR__ . '/debug-prefix.txt')) {
    echo "File exists! Content:\n";
    echo file_get_contents(__DIR__ . '/debug-prefix.txt');
} else {
    echo "File does NOT exist\n";
}

echo "\n=== Restoring and re-patching MySqlBuilder.php ===\n";

// First, restore original
$builderFile = __DIR__ . '/vendor/laravel/framework/src/Illuminate/Database/Schema/MySqlBuilder.php';
exec("cd " . __DIR__ . " && git checkout vendor/laravel/framework/src/Illuminate/Database/Schema/MySqlBuilder.php 2>&1", $output, $code);
echo "Git checkout result: $code\n";

// Read original
$builderCode = file_get_contents($builderFile);

// More aggressive patch - wrap everything in try-catch
$original = "    public function hasTable(\$table)\n    {\n        \$table = \$this->connection->getTablePrefix().\$table;";

$patched = "    public function hasTable(\$table)\n    {\n        try {\n            \$prefix = \$this->connection->getTablePrefix();\n            \$prefixType = gettype(\$prefix);\n            \$prefixValue = var_export(\$prefix, true);\n            file_put_contents(__DIR__.'/../../../../../../debug-prefix.txt', \"Type: \$prefixType\\nValue: \$prefixValue\\n\", FILE_APPEND);\n            if (is_array(\$prefix)) {\n                \$prefix = '';\n                file_put_contents(__DIR__.'/../../../../../../debug-prefix.txt', \"WARNING: Prefix was array, converted to empty string\\n\", FILE_APPEND);\n            }\n            \$table = \$prefix.\$table;\n        } catch (\\Exception \$e) {\n            file_put_contents(__DIR__.'/../../../../../../debug-prefix.txt', \"EXCEPTION: \" . \$e->getMessage() . \"\\n\" . \$e->getTraceAsString() . \"\\n\", FILE_APPEND);\n            throw \$e;\n        }";

$builderCode = str_replace($original, $patched, $builderCode);
file_put_contents($builderFile, $builderCode);

echo "✓ MySqlBuilder.php patched with aggressive debug + auto-fix\n";
echo "\nNow run: php artisan migrate --force\n";
