<?php
session_start();
include("db.php"); 

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<?php include("header.php"); ?>
<div class="container mt-4">
  <h3>登入</h3>
  <form method="post" action="login_process.php" class="mt-3">
    <div class="mb-3">
      <label class="form-label">帳號：</label>
      <input type="text" name="account" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">密碼：</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <input type="submit" value="登入" class="btn btn-primary">
  </form>

  <?php if ($error) echo "<p class='text-danger mt-2'>$error</p>"; ?>
</div>
<?php include("footer.php"); ?>