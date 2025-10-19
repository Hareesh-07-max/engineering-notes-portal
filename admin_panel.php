<?php
require 'config.php';
if(!is_admin()){ header('Location: login.php'); exit; }

$pending = load_json(PENDING_FILE);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Panel</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Admin Panel — Pending Uploads</h2>
  <?php if(empty($pending)): ?>
    <p>No pending uploads.</p>
  <?php else: ?>
    <table class="admin-table">
      <thead><tr><th>Title</th><th>Branch</th><th>Year</th><th>Sem</th><th>Uploaded by</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($pending as $id=>$p): ?>
        <tr>
          <td><?php echo htmlspecialchars($p['title'] . " ({$p['original_name']})"); ?></td>
          <td><?php echo htmlspecialchars($p['branch']); ?></td>
          <td><?php echo htmlspecialchars($p['year']); ?></td>
          <td><?php echo htmlspecialchars($p['sem']); ?></td>
          <td><?php echo htmlspecialchars($p['uploaded_by']); ?></td>
          <td>
            <a href="admin_action.php?action=approve&id=<?php echo urlencode($id); ?>">Approve</a> |
            <a href="admin_action.php?action=reject&id=<?php echo urlencode($id); ?>">Reject</a> |
            <a href="pending_uploads/<?php echo rawurlencode($p['stored_name']); ?>" target="_blank">Preview</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
  <p><a href="index.php">← Home</a></p>
</div>
</body>
</html>
