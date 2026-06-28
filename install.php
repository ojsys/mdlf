<?php
/* =====================================================================
 *  MDLF — One-time installer
 *  Open this in your browser ONCE after editing config.php:
 *      https://yoursite.com/install.php
 *  It creates all tables and seeds starter content.
 *  DELETE this file afterwards.
 * ===================================================================== */

$config = require __DIR__ . '/config.php';
header('Content-Type: text/html; charset=utf-8');

function page(string $body): void {
    echo '<!doctype html><meta charset="utf-8"><title>MDLF Installer</title>'
       . '<style>body{font-family:system-ui,Segoe UI,Roboto,sans-serif;max-width:680px;margin:8vh auto;padding:0 24px;line-height:1.6;color:#0E1B33}'
       . 'h1{font-size:1.6rem}.ok{color:#2F7D5B}.bad{color:#B23A3A}code{background:#F3EEDF;padding:.1rem .4rem;border-radius:4px}'
       . '.box{background:#FBF8F1;border:1px solid #E3DACA;border-radius:12px;padding:1.2rem 1.5rem;margin:1rem 0}'
       . 'a.btn{display:inline-block;background:#D7A33A;color:#08101F;text-decoration:none;font-weight:700;padding:.7rem 1.3rem;border-radius:999px;margin-top:1rem}</style>'
       . $body;
}

try {
    $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset={$config['db']['charset']}";
    $pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (Throwable $e) {
    page('<h1 class="bad">Could not connect to the database</h1>'
        . '<div class="box">Check the database settings in <code>config.php</code>, then reload this page.<br><br>'
        . '<small>' . htmlspecialchars($e->getMessage()) . '</small></div>');
    exit;
}

$sqlFile = __DIR__ . '/database/schema.sql';
if (!is_readable($sqlFile)) {
    page('<h1 class="bad">schema.sql not found</h1><div class="box">Expected it at <code>database/schema.sql</code>. '
        . 'Re-upload that file, or import it manually via phpMyAdmin.</div>');
    exit;
}

$sql = file_get_contents($sqlFile);

try {
    // Run the whole script. PDO with mysqlnd executes multi-statements fine.
    $pdo->exec($sql);

    // Seed the default page-builder blocks (home page), same as local installer.
    require __DIR__ . '/database/blocks_seed.php';
    seed_default_blocks($pdo);

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $modules = (int) $pdo->query("SELECT COUNT(*) FROM modules")->fetchColumn();
    $lessons = (int) $pdo->query("SELECT COUNT(*) FROM lessons")->fetchColumn();

    page('<h1 class="ok">✓ Installation complete</h1>'
        . '<div class="box">Created <strong>' . count($tables) . '</strong> tables, '
        . '<strong>' . $modules . '</strong> modules and <strong>' . $lessons . '</strong> lessons.</div>'
        . '<div class="box"><strong>Default sign-in details</strong><br>'
        . 'Admin &nbsp;→ <code>admin@mdlf.org</code> / <code>admin1234</code><br>'
        . 'Member → <code>disciple@mdlf.org</code> / <code>disciple1234</code><br><br>'
        . '<strong style="color:#B23A3A">Change these passwords immediately.</strong></div>'
        . '<div class="box" style="border-color:#B23A3A"><strong>Now delete <code>install.php</code></strong> from your server for security.</div>'
        . '<a class="btn" href="' . htmlspecialchars(dirname($_SERVER['SCRIPT_NAME'])) . '/">Go to the website →</a>');
} catch (Throwable $e) {
    page('<h1 class="bad">Installation error</h1>'
        . '<div class="box">' . htmlspecialchars($e->getMessage()) . '</div>'
        . '<div class="box">You can also import <code>database/schema.sql</code> directly in phpMyAdmin instead.</div>');
}
