<?php
session_start();


if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

include 'header.php';
?>

<div class="py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Welcome back, <span class="brand">
      <?= htmlspecialchars($_SESSION["email"]) ?>
    </span></h2>
    <a href="logout.php" class="btn btn-outline-light">Logout</a>
  </div>

  <div class="row g-4">
    
    <div class="col-md-4">
      <div class="card p-3">
        <h5 class="card-title">My Clubs</h5>
        <p class="card-text">View and manage your joined clubs in one place.</p>
        <a href="clubs.php" class="btn btn-light w-100">Go to Clubs</a>
      </div>
    </div>

    
    <div class="col-md-4">
      <div class="card p-3">
        <h5 class="card-title">Events</h5>
        <p class="card-text">Stay updated on upcoming events and activities.</p>
        <a href="events.php" class="btn btn-light w-100">Go to Events</a>
      </div>
    </div>

    
    <div class="col-md-4">
      <div class="card p-3">
        <h5 class="card-title">Profile</h5>
        <p class="card-text">Update your profile details and preferences.</p>
        <a href="profile.php" class="btn btn-light w-100">Go to Profile</a>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
