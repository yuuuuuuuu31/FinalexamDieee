<?php
session_start();
require_once 'db_connect.php';

// 檢查是否已登入
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 處理報修提交
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_repair'])) {
    $location = $_POST['location'];
    $item = $_POST['item'];
    $description = $_POST['description'];
    
    $stmt = $pdo->prepare("INSERT INTO repair_requests (resident_id, location, item, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $location, $item, $description]);
    $repair_success = "報修單已成功提交！";
}

// 處理飲料機問題提交
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_vending_issue'])) {
    $machine_location = $_POST['machine_location'];
    $amount_lost = $_POST['amount_lost'];
    $description = $_POST['vending_description'];
    
    $stmt = $pdo->prepare("INSERT INTO vending_machine_issues (resident_id, machine_location, amount_lost, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $machine_location, $amount_lost, $description]);
    $vending_success = "飲料機問題已成功提交！";
}

// 獲取使用者的報修紀錄
$stmt = $pdo->prepare("SELECT * FROM repair_requests WHERE resident_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$repair_requests = $stmt->fetchAll();

// 獲取即將到來的活動
$stmt = $pdo->prepare("
    SELECT a.*, 
           (SELECT COUNT(*) FROM activity_signups WHERE activity_id = a.id AND resident_id = ?) as signed_up
    FROM activities a 
    WHERE a.activity_date >= CURDATE() 
    ORDER BY a.activity_date, a.activity_time
");
$stmt->execute([$_SESSION['user_id']]);
$upcoming_activities = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>住民儀表板 - 宿舍管理系統</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0; }
        .header { background-color: #4CAF50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 100px; }
        button { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .success { color: green; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .status-pending { color: #ff9800; }
        .status-in_progress { color: #2196F3; }
        .status-completed { color: #4CAF50; }
        .nav-tabs { display: flex; border-bottom: 1px solid #ddd; margin-bottom: 20px; }
        .nav-tab { padding: 10px 20px; cursor: pointer; border: 1px solid transparent; }
        .nav-tab.active { border: 1px solid #ddd; border-bottom: 1px solid white; margin-bottom: -1px; background: white; border-radius: 4px 4px 0 0; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body>
    <div class="header">
        <h1>住民儀表板</h1>
        <div>
            <span>歡迎, <?php echo $_SESSION['name']; ?></span>
            <a href="logout.php" style="color: white; margin-left: 15px;">登出</a>
        </div>
    </div>
    
    <div class="container">
        <div class="nav-tabs">
            <div class="nav-tab active" data-tab="repair">報修系統</div>
            <div class="nav-tab" data-tab="activities">活動資訊</div>
            <div class="nav-tab" data-tab="vending">飲料機問題</div>
        </div>
        
        <!-- 報修系統標籤 -->
        <div id="repair" class="tab-content active">
            <div class="section">
                <h2>提交報修單</h2>
                <?php if (isset($repair_success)): ?>
                    <div class="success"><?php echo $repair_success; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="location">報修地點:</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="item">報修項目:</label>
                        <input type="text" id="item" name="item" required>
                    </div>
                    <div class="form-group">
                        <label for="description">問題描述:</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                    <button type="submit" name="submit_repair">提交報修</button>
                </form>
            </div>
            
            <div class="section">
                <h2>我的報修紀錄</h2>
                <?php if (count($repair_requests) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>日期</th>
                                <th>地點</th>
                                <th>項目</th>
                                <th>描述</th>
                                <th>狀態</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($repair_requests as $request): ?>
                                <tr>
                                    <td><?php echo date('Y-m-d', strtotime($request['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($request['location']); ?></td>
                                    <td><?php echo htmlspecialchars($request['item']); ?></td>
                                    <td><?php echo htmlspecialchars($request['description']); ?></td>
                                    <td>
                                        <?php 
                                        $status_text = '';
                                        switch($request['status']) {
                                            case 'pending': $status_text = '待處理'; break;
                                            case 'in_progress': $status_text = '處理中'; break;
                                            case 'completed': $status_text = '已完成'; break;
                                        }
                                        echo '<span class="status-' . $request['status'] . '">' . $status_text . '</span>';
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>您目前沒有報修紀錄。</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- 活動資訊標籤 -->
        <div id="activities" class="tab-content">
            <div class="section">
                <h2>即將到來的活動</h2>
                <?php if (count($upcoming_activities) > 0): ?>
                    <?php foreach ($upcoming_activities as $activity): ?>
                        <div class="section" style="margin-bottom: 15px;">
                            <h3><?php echo htmlspecialchars($activity['title']); ?></h3>
                            <p><strong>日期:</strong> <?php echo $activity['activity_date']; ?></p>
                            <p><strong>時間:</strong> <?php echo $activity['activity_time']; ?></p>
                            <p><strong>地點:</strong> <?php echo htmlspecialchars($activity['location']); ?></p>
                            <p><?php echo htmlspecialchars($activity['description']); ?></p>
                            
                            <?php if ($activity['signed_up']): ?>
                                <p style="color: green;">您已報名此活動</p>
                            <?php else: ?>
                                <form method="POST" action="signup_activity.php">
                                    <input type="hidden" name="activity_id" value="<?php echo $activity['id']; ?>">
                                    <button type="submit">報名活動</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>目前沒有即將到來的活動。</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- 飲料機問題標籤 -->
        <div id="vending" class="tab-content">
            <div class="section">
                <h2>飲料機吃錢登記</h2>
                <?php if (isset($vending_success)): ?>
                    <div class="success"><?php echo $vending_success; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="machine_location">飲料機位置:</label>
                        <input type="text" id="machine_location" name="machine_location" required>
                    </div>
                    <div class="form-group">
                        <label for="amount_lost">損失金額:</label>
                        <input type="number" id="amount_lost" name="amount_lost" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="vending_description">問題描述