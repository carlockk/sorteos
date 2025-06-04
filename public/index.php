<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorteos App - Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Sorteos App</h1>
        <p>Inicie sesión con su red social</p>
        <a class="button" 
           href="login.php?provider=facebook">Entrar con Facebook</a>
        <a class="button" 
           href="login.php?provider=instagram">Entrar con Instagram</a>
    </div>
</body>
</html>
