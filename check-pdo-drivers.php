<?php

echo "=== PDO Drivers Available ===\n";
$drivers = PDO::getAvailableDrivers();
print_r($drivers);

echo "\n=== Looking for MySQL driver ===\n";
if (in_array('mysql', $drivers)) {
    echo "✓ PDO MySQL driver IS available\n";
    
    // Try to connect
    echo "\n=== Testing MySQL Connection ===\n";
    try {
        $host = 'localhost';
        $db = getenv('DB_DATABASE') ?: '';
        $user = getenv('DB_USERNAME') ?: '';
        $pass = getenv('DB_PASSWORD') ?: '';
        
        echo "Host: $host\n";
        echo "Database: $db\n";
        echo "Username: $user\n";
        
        $dsn = "mysql:host=$host;dbname=$db";
        $pdo = new PDO($dsn, $user, $pass);
        echo "✓ Connection successful!\n";
        
    } catch (PDOException $e) {
        echo "✗ Connection failed: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "✗ PDO MySQL driver NOT available\n";
    echo "\nAvailable drivers: " . implode(', ', $drivers) . "\n";
}

echo "\n=== PHP Extensions Related to MySQL ===\n";
$extensions = get_loaded_extensions();
$mysql_related = array_filter($extensions, function($ext) {
    return stripos($ext, 'mysql') !== false || stripos($ext, 'pdo') !== false;
});
print_r($mysql_related);
