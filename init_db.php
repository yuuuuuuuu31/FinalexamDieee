<?php
require_once 'db_connect.php';

try {
    // 建立預設管理員帳號 (密碼: admin123)
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, password, name, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['admin', $hashed_password, '系統管理員', 'admin']);
    
    // 建立預設住民帳號 (密碼: resident123)
    $hashed_password = password_hash('resident123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, password, name, room_number) VALUES (?, ?, ?, ?)");
    $stmt->execute(['resident1', $hashed_password, '住民範例', 'A101']);
    
    echo "資料庫初始化完成！<br>";
    echo "管理員帳號: admin / 密碼: admin123<br>";
    echo "住民帳號: resident1 / 密碼: resident123<br>";
    echo "<a href='login.php'>前往登入頁面</a>";
    
} catch(PDOException $e) {
    die("初始化失敗: " . $e->getMessage());
}
?>