<?php
require 'config.php';
if(!is_admin()){ header('Location: login.php'); exit; }

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$pending = load_json(PENDING_FILE);

if(!$id || !isset($pending[$id])){ die('Invalid request'); }

$entry = $pending[$id];
$stored = PENDING_DIR . '/' . $entry['stored_name'];

if($action === 'approve'){
    // ensure target dir exists
    $targetDir = NOTES_DIR . '/' . preg_replace('/[^A-Za-z0-9_-]/','', $entry['branch']) . '/' . preg_replace('/[^0-9]/','', $entry['year']) . '/' . preg_replace('/[^0-9]/','', $entry['sem']);
    @mkdir($targetDir, 0775, true);
    $dest = $targetDir . '/' . basename($entry['original_name']);
    if(!file_exists($stored)) { die('File missing.'); }
    if(!rename($stored, $dest)){ die('Move failed'); }
    // remove pending entry
    unset($pending[$id]);
    save_json(PENDING_FILE, $pending);
    header('Location: admin_panel.php'); exit;
} elseif($action === 'reject'){
    // delete file and remove
    if(file_exists($stored)) @unlink($stored);
    unset($pending[$id]);
    save_json(PENDING_FILE, $pending);
    header('Location: admin_panel.php'); exit;
} else {
    die('Unknown action');
}
