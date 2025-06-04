<?php
session_start();
if (!isset($_SESSION['user'])) {
    http_response_code(403);
    echo json_encode(['error' => 'no session']);
    exit;
}
require_once '../src/db.php';
$stmt = $pdo->query("SELECT comments.id, comments.text, users.name FROM comments JOIN users ON comments.user_id = users.id ORDER BY RAND() LIMIT 1");
$winner = $stmt->fetch();
if ($winner) {
    $pdo->prepare('INSERT INTO raffle_results (raffle_id, comment_id) VALUES (1, ?)')->execute([$winner['id']]);
}
header('Content-Type: application/json');
echo json_encode($winner ?: []);

