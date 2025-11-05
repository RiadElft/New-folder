<?php
/**
 * PostgreSQL Migration Script
 * Run this script to create all database tables
 * 
 * Usage: php database/migrate-pgsql.php
 */

require_once __DIR__ . '/../src/config/database.php';

try {
    $db = db();
    
    // Read the PostgreSQL schema file
    $schemaFile = __DIR__ . '/schema.pgsql';
    if (!file_exists($schemaFile)) {
        die("Error: schema.pgsql file not found!\n");
    }
    
    $schema = file_get_contents($schemaFile);
    
    // Split by semicolon and execute each statement
    // Note: We need to handle multi-line statements carefully
    $statements = [];
    $currentStatement = '';
    
    // Simple approach: Split by semicolon, but handle triggers and functions
    $lines = explode("\n", $schema);
    $inBlock = false;
    $blockType = '';
    
    foreach ($lines as $line) {
        $trimmed = trim($line);
        
        // Skip empty lines and comments
        if (empty($trimmed) || strpos($trimmed, '--') === 0) {
            continue;
        }
        
        // Check if we're entering a function/trigger block
        if (preg_match('/CREATE\s+(OR\s+REPLACE\s+)?FUNCTION/i', $trimmed)) {
            $inBlock = true;
            $blockType = 'function';
            $currentStatement = $trimmed . "\n";
            continue;
        }
        
        if (preg_match('/CREATE\s+TRIGGER/i', $trimmed)) {
            $inBlock = true;
            $blockType = 'trigger';
            $currentStatement = $trimmed . "\n";
            continue;
        }
        
        // Check if we're in a function block and looking for the end
        if ($inBlock && $blockType === 'function') {
            $currentStatement .= $line . "\n";
            if (preg_match('/\$\$.*language/i', $trimmed)) {
                $statements[] = trim($currentStatement);
                $currentStatement = '';
                $inBlock = false;
                $blockType = '';
            }
            continue;
        }
        
        // Check if we're in a trigger block
        if ($inBlock && $blockType === 'trigger') {
            $currentStatement .= $line . "\n";
            if (preg_match('/;$/i', $trimmed)) {
                $statements[] = trim($currentStatement);
                $currentStatement = '';
                $inBlock = false;
                $blockType = '';
            }
            continue;
        }
        
        // Regular statements
        $currentStatement .= $line . "\n";
        
        // If line ends with semicolon and we're not in a block, it's a complete statement
        if (substr(rtrim($trimmed), -1) === ';' && !$inBlock) {
            $statements[] = trim($currentStatement);
            $currentStatement = '';
        }
    }
    
    // Add any remaining statement
    if (!empty(trim($currentStatement))) {
        $statements[] = trim($currentStatement);
    }
    
    echo "Executing PostgreSQL schema migration...\n\n";
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $index => $statement) {
        $statement = trim($statement);
        if (empty($statement)) {
            continue;
        }
        
        // Skip comments
        if (strpos(trim($statement), '--') === 0) {
            continue;
        }
        
        try {
            // Extract first few words for logging
            $preview = substr($statement, 0, 50);
            echo "Executing: " . $preview . "...\n";
            
            $db->exec($statement);
            $successCount++;
        } catch (PDOException $e) {
            $errorCount++;
            echo "ERROR: " . $e->getMessage() . "\n";
            echo "Statement: " . substr($statement, 0, 100) . "...\n\n";
            
            // Continue with next statement
            continue;
        }
    }
    
    echo "\n=== Migration Complete ===\n";
    echo "Successful: $successCount statements\n";
    echo "Errors: $errorCount statements\n";
    
    if ($errorCount === 0) {
        echo "\n✅ All tables created successfully!\n";
    } else {
        echo "\n⚠️  Some errors occurred. Please review the output above.\n";
    }
    
} catch (Exception $e) {
    die("Fatal error: " . $e->getMessage() . "\n");
}

