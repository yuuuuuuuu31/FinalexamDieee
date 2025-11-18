<?php
$host = 'localhost';
$dbname = 'dorm_management';
$username = 'root'; // 請根據您的環境修改
$password = ''; // 請根據您的環境修改

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("資料庫連接失敗: " . $e->getMessage());
}
?>