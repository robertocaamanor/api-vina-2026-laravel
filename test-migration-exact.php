<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Exact Migration Flow ===\n\n";

// Get the database connection
$db = $app->make('db');
$connection = $db->connection();

echo "Connection class: " . get_class($connection) . "\n";
echo "Driver name: " . $connection->getDriverName() . "\n";

// Get the table prefix (this is what MySqlBuilder.php line 41 uses)
echo "\n=== Testing getTablePrefix() ===\n";
try {
    $prefix = $connection->getTablePrefix();
    echo "Prefix type: " . gettype($prefix) . "\n";
    echo "Prefix value: " . var_export($prefix, true) . "\n";
    
    if (is_array($prefix)) {
        echo "ERROR: Prefix is an array!\n";
        print_r($prefix);
    }
} catch (\Exception $e) {
    echo "Error getting prefix: " . $e->getMessage() . "\n";
}

// Get the schema builder
echo "\n=== Testing Schema Builder ===\n";
try {
    $schema = $connection->getSchemaBuilder();
    echo "Schema builder class: " . get_class($schema) . "\n";
    
    // Get the grammar (MySqlBuilder.php)
    $grammar = $schema->getConnection()->getSchemaGrammar();
    echo "Grammar class: " . get_class($grammar) . "\n";
    
    // Try to get table prefix from grammar
    echo "\n=== Testing Grammar getTablePrefix() ===\n";
    $grammarPrefix = $grammar->getTablePrefix();
    echo "Grammar prefix type: " . gettype($grammarPrefix) . "\n";
    echo "Grammar prefix value: " . var_export($grammarPrefix, true) . "\n";
    
    if (is_array($grammarPrefix)) {
        echo "ERROR: Grammar prefix is an array!\n";
        print_r($grammarPrefix);
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

// Test the actual hasTable method that triggers the error
echo "\n=== Testing hasTable() Method ===\n";
try {
    $exists = $schema->hasTable('migrations');
    echo "hasTable('migrations'): " . ($exists ? 'true' : 'false') . "\n";
} catch (\Exception $e) {
    echo "ERROR in hasTable(): " . $e->getMessage() . "\n";
    echo "\nFull trace:\n";
    echo $e->getTraceAsString() . "\n";
}

// Dump all connection config
echo "\n=== Full Connection Config ===\n";
$config = $connection->getConfig();
foreach ($config as $key => $value) {
    echo "$key: " . gettype($value) . " = " . var_export($value, true) . "\n";
}
