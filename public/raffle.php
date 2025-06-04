<?php
session_start();
if (!isset($_SESSION['user'])) {
    http_response_code(403);
    echo json_encode(['error' => 'no session']);
    exit;
}
require_once '../src/db.php';
$stmt = $pdo->query("SELECT comments.text, users.name FROM comments JOIN users ON comments.user_id = users.id ORDER BY RAND() LIMIT 1");
$winner = $stmt->fetch();
header('Content-Type: application/json');
echo json_encode($winner ?: []);

