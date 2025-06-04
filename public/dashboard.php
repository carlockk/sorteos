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
        <button id="loadPostsBtn">Cargar publicaciones</button>
        <form id="postSelect" style="display:none">
            <label for="post">Publicaci&oacute;n:</label>
            <select id="post"></select>
        </form>
        <button id="countdownBtn" style="display:none">Iniciar conteo 5-4-3</button>
        <div id="countdown"></div>
        <button id="raffleBtn" style="display:none">Realizar sorteo</button>
        <div id="winner"></div>
        <h2>Comentarios</h2>
        <table class="comments" id="commentsTable">
            <tr><th>Usuario</th><th>Comentario</th></tr>
        </table>
        <form method="POST" action="logout.php">
            <button type="submit">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>

