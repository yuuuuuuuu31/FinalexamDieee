<?php 
$title = "活動管理系統";
include('header.php'); 
?>

<link rel="stylesheet" href="custom.css"> <!-- 套用自訂 CSS -->
<?php
// ====== 資料庫連線 ======
$conn = mysqli_connect("localhost:3307", "root", "", "accommodation");

if (!$conn) {
    die("連線失敗: " . mysqli_connect_error());
}

// ====== 表單送出處理 ======
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $applicant    = trim($_POST['applicant']);
    $location     = trim($_POST['location']);

    // 修正 datetime-local 格式：2025-02-10T14:30 → 2025-02-10 14:30
    $request_time = str_replace("T", " ", $_POST['report_time']);

    $item         = trim($_POST['item']);
    $issue_report = trim($_POST['issue']);

    // ====== SQL 指令 ======
    $sql = "INSERT INTO repair (applicant_name, location, request_time, item, issue_report) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("<p class='error'>SQL 錯誤（prepare 失敗）: " . $conn->error . "</p>");
    }

    $stmt->bind_param("sssss", $applicant, $location, $request_time, $item, $issue_report);

    if ($stmt->execute()) {
        $message = "<p class='success'>報修資料已成功新增！</p>";
    } else {
        $message = "<p class='error'>新增資料時發生錯誤: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>住宿報修系統</title>    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f9f9f9; }
        header, footer { background: white; color: white; padding: 10px; text-align: center; }
        main { max-width: 600px; margin: 20px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2 { text-align: center; }
        form { display: flex; flex-direction: column; }
        label { margin-top: 10px; font-weight: bold; }
        input, textarea { padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        textarea { resize: vertical; height: 100px; }
        input[type="submit"] { margin-top: 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background: #45a049; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <header>
        <h1>住宿報修系統</h1>
    </header>

    <main>
        <?php echo $message; ?>

        <h2>報修申請表單</h2>
        <form method="post" action="">
            <label>申請者:</label>
            <input type="text" name="applicant" required>

            <label>地點:</label>
            <input type="text" name="location" required>

            <label>時間:</label>
            <input type="datetime-local" name="report_time" required>

            <label>報修項目:</label>
            <input type="text" name="item" required>

            <label>問題回報:</label>
            <textarea name="issue" required></textarea>

            <input type="submit" value="送出報修">
        </form>
    </main>

</body>
</html>
<?php
$conn->close();
?>
<?php include('footer.php'); ?>