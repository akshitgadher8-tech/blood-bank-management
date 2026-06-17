<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home - Netflix Clone</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Welcome, <?php echo $_SESSION['email']; ?>!</h1>
    <a href="logout.php">Logout</a>
  </header>
  <main>
    <?php include 'home.html'; ?>
  </main>
</body>
</html>