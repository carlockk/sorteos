<?php
session_start();
$provider = $_GET['provider'] ?? '';
require_once '../src/db.php';
// En una aplicación real iría el flujo OAuth.
// Para la demo creamos o recuperamos el usuario en la base de datos.
if ($provider) {
    $name = 'Demo User';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name=? AND provider=?");
    $stmt->execute([$name, $provider]);
    $user = $stmt->fetch();
    if (!$user) {
        $stmt = $pdo->prepare("INSERT INTO users (name, provider) VALUES (?, ?)");
        $stmt->execute([$name, $provider]);
        $id = $pdo->lastInsertId();
        $user = ['id' => $id, 'name' => $name, 'provider' => $provider];
    }
    $_SESSION['user'] = $user;
    header('Location: dashboard.php');
    exit;
}
header('Location: index.php');
