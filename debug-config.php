<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Diagnóstico de configuración de DB ===\n\n";

$keys = [
    'database.connections.mysql.host',
    'database.connections.mysql.port',
    'database.connections.mysql.database',
    'database.connections.mysql.username',
    'database.connections.mysql.prefix',
    'database.migrations.table',
];

foreach ($keys as $key) {
    $value = config($key);
    $type = gettype($value);
    $display = $type === 'array' ? json_encode($value) : var_export($value, true);
    echo "$key => tipo: $type, valor: $display\n";
}

echo "\n=== Valores ENV directos ===\n\n";

$envKeys = ['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PREFIX'];

foreach ($envKeys as $key) {
    $value = env($key);
    $type = gettype($value);
    $display = $type === 'array' ? json_encode($value) : var_export($value, true);
    echo "$key => tipo: $type, valor: $display\n";
}

echo "\n=== Test de getTablePrefix() ===\n\n";

try {
    $connection = \Illuminate\Support\Facades\DB::connection('mysql');
    $prefix = $connection->getTablePrefix();
    echo "getTablePrefix() tipo: " . gettype($prefix) . ", valor: " . var_export($prefix, true) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
