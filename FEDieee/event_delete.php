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
  $id = "";
  $name = "";
  $description = "";

  if ($_GET) {
    require_once 'testdb.php';
    $action = $_GET["action"] ?? "";

    if ($action == "confirmed") {
      // 真正刪除資料
      $id = $_GET["id"];
      $sql = "DELETE FROM event WHERE id = ?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "i", $id);
      mysqli_stmt_execute($stmt);
      mysqli_close($conn);

      // 刪除後回首頁
      header('Location: index.php');
      exit;
    } else {
      // 顯示刪除確認畫面
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
  }
} catch (Exception $e) {
  echo 'Message: ' . $e->getMessage();
}
?>

<div class="container my-4">
  <table class="table table-bordered table-striped">
    <tr>
      <th>編號</th>
      <th>活動名稱</th>
      <th>活動說明</th>
    </tr>
    <tr>
        <td><?=$id?></td>
      <td><?=$name?></td>
      <td><?=$description?></td>
     
    </tr>
  </table>
  <a href="event_delete.php?id=<?= $id ?>&action=confirmed" class="btn btn-danger">刪除</a>
  <a href="index.php" class="btn btn-secondary">取消</a>
</div>
<?php include("footer.php"); ?>