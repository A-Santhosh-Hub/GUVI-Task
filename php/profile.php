<?php
header('Content-Type: application/json');

// Read token from Authorization header (sent from localStorage via JS)
$headers = apache_request_headers();
$token   = $headers['Authorization'] ?? '';

if (empty($token)) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Verify token in Redis
try {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $user_id = $redis->get("session:$token");

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired. Please login again.']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Redis error: ' . $e->getMessage()]);
    exit;
}

// MongoDB: store/retrieve profile details
try {
    $manager        = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $db_name        = "guvi_task";
    $collection     = "profiles";
    $namespace      = "$db_name.$collection";

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Fetch profile
        $filter = ['user_id' => (int)$user_id];
        $query  = new MongoDB\Driver\Query($filter);
        $cursor = $manager->executeQuery($namespace, $query);
        $result = $cursor->toArray();

        if (count($result) > 0) {
            $profile = (array) $result[0];
            // Remove MongoDB ObjectId (_id) to avoid JSON serialization issues
            unset($profile['_id']);
            echo json_encode(['status' => 'success', 'data' => $profile]);
        } else {
            echo json_encode(['status' => 'success', 'data' => null]);
        }

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Save/update profile
        $age     = $_POST['age']     ?? '';
        $dob     = $_POST['dob']     ?? '';
        $contact = $_POST['contact'] ?? '';
        $address = $_POST['address'] ?? '';

        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->update(
            ['user_id' => (int)$user_id],
            ['$set'    => [
                'user_id' => (int)$user_id,
                'age'     => $age,
                'dob'     => $dob,
                'contact' => $contact,
                'address' => $address,
            ]],
            ['upsert'  => true]
        );
        $manager->executeBulkWrite($namespace, $bulk);

        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully.']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'MongoDB error: ' . $e->getMessage()]);
}
?>
