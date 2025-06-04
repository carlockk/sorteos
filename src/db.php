<?php
$host = 'localhost';
$db   = 'sorteos';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsnNoDb = "mysql:host=$host;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    // connect without database to create it if needed
    $pdo = new PDO($dsnNoDb, $user, $pass, $options);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$db`");
    // create tables if not exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        provider VARCHAR(50) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        text TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS raffles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    // insert demo data if tables are empty
    $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO users (name, provider) VALUES
            ('Demo User','facebook'),
            ('Otro Usuario','instagram')");
        $pdo->exec("INSERT INTO comments (user_id, text) VALUES
            (1,'Comentario de prueba 1'),
            (2,'Comentario de prueba 2'),
            (1,'Comentario de prueba 3')");
        $pdo->exec("INSERT INTO raffles (title) VALUES ('Sorteo de ejemplo')");
    }
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
$dsnDb = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsnDb, $user, $pass, $options);
?>
