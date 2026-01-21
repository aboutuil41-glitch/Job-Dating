<?php
// Database setup script
require_once __DIR__ . '/Config/database.php';

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to MySQL server successfully!\n";
    
    // Read and execute the database setup SQL
    $sql = file_get_contents(__DIR__ . '/database_setup.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "Database setup completed successfully!\n";
    echo "Sample data inserted!\n";
    echo "\nTest credentials:\n";
    echo "Email: admin@jobdating.com\n";
    echo "Password: password\n";
    echo "\nOr:\n";
    echo "Email: john@student.com\n";
    echo "Password: password\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>