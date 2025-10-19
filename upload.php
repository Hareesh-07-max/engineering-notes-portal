<?php
require 'config.php';
if(!is_logged_in()){ header('Location: login.php'); exit; }

$branch = isset($_GET['branch']) ? preg_replace('/[^A-Za-z0-9_-]/','',$_GET['branch']) : '';
$year = isset($_GET['year']) ? preg_replace('/[^0-9]/','',$_GET['year']) : '';
$sem = isset($_GET['sem']) ? preg_replace('/[^0-9]/','',$_GET['sem']) : '';

$err = $success = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK){
        $err = 'File upload failed.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $branch = preg_replace('/[^A-Za-z0-9_-]/','',$_POST['branch'] ?? '');
        $year = preg_replace('/[^0-9]/','',$_POST['year'] ?? '');
        $sem = preg_replace('/[^0-9]/','',$_POST['sem'] ?? '');

        $f = $_FILES['pdf'];
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        if($ext !== 'pdf'){ $err = 'Only PDF allowed.'; }
        else {
            $uniq = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $dest = PENDING_DIR . '/' . $uniq;
            if(!move_uploaded_file($f['tmp_name'], $dest)){
                $err = 'Failed to save file.';
            } else {
                $pending = load_json(PENDING_FILE);
                $entry = [
                    'id' => $uniq,
                    'original_name' => $f['name'],
                    'stored_name' => $uniq,
                    'title' => $title ?: $f['name'],
                    'branch' => $branch,
                    'year' => $year,
                    'sem' => $sem,
                    'uploaded_by' => $_SESSION['user'],
                    'uploaded_at' => time()
                ];
                $pending[$uniq] = $entry;
                save_json(PENDING_FILE, $pending);
                $success = 'Uploaded and awaiting admin approval.';
            }
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Upload PDF</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Upload Note</h2>
  <?php if($err) echo "<div class='error'>".htmlspecialchars($err)."</div>"; ?>
  <?php if($success) echo "<div class='success'>".htmlspecialchars($success)."</div>"; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Title: <input name="title" value="<?php echo htmlspecialchars($title); ?>"></label><br>
    <label>Branch: <input name="branch" value="<?php echo htmlspecialchars($branch); ?>" required></label><br>
    <label>Year: <input name="year" value="<?php echo htmlspecialchars($year); ?>" required></label><br>
    <label>Semester: <input name="sem" value="<?php echo htmlspecialchars($sem); ?>" required></label><br>
    <label>PDF file: <input type="file" name="pdf" accept="application/pdf" required></label><br>
    <button type="submit">Upload (Pending Admin Approval)</button>
  </form>
  <p><a href="index.php">← Home</a></p>
</div>
</body>
</html>
