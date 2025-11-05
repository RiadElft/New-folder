<?php
/**
 * Database Migration Script
 * Run this once by visiting: https://your-app-url.onrender.com/migrate-db.php
 * DELETE THIS FILE after migration is complete for security!
 */

// Security: Simple check - you can add more security if needed
// For now, just check if it's a GET request (you can delete this file after migration)
$isProduction = (getenv('APP_ENV') === 'production');
if ($isProduction) {
    $secretKey = 'RUN_MIGRATION_2025'; // Simple key for production
    $providedKey = $_GET['key'] ?? '';
    if ($providedKey !== $secretKey) {
        die('❌ Add ?key=RUN_MIGRATION_2025 to the URL to run migration');
    }
}

// Load database connection
require_once __DIR__ . '/../src/config/database.php';

header('Content-Type: text/plain');
echo "🚀 Starting Database Migration...\n\n";

try {
    $db = db();
    
    // Read schema file
    $schemaFile = __DIR__ . '/../database/run-migration.sql';
    if (!file_exists($schemaFile)) {
        die("❌ Error: Migration file not found at: $schemaFile\n");
    }
    
    echo "📄 Reading migration file...\n";
    $schema = file_get_contents($schemaFile);
    
    // Split into statements (simple approach)
    $statements = array_filter(
        array_map('trim', explode(';', $schema)),
        function($stmt) {
            return !empty($stmt) && 
                   strpos(trim($stmt), '--') !== 0 && 
                   !preg_match('/^\s*SELECT\s/i', trim($stmt));
        }
    );
    
    echo "📊 Found " . count($statements) . " statements to execute\n\n";
    
    $success = 0;
    $failed = 0;
    $errors = [];
    
    foreach ($statements as $index => $statement) {
        $statement = trim($statement);
        if (empty($statement)) continue;
        
        // Extract statement type for logging
        preg_match('/^\s*(DROP|CREATE|ALTER)\s+(TABLE|FUNCTION|TRIGGER|INDEX|OR\s+REPLACE\s+FUNCTION)?/i', $statement, $matches);
        $stmtType = !empty($matches) ? $matches[0] : 'STATEMENT';
        
        try {
            echo "[$index] Executing: $stmtType... ";
            $db->exec($statement);
            echo "✅\n";
            $success++;
        } catch (PDOException $e) {
            $failed++;
            $errorMsg = $e->getMessage();
            
            // Ignore "already exists" errors
            if (strpos($errorMsg, 'already exists') !== false || 
                strpos($errorMsg, 'does not exist') !== false) {
                echo "⚠️ (already exists)\n";
                $success++;
                $failed--;
            } else {
                echo "❌\n";
                echo "   Error: " . substr($errorMsg, 0, 100) . "\n";
                $errors[] = $errorMsg;
            }
        }
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "✅ Successful: $success\n";
    if ($failed > 0) {
        echo "❌ Failed: $failed\n";
    }
    echo str_repeat("=", 60) . "\n\n";
    
    // Verify tables
    echo "🔍 Verifying tables...\n";
    $tables = $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name")->fetchAll(PDO::FETCH_COLUMN);
    
    $expectedTables = ['users', 'categories', 'products', 'addresses', 'orders', 'order_items', 'reviews', 'wishlist', 'newsletter'];
    
    if (count($tables) > 0) {
        echo "✅ Found " . count($tables) . " tables:\n";
        foreach ($tables as $table) {
            $mark = in_array($table, $expectedTables) ? '✅' : '  ';
            echo "  $mark $table\n";
        }
        
        $missing = array_diff($expectedTables, $tables);
        if (!empty($missing)) {
            echo "\n⚠️  Missing: " . implode(', ', $missing) . "\n";
        } else {
            echo "\n🎉 All tables created successfully!\n";
            echo "\n⚠️  IMPORTANT: Delete this file (migrate-db.php) for security!\n";
        }
    } else {
        echo "⚠️  No tables found. Check errors above.\n";
    }
    
    if (!empty($errors)) {
        echo "\n❌ Errors encountered:\n";
        foreach (array_slice($errors, 0, 5) as $error) {
            echo "  - " . substr($error, 0, 80) . "...\n";
        }
    }
    
} catch (Exception $e) {
    die("❌ Fatal error: " . $e->getMessage() . "\n");
}

