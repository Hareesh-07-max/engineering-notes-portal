<?php
require 'config.php';
if(!isset($_GET['file'])) die('File not specified.');
$requested = preg_replace('/[^A-Za-z0-9\/\._ -]/', '', $_GET['file']);
$base = realpath(NOTES_DIR);
$target = realpath($base . '/' . $requested);
if($target === false || strpos($target, $base) !== 0 || !file_exists($target)) die('File not found.');
$filename = basename($target);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title><?php echo htmlspecialchars($filename); ?></title>
<style>body,html{margin:0;height:100%}iframe{width:100%;height:100vh;border:none}</style>
</head>
<body>
<iframe src="<?php echo 'notes/' . htmlspecialchars($requested); ?>"></iframe>
</body>
</html>
