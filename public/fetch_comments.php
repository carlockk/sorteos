<?php
session_start();
require_once '../src/config.php';
require_once '../src/db.php';

header('Content-Type: application/json');
$token = $_SESSION['page_access_token'] ?? null;
$postId = $_GET['id'] ?? '';
if (!$token || !$postId) {
    echo json_encode([]);
    exit;
}
$url = 'https://graph.facebook.com/v19.0/'.$postId.'/comments?fields=id,username,text&access_token='.$token;
$data = json_decode(file_get_contents($url), true);
$comments = $data['data'] ?? [];
// Store comments in DB
foreach ($comments as $c) {
    $username = $c['username'];
    $text = $c['text'];
    $externalId = $c['id'];
    // find or create user
    $stmt = $pdo->prepare('SELECT id FROM users WHERE name=? AND provider=?');
    $stmt->execute([$username, 'instagram']);
    $u = $stmt->fetch();
    if (!$u) {
        $stmt = $pdo->prepare('INSERT INTO users (name, provider) VALUES (?, ?)');
        $stmt->execute([$username, 'instagram']);
        $uid = $pdo->lastInsertId();
    } else {
        $uid = $u['id'];
    }
    $stmt = $pdo->prepare('SELECT id FROM posts WHERE external_id=?');
    $stmt->execute([$postId]);
    $p = $stmt->fetch();
    $internalPostId = $p ? $p['id'] : null;
    if ($internalPostId && !empty($text)) {
        // insert comment if not exists
        $stmt = $pdo->prepare('SELECT id FROM comments WHERE external_id=?');
        $stmt->execute([$externalId]);
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare('INSERT INTO comments (user_id, post_id, text, external_id) VALUES (?, ?, ?, ?)');
            $stmt->execute([$uid, $internalPostId, $text, $externalId]);
        }
    }
}
echo json_encode($comments);
?>
