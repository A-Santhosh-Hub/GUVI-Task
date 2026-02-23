<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // MySQL: verify credentials using prepared statements
    require_once 'db.php';

    try {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            // Generate session token
            $token = bin2hex(random_bytes(32));

            // Store token -> user_id in Redis with 1 hour expiry
            try {
                $redis = new Redis();
                $redis->connect('127.0.0.1', 6379);
                $redis->setex("session:$token", 3600, $user['id']);

                echo json_encode([
                    'status'   => 'success',
                    'token'    => $token,
                    'user_id'  => $user['id'],
                    'username' => $user['username']
                ]);

            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Redis error: ' . $e->getMessage()]);
            }

        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
        }

    } catch (\PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
