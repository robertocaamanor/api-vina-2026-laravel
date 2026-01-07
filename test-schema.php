<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test de Schema Builder ===\n\n";

try {
    $schema = \Illuminate\Support\Facades\DB::connection('mysql')->getSchemaBuilder();
    
    echo "1. Testing hasTable('migrations')...\n";
    $result = $schema->hasTable('migrations');
    echo "   Result: " . ($result ? 'true' : 'false') . "\n\n";
    
    echo "2. Testing hasTable('users')...\n";
    $result = $schema->hasTable('users');
    echo "   Result: " . ($result ? 'true' : 'false') . "\n\n";
    
    echo "3. Getting migrations table name from config...\n";
    $migrationsTable = config('database.migrations.table');
    echo "   Type: " . gettype($migrationsTable) . "\n";
    echo "   Value: " . var_export($migrationsTable, true) . "\n\n";
    
    echo "4. Testing hasTable with migrations config value...\n";
    $result = $schema->hasTable($migrationsTable);
    echo "   Result: " . ($result ? 'true' : 'false') . "\n\n";
    
    echo "✅ All tests passed!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
