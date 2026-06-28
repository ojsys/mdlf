<?php
/* =====================================================================
 *  MDLF — Local SQLite installer (development only)
 *  Creates storage/mdlf.sqlite with the same tables + seed data as the
 *  MySQL schema. Run from the command line:
 *      php database/install_sqlite.php
 *  Production/cPanel still uses database/schema.sql + MySQL.
 * ===================================================================== */

$dbFile = __DIR__ . '/../storage/mdlf.sqlite';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Run this from the command line.');
}

@unlink($dbFile);
$pdo = new PDO('sqlite:' . $dbFile, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec('PRAGMA foreign_keys = ON');

/* ---------- Schema (SQLite equivalents of database/schema.sql) ---------- */
$pdo->exec(<<<'SQL'
CREATE TABLE users (
  id            INTEGER PRIMARY KEY AUTOINCREMENT,
  name          TEXT NOT NULL,
  email         TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  role          TEXT NOT NULL DEFAULT 'member',
  phone         TEXT,
  location      TEXT,
  created_at    TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE settings (
  name  TEXT PRIMARY KEY,
  value TEXT
);

CREATE TABLE impact_stats (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  label      TEXT NOT NULL,
  value      TEXT NOT NULL,
  suffix     TEXT DEFAULT '',
  sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE posts (
  id           INTEGER PRIMARY KEY AUTOINCREMENT,
  title        TEXT NOT NULL,
  slug         TEXT NOT NULL UNIQUE,
  excerpt      TEXT,
  body         TEXT,
  cover_image  TEXT,
  status       TEXT NOT NULL DEFAULT 'draft',
  published_at TEXT,
  created_at   TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE modules (
  id          INTEGER PRIMARY KEY AUTOINCREMENT,
  title       TEXT NOT NULL,
  slug        TEXT NOT NULL UNIQUE,
  summary     TEXT,
  description TEXT,
  cover_image TEXT,
  scripture   TEXT,
  sort_order  INTEGER NOT NULL DEFAULT 0,
  status      TEXT NOT NULL DEFAULT 'published',
  created_at  TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE lessons (
  id           INTEGER PRIMARY KEY AUTOINCREMENT,
  module_id    INTEGER NOT NULL REFERENCES modules(id) ON DELETE CASCADE,
  title        TEXT NOT NULL,
  slug         TEXT NOT NULL UNIQUE,
  summary      TEXT,
  content      TEXT,
  duration_min INTEGER NOT NULL DEFAULT 10,
  sort_order   INTEGER NOT NULL DEFAULT 0,
  status       TEXT NOT NULL DEFAULT 'published',
  created_at   TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE resources (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  lesson_id  INTEGER NOT NULL REFERENCES lessons(id) ON DELETE CASCADE,
  label      TEXT NOT NULL,
  type       TEXT NOT NULL DEFAULT 'link',
  url        TEXT,
  sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE lesson_progress (
  id           INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id      INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  lesson_id    INTEGER NOT NULL REFERENCES lessons(id) ON DELETE CASCADE,
  completed_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE (user_id, lesson_id)
);

CREATE TABLE messages (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  name       TEXT NOT NULL,
  email      TEXT NOT NULL,
  subject    TEXT,
  body       TEXT NOT NULL,
  is_read    INTEGER NOT NULL DEFAULT 0,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pages (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  title      TEXT NOT NULL,
  slug       TEXT NOT NULL UNIQUE,
  content    TEXT,
  status     TEXT NOT NULL DEFAULT 'published',
  sort_order INTEGER NOT NULL DEFAULT 0,
  updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE objectives (
  id          INTEGER PRIMARY KEY AUTOINCREMENT,
  title       TEXT NOT NULL,
  description TEXT,
  sort_order  INTEGER NOT NULL DEFAULT 0,
  created_at  TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE blocks (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  page_id    INTEGER NOT NULL REFERENCES pages(id) ON DELETE CASCADE,
  type       TEXT NOT NULL,
  sort_order INTEGER NOT NULL DEFAULT 0,
  data       TEXT,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE media (
  id            INTEGER PRIMARY KEY AUTOINCREMENT,
  filename      TEXT NOT NULL,
  type          TEXT NOT NULL DEFAULT 'image',
  mime          TEXT,
  original_name TEXT,
  created_at    TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
SQL);

/* ---------- Seed data: reuse the INSERTs from schema.sql ----------
 * We extract the seed section, drop the MySQL-only upsert tails, run it,
 * then convert the literal "\n" escapes (MySQL string escapes) into real
 * newlines so body/content render exactly as they do on MySQL.            */
$schema = file_get_contents(__DIR__ . '/schema.sql');
$seed   = substr($schema, strpos($schema, 'INSERT INTO users'));
$seed   = preg_replace('/\s*ON DUPLICATE KEY UPDATE[^;]*/i', '', $seed);

$pdo->exec($seed);

foreach (['posts' => 'body', 'lessons' => 'content'] as $table => $col) {
    // char(92)||'n'  ==  the two characters  \n  ->  char(10) newline
    $pdo->exec("UPDATE $table SET $col = replace($col, char(92)||'n', char(10))");
}

/* ---------- Default page-builder blocks ---------- */
require __DIR__ . '/blocks_seed.php';
seed_default_blocks($pdo);

$count = fn($t) => (int) $pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn();
echo "✓ SQLite database created at: $dbFile\n";
echo "  users={$count('users')} modules={$count('modules')} lessons={$count('lessons')} ",
     "posts={$count('posts')} resources={$count('resources')}\n";
echo "  pages={$count('pages')} objectives={$count('objectives')} blocks={$count('blocks')}\n";
echo "  Admin:  admin@mdlf.org / admin1234\n";
echo "  Member: disciple@mdlf.org / disciple1234\n";
