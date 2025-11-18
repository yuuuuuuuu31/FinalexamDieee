<?php
session_start();
require_once 'db_connect.php';

// 檢查是否已登入且為管理員
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

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

// 獲取參加者名單
$stmt = $pdo->prepare("
    SELECT u.name, u.room_number, asu.signup_time 
    FROM activity_signups asu 
    JOIN users u ON asu.resident_id = u.id 
    WHERE asu.activity_id = ? 
    ORDER BY asu.signup_time
");
$stmt->execute([$activity_id]);
$attendees = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>活動參加者 - <?php echo htmlspecialchars($activity['title']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 20px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; margin-bottom: 30px; }
        .activity-info { background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .back-btn { display: inline-block; padding: 10px 15px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 4px; margin-bottom: 20px; }
        .back-btn:hover { background-color: #0b7dda; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_dashboard.php" class="back-btn">返回管理員儀表板</a>
        
        <h1>活動參加者名單</h1>
        
        <div class="activity-info">
            <h2><?php echo htmlspecialchars($activity['title']); ?></h2>
            <p><strong>日期:</strong> <?php echo $activity['activity_date']; ?></p>
            <p><strong>時間:</strong> <?php echo $activity['activity_time']; ?></p>
            <p><strong>地點:</strong> <?php echo htmlspecialchars($activity['location']); ?></p>
        </div>
        
        <h3>參加者列表 (共 <?php echo count($attendees); ?> 人)</h3>
        
        <?php if (count($attendees) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>姓名</th>
                        <th>房號</th>
                        <th>簽到時間</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendees as $attendee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($attendee['name']); ?></td>
                            <td><?php echo htmlspecialchars($attendee['room_number']); ?></td>
                            <td><?php echo date('Y-m-d H:i:s', strtotime($attendee['signup_time'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>目前還沒有參加者。</p>
        <?php endif; ?>
    </div>
</body>
</html>