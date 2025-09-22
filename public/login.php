<?php
require_once "../vendor/autoload.php";

use App\classes\Database;
use App\classes\User;

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $database = new Database();
        $user = new User($database);

        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        if ($user->login($email, $password)) {
            // Start session and redirect to dashboard
            session_start();
            $_SESSION["user_id"] = $user->getCurrentUserId();
            $_SESSION["email"] = $email;
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Invalid email or password.";
            $messageType = "danger";
        }
    } catch (Exception $e) {
        $message = "An error occurred during login.";
        $messageType = "danger";
        error_log($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Duara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4 class="mb-0">Login to Duara</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-<?= $messageType ?>" role="alert">
                                <?= htmlspecialchars($message) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
                            <p><a href="forgot-password.php">Forgot your password?</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>