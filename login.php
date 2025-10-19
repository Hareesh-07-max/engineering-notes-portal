<?php
require 'config.php';
$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    $users = load_json(USERS_FILE);
    if(isset($users[$u]) && password_verify($p, $users[$u]['password'])){
        $_SESSION['user'] = $u;
        $_SESSION['is_admin'] = !empty($users[$u]['is_admin']);
        header('Location: index.php'); exit;
    } else $err = 'Invalid credentials';
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Login</h2>
  <?php if($err) echo "<div class='error'>".htmlspecialchars($err)."</div>"; ?>
  <form method="post">
    <label>Username: <input name="username" required></label><br>
    <label>Password: <input name="password" type="password" required></label><br>
    <button type="submit">Login</button>
  </form>
  <p>No account? <a href="register.php">Register</a></p>
</div>
</body>
</html>
