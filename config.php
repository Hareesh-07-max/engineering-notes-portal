<?php
// config.php
session_start();

// base dirs
define('BASE_DIR', __DIR__);
define('NOTES_DIR', BASE_DIR . '/notes');
define('PENDING_DIR', BASE_DIR . '/pending_uploads');
define('DATA_DIR', BASE_DIR . '/data');

@mkdir(NOTES_DIR, 0775, true);
@mkdir(PENDING_DIR, 0775, true);
@mkdir(DATA_DIR, 0775, true);

// JSON data files
define('USERS_FILE', DATA_DIR . '/users.json');
define('PENDING_FILE', DATA_DIR . '/pending.json');
define('RATINGS_FILE', DATA_DIR . '/ratings.json');

// create users.json with admin if not exists
if(!file_exists(USERS_FILE)){
    $admin = [
        'admin' => [
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'is_admin' => true,
            'created_at' => time()
        ]
    ];
    file_put_contents(USERS_FILE, json_encode($admin, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), LOCK_EX);
}

// helpers
function load_json($path){
    if(!file_exists($path)) return [];
    $s = file_get_contents($path);
    $a = json_decode($s, true);
    return $a ?: [];
}
function save_json($path, $data){
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), LOCK_EX);
}

function is_logged_in(){ return !empty($_SESSION['user']); }
function is_admin(){ return !empty($_SESSION['user']) && !empty($_SESSION['is_admin']); }

?>
