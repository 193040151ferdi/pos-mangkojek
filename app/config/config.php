<?php

// Dynamic Base URL
if (isset($_SERVER['HTTP_HOST'])) {
    $protocol = 'http://';
    if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
        $protocol = 'https://';
    }
    
    // Check if it's Vercel
    if (strpos($_SERVER['HTTP_HOST'], 'vercel.app') !== false || getenv('VERCEL') !== false || isset($_SERVER['VERCEL'])) {
        $base = $protocol . $_SERVER['HTTP_HOST'];
    } else {
        // Local environment (XAMPP or local server)
        $script_name = $_SERVER['SCRIPT_NAME'];
        $script_dir = str_replace('\\', '/', dirname($script_name));
        $script_dir = rtrim($script_dir, '/');
        
        // Strip /api if accessed through api/index.php
        if (substr($script_dir, -4) === '/api') {
            $script_dir = substr($script_dir, 0, -4);
        }
        
        $base = $protocol . $_SERVER['HTTP_HOST'] . $script_dir;
    }
    define('BASEURL', $base);
} else {
    define('BASEURL', 'http://localhost/pos_mangkojek');
}

// DB Constants
define('DB_HOST', getenv('DB_HOST') ?: ($_SERVER['DB_HOST'] ?? 'sql12.freesqldatabase.com'));
define('DB_USER', getenv('DB_USER') ?: ($_SERVER['DB_USER'] ?? 'sql12831040'));
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : ($_SERVER['DB_PASS'] ?? 'T4Px1GXHxs'));
define('DB_NAME', getenv('DB_NAME') ?: ($_SERVER['DB_NAME'] ?? 'sql12831040'));

// Absolute Project Root Path
define('ROOTPATH', dirname(__DIR__, 2));
