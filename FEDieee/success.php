<?php
session_start();
include('header.php');
if (!isset($_SESSION["name"])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="zh-Hant">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>登入成功</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
 <div class="row justify-content-center">
  <div class="col-md-6">
   <div class="card shadow">
    <div class="card-body text-center">
     <h3 class="mb-4">歡迎，<?=htmlspecialchars($_SESSION["user"]["name"])?></h3>
     <a href="logout.php" class="btn btn-outline-primary me-2">登出</a>
     <a href="index.php" class="btn btn-primary">回首頁</a>
    </div>
   </div>
  </div>
 </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('footer.php'); ?>