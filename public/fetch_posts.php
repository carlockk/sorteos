<?php
session_start();
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/http.php';

header('Content-Type: application/json');

$token = $_SESSION['page_access_token'] ?? null;
$igId = $_SESSION['instagram_id'] ?? null;
if (!$token || !$igId) {
    echo json_encode(['error' => 'not authenticated']);
    exit;
}
$url = 'https://graph.facebook.com/v19.0/'.$igId.'/media?fields=id,caption&access_token='.$token;
$data = http_get_json($url);
if (!empty($data['error'])) {
    echo json_encode([]);
    exit;
}
$posts = $data['data'] ?? [];
foreach ($posts as $p) {
    $externalId = $p['id'];
    $caption = $p['caption'] ?? '';
    $stmt = $pdo->prepare('SELECT id FROM posts WHERE external_id=?');
    $stmt->execute([$externalId]);
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare('INSERT INTO posts (external_id, caption) VALUES (?, ?)');
        $stmt->execute([$externalId, $caption]);
    }
}
echo json_encode($posts);
?>
