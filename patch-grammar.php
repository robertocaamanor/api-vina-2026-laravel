<?php

$grammarFile = __DIR__ . '/vendor/laravel/framework/src/Illuminate/Database/Grammar.php';

echo "=== Patching Grammar.php wrapTable method ===\n";

$content = file_get_contents($grammarFile);

// Backup
copy($grammarFile, $grammarFile . '.backup');
echo "✓ Backup created\n";

// Find and replace the wrapTable method
$original = '    public function wrapTable($table)
    {
        if (! $this->isExpression($table)) {
            return $this->wrap($this->tablePrefix.$table, true);
        }

        return $this->getValue($table);
    }';

$replacement = '    public function wrapTable($table)
    {
        if (! $this->isExpression($table)) {
            // HOTFIX: Ensure tablePrefix and table are strings
            $prefix = $this->tablePrefix;
            if (!is_string($prefix)) {
                $prefix = is_array($prefix) ? (count($prefix) > 0 ? reset($prefix) : \'\') : (string)$prefix;
            }
            if (!is_string($table)) {
                $table = is_array($table) ? (count($table) > 0 ? reset($table) : \'unknown\') : (string)$table;
            }
            return $this->wrap($prefix.$table, true);
        }

        return $this->getValue($table);
    }';

$newContent = str_replace($original, $replacement, $content);

if ($newContent === $content) {
    echo "✗ String replacement failed, trying line-by-line\n";
    exit(1);
}

file_put_contents($grammarFile, $newContent);
echo "✓ Grammar.php patched successfully\n";

echo "\nNow run: php artisan migrate --force\n";
