<?php

$builderFile = __DIR__ . '/vendor/laravel/framework/src/Illuminate/Database/Schema/MySqlBuilder.php';

echo "=== Creating complete MySqlBuilder.php replacement ===\n";

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
        $prefix = $this->connection->getTablePrefix();
        // HOTFIX: Auto-convert array to string
        if (is_array($prefix)) {
            $prefix = '';
        }
        $table = $prefix.$table;

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
        // HOTFIX: Auto-convert array to string
        if (is_array($prefix)) {
            $prefix = '';
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
copy($builderFile, $builderFile . '.backup2');
echo "✓ Backup created: MySqlBuilder.php.backup2\n";

// Write new content
file_put_contents($builderFile, $newContent);
echo "✓ MySqlBuilder.php completely replaced with hotfixed version\n";

echo "\n=== Verifying ===\n";
$lines = explode("\n", file_get_contents($builderFile));
echo "Lines 39-48 (hasTable):\n";
for ($i = 38; $i < 51 && $i < count($lines); $i++) {
    echo ($i + 1) . ": " . $lines[$i] . "\n";
}

echo "\nLines 57-66 (getColumnListing):\n";
for ($i = 56; $i < 69 && $i < count($lines); $i++) {
    echo ($i + 1) . ": " . $lines[$i] . "\n";
}

echo "\nNow run: php artisan migrate --force\n";
