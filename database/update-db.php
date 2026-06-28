<?php
/**
 * MDLF Database Update Script
 * Adds pages table and seed data to existing database
 */

// Load bootstrap to get config and DB connection
require __DIR__ . '/../bootstrap.php';

try {
    // 1. Create pages table if it doesn't exist (SQLite compatible)
    DB::run("
        CREATE TABLE IF NOT EXISTS pages (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          title TEXT NOT NULL,
          slug TEXT NOT NULL UNIQUE,
          content TEXT,
          status TEXT NOT NULL DEFAULT 'published',
          sort_order INTEGER NOT NULL DEFAULT 0,
          updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
          created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ pages table created or already exists\n";

    // 2. Create objectives table
    DB::run("
        CREATE TABLE IF NOT EXISTS objectives (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          title TEXT NOT NULL,
          description TEXT,
          sort_order INTEGER NOT NULL DEFAULT 0
        )
    ");
    echo "✅ objectives table created or already exists\n";

    // 3. Insert default pages if they don't exist
    $defaultPages = [
        ['title' => 'Home', 'slug' => 'home', 'content' => '', 'status' => 'published', 'sort_order' => 1],
        ['title' => 'About', 'slug' => 'about', 'content' => '', 'status' => 'published', 'sort_order' => 2],
        ['title' => 'Our Work', 'slug' => 'our-work', 'content' => '', 'status' => 'published', 'sort_order' => 3],
        ['title' => 'Discipleship', 'slug' => 'discipleship', 'content' => '', 'status' => 'published', 'sort_order' => 4],
        ['title' => 'Give', 'slug' => 'give', 'content' => '', 'status' => 'published', 'sort_order' => 5],
        ['title' => 'Contact', 'slug' => 'contact', 'content' => '', 'status' => 'published', 'sort_order' => 6],
    ];

    foreach ($defaultPages as $page) {
        // Check if page already exists
        $existing = DB::one("SELECT id FROM pages WHERE slug = ?", [$page['slug']]);
        if (!$existing) {
            DB::insert('pages', $page);
            echo "✅ Inserted page: {$page['title']}\n";
        } else {
            echo "ℹ️  Page already exists: {$page['title']}\n";
        }
    }

    // 4. Insert default objectives
    $defaultObjectives = [
        ['title' => 'Sustainable care', 'description' => 'Addressing people’s sociological and economic needs through sustainable, lasting approaches.', 'sort_order' => 1],
        ['title' => 'A right perspective', 'description' => 'Helping people recover a right perspective toward one another — every person bears God’s image.', 'sort_order' => 2],
        ['title' => 'Renewal & mentorship', 'description' => 'Providing informal renewal and mentorship to emerging leaders across communities.', 'sort_order' => 3],
        ['title' => 'Conflict & crisis', 'description' => 'Building the capacity of persons to manage the conflicts and crises of life with appropriate measures.', 'sort_order' => 4],
        ['title' => 'Intervention', 'description' => 'Providing intervention for vulnerable persons to cope with life beyond temporal physical needs.', 'sort_order' => 5],
        ['title' => 'Faith & purpose', 'description' => 'Through the ministry of the foundation, many have come to faith in Christ and found purpose.', 'sort_order' => 6],
    ];

    foreach ($defaultObjectives as $obj) {
        $existing = DB::one("SELECT id FROM objectives WHERE title = ?", [$obj['title']]);
        if (!$existing) {
            DB::insert('objectives', $obj);
            echo "✅ Inserted objective: {$obj['title']}\n";
        } else {
            echo "ℹ️  Objective already exists: {$obj['title']}\n";
        }
    }

    echo "\n🎉 Database update complete!\n";
    echo "Please delete this file after use.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
