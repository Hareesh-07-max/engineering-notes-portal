<?php
require 'config.php';
header('Content-Type: application/json');
if(!is_logged_in()){ echo json_encode(['ok'=>false,'error'=>'login']); exit; }

if(!isset($_POST['file']) || !isset($_POST['rating'])){ echo json_encode(['ok'=>false,'error'=>'missing']); exit; }

$file = preg_replace('/[^A-Za-z0-9\/\._ -]/','', $_POST['file']);
$rating = intval($_POST['rating']);
if($rating < 1 || $rating > 5){ echo json_encode(['ok'=>false,'error'=>'invalid']); exit; }

$base = realpath(NOTES_DIR);
$target = realpath($base . '/' . $file);
if($target === false || strpos($target, $base) !== 0 || !file_exists($target)){ echo json_encode(['ok'=>false,'error'=>'notfound']); exit; }

$ratings = load_json(RATINGS_FILE);
$key = $file;
if(!isset($ratings[$key])) $ratings[$key] = ['total'=>0,'count'=>0];
$ratings[$key]['total'] += $rating;
$ratings[$key]['count'] += 1;
save_json(RATINGS_FILE, $ratings);

$avg = $ratings[$key]['total'] / $ratings[$key]['count'];
echo json_encode(['ok'=>true,'avg'=>$avg,'count'=>$ratings[$key]['count']]);
exit;
