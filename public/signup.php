<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\classes\Database;
use App\classes\User;
use App\classes\Mailer;

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST["name"] ?? "");
  $email = trim($_POST["email"] ?? "");
  $password = $_POST["password"] ?? "";
  $confirmPassword = $_POST["confirm_password"] ?? "";

  if (empty($name) || empty($email) || empty($password)) {
    $message = "All fields are required.";
    $messageType = "danger";
  } elseif ($password !== $confirmPassword) {
    $message = "Passwords do not match.";
    $messageType = "danger";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Invalid email format.";
    $messageType = "danger";
  } else {
    try {
      $database = new Database();
      $user = new User($database);

      if ($user->emailExists($email)) {
        $message = "Email already registered.";
        $messageType = "warning";
      } else {
        $token = $user->create($email, $password, $name);

        if ($token) {
          $mailer = new Mailer();
          if ($mailer->sendVerificationEmail($email, $name, $token)) {
            $message =
              "Registration successful! Please check your email to verify your account.";
            $messageType = "success";
          } else {
            $message =
              "Registration successful, but failed to send verification email.";
            $messageType = "warning";
          }
        } else {
          $message = "Registration failed. Please try again.";
          $messageType = "danger";
        }
      }
    } catch (Exception $e) {
      $message = "An error occurred. Please try again.";
      $messageType = "danger";
      error_log($e->getMessage());
    }
  }
}
?>

<?php include 'header.php'; ?>

<div class="form-card">
  <h3 class="mb-3 text-center">Create Account</h3>

  <?php if ($message): ?>
    <div class="alert alert-<?= $messageType ?> text-center">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form method="POST" id="signupForm" novalidate>
    <div class="mb-3">
      <label for="name" class="form-label">Full Name</label>
      <input type="text" class="form-control" id="name" name="name"
        value="<?= htmlspecialchars($_POST["name"] ?? "") ?>" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email"
        value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
      <div class="form-text small-muted">At least 8 characters</div>
    </div>

    <div class="mb-3">
      <label for="confirm_password" class="form-label">Confirm Password</label>
      <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>

    <button type="submit" class="btn btn-light w-100">Sign Up</button>
  </form>

  <div class="text-center mt-3">
    <a href="index.php" class="small-muted">Back to Home</a>
  </div>
</div>

<?php include 'footer.php'; ?>
