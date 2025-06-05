<?php
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'sorteos';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
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
        provider VARCHAR(50) NOT NULL,
        access_token TEXT,
        instagram_id VARCHAR(50)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        post_id INT,
        external_id VARCHAR(64),
        text TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        external_id VARCHAR(64),
        caption TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS raffles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        post_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS raffle_results (
        id INT AUTO_INCREMENT PRIMARY KEY,
        raffle_id INT,
        comment_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        provider VARCHAR(50),
        amount DECIMAL(10,2),
        status VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    // insert demo data if tables are empty
    $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO users (name, provider, access_token, instagram_id) VALUES
            ('Demo User','facebook',NULL,NULL),
            ('Otro Usuario','instagram',NULL,NULL)");
        $pdo->exec("INSERT INTO posts (external_id, caption) VALUES
            ('abc123','Foto de playa'),
            ('def456','Video promocional')");
        $pdo->exec("INSERT INTO comments (user_id, post_id, external_id, text) VALUES
            (1,1,'c1','Comentario de prueba 1'),
            (2,1,'c2','Comentario de prueba 2'),
            (1,2,'c3','Comentario de prueba 3')");
        $pdo->exec("INSERT INTO raffles (title, post_id) VALUES ('Sorteo de ejemplo',1)");
    }
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
$dsnDb = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsnDb, $user, $pass, $options);
?>
