<?php
/* Dev-only router for PHP's built-in server (`php -S`).
 * Serves real static files directly; routes everything else to index.php,
 * mirroring the production .htaccess rewrite. Not used on cPanel/Apache. */

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

// Serve existing static assets directly (css/js/images/etc.), but never PHP files.
if ($path !== '/' && is_file($file) && pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
    return false;
}

require __DIR__ . '/index.php';
