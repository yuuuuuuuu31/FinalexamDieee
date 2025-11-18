<?php
session_start();
require_once 'db_connect.php';

// 檢查活動ID
if (!isset($_GET['id'])) {
    die("活動ID未指定");
}

$activity_id = $_GET['id'];

// 獲取活動資訊
$stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ?");
$stmt->execute([$activity_id]);
$activity = $stmt->fetch();

if (!$activity) {
    die("活動不存在");
}

// 處理簽到
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = $_POST['room_number'];
    
    // 根據房號查找住民
    $stmt = $pdo->prepare("SELECT * FROM users WHERE room_number = ? AND role = 'resident'");
    $stmt->execute([$room_number]);
    $resident = $stmt->fetch();
    
    if ($resident) {
        // 檢查是否已經簽到
        $stmt = $pdo->prepare("SELECT * FROM activity_signups WHERE activity_id = ? AND resident_id = ?");
        $stmt->execute([$activity_id, $resident['id']]);
        $existing_signup = $stmt->fetch();
        
        if (!$existing_signup) {
            // 新增簽到紀錄
            $stmt = $pdo->prepare("INSERT INTO activity_signups (activity_id, resident_id) VALUES (?, ?)");
            $stmt->execute([$activity_id, $resident['id']]);
            $signup_success = "簽到成功！";
        } else {
            $signup_error = "您已經簽到過了！";
        }
    } else {
        $signup_error = "找不到此房號的住民！";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>活動簽到 - <?php echo htmlspecialchars($activity['title']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; margin-bottom: 30px; }
        .activity-info { background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #45a049; }
        .success { color: green; text-align: center; margin-bottom: 15px; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>活動簽到</h1>
        
        <div class="activity-info">
            <h2><?php echo htmlspecialchars($activity['title']); ?></h2>
            <p><strong>日期:</strong> <?php echo $activity['activity_date']; ?></p>
            <p><strong>時間:</strong> <?php echo $activity['activity_time']; ?></p>
            <p><strong>地點:</strong> <?php echo htmlspecialchars($activity['location']); ?></p>
            <p><?php echo htmlspecialchars($activity['description']); ?></p>
        </div>
        
        <?php if (isset($signup_success)): ?>
            <div class="success"><?php echo $signup_success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($signup_error)): ?>
            <div class="error"><?php echo $signup_error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="room_number">請輸入您的房號:</label>
                <input type="text" id="room_number" name="room_number" required autofocus>
            </div>
            <button type="submit">簽到</button>
        </form>
    </div>
</body>
</html>