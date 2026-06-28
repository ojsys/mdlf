<?php
/* Bootstrap — included by the front controller before routing. */

session_start();

$config = require __DIR__ . '/config.php';

error_reporting(E_ALL);
ini_set('display_errors', $config['debug'] ? '1' : '0');

require __DIR__ . '/app/core/db.php';
require __DIR__ . '/app/core/helpers.php';
require __DIR__ . '/app/core/blocks.php';
require __DIR__ . '/app/core/Router.php';

/* ---- Auto-detect base URL (works in public_html root or a subfolder) ---- */
$scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$autoBase = $scheme . '://' . $host . $basePath;

$GLOBALS['APP'] = [
    'base_url'  => $config['base_url'] !== '' ? rtrim($config['base_url'], '/') : $autoBase,
    'base_path' => $basePath,
    'site_name' => $config['site_name'],
    'debug'     => $config['debug'],
];

/* ---- Work out the route path relative to the base path ---- */
$uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$uri  = rawurldecode($uri);
if ($basePath && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$GLOBALS['ROUTE_PATH'] = '/' . trim($uri, '/');

/* ---- Connect to the database (graceful message if not configured) ---- */
try {
    DB::connect($config['db']);
} catch (Throwable $ex) {
    http_response_code(500);
    $hint = $config['debug'] ? '<pre style="white-space:pre-wrap">' . e($ex->getMessage()) . '</pre>' : '';
    echo '<!doctype html><meta charset="utf-8"><title>Setup needed</title>'
        . '<div style="font-family:system-ui;max-width:640px;margin:12vh auto;padding:0 24px;line-height:1.6">'
        . '<h1 style="font-size:1.4rem">Database not connected</h1>'
        . '<p>Edit <code>config.php</code> with your cPanel MySQL database name, user and password, '
        . 'then import <code>database/schema.sql</code> in phpMyAdmin (or run <code>install.php</code>).</p>'
        . $hint . '</div>';
    exit;
}
