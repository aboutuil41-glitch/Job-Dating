<?php
require_once __DIR__ . '/Config/database.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if test user already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute(['test@test.com']);
    
    if ($stmt->fetchColumn() == 0) {
        // Create test user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            'Test User',
            'test@test.com',
            password_hash('password', PASSWORD_BCRYPT),
            'student'
        ]);
        echo "Test user created successfully!\n";
    } else {
        echo "Test user already exists!\n";
    }
    
    echo "\nLogin credentials:\n";
    echo "Email: test@test.com\n";
    echo "Password: password\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>