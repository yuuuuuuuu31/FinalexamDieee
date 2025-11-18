<?php
session_start();
require_once 'db_connect.php';

// 檢查是否已登入且為管理員
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// 更新報修狀態
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE repair_requests SET status = ? WHERE id = ?");
    $stmt->execute([$status, $request_id]);
}

// 建立新活動
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_activity'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $activity_date = $_POST['activity_date'];
    $activity_time = $_POST['activity_time'];
    $location = $_POST['location'];
    
    $stmt = $pdo->prepare("INSERT INTO activities (title, description, activity_date, activity_time, location, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $activity_date, $activity_time, $location, $_SESSION['user_id']]);
    $activity_success = "活動已成功建立！";
}

// 處理飲料機問題退款
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_refunded'])) {
    $issue_id = $_POST['issue_id'];
    
    $stmt = $pdo->prepare("UPDATE vending_machine_issues SET status = 'refunded' WHERE id = ?");
    $stmt->execute([$issue_id]);
}

// 獲取所有報修單
$stmt = $pdo->prepare("SELECT rr.*, u.name as resident_name FROM repair_requests rr JOIN users u ON rr.resident_id = u.id ORDER BY rr.created_at DESC");
$stmt->execute();
$repair_requests = $stmt->fetchAll();

// 獲取所有活動
$stmt = $pdo->prepare("SELECT a.*, u.name as creator_name FROM activities a JOIN users u ON a.created_by = u.id ORDER BY a.activity_date DESC");
$stmt->execute();
$activities = $stmt->fetchAll();

// 獲取飲料機問題
$stmt = $pdo->prepare("SELECT v.*, u.name as resident_name FROM vending_machine_issues v JOIN users u ON v.resident_id = u.id ORDER BY v.created_at DESC");
$stmt->execute();
$vending_issues = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理員儀表板 - 宿舍管理系統</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0; }
        .header { background-color: #2196F3; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="date"], input[type="time"], textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 100px; }
        button { padding: 10px 15px; background-color: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0b7dda; }
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
        <h1>管理員儀表板</h1>
        <div>
            <span>歡迎, <?php echo $_SESSION['name']; ?></span>
            <a href="logout.php" style="color: white; margin-left: 15px;">登出</a>
        </div>
    </div>
    
    <div class="container">
        <div class="nav-tabs">
            <div class="nav-tab active" data-tab="repair">報修管理</div>
            <div class="nav-tab" data-tab="activities">活動管理</div>
            <div class="nav-tab" data-tab="vending">飲料機問題</div>
        </div>
        
        <!-- 報修管理標籤 -->
        <div id="repair" class="tab-content active">
            <div class="section">
                <h2>所有報修單</h2>
                <?php if (count($repair_requests) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>日期</th>
                                <th>住民</th>
                                <th>地點</th>
                                <th>項目</th>
                                <th>描述</th>
                                <th>狀態</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($repair_requests as $request): ?>
                                <tr>
                                    <td><?php echo date('Y-m-d', strtotime($request['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($request['resident_name']); ?></td>
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
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="pending" <?php echo $request['status'] == 'pending' ? 'selected' : ''; ?>>待處理</option>
                                                <option value="in_progress" <?php echo $request['status'] == 'in_progress' ? 'selected' : ''; ?>>處理中</option>
                                                <option value="completed" <?php echo $request['status'] == 'completed' ? 'selected' : ''; ?>>已完成</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>目前沒有報修單。</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- 活動管理標籤 -->
        <div id="activities" class="tab-content">
            <div class="section">
                <h2>建立新活動</h2>
                <?php if (isset($activity_success)): ?>
                    <div class="success"><?php echo $activity_success; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="title">活動標題:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">活動描述:</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="activity_date">活動日期:</label>
                        <input type="date" id="activity_date" name="activity_date" required>
                    </div>
                    <div class="form-group">
                        <label for="activity_time">活動時間:</label>
                        <input type="time" id="activity_time" name="activity_time" required>
                    </div>
                    <div class="form-group">
                        <label for="location">活動地點:</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                    <button type="submit" name="create_activity">建立活動</button>
                </form>
            </div>
            
            <div class="section">
                <h2>所有活動</h2>
                <?php if (count($activities) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>標題</th>
                                <th>日期</th>
                                <th>時間</th>
                                <th>地點</th>
                                <th>建立者</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activities as $activity): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($activity['title']); ?></td>
                                    <td><?php echo $activity['activity_date']; ?></td>
                                    <td><?php echo $activity['activity_time']; ?></td>
                                    <td><?php echo htmlspecialchars($activity['location']); ?></td>
                                    <td><?php echo htmlspecialchars($activity['creator_name']); ?></td>
                                    <td>
                                        <a href="activity_signup.php?id=<?php echo $activity['id']; ?>" target="_blank">簽到頁面</a> |
                                        <a href="view_attendees.php?id=<?php echo $activity['id']; ?>">查看參加者</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>目前沒有活動。</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- 飲料機問題標籤 -->
        <div id="vending" class="tab-content">
            <div class="section">
                <h2>飲料機問題紀錄</h2>
                <?php if (count($vending_issues) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>日期</th>
                                <th>住民</th>
                                <th>機器位置</th>
                                <th>損失金額</th>
                                <th>問題描述</th>
                                <th>狀態</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vending_issues as $issue): ?>
                                <tr>
                                    <td><?php echo date('Y-m-d', strtotime($issue['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($issue['resident_name']); ?></td>
                                    <td><?php echo htmlspecialchars($issue['machine_location']); ?></td>
                                    <td>$<?php echo number_format($issue['amount_lost'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($issue['description']); ?></td>
                                    <td>
                                        <?php 
                                        $status_text = $issue['status'] == 'pending' ? '待退款' : '已退款';
                                        $status_class = $issue['status'] == 'pending' ? 'status-pending' : 'status-completed';
                                        echo '<span class="' . $status_class . '">' . $status_text . '</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($issue['status'] == 'pending'): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
                                                <button type="submit" name="mark_refunded">標記為已退款</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>目前沒有飲料機問題紀錄。</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // 標籤切換功能
        document.querySelectorAll('.nav-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // 移除所有標籤和內容的active類
                document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // 添加active類到當前標籤和內容
                tab.classList.add('active');
                document.getElementById(tab.dataset.tab).classList.add('active');
            });
        });
    </script>
</body>
</html>