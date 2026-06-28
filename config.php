<?php
/* =====================================================================
 *  MDLF — Configuration
 *  Edit the database settings below to match your cPanel MySQL database.
 *  In cPanel: "MySQL Databases" -> create a database + user, add the user
 *  to the database with ALL PRIVILEGES, then paste the values here.
 * ===================================================================== */

return [
    // ---- Database ----
    // For cPanel/production, set 'driver' => 'mysql' and fill host/name/user/pass.
    // For local development with zero setup, 'driver' => 'sqlite' uses a single file.
    'db' => [
        'driver'  => 'sqlite',                       // 'sqlite' (local) or 'mysql' (cPanel)
        'path'    => __DIR__ . '/storage/mdlf.sqlite', // sqlite database file (local only)

        // ---- MySQL settings (used when driver = 'mysql', from cPanel > MySQL Databases) ----
        'host'    => 'localhost',          // almost always 'localhost' on cPanel
        'name'    => 'yourcpaneluser_mdlf', // e.g. cpaneluser_mdlf
        'user'    => 'yourcpaneluser_mdlf', // database username
        'pass'    => 'your_db_password',    // database password
        'charset' => 'utf8mb4',
    ],

    // ---- Site ----
    // Leave base_url empty to auto-detect. Set it only if auto-detect fails,
    // e.g. 'https://mdlf.org' or 'https://yoursite.com/mdlf'
    'base_url'  => '',

    // Show detailed errors while setting up. Set to false once live.
    'debug'     => true,

    // Used as a fallback site name before the DB is reachable.
    'site_name' => 'Mipo Dadang Leadership Foundation',
];
