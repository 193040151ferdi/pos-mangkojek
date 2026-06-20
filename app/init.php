<?php

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/App.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Database.php';

// Helper function for product images
require_once __DIR__ . '/helpers/image_helper.php';
