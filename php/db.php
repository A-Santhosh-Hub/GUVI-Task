<?php
// Database configuration
$host = getenv('MYSQL_HOST') ?: "localhost";
$user = getenv('MYSQL_USER') ?: "root";
$password = getenv('MYSQL_PASS') ?: "";
$db = getenv('MYSQL_DB') ?: "guvi_task";
$port = getenv('MYSQL_PORT') ?: "3306";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (\PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
?>
