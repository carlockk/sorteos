<?php
session_start();
$provider = $_GET['provider'] ?? '';
// En una aplicación real, aquí iría el flujo OAuth
// A modo de demostración, simplemente guardamos el proveedor
if ($provider) {
    $_SESSION['user'] = [
        'name' => 'Demo User',
        'provider' => $provider
    ];
    header('Location: dashboard.php');
    exit;
}
header('Location: index.php');
