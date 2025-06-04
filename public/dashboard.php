<?php
session_start();
require_once '../src/db.php';
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
$user = $_SESSION['user'];
$postId = $_GET['post'] ?? 1;
$posts = $pdo->query('SELECT id, caption FROM posts ORDER BY id DESC')->fetchAll();
$stmt = $pdo->prepare('SELECT comments.id, comments.text, users.name FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.id DESC');
$stmt->execute([$postId]);
$comments = $stmt->fetchAll();
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
        <form method="GET" id="postSelect">
            <label for="post">Publicaci&oacute;n:</label>
            <select name="post" id="post" onchange="document.getElementById('postSelect').submit()">
                <?php foreach ($posts as $p): ?>
                    <option value="<?php echo $p['id']; ?>"<?php if ($postId == $p['id']) echo ' selected'; ?>>
                        <?php echo htmlspecialchars($p['caption']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <button id="countdownBtn">Iniciar conteo 5-4-3</button>
        <div id="countdown"></div>
        <button id="raffleBtn">Realizar sorteo</button>
        <div id="winner"></div>
        <h2>Comentarios</h2>
        <table class="comments">
            <tr><th>Usuario</th><th>Comentario</th></tr>
            <?php foreach ($comments as $c): ?>
                <tr><td><?php echo htmlspecialchars($c['name']); ?></td><td><?php echo htmlspecialchars($c['text']); ?></td></tr>
            <?php endforeach; ?>
        </table>
        <form method="POST" action="logout.php">
            <button type="submit">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>

