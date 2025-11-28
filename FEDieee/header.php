<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//header.php
$title=$title??"宿舍管理系統";
function nav_active($file) {
    $current = basename($_SERVER['PHP_SELF']);
    return $current === $file ? ' active' : '';
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?=$title?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="custom.css" />
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg custom-bg">
    <div class="container">
    <a class="navbar-brand" href="#" onclick="if(document.referrer){ history.back(); } else { window.location='index.php'; }">⇦</a>
      <a class="navbar-brand" href="index.php">宿舍管理系統</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link<?=nav_active('index.php')?>" aria-current="page" href="index.php">首頁</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?=nav_active('event.php')?>" href="event.php">活動</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?=nav_active('repair.php')?>" href="repair.php">報修</a>
          </li>
        </ul>
      </div>
      <div class="ms-auto">
    <?php if (isset($_SESSION["user"])) : ?>
        <span class="me-3">歡迎，<?= htmlspecialchars($_SESSION["user"]["name"]) ?>!</span>
      <a href="logout.php" class="btn btn-outline-danger btn-sm">登出</a>
    <?php else: ?>
      <a href="login.php" class="btn btn-outline-primary btn-sm">登入</a>
    <?php endif; ?>
  </div>
    </div>
  </nav>
