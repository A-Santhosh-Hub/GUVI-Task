<?php
require_once 'php/db.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ";
    
    $pdo->exec($sql);
    echo "<h1>Database Setup Successful!</h1>";
    echo "<p>The 'users' table has been created in your remote Aiven database.</p>";
    echo "<p>You can now delete this file and <a href='register.html'>go back to register</a>.</p>";
} catch (PDOException $e) {
    echo "<h1>Error setting up database:</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
