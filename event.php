<?php 
$title = "活動管理系統";
include('header.php'); 
?>

<link rel="stylesheet" href="custom.css"> <!-- 套用自訂 CSS -->

<?php
// ====== 資料庫連線 ======
$conn = mysqli_connect("localhost:3307", "root", "", "accommodation");
if (!$conn) {
    die("<p class='error'>連線失敗：" . mysqli_connect_error() . "</p>");
}

// ====== 取得活動資料 ======
$sql = "SELECT * FROM event ORDER BY event_time ASC";
$result = mysqli_query($conn, $sql);
?>

<div class="container">
    <h2 class="text-center mt-4 mb-4">活動列表</h2>
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-primary">
            <tr style="background-color:#007bff; color:white;">
                <th>活動名稱</th>
                <th>活動內容</th>
                <th>日期</th>
                <th>簽到地點</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".htmlspecialchars($row['event_name'])."</td>";
                echo "<td>".htmlspecialchars($row['description'])."</td>";
                echo "<td>".htmlspecialchars($row['event_time'])."</td>";
                echo "<td>".htmlspecialchars($row['sign_in_area'])."</td>";
                echo "<td><a href='sign.php?id=".$row['id']."' class='btn btn-primary btn-sm'>簽到 ➜</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>目前沒有活動</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php
mysqli_close($conn);
include('footer.php');
?>
