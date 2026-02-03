<?php
require_once __DIR__ . '/Config/database.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS `applications` (
        `id` int PRIMARY KEY AUTO_INCREMENT,
        `user_id` int NOT NULL,
        `announcement_id` int NOT NULL,
        `full_name` varchar(100) NOT NULL,
        `email` varchar(150) NOT NULL,
        `cover_letter` text,
        `resume_path` varchar(255),
        `status` enum('pending','accepted','rejected') DEFAULT 'pending',
        `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE
    )";

    $pdo->exec($sql);
    echo "Applications table created successfully!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>