<?php
// admin-repair.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'M') { exit('無權限'); }
$title = "報修管理後台";
include 'header.php';
?>
<link rel="stylesheet" href="custom.css">

<?php
// ====== 資料庫連線 ======
$conn = mysqli_connect("localhost", "root", "", "accommodation");

if (!$conn) {
    die("連線失敗: " . mysqli_connect_error());
}

// ====== ★★ 處理「更新狀態」表單提交 ★★ ======
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'], $_POST['status'])) {

    $id = intval($_POST['id']);
    $new_status = intval($_POST['status']);

    $update_sql = "UPDATE repair SET status = $new_status WHERE id = $id";
    mysqli_query($conn, $update_sql);

    // 避免重新整理重複提交
    echo "<script>location.href='admin-repair.php';</script>";
    exit;
}

// ====== 狀態篩選 ======
$where = "";
if (isset($_GET['status']) && $_GET['status'] !== "") {
    $status = intval($_GET['status']);
    $where = "WHERE status = $status";
}

// ====== 套用篩選 SQL ======
$sql = "SELECT * FROM repair $where ORDER BY request_time DESC";
$result = mysqli_query($conn, $sql);

// 狀態顯示文字
function status_text($s) {
    switch ($s) {
        case 0: return "待處理";
        case 1: return "處理中";
        case 2: return "已完成";
        default: return "未知";
    }
}
?>

<main class="container mt-4">
    <h1 class="mb-3">報修管理後台</h1>

    <div class="mb-3">
        <span>狀態篩選：</span>
        <a href="admin-repair.php" class="btn btn-sm btn-outline-secondary">全部</a>
        <a href="admin-repair.php?status=0" class="btn btn-sm btn-outline-secondary">待處理</a>
        <a href="admin-repair.php?status=1" class="btn btn-sm btn-outline-secondary">處理中</a>
        <a href="admin-repair.php?status=2" class="btn btn-sm btn-outline-secondary">已完成</a>
    </div>

    <div class="container">
        <table class="table table-bordered table-striped">
            <tr>
                <th>申請者</th>
                <th>時間</th>
                <th>地點</th>
                <th>報修項目</th>
                <th>問題描述</th>
                <th>狀態</th>
                <th>操作</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row["applicant_name"] ?></td>
                <td><?= $row["request_time"] ?></td>
                <td><?= $row["location"] ?></td>
                <td><?= $row["item"] ?></td>
                <td><?= $row["issue_report"] ?></td>
                <td><?= status_text($row["status"]) ?></td>

                <td>
                    <form method="post" action="admin-repair.php">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">

                        <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                            <option value="0" <?php if ($row['status'] == 0) echo 'selected'; ?>>待處理</option>
                            <option value="1" <?php if ($row['status'] == 1) echo 'selected'; ?>>處理中</option>
                            <option value="2" <?php if ($row['status'] == 2) echo 'selected'; ?>>已完成</option>
                        </select>

                        <button class="btn btn-sm btn-primary">更新</button>
                    </form>
                </td>

            </tr>
            <?php } ?>

        </table>
    </div>
</main>

<?php include 'footer.php'; ?>
