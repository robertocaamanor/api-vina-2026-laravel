<?php

$builderFile = __DIR__ . '/vendor/laravel/framework/src/Illuminate/Database/Schema/MySqlBuilder.php';

echo "=== Reading current MySqlBuilder.php ===\n";
$content = file_get_contents($builderFile);

// Show lines around 43
$lines = explode("\n", $content);
echo "Lines 38-48:\n";
for ($i = 37; $i < 48 && $i < count($lines); $i++) {
    echo ($i + 1) . ": " . $lines[$i] . "\n";
}

echo "\n=== Creating backup ===\n";
copy($builderFile, $builderFile . '.backup');
echo "✓ Backup created: MySqlBuilder.php.backup\n";

echo "\n=== Applying complete method replacement ===\n";

// Find and replace the entire hasTable method
$pattern = '/public function hasTable\(\$table\)\s*\{[^}]*\$this->connection->getTablePrefix\(\)[^}]*\}/s';

$replacement = 'public function hasTable($table)
    {
        // DEBUG: Log everything
        $debugFile = __DIR__."/../../../../../../debug-prefix.txt";
        file_put_contents($debugFile, "=== hasTable() called ===\n", FILE_APPEND);
        
        try {
            $prefix = $this->connection->getTablePrefix();
            $prefixType = gettype($prefix);
            
            file_put_contents($debugFile, "Prefix type: $prefixType\n", FILE_APPEND);
            file_put_contents($debugFile, "Prefix value: " . var_export($prefix, true) . "\n", FILE_APPEND);
            
            // Auto-fix: Convert array to empty string
            if (is_array($prefix)) {
                file_put_contents($debugFile, "WARNING: Prefix was array, converting to empty string\n", FILE_APPEND);
                $prefix = "";
            }
            
            $table = $prefix.$table;
            
            file_put_contents($debugFile, "Final table name: $table\n", FILE_APPEND);
            
            return count($this->connection->selectFromWriteConnection(
                $this->grammar->compileTableExists(), [$this->connection->getDatabaseName(), $table]
            )) > 0;
            
        } catch (\Exception $e) {
            file_put_contents($debugFile, "EXCEPTION: " . $e->getMessage() . "\n", FILE_APPEND);
            file_put_contents($debugFile, $e->getTraceAsString() . "\n", FILE_APPEND);
            throw $e;
        }
    }';

$newContent = preg_replace($pattern, $replacement, $content);

if ($newContent === $content) {
    echo "✗ Pattern did not match! Trying alternative approach...\n";
    
    // Alternative: Find by line numbers
    $lines = explode("\n", $content);
    $start = -1;
    $end = -1;
    
    for ($i = 0; $i < count($lines); $i++) {
        if (strpos($lines[$i], 'public function hasTable($table)') !== false) {
            $start = $i;
        }
        if ($start !== -1 && $end === -1 && strpos($lines[$i], '    }') !== false && $i > $start) {
            $end = $i;
            break;
        }
    }
    
    if ($start !== -1 && $end !== -1) {
        echo "Found method from line " . ($start + 1) . " to " . ($end + 1) . "\n";
        
        // Replace lines
        $newLines = array_slice($lines, 0, $start);
        $newLines = array_merge($newLines, explode("\n", $replacement));
        $newLines = array_merge($newLines, array_slice($lines, $end + 1));
        
        $newContent = implode("\n", $newLines);
    }
}

file_put_contents($builderFile, $newContent);
echo "✓ MySqlBuilder.php patched\n";

echo "\n=== Verifying patch ===\n";
$lines = explode("\n", file_get_contents($builderFile));
echo "Lines 38-48 after patch:\n";
for ($i = 37; $i < 48 && $i < count($lines); $i++) {
    echo ($i + 1) . ": " . $lines[$i] . "\n";
}

echo "\nNow run: php artisan migrate --force\n";
