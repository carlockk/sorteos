<?php
session_start();
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/http.php';

if (!isset($_GET['state']) || $_GET['state'] !== ($_SESSION['fb_state'] ?? '')) {
    die('Invalid state');
}
$code = $_GET['code'] ?? '';
if (!$code) {
    die('No code');
}
// Exchange code for access token
$tokenUrl = 'https://graph.facebook.com/v19.0/oauth/access_token?client_id='.
    FB_APP_ID.'&redirect_uri='.urlencode(FB_REDIRECT_URI).
    '&client_secret='.FB_APP_SECRET.'&code='.$code;
$tokenData = http_get_json($tokenUrl);
$accessToken = $tokenData['access_token'] ?? null;
if (!$accessToken) {
    die('Token error');
}
$_SESSION['fb_access_token'] = $accessToken;
// Get pages with instagram business account
$pagesUrl = 'https://graph.facebook.com/v19.0/me/accounts?fields=id,name,instagram_business_account,access_token&access_token='.$accessToken;
$pagesResp = http_get_json($pagesUrl);
$page = $pagesResp['data'][0] ?? null;
if (!$page || empty($page['instagram_business_account']['id'])) {
    die('No Instagram business account');
}
$instagramId = $page['instagram_business_account']['id'];
$pageToken = $page['access_token'];
$_SESSION['instagram_id'] = $instagramId;
$_SESSION['page_access_token'] = $pageToken;
// Get user name
$userInfo = http_get_json('https://graph.facebook.com/v19.0/me?access_token='.$accessToken);
$name = $userInfo['name'] ?? 'Usuario';
// Save or update user in DB
$stmt = $pdo->prepare('SELECT id FROM users WHERE provider=? AND instagram_id=?');
$stmt->execute(['facebook', $instagramId]);
$existing = $stmt->fetch();
if ($existing) {
    $stmt = $pdo->prepare('UPDATE users SET name=?, access_token=? WHERE id=?');
    $stmt->execute([$name, $accessToken, $existing['id']]);
    $userId = $existing['id'];
} else {
    $stmt = $pdo->prepare('INSERT INTO users (name, provider, access_token, instagram_id) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, 'facebook', $accessToken, $instagramId]);
    $userId = $pdo->lastInsertId();
}
$_SESSION['user'] = ['id'=>$userId,'name'=>$name,'provider'=>'facebook'];
header('Location: dashboard.php');
exit;
?>
