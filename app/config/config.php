<?php

// Dynamic Base URL
define('BASEURL', isset($_SERVER['HTTP_HOST']) ? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] : 'http://localhost/pos_mangkojek');

// DB Constants
define('DB_HOST', getenv('DB_HOST') ?: ($_SERVER['DB_HOST'] ?? 'localhost'));
define('DB_USER', getenv('DB_USER') ?: ($_SERVER['DB_USER'] ?? 'root'));
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : ($_SERVER['DB_PASS'] ?? ''));
define('DB_NAME', getenv('DB_NAME') ?: ($_SERVER['DB_NAME'] ?? 'pos_mangkojek'));
