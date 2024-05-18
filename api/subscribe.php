<?php
header('Content-Type: application/json');

$dbFile = '../database/database.sqlite';
$db = new PDO('sqlite:' . $dbFile);

// Initialize the database if the subscriptions table does not exist
$db->exec("CREATE TABLE IF NOT EXISTS subscriptions (id INTEGER PRIMARY KEY, email TEXT UNIQUE)");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email address']);
        exit();
    }

    $stmt = $db->prepare('SELECT * FROM subscriptions WHERE email = ?');
    $stmt->execute([$email]);
    $existing = $stmt->fetch();

    if ($existing) {
        http_response_code(409);
        echo json_encode(['error' => 'Email already exists']);
    } else {
        $stmt = $db->prepare('INSERT INTO subscriptions (email) VALUES (?)');
        if ($stmt->execute([$email])) {
            echo json_encode(['message' => 'E-mail added']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error']);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>