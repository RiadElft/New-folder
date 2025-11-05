<?php
/**
 * PostgreSQL Migration Script - Direct Connection
 * Executes schema.pgsql using the provided connection string
 */

// Connection string from user
$connectionString = "postgresql://sultan_43oo_user:SZX8pLJ3BJaTWBAxKZuNJw2YRzeGQWW2@dpg-d45kd03uibrs73f7nm1g-a.frankfurt-postgres.render.com/sultan_43oo";

try {
    // Connect to database
    echo "Connecting to PostgreSQL database...\n";
    $db = new PDO($connectionString);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected successfully!\n\n";
    
    // Read schema file
    $schemaFile = __DIR__ . '/schema.pgsql';
    if (!file_exists($schemaFile)) {
        die("❌ Error: schema.pgsql file not found at: $schemaFile\n");
    }
    
    echo "Reading schema file...\n";
    $schema = file_get_contents($schemaFile);
    
    // Remove comments and split by semicolon
    $lines = explode("\n", $schema);
    $statements = [];
    $currentStatement = '';
    $inFunction = false;
    $functionDelimiter = '';
    
    foreach ($lines as $line) {
        $trimmed = trim($line);
        
        // Skip empty lines and comments
        if (empty($trimmed) || strpos($trimmed, '--') === 0) {
            continue;
        }
        
        // Check for function definition
        if (preg_match('/CREATE\s+(OR\s+REPLACE\s+)?FUNCTION/i', $trimmed)) {
            $inFunction = true;
            $currentStatement = $trimmed . "\n";
            // Find the delimiter ($$)
            if (preg_match('/\$\$/', $trimmed)) {
                $functionDelimiter = '$$';
            }
            continue;
        }
        
        // If in function, accumulate until we find the end
        if ($inFunction) {
            $currentStatement .= $line . "\n";
            // Check for function end
            if (preg_match('/\$\$.*language/i', $trimmed) || (preg_match('/language\s+[\'"]plpgsql[\'"]/i', $trimmed) && preg_match('/\$\$/', $trimmed))) {
                $statements[] = trim($currentStatement);
                $currentStatement = '';
                $inFunction = false;
                $functionDelimiter = '';
            }
            continue;
        }
        
        // Regular statement
        $currentStatement .= $line . "\n";
        
        // If line ends with semicolon, it's a complete statement
        if (substr(rtrim($trimmed), -1) === ';') {
            $statements[] = trim($currentStatement);
            $currentStatement = '';
        }
    }
    
    // Add any remaining statement
    if (!empty(trim($currentStatement))) {
        $statements[] = trim($currentStatement);
    }
    
    echo "Found " . count($statements) . " statements to execute\n\n";
    echo "Executing migration...\n\n";
    
    $successCount = 0;
    $errorCount = 0;
    $errors = [];
    
    foreach ($statements as $index => $statement) {
        $statement = trim($statement);
        if (empty($statement)) {
            continue;
        }
        
        // Extract statement type for logging
        preg_match('/^\s*(DROP|CREATE|ALTER)\s+(TABLE|FUNCTION|TRIGGER|INDEX|OR\s+REPLACE\s+FUNCTION)?/i', $statement, $matches);
        $stmtType = !empty($matches) ? $matches[0] : 'STATEMENT';
        
        // Extract table/object name if available
        preg_match('/(?:TABLE|FUNCTION|TRIGGER|INDEX)\s+[IF\s+NOT\s+EXISTS\s+]?([a-z_]+)/i', $statement, $nameMatches);
        $objectName = !empty($nameMatches) ? $nameMatches[1] : '';
        
        $description = $stmtType . (!empty($objectName) ? " $objectName" : '');
        
        try {
            echo "  [$index] Executing: $description... ";
            $db->exec($statement);
            echo "✅\n";
            $successCount++;
        } catch (PDOException $e) {
            $errorCount++;
            $errorMsg = $e->getMessage();
            echo "❌\n";
            echo "     Error: $errorMsg\n";
            $errors[] = [
                'statement' => substr($statement, 0, 100),
                'error' => $errorMsg
            ];
            
            // Continue with next statement
            continue;
        }
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "Migration Summary:\n";
    echo "  ✅ Successful: $successCount statements\n";
    echo "  ❌ Failed: $errorCount statements\n";
    echo str_repeat("=", 60) . "\n";
    
    // Verify tables were created
    echo "\nVerifying created tables...\n";
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
            echo "\n⚠️  Missing tables: " . implode(', ', $missing) . "\n";
        }
    } else {
        echo "⚠️  No tables found. Migration may have failed.\n";
    }
    
    if ($errorCount > 0 && !empty($errors)) {
        echo "\n⚠️  Errors encountered:\n";
        foreach ($errors as $error) {
            echo "  - " . $error['error'] . "\n";
            echo "    Statement: " . substr($error['statement'], 0, 80) . "...\n";
        }
    }
    
    if ($successCount > 0 && $errorCount === 0) {
        echo "\n🎉 Migration completed successfully!\n";
    }
    
} catch (Exception $e) {
    die("❌ Fatal error: " . $e->getMessage() . "\n");
}

