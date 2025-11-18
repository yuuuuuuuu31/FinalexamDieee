<?php
session_start();
require_once 'db_connect.php';

// 檢查是否已登入
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 處理活動報名
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['activity_id'])) {
    $activity_id = $_POST['activity_id'];
    $resident_id = $_SESSION['user_id'];
    
    // 檢查是否已經報名
    $stmt = $pdo->prepare("SELECT * FROM activity_signups WHERE activity_id = ? AND resident_id = ?");
    $stmt->execute([$activity_id, $resident_id]);
    $existing_signup = $stmt->fetch();
    
    if (!$existing_signup) {
        // 新增報名紀錄
        $stmt = $pdo->prepare("INSERT INTO activity_signups (activity_id, resident_id) VALUES (?, ?)");
        $stmt->execute([$activity_id, $resident_id]);
        $_SESSION['signup_success'] = "活動報名成功！";
    } else {
        $_SESSION['signup_error'] = "您已經報名此活動了！";
    }
    
    header("Location: resident_dashboard.php");
    exit();
}
?>