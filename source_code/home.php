<?php 
session_start();
include('db.php');

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">  <!-- Separate CSS File -->
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">🏠 Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <!-- Settings Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            ⚙ Settings
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="profile.php">👤 Profile</a></li>
            <li><a class="dropdown-item" href="Persona.php">📜 Persona Setting</a></li>
            <li><a class="dropdown-item" href="user_setting.php">🔧 User Setting</a></li>
            <li><a class="dropdown-item" href="Stopword.php">🚫 Stop Words Setting</a></li>
            <li><a class="dropdown-item" href="Annotator.php">🖋 Annotator Setting</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="create_prompt.php">✍ Prompt Creation</a>
        </li>
        <li class="nav-item">
          <a class="nav-link logout-link" href="logout.php">🚪 Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Welcome Section -->
<div class="dashboard-header">
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!🎉</h2>
    <p>Your GenAI management hub. View and manage your profiles, personas, and annotators.</p>
</div>


</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

