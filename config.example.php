<?php
/* =====================================================================
 *  MDLF — Configuration TEMPLATE
 *
 *  Copy this file to `config.php` and fill in your real values:
 *      cp config.example.php config.php
 *
 *  `config.php` is git-ignored, so your credentials never get committed.
 *  Keep the real `config.php` only on each machine/server.
 * ===================================================================== */

return [
    // ---- Database ----
    // Production (cPanel/Hostinger): set 'driver' => 'mysql' and fill host/name/user/pass.
    // Local development with zero setup: 'driver' => 'sqlite' uses a single file.
    'db' => [
        'driver'  => 'mysql',                          // 'mysql' (production) or 'sqlite' (local)
        'path'    => __DIR__ . '/storage/mdlf.sqlite',  // sqlite database file (local only)

        // ---- MySQL settings (used when driver = 'mysql') ----
        'host'    => 'localhost',           // almost always 'localhost'
        'name'    => 'yourcpaneluser_mdlf', // e.g. u123456789_mdlf on Hostinger
        'user'    => 'yourcpaneluser_mdlf', // database username
        'pass'    => 'your_db_password',    // database password
        'charset' => 'utf8mb4',
    ],

    // ---- Site ----
    // Leave base_url empty to auto-detect. Set it only if auto-detect fails,
    // e.g. 'https://mdlf.org' or 'https://yoursite.com/mdlf'
    'base_url'  => '',

    // Show detailed errors while setting up. Set to false once live.
    'debug'     => false,

    // Used as a fallback site name before the DB is reachable.
    'site_name' => 'Mipo Dadang Leadership Foundation',
];
