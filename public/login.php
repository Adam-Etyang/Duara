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

<?php include 'header.php'; ?>

<div class="form-card">
  <h3 class="mb-3 text-center">Login to Duara</h3>

  <?php if ($message): ?>
    <div class="alert alert-<?= $messageType ?> text-center">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form method="POST" novalidate>
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control" id="email" name="email"
             value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <div class="d-grid gap-2">
      <button type="submit" class="btn btn-light">Login</button>
    </div>
  </form>

  <div class="mt-3 text-center">
    <p class="small-muted">Don't have an account? <a href="signup.php">Sign up here</a></p>
    <p class="small-muted"><a href="#">Forgot your password?</a></p>
  </div>
</div>

<?php include 'footer.php'; ?>
