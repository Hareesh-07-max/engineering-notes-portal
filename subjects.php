<?php
require 'config.php';

$branch = isset($_GET['branch']) ? preg_replace('/[^A-Za-z0-9_-]/','',$_GET['branch']) : '';
$year = isset($_GET['year']) ? preg_replace('/[^0-9]/','',$_GET['year']) : '';
$sem = isset($_GET['sem']) ? preg_replace('/[^0-9]/','',$_GET['sem']) : '';

$targetDir = NOTES_DIR . '/' . $branch . '/' . $year . '/' . $sem;
$files = [];
if(is_dir($targetDir)){
    foreach(scandir($targetDir) as $f){
        if(is_file("$targetDir/$f") && strtolower(pathinfo($f, PATHINFO_EXTENSION)) === 'pdf'){
            $files[] = $f;
        }
    }
}
sort($files);
$ratings = load_json(RATINGS_FILE);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Notes</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2><?php echo htmlspecialchars("$branch — Year $year Sem $sem"); ?></h2>
  <?php if(is_logged_in()): ?>
    <p><a href="upload.php?branch=<?php echo urlencode($branch); ?>&year=<?php echo urlencode($year); ?>&sem=<?php echo urlencode($sem); ?>">Upload a note</a></p>
  <?php else: ?>
    <p><a href="login.php">Login</a> to upload notes.</p>
  <?php endif; ?>

  <?php if(empty($files)): ?>
    <p>No notes found. Admin will approve uploaded notes. Put files in <?php echo "notes/{$branch}/{$year}/{$sem}"; ?> to pre-populate.</p>
  <?php else: ?>
    <div class="notes-grid">
    <?php foreach($files as $file):
        $fileRel = "{$branch}/{$year}/{$sem}/{$file}";
        $fileUrl = "notes/".rawurlencode($branch)."/".rawurlencode($year)."/".rawurlencode($sem)."/".rawurlencode($file);
        $viewUrl = "view.php?file=" . rawurlencode($fileRel);
        $key = $fileRel;
        $avg = isset($ratings[$key]) && $ratings[$key]['count'] ? $ratings[$key]['total'] / $ratings[$key]['count'] : null;
    ?>
      <div class="note-card">
        <div class="note-title"><?php echo htmlspecialchars($file); ?></div>
        <div class="note-actions">
          <a class="btn" href="<?php echo $viewUrl; ?>" target="_blank">View</a>
          <a class="btn" href="<?php echo $fileUrl; ?>" download>Download</a>
        </div>
        <div class="rating" data-file="<?php echo htmlspecialchars($fileRel); ?>">
          <div class="avg"><?php echo $avg ? "Average: ".number_format($avg,2)." ({$ratings[$key]['count']} ratings)" : "Average: — (0 ratings)"; ?></div>
          <div class="stars">
            <span class="star" data-value="1">☆</span>
            <span class="star" data-value="2">☆</span>
            <span class="star" data-value="3">☆</span>
            <span class="star" data-value="4">☆</span>
            <span class="star" data-value="5">☆</span>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <p><a href="branch.php?branch=<?php echo urlencode($branch); ?>">← Back</a></p>
</div>

<script>
async function postRating(file, rating){
  const data = new FormData();
  data.append('file', file);
  data.append('rating', rating);
  const res = await fetch('rate.php', { method: 'POST', body: data});
  if(!res.ok) return null;
  return await res.json();
}

document.querySelectorAll('.rating').forEach(rDiv=>{
  const file = rDiv.dataset.file;
  const avgDiv = rDiv.querySelector('.avg');
  const stars = Array.from(rDiv.querySelectorAll('.star'));
  stars.forEach(s=>{
    s.addEventListener('mouseover', ()=> {
      const v = Number(s.dataset.value);
      stars.forEach(st => st.textContent = Number(st.dataset.value) <= v ? '★' : '☆');
    });
    s.addEventListener('mouseout', ()=> stars.forEach(st => st.textContent = '☆'));
    s.addEventListener('click', async ()=> {
      const v = Number(s.dataset.value);
      const resp = await postRating(file, v);
      if(resp && resp.ok){
        avgDiv.textContent = `Average: ${resp.avg.toFixed(2)} (${resp.count} ratings)`;
        alert('Thanks for rating!');
      } else {
        alert('Rating failed or you must be logged in.');
      }
    });
  });
});
</script>
</body>
</html>
