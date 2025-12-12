<?php 
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$title = "吃錢報修系統";
include('header.php'); 

?>

<link rel="stylesheet" href="custom.css"> <!-- 套用自訂 CSS -->
<?php
// ====== 資料庫連線 ======
$conn = mysqli_connect("localhost", "root", "", "accommodation");

if (!$conn) {
    die("連線失敗: " . mysqli_connect_error());
}

// ====== 表單送出處理 ======
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $applicant    = trim($_POST['applicant_name']);
    $location     = trim($_POST['location']);
    $amount         = trim($_POST['amount']);

    // ====== SQL 指令 ======
    $sql = "INSERT INTO vending (applicant_name, location, amount) 
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("<p class='error'>SQL 錯誤（prepare 失敗）: " . $conn->error . "</p>");
    }

    $stmt->bind_param("ssi", $applicant, $location, $amount);

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
    <title>吃錢報修系統</title>    <style>
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
        <h1>吃錢報修系統</h1>
    </header>

    <main>
        <?php echo $message; ?>

        <h2>吃錢退費申請表單</h2>
        <form method="post" action="">
        <label>申請者:</label>
        <input type="text" name="applicant_name" required>
        <label>地點:</label>
        <input type="text" name="location" required>
        <label>金額:</label>
        <input type="number" name="amount" required>


            <input type="submit" value="送出報修">
            <a href="repair_select.php" class="btn btn-secondary">返回選單</a>

        </form>
    </main>

</body>
</html>
<?php
$conn->close();
?>
<?php include('footer.php'); ?>