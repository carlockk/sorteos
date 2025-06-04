<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Sorteos</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($user['name']); ?></h1>
        <p>Proveedor: <?php echo htmlspecialchars($user['provider']); ?></p>
        <button id="countdownBtn">Iniciar conteo 5-4-3</button>
        <div id="countdown"></div>
        <form method="POST" action="logout.php">
            <button type="submit">Cerrar sesión</button>
        </form>
        <!-- TODO: Implementar obtención de comentarios -->
        <!-- TODO: Integrar pasarelas de pago -->
    </div>
</body>
</html>
