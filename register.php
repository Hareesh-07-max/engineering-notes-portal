<?php
require 'config.php';
$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if(!$u || !$p) $err = 'Enter username & password';
    else {
        $users = load_json(USERS_FILE);
        if(isset($users[$u])) $err = 'Username exists';
        else {
            $users[$u] = ['password' => password_hash($p, PASSWORD_DEFAULT), 'is_admin' => false, 'created_at'=>time()];
            save_json(USERS_FILE, $users);
            $_SESSION['user'] = $u;
            $_SESSION['is_admin'] = false;
            header('Location: index.php'); exit;
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Register</h2>
  <?php if($err) echo "<div class='error'>".htmlspecialchars($err)."</div>"; ?>
  <form method="post">
    <label>Username: <input name="username" required></label><br>
    <label>Password: <input name="password" type="password" required></label><br>
    <button type="submit">Register</button>
  </form>
  <p>Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>
