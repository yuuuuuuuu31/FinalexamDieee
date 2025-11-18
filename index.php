<?php 
$title = "宿舍管理系統";
include('header.php'); 
?>
<div class="container my-5">
    <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100 bg-white text-dark">
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title">活動簽到</h3>
                        <p class="card-text">
                        預覽活動資訊並完成簽到，以便統計出席與管理活動。
                        </p>
                        <div class="mt-auto text-end">
                            <a href="event.php" class="btn btn-primary">查看活動 ➜</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 bg-white text-dark">
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title">報修</h3>
                        <p class="card-text">
                            住民可線上提交報修單。
                            需標明地點、時間、報修項目及問題回報。
                        </p>
                        <div class="mt-auto text-end">
                            <a href="repair.php" class="btn btn-primary">報修 ➜</a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<?php include('footer.php'); ?>
