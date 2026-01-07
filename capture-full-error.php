<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Set error handler to capture full details
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $debugFile = __DIR__ . '/debug-full-error.txt';
    
    $output = "=== Error Captured ===\n";
    $output .= "Error: $errstr\n";
    $output .= "File: $errfile\n";
    $output .= "Line: $errline\n\n";
    $output .= "Backtrace:\n";
    $output .= print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true);
    $output .= "\n\n";
    
    file_put_contents($debugFile, $output, FILE_APPEND);
    
    // Return false to let PHP's normal error handler run
    return false;
});

echo "=== Running migrate with full error capture ===\n";

try {
    $kernel->call('migrate', ['--force' => true]);
    echo "✓ Migration succeeded!\n";
} catch (\Exception $e) {
    echo "✗ Migration failed: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    
    $debugFile = __DIR__ . '/debug-full-error.txt';
    file_put_contents($debugFile, "=== Exception ===\n", FILE_APPEND);
    file_put_contents($debugFile, $e->getMessage() . "\n", FILE_APPEND);
    file_put_contents($debugFile, $e->getFile() . " line " . $e->getLine() . "\n", FILE_APPEND);
    file_put_contents($debugFile, $e->getTraceAsString() . "\n\n", FILE_APPEND);
}

echo "\nCheck debug-full-error.txt for details\n";
