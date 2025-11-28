<?php
require_once "header.php";

//session_start();

// 檢查是否登入
if (!isset($_SESSION["role"])) {
  header("Location: login.php"); // 尚未登入時導向登入頁
  exit;
}

// 檢查是否為管理員
if ($_SESSION["role"] !== 'M') {
  echo "<div class='alert alert-danger text-center mt-4'>您沒有權限執行此操作。</div>";
  require_once "footer.php";
  exit;
}

try {
  require_once 'testdb.php';
  $msg="";

  // ✅ 表單送出後執行新增
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"] ?? '';
    $description = $_POST["description"] ?? '';

    $sql = "INSERT INTO event (name, description) VALUES (?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, "ss", $name, $description);
      $result = mysqli_stmt_execute($stmt);

      if ($result) {
        // 新增成功 → 回首頁
        header("Location: index.php");
        exit;
      } else {
        $msg = "<div class='alert alert-danger'>無法新增資料</div>";
      }
    } else {
      $msg = "<div class='alert alert-danger'>SQL 準備失敗</div>";
    }
  }
?>
<div class="container">
<form action="event_insert.php" method="post">
  <div class="mb-3 ">
    <label for="_name" class="col-sm-6 col-form-label">活動名稱</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" id="_name" placeholder="活動名稱輸入" required>
    </div>
  </div>
   <div class="mb-3 ">
    <label for="_description" class="form-label">活動說明</label>
    <textarea class="form-control" name="description" id="_description" rows="5" required></textarea>
  </div>
   <input class="btn btn-primary" type="submit" value="新增">
<a href="index.php" class="btn btn-secondary">取消</a>
  <?=$msg?>
</form>
</div>

<?php
  mysqli_close($conn);
}
//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
require_once "footer.php";
?>