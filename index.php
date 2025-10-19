<?php
require 'config.php';
$branches = ['CSE','ECE','EEE','ME','CE','IT'];
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Notes Portal</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <header>
    <h1>Notes Portal</h1>
    <div class="top-links">
      <?php if(is_logged_in()): ?>
        Hello, <?php echo htmlspecialchars($_SESSION['user']); ?> |
        <a href="upload.php">Upload Note</a> |
        <?php if(is_admin()): ?><a href="admin_panel.php">Admin Panel</a> |<?php endif; ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="register.php">Register</a> | <a href="login.php">Login</a>
      <?php endif; ?>
    </div>
  </header>

  <h2>Branches</h2>
  <div class="branches">
    <?php foreach($branches as $b): ?>
      <a class="branch-card" href="branch.php?branch=<?php echo urlencode($b); ?>"><?php echo htmlspecialchars($b); ?></a>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
