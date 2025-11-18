<?php
require_once "header.php";
require_once "db.php";

// 取得活動 id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p class='error'>未指定活動 ID</p>");
}
$event_id = intval($_GET['id']);

// 查詢活動資料
$sql_event = "SELECT * FROM event WHERE id = ?";
$stmt_event = $conn->prepare($sql_event);
if (!$stmt_event) {
    die("<p class='error'>Prepare 失敗: " . $conn->error . "</p>");
}
$stmt_event->bind_param("i", $event_id);
$stmt_event->execute();
$result_event = $stmt_event->get_result();

if ($result_event->num_rows === 0) {
    die("<p class='error'>找不到此活動</p>");
}

$event = $result_event->fetch_assoc();
$stmt_event->close();

$message = "";

// 處理表單送出
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $participant_name = trim($_POST['participant_name']);
    $note = trim($_POST['note']);

    $insert_sql = "INSERT INTO sign_in (event_id, participant_name, note, sign_time) VALUES (?, ?, ?, NOW())";
    $stmt_insert = $conn->prepare($insert_sql);

    if (!$stmt_insert) {
        die("<p class='error'>Prepare 失敗: " . $conn->error . "</p>");
    }

    $stmt_insert->bind_param("iss", $event_id, $participant_name, $note);

    if ($stmt_insert->execute()) {
        $message = "<p class='success'>簽到成功！</p>";
    } else {
        $message = "<p class='error'>簽到失敗: " . $stmt_insert->error . "</p>";
    }

    $stmt_insert->close();
}
?>

<div class="container">
    <h2 class="mt-4">活動簽到</h2>

    <?php echo $message; ?>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <?php echo htmlspecialchars($event['event_name']); ?>
        </div>
        <div class="card-body">
            <p><strong>活動內容:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
            <p><strong>日期:</strong> <?php echo htmlspecialchars($event['event_time']); ?></p>
            <p><strong>簽到地點:</strong> <?php echo htmlspecialchars($event['sign_in_area']); ?></p>
        </div>
    </div>

    <form method="post" action="">
        <div class="mb-3">
            <label>姓名</label>
            <input type="text" name="participant_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>備註</label>
            <textarea name="note" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">送出簽到</button>
    </form>
</div>

<?php
mysqli_close($conn);
require_once "footer.php";
?>
