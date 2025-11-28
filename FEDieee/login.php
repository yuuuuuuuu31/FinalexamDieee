<?php 
require_once('header.php');
require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") { 
    $password = $_POST["password"] ?? "";
    $redirect = $_POST["redirect"] ?? "success.php";
    $account = mysqli_real_escape_string($conn, $_POST['account']);
    $sql = "SELECT * FROM user WHERE account = ?";
    $result = mysqli_query($conn, $sql);

    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $account);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if ($password === $user['password']) {
            $_SESSION["user"] = [
                "id" => $user["id"],
                "name" => $user["name"],
                "account" => $user["account"],
                "role" => $user["role"]
            ];

            header("Location: $redirect");
            exit; 
        }
    }

    header("Location: login.php?msg=" . urlencode("帳號或密碼錯誤") . "&redirect=" . urlencode($redirect));
    exit;
}

$msg = $_GET["msg"] ?? "";
$redirect = $_GET["redirect"] ?? "success.php";
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="card-title mb-4">登入</h4>
                    <form method="post" action="login.php">
                        <input type="hidden" name="redirect" value="<?=htmlspecialchars($redirect)?>">
                        <div class="mb-3">
                            <label for="account" class="form-label">帳號</label>
                            <input type="text" class="form-control" id="account" name="account" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">密碼</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">登入</button>
                    </form>
                    <?php if ($msg): ?>
                        <div class="alert alert-danger mt-3"><?=htmlspecialchars($msg)?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>