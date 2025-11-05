<?php
/**
 * Simple PostgreSQL Migration Script
 * Execute the schema.pgsql file directly
 */

require_once __DIR__ . '/../src/config/database.php';

try {
    $db = db();
    
    echo "Reading schema file...\n";
    $schemaFile = __DIR__ . '/schema.pgsql';
    if (!file_exists($schemaFile)) {
        die("Error: schema.pgsql file not found!\n");
    }
    
    $schema = file_get_contents($schemaFile);
    
    echo "Executing schema...\n\n";
    
    // Execute the entire schema as one transaction
    // PostgreSQL allows multiple statements in one exec() call
    try {
        $db->exec($schema);
        echo "✅ Schema executed successfully!\n";
        echo "All tables should now be created.\n";
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        echo "\nTrying to execute statements individually...\n\n";
        
        // Fallback: execute statements one by one
        $statements = array_filter(
            array_map('trim', explode(';', $schema)),
            function($stmt) {
                return !empty($stmt) && strpos(trim($stmt), '--') !== 0;
            }
        );
        
        $success = 0;
        $failed = 0;
        
        foreach ($statements as $stmt) {
            if (empty(trim($stmt))) continue;
            
            try {
                $db->exec($stmt . ';');
                $success++;
            } catch (PDOException $e) {
                $failed++;
                echo "Failed: " . substr($stmt, 0, 50) . "...\n";
                echo "  Error: " . $e->getMessage() . "\n";
            }
        }
        
        echo "\nResults: $success successful, $failed failed\n";
    }
    
    // Verify tables were created
    echo "\nVerifying tables...\n";
    $tables = $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name")->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "✅ Created tables:\n";
        foreach ($tables as $table) {
            echo "  - $table\n";
        }
    } else {
        echo "⚠️  No tables found. Migration may have failed.\n";
    }
    
} catch (Exception $e) {
    die("Fatal error: " . $e->getMessage() . "\n");
}

