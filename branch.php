<?php
require 'config.php';
$branch = isset($_GET['branch']) ? preg_replace('/[^A-Za-z0-9_-]/','',$_GET['branch']) : '';
$years = [1,2,3,4];
$semesters = [1,2]; // change to 1..8 if you like
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title><?php echo htmlspecialchars($branch); ?></title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Branch: <?php echo htmlspecialchars($branch ?: 'Unknown'); ?></h2>
  <form action="subjects.php" method="get" class="selector-form">
    <input type="hidden" name="branch" value="<?php echo htmlspecialchars($branch); ?>">
    <label>Year:
      <select name="year" required><?php foreach($years as $y) echo "<option>$y</option>"; ?></select>
    </label>
    <label>Semester:
      <select name="sem" required><?php foreach($semesters as $s) echo "<option>$s</option>"; ?></select>
    </label>
    <button type="submit">Show Notes</button>
  </form>
  <p><a href="index.php">← Home</a></p>
</div>
</body>
</html>
