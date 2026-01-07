<?php

$builderFile = __DIR__ . '/vendor/laravel/framework/src/Illuminate/Database/Schema/MySqlBuilder.php';

echo "=== Creating complete MySqlBuilder.php with full debugging ===\n";

$newContent = <<<'PHP'
<?php

namespace Illuminate\Database\Schema;

class MySqlBuilder extends Builder
{
    /**
     * Create a database in the schema.
     *
     * @param  string  $name
     * @return bool
     */
    public function createDatabase($name)
    {
        return $this->connection->statement(
            $this->grammar->compileCreateDatabase($name, $this->connection)
        );
    }

    /**
     * Drop a database from the schema if the database exists.
     *
     * @param  string  $name
     * @return bool
     */
    public function dropDatabaseIfExists($name)
    {
        return $this->connection->statement(
            $this->grammar->compileDropDatabaseIfExists($name)
        );
    }

    /**
     * Determine if the given table exists.
     *
     * @param  string  $table
     * @return bool
     */
    public function hasTable($table)
    {
        $debugFile = __DIR__."/../../../../../../debug-hastable.txt";
        
        // Log incoming table parameter
        file_put_contents($debugFile, "=== hasTable() called ===\n", FILE_APPEND);
        file_put_contents($debugFile, "Table param type: " . gettype($table) . "\n", FILE_APPEND);
        file_put_contents($debugFile, "Table param value: " . var_export($table, true) . "\n", FILE_APPEND);
        
        $prefix = $this->connection->getTablePrefix();
        file_put_contents($debugFile, "Prefix type: " . gettype($prefix) . "\n", FILE_APPEND);
        file_put_contents($debugFile, "Prefix value: " . var_export($prefix, true) . "\n", FILE_APPEND);
        
        // HOTFIX: Auto-convert array to string for BOTH prefix and table
        if (is_array($prefix)) {
            file_put_contents($debugFile, "WARNING: Prefix was array, converting to empty string\n", FILE_APPEND);
            $prefix = '';
        }
        
        if (is_array($table)) {
            file_put_contents($debugFile, "WARNING: Table was array, converting to first element\n", FILE_APPEND);
            $table = is_array($table) && count($table) > 0 ? $table[0] : 'unknown';
        }
        
        $table = $prefix.$table;
        file_put_contents($debugFile, "Final table: " . $table . "\n", FILE_APPEND);

        return count($this->connection->selectFromWriteConnection(
            $this->grammar->compileTableExists(), [$this->connection->getDatabaseName(), $table]
        )) > 0;
    }

    /**
     * Get the column listing for a given table.
     *
     * @param  string  $table
     * @return array
     */
    public function getColumnListing($table)
    {
        $prefix = $this->connection->getTablePrefix();
        // HOTFIX: Auto-convert array to string for BOTH prefix and table
        if (is_array($prefix)) {
            $prefix = '';
        }
        if (is_array($table)) {
            $table = is_array($table) && count($table) > 0 ? $table[0] : 'unknown';
        }
        $table = $prefix.$table;

        $results = $this->connection->selectFromWriteConnection(
            $this->grammar->compileColumnListing(), [$this->connection->getDatabaseName(), $table]
        );

        return $this->connection->getPostProcessor()->processColumnListing($results);
    }

    /**
     * Drop all tables from the database.
     *
     * @return void
     */
    public function dropAllTables()
    {
        $tables = [];

        foreach ($this->getAllTables() as $row) {
            $row = (array) $row;

            $tables[] = reset($row);
        }

        if (empty($tables)) {
            return;
        }

        $this->disableForeignKeyConstraints();

        $this->connection->statement(
            $this->grammar->compileDropAllTables($tables)
        );

        $this->enableForeignKeyConstraints();
    }

    /**
     * Drop all views from the database.
     *
     * @return void
     */
    public function dropAllViews()
    {
        $views = [];

        foreach ($this->getAllViews() as $row) {
            $row = (array) $row;

            $views[] = reset($row);
        }

        if (empty($views)) {
            return;
        }

        $this->connection->statement(
            $this->grammar->compileDropAllViews($views)
        );
    }

    /**
     * Get all of the table names for the database.
     *
     * @return array
     */
    public function getAllTables()
    {
        return $this->connection->select(
            $this->grammar->compileGetAllTables()
        );
    }

    /**
     * Get all of the view names for the database.
     *
     * @return array
     */
    public function getAllViews()
    {
        return $this->connection->select(
            $this->grammar->compileGetAllViews()
        );
    }
}

PHP;

// Backup current file
copy($builderFile, $builderFile . '.backup3');
echo "✓ Backup created: MySqlBuilder.php.backup3\n";

// Write new content
file_put_contents($builderFile, $newContent);
echo "✓ MySqlBuilder.php replaced with full debugging + hotfix version\n";

echo "\nNow run: php artisan migrate --force\n";
echo "Then check: cat debug-hastable.txt\n";
