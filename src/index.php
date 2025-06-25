<?php
$host = 'lemp_mysql';
$db   = getenv('MYSQL_DATABASE');
$user = getenv('MYSQL_USER');
$pass = getenv('MYSQL_PASSWORD');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("CREATE TABLE IF NOT EXISTS people (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    $stmt = $pdo->query("SELECT COUNT(*) FROM people");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO people (name) VALUES ('Juan Pérez'), ('María García')");
    }
    $stmt = $pdo->query("SELECT * FROM people");

    echo "<h1>Personas registradas:</h1><ul>";
    foreach ($stmt as $row) {
        echo "<li>{$row['id']} - {$row['name']} ({$row['created_at']})</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo "<h2>Error de conexión a la base de datos:</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
