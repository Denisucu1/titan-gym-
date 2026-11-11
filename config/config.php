<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'Titan Gym & Fitness');
define('APP_ENV', 'development');

if (!defined('APP_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $appUrl = rtrim($protocol . $host . $scriptDir, '/');
    define('APP_URL', $appUrl ?: 'http://localhost:8000');
}

define('ROOT_DIR', dirname(__DIR__));
define('APP_DIR', ROOT_DIR . '/app');
define('VIEW_DIR', ROOT_DIR . '/views');
define('STORAGE_DIR', ROOT_DIR . '/storage');
define('UPLOAD_DIR', ROOT_DIR . '/public/assets/uploads');

define('MAX_UPLOAD_SIZE', 12 * 1024 * 1024);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf'])

define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51MzDemoKeyTitanGym00112233445566778899aabbccddeeff');
define('STRIPE_SECRET_KEY', 'sk_test_51MzDemoKeyTitanGymSecret00112233445566778899aabb');
define('STRIPE_CURRENCY', 'ron');

define('RATE_LIMIT_MAX_REQUESTS', 100);
define('RATE_LIMIT_TIME_WINDOW', 60);