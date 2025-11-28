<?php
require_once "header.php";
//session_start();

// 檢查是否登入
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}

// 檢查是否為管理員
if ($_SESSION["user"]["role"] !== 'M') {
  echo "<div class='alert alert-danger text-center mt-4'>你沒有權限喔!</div>";
  exit;
}

try {
  $id = "";
  $name = "";
  $description = "";

  // 取得 action
  $action = $_GET["action"] ?? "";

  // 若是表單送出（更新資料）
  if ($action == "confirmed" && $_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'testdb.php';

    $id = $_GET["id"];
    $name = $_POST["name"];
    $description = $_POST["description"];

    $sql = "UPDATE event SET name = ?, description = ? WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $id);
    mysqli_stmt_execute($stmt);
    mysqli_close($conn);

    // 更新成功後回首頁
    header("Location: index.php");
    exit;
  }

  // 若只是開啟畫面（載入現有資料）
  if ($_GET && $action != "confirmed") {
    require_once 'testdb.php';

    $id = $_GET["id"];
    $sql = "SELECT id, name, description FROM event WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $name, $description);
    mysqli_stmt_fetch($stmt);
    mysqli_close($conn);
  }
} catch (Exception $e) {
  echo 'Message: ' . $e->getMessage();
}
?>

<!-- 修改活動資料表單 -->
<div class="container my-4">
  <h3 class="mb-4">修改活動資料</h3>

  <form action="activity_update.php?id=<?= $id ?>&action=confirmed" method="post">
    <div class="mb-3">
      <label for="_name" class="form-label">活動名稱</label>
      <input type="text" class="form-control" id="_name" name="name" 
             value="<?= htmlspecialchars($name) ?>" required>
    </div>

    <div class="mb-3">
      <label for="_description" class="form-label">活動說明</label>
      <textarea class="form-control" id="_description" name="description" 
                rows="5" required><?= htmlspecialchars($description) ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">更新活動</button>
    <a href="index.php" class="btn btn-secondary">取消</a>
  </form>
</div>

<?php
require_once "footer.php";
?>