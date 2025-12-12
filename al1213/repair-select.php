<?php
session_start();
$isManager = (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'M');
$title = "報修服務";
include 'header.php';
?>

<main class="container mt-5">
  <h1 class="mb-4">請選擇服務</h1>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">一般報修</h5>
          <p class="card-text">設備故障、公共區域損壞等。</p>
          <a href="repair.php" class="btn btn-primary">前往一般報修</a>

          <?php if ($isManager): ?>
          <a href="admin-vending.php" class="btn btn-dark">清單</a>
          <?php endif; ?>
          
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">飲料機吃錢登記</h5>
          <p class="card-text">投錢沒出貨、退款登記。</p>
          <a href="vending.php" class="btn btn-warning">前往吃錢登記</a>
          
          <?php if ($isManager): ?>
          <a href="admin-vending.php" class="btn btn-dark">清單</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>
