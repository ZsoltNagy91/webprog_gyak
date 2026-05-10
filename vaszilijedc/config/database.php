<?php

$host = 'localhost';
$dbname = 'webprog';
$username = 'root';
$password = '';

try {
    // Adatbázis kapcsolat létrehozása
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Hibakezelés bekapcsolása
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die('Adatbázis kapcsolódási hiba: ' . $e->getMessage());
}