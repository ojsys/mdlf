<?php
/* Admin / CMS. All routes except login require an admin session. */

function ctl_admin_login(): void {
    if (is_admin()) redirect('admin');
    render('admin/login', [], 'auth');
}
function ctl_admin_login_post(): void {
    csrf_check();
    $email = trim($_POST['email'] ?? '');
    $user  = DB::one("SELECT * FROM users WHERE email=? AND role='admin'", [$email]);
    if (!$user || !password_verify($_POST['password'] ?? '', $user['password_hash'])) {
        keep_old(['email' => $email]);
        flash('Invalid admin credentials.', 'error');
        redirect('admin/login');
    }
    login_user($user);
    redirect('admin');
}
function ctl_admin_logout(): void { logout_user(); redirect('admin/login'); }

function ctl_admin_dashboard(): void {
    require_admin();
    $stats = [
        'members'  => (int) DB::value("SELECT COUNT(*) FROM users WHERE role='member'"),
        'modules'  => (int) DB::value("SELECT COUNT(*) FROM modules"),
        'lessons'  => (int) DB::value("SELECT COUNT(*) FROM lessons"),
        'posts'    => (int) DB::value("SELECT COUNT(*) FROM posts"),
        'messages' => (int) DB::value("SELECT COUNT(*) FROM messages WHERE is_read=0"),
        'completions' => (int) DB::value("SELECT COUNT(*) FROM lesson_progress"),
    ];
    $recentMsgs = DB::all("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");
    $recentMembers = DB::all("SELECT name,email,location,created_at FROM users WHERE role='member' ORDER BY created_at DESC LIMIT 5");
    render('admin/dashboard', compact('stats', 'recentMsgs', 'recentMembers'), 'admin');
}

/* ---------------- Posts ---------------- */
function ctl_admin_posts(): void {
    require_admin();
    $posts = DB::all("SELECT * FROM posts ORDER BY created_at DESC");
    render('admin/posts_index', compact('posts'), 'admin');
}
function ctl_admin_post_form(?string $id = null): void {
    require_admin();
    $post = $id ? DB::one("SELECT * FROM posts WHERE id=?", [$id]) : null;
    render('admin/post_form', compact('post'), 'admin');
}
function ctl_admin_post_save(?string $id = null): void {
    require_admin(); csrf_check();
    $title = trim($_POST['title'] ?? '');
    if ($title === '') { flash('A title is required.', 'error'); redirect('admin/posts'); }
    $data = [
        'title'       => $title,
        'slug'        => slugify($_POST['slug'] ?: $title),
        'excerpt'     => trim($_POST['excerpt'] ?? '') ?: null,
        'body'        => trim($_POST['body'] ?? ''),
        'cover_image' => trim($_POST['cover_image'] ?? '') ?: null,
        'status'      => ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft',
        'published_at'=> ($_POST['status'] ?? '') === 'published' ? date('Y-m-d H:i:s') : null,
    ];
    if ($id) { DB::update('posts', (int)$id, $data); flash('Story updated.'); }
    else     { DB::insert('posts', $data); flash('Story created.'); }
    redirect('admin/posts');
}
function ctl_admin_post_delete(string $id): void {
    require_admin(); csrf_check();
    DB::delete('posts', (int)$id); flash('Story deleted.'); redirect('admin/posts');
}

/* ---------------- Modules ---------------- */
function ctl_admin_modules(): void {
    require_admin();
    $modules = DB::all("SELECT m.*, (SELECT COUNT(*) FROM lessons l WHERE l.module_id=m.id) lessons
                        FROM modules m ORDER BY sort_order");
    render('admin/modules_index', compact('modules'), 'admin');
}
function ctl_admin_module_form(?string $id = null): void {
    require_admin();
    $module = $id ? DB::one("SELECT * FROM modules WHERE id=?", [$id]) : null;
    render('admin/module_form', compact('module'), 'admin');
}
function ctl_admin_module_save(?string $id = null): void {
    require_admin(); csrf_check();
    $title = trim($_POST['title'] ?? '');
    if ($title === '') { flash('A title is required.', 'error'); redirect('admin/modules'); }
    $data = [
        'title'       => $title,
        'slug'        => slugify($_POST['slug'] ?: $title),
        'summary'     => trim($_POST['summary'] ?? '') ?: null,
        'description' => trim($_POST['description'] ?? '') ?: null,
        'scripture'   => trim($_POST['scripture'] ?? '') ?: null,
        'cover_image' => trim($_POST['cover_image'] ?? '') ?: null,
        'sort_order'  => (int)($_POST['sort_order'] ?? 0),
        'status'      => ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published',
    ];
    if ($id) { DB::update('modules', (int)$id, $data); flash('Module updated.'); }
    else     { $id = DB::insert('modules', $data); flash('Module created. Now add lessons.'); }
    redirect('admin/modules/' . $id . '/lessons');
}
function ctl_admin_module_delete(string $id): void {
    require_admin(); csrf_check();
    DB::delete('modules', (int)$id); flash('Module and its lessons deleted.'); redirect('admin/modules');
}

/* ---------------- Lessons ---------------- */
function ctl_admin_lessons(string $moduleId): void {
    require_admin();
    $module  = DB::one("SELECT * FROM modules WHERE id=?", [$moduleId]);
    if (!$module) { flash('Module not found.', 'error'); redirect('admin/modules'); }
    $lessons = DB::all("SELECT * FROM lessons WHERE module_id=? ORDER BY sort_order", [$moduleId]);
    render('admin/lessons_index', compact('module', 'lessons'), 'admin');
}
function ctl_admin_lesson_form(string $moduleId, ?string $id = null): void {
    require_admin();
    $module = DB::one("SELECT * FROM modules WHERE id=?", [$moduleId]);
    if (!$module) { flash('Module not found.', 'error'); redirect('admin/modules'); }
    $lesson    = $id ? DB::one("SELECT * FROM lessons WHERE id=?", [$id]) : null;
    $resources = $id ? DB::all("SELECT * FROM resources WHERE lesson_id=? ORDER BY sort_order,id", [$id]) : [];
    render('admin/lesson_form', compact('module', 'lesson', 'resources'), 'admin');
}
function ctl_admin_lesson_save(string $moduleId, ?string $id = null): void {
    require_admin(); csrf_check();
    $title = trim($_POST['title'] ?? '');
    if ($title === '') { flash('A title is required.', 'error'); redirect('admin/modules/' . $moduleId . '/lessons'); }
    $data = [
        'module_id'    => (int)$moduleId,
        'title'        => $title,
        'slug'         => slugify($_POST['slug'] ?: $title),
        'summary'      => trim($_POST['summary'] ?? '') ?: null,
        'content'      => trim($_POST['content'] ?? ''),
        'duration_min' => (int)($_POST['duration_min'] ?? 10),
        'sort_order'   => (int)($_POST['sort_order'] ?? 0),
        'status'       => ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published',
    ];
    if ($id) { DB::update('lessons', (int)$id, $data); flash('Lesson updated.'); }
    else     { $id = DB::insert('lessons', $data); flash('Lesson created.'); }
    redirect('admin/modules/' . $moduleId . '/lessons/' . $id . '/edit');
}
function ctl_admin_lesson_delete(string $moduleId, string $id): void {
    require_admin(); csrf_check();
    DB::delete('lessons', (int)$id); flash('Lesson deleted.');
    redirect('admin/modules/' . $moduleId . '/lessons');
}

/* ---------------- Resources (inside lesson editor) ---------------- */
function ctl_admin_resource_add(string $moduleId, string $lessonId): void {
    require_admin(); csrf_check();
    $label = trim($_POST['label'] ?? '');
    if ($label !== '') {
        DB::insert('resources', [
            'lesson_id'  => (int)$lessonId,
            'label'      => $label,
            'type'       => in_array($_POST['type'] ?? '', ['video','pdf','link','scripture','audio']) ? $_POST['type'] : 'link',
            'url'        => trim($_POST['url'] ?? '') ?: null,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ]);
        flash('Resource added.');
    }
    redirect('admin/modules/' . $moduleId . '/lessons/' . $lessonId . '/edit');
}
function ctl_admin_resource_delete(string $moduleId, string $lessonId, string $id): void {
    require_admin(); csrf_check();
    DB::delete('resources', (int)$id); flash('Resource removed.');
    redirect('admin/modules/' . $moduleId . '/lessons/' . $lessonId . '/edit');
}

/* ---------------- Messages ---------------- */
function ctl_admin_messages(): void {
    require_admin();
    $messages = DB::all("SELECT * FROM messages ORDER BY created_at DESC");
    render('admin/messages_index', compact('messages'), 'admin');
}
function ctl_admin_message_read(string $id): void {
    require_admin(); csrf_check();
    DB::update('messages', (int)$id, ['is_read' => 1]);
    redirect('admin/messages');
}
function ctl_admin_message_delete(string $id): void {
    require_admin(); csrf_check();
    DB::delete('messages', (int)$id); flash('Message deleted.'); redirect('admin/messages');
}

/* ---------------- Members ---------------- */
function ctl_admin_members(): void {
    require_admin();
    $members = DB::all(
        "SELECT u.*, (SELECT COUNT(*) FROM lesson_progress lp WHERE lp.user_id=u.id) completed
         FROM users u WHERE role='member' ORDER BY created_at DESC");
    render('admin/members_index', compact('members'), 'admin');
}

/* ---------------- Pages ---------------- */
function ctl_admin_pages(): void {
    require_admin();
    $pages = DB::all("SELECT * FROM pages ORDER BY sort_order, id");
    render('admin/pages_index', compact('pages'), 'admin');
}
function ctl_admin_page_form(?string $id = null): void {
    require_admin();
    $page = $id ? DB::one("SELECT * FROM pages WHERE id=?", [$id]) : null;
    render('admin/page_form', compact('page'), 'admin');
}
function ctl_admin_page_save(?string $id = null): void {
    require_admin(); csrf_check();
    $title = trim($_POST['title'] ?? '');
    if ($title === '') { flash('A title is required.', 'error'); redirect('admin/pages'); }
    $data = [
        'title'       => $title,
        'slug'        => slugify($_POST['slug'] ?: $title),
        'content'     => trim($_POST['content'] ?? ''),
        'status'      => ($_POST['status'] ?? 'published') === 'published' ? 'published' : 'draft',
        'sort_order'  => (int)($_POST['sort_order'] ?? 0),
    ];
    if ($id) {
        DB::update('pages', (int)$id, $data);
        flash('Page updated.');
        redirect('admin/pages');
    }
    // New page → go straight to the block builder to compose it.
    $newId = DB::insert('pages', $data);
    flash('Page created — now add blocks.');
    redirect('admin/pages/' . $newId . '/builder');
}
function ctl_admin_page_delete(string $id): void {
  require_admin(); csrf_check();
  // Don't allow deleting default pages (home, about, etc.)
  $page = DB::one("SELECT slug FROM pages WHERE id=?", [$id]);
  if (in_array($page['slug'], ['home', 'about', 'our-work', 'discipleship', 'give', 'contact'])) {
    flash('Cannot delete default pages.', 'error');
  } else {
    DB::delete('pages', (int)$id);
    flash('Page deleted.');
  }
  redirect('admin/pages');
}

/* ---------------- Page builder (blocks) ---------------- */
function ctl_admin_page_builder(string $id): void {
    require_admin();
    $page = DB::one("SELECT * FROM pages WHERE id=?", [$id]);
    if (!$page) { flash('Page not found.', 'error'); redirect('admin/pages'); }
    $blocks = page_blocks((int)$id);
    render('admin/page_builder', compact('page', 'blocks'), 'admin');
}

function ctl_admin_block_add(string $id): void {
    require_admin(); csrf_check();
    $page = DB::one("SELECT id FROM pages WHERE id=?", [$id]);
    if (!$page) {
        if (is_ajax()) json_out(['ok' => false, 'error' => 'no_page'], 404);
        flash('Page not found.', 'error'); redirect('admin/pages');
    }
    $type = $_POST['type'] ?? '';
    if (!block_def($type)) {
        if (is_ajax()) json_out(['ok' => false, 'error' => 'bad_type'], 400);
        flash('Unknown block type.', 'error'); redirect('admin/pages/' . $id . '/builder');
    }

    // Position: no 'after' key → append to end; 'after'=0 → start; else after that block.
    if (!array_key_exists('after', $_POST)) {
        $newOrder = (int) DB::value("SELECT COALESCE(MAX(sort_order),0) FROM blocks WHERE page_id=?", [$id]) + 1;
    } else {
        $after = (int) $_POST['after'];
        $afterOrder = $after > 0
            ? (int) DB::value("SELECT sort_order FROM blocks WHERE id=? AND page_id=?", [$after, $id])
            : 0;
        DB::run("UPDATE blocks SET sort_order = sort_order + 1 WHERE page_id=? AND sort_order > ?", [$id, $afterOrder]);
        $newOrder = $afterOrder + 1;
    }

    $newId = DB::insert('blocks', [
        'page_id'    => (int)$id,
        'type'       => $type,
        'sort_order' => $newOrder,
        'data'       => block_data_encode(block_def($type)['defaults'] ?? []),
    ]);

    if (is_ajax()) {
        $block = DB::one("SELECT * FROM blocks WHERE id=?", [$newId]);
        json_out(['ok' => true, 'id' => (int)$newId, 'html' => block_render_editable_one($block)]);
    }
    flash('Block added — edit it below.');
    redirect('admin/pages/' . $id . '/builder#block-' . $newId);
}

function ctl_admin_block_save(string $id): void {
    require_admin(); csrf_check();
    $block = DB::one("SELECT * FROM blocks WHERE id=?", [$id]);
    if (!$block) {
        if (is_ajax()) json_out(['ok' => false, 'error' => 'not_found'], 404);
        flash('Block not found.', 'error'); redirect('admin/pages');
    }
    $data = block_data_from_post($block['type'], $_POST);
    DB::update('blocks', (int)$id, ['data' => block_data_encode($data)]);
    if (is_ajax()) {
        $fresh = DB::one("SELECT * FROM blocks WHERE id=?", [$id]); // re-render with new data
        json_out(['ok' => true, 'html' => block_render($fresh, true)]);
    }
    flash('Block saved.');
    redirect('admin/pages/' . $block['page_id'] . '/builder#block-' . $id);
}

/**
 * Gather every media item: DB-tracked uploads + bundled assets/img files.
 * Each item: ['path','url','name','type','id'(uploads only),'system'(bundled)].
 */
function media_all(?string $filterType = null): array {
    $items = [];
    foreach (DB::all("SELECT * FROM media ORDER BY created_at DESC, id DESC") as $m) {
        $items[] = [
            'id'     => (int)$m['id'],
            'path'   => 'uploads/' . $m['filename'],
            'url'    => upload_url($m['filename']),
            'name'   => $m['original_name'] ?: $m['filename'],
            'type'   => $m['type'],
            'system' => false,
        ];
    }
    // Bundled images shipped in the theme (read-only).
    foreach (glob(dirname(__DIR__, 2) . '/assets/img/*.{jpg,jpeg,png,gif,svg,webp}', GLOB_BRACE) ?: [] as $f) {
        $name = basename($f);
        $items[] = ['id' => 0, 'path' => 'img/' . $name, 'url' => asset('img/' . $name),
                    'name' => $name, 'type' => 'image', 'system' => true];
    }
    if ($filterType && in_array($filterType, ['image', 'audio', 'video'], true)) {
        $items = array_values(array_filter($items, fn($i) => $i['type'] === $filterType));
    }
    return $items;
}

/** Media Library admin page. */
function ctl_admin_media(): void {
    require_admin();
    $media = media_all();
    render('admin/media_index', compact('media'), 'admin');
}

/** AJAX: list media (for the picker), optional ?type=image|audio|video. */
function ctl_admin_media_list(): void {
    require_admin();
    json_out(['ok' => true, 'items' => media_all($_GET['type'] ?? null)]);
}

/** Handle a media upload (image/audio/video); record it; return path/url/type. */
function ctl_admin_media_upload(): void {
    require_admin(); csrf_check();
    $allowed = array_merge(...array_values(media_extensions()));
    $file = handle_upload('file', $allowed, 64 * 1024 * 1024); // up to 64MB
    if (!$file) {
        if (is_ajax()) json_out(['ok' => false, 'error' => 'upload_failed'], 400);
        flash('Upload failed — check the file type and size.', 'error'); redirect('admin/media');
    }
    $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $orig = $_FILES['file']['name'] ?? $file;
    $id = DB::insert('media', [
        'filename'      => $file,
        'type'          => media_type_for_ext($ext),
        'mime'          => $_FILES['file']['type'] ?? null,
        'original_name' => $orig,
    ]);
    if (is_ajax()) {
        json_out(['ok' => true, 'id' => $id, 'path' => 'uploads/' . $file,
                  'url' => upload_url($file), 'type' => media_type_for_ext($ext), 'name' => $orig]);
    }
    flash('Media uploaded.'); redirect('admin/media');
}

/** Delete an uploaded media item (DB row + file). Bundled assets are protected. */
function ctl_admin_media_delete(string $id): void {
    require_admin(); csrf_check();
    $m = DB::one("SELECT * FROM media WHERE id=?", [$id]);
    if ($m) {
        $path = dirname(__DIR__, 2) . '/storage/uploads/' . $m['filename'];
        if (is_file($path)) @unlink($path);
        DB::delete('media', (int)$id);
        flash('Media deleted.');
    }
    if (is_ajax()) json_out(['ok' => true]);
    redirect('admin/media');
}

/** AJAX: return the editable field form (inputs) for one block. */
function ctl_admin_block_form(string $id): void {
    require_admin();
    $block = DB::one("SELECT * FROM blocks WHERE id=?", [$id]);
    if (!$block) json_out(['ok' => false, 'error' => 'not_found'], 404);
    $def  = block_def($block['type']);
    $data = block_data($block);
    $html = '';
    foreach (($def['fields'] ?? []) as $key => $field) {
        $html .= block_field_input((int)$block['id'], $key, $field, $data[$key] ?? '');
    }
    json_out([
        'ok'      => true,
        'type'    => $block['type'],
        'label'   => $def['label'],
        'dynamic' => !empty($def['dynamic']),
        'html'    => $html,
    ]);
}

function ctl_admin_block_delete(string $id): void {
    require_admin(); csrf_check();
    $block = DB::one("SELECT page_id FROM blocks WHERE id=?", [$id]);
    DB::delete('blocks', (int)$id);
    if (is_ajax()) json_out(['ok' => true]);
    flash('Block deleted.');
    redirect('admin/pages/' . ($block['page_id'] ?? '') . '/builder');
}

function ctl_admin_blocks_reorder(string $id): void {
    require_admin(); csrf_check();
    if (!empty($_POST['order']) && is_array($_POST['order'])) {
        // Full ordering (drag-and-drop): assign sort_order by position.
        $i = 1;
        foreach ($_POST['order'] as $bid) {
            DB::run("UPDATE blocks SET sort_order=? WHERE id=? AND page_id=?", [$i++, (int)$bid, (int)$id]);
        }
    } elseif (!empty($_POST['move'])) {
        // Single step move (no-JS ▲▼ buttons): swap with the neighbour.
        block_move((int)$_POST['move'], (int)$id, ($_POST['dir'] ?? 'up') === 'down' ? 'down' : 'up');
    }
    if (is_ajax()) json_out(['ok' => true]);
    redirect('admin/pages/' . $id . '/builder');
}

/** Swap a block's order with its neighbour above ('up') or below ('down'). */
function block_move(int $blockId, int $pageId, string $dir): void {
    $cur = DB::one("SELECT id, sort_order FROM blocks WHERE id=? AND page_id=?", [$blockId, $pageId]);
    if (!$cur) return;
    $op  = $dir === 'up' ? '<' : '>';
    $ord = $dir === 'up' ? 'DESC' : 'ASC';
    $nb  = DB::one("SELECT id, sort_order FROM blocks WHERE page_id=? AND sort_order $op ? ORDER BY sort_order $ord LIMIT 1",
        [$pageId, $cur['sort_order']]);
    if (!$nb) return;
    DB::update('blocks', (int)$cur['id'], ['sort_order' => (int)$nb['sort_order']]);
    DB::update('blocks', (int)$nb['id'],  ['sort_order' => (int)$cur['sort_order']]);
}

function ctl_admin_objectives(): void {
  require_admin();
  $objectives = DB::all("SELECT * FROM objectives ORDER BY sort_order, id");
  render('admin/objectives_index', compact('objectives'), 'admin');
}

function ctl_admin_objective_form(?string $id = null): void {
  require_admin();
  $objective = $id ? DB::one("SELECT * FROM objectives WHERE id=?", [$id]) : null;
  render('admin/objective_form', compact('objective'), 'admin');
}

function ctl_admin_objective_save(?string $id = null): void {
  require_admin(); csrf_check();
  $title = trim($_POST['title'] ?? '');
  if ($title === '') { flash('A title is required.', 'error'); redirect('admin/objectives'); }
  $data = [
    'title' => $title,
    'description' => trim($_POST['description'] ?? ''),
    'sort_order' => (int)($_POST['sort_order'] ?? 0),
  ];
  if ($id) {
    DB::update('objectives', (int)$id, $data);
    flash('Objective updated.');
  } else {
    DB::insert('objectives', $data);
    flash('Objective created.');
  }
  redirect('admin/objectives');
}

function ctl_admin_objective_delete(string $id): void {
  require_admin(); csrf_check();
  DB::delete('objectives', (int)$id);
  flash('Objective deleted.');
  redirect('admin/objectives');
}

/* ---------------- Settings + impact stats ---------------- */
function ctl_admin_settings(): void {
    require_admin();
    $stats = DB::all("SELECT * FROM impact_stats ORDER BY sort_order");
    render('admin/settings', compact('stats'), 'admin');
}
function ctl_admin_settings_save(): void {
    require_admin(); csrf_check();
    
    // Handle logo upload
    if ($logoFile = handle_upload('logo', ['png', 'jpg', 'jpeg', 'gif', 'svg'])) {
        $upsert = DB::driver() === 'sqlite'
            ? "INSERT INTO settings (name,value) VALUES (?,?)
               ON CONFLICT(name) DO UPDATE SET value=excluded.value"
            : "INSERT INTO settings (name,value) VALUES (?,?)
               ON DUPLICATE KEY UPDATE value=VALUES(value)";
        DB::run($upsert, ['logo', $logoFile]);
    }
    
    // Handle favicon upload
    if ($faviconFile = handle_upload('favicon', ['ico', 'png'])) {
        $upsert = DB::driver() === 'sqlite'
            ? "INSERT INTO settings (name,value) VALUES (?,?)
               ON CONFLICT(name) DO UPDATE SET value=excluded.value"
            : "INSERT INTO settings (name,value) VALUES (?,?)
               ON DUPLICATE KEY UPDATE value=VALUES(value)";
        DB::run($upsert, ['favicon', $faviconFile]);
    }
    
    foreach (($_POST['settings'] ?? []) as $name => $value) {
        $upsert = DB::driver() === 'sqlite'
            ? "INSERT INTO settings (name,value) VALUES (?,?)
               ON CONFLICT(name) DO UPDATE SET value=excluded.value"
            : "INSERT INTO settings (name,value) VALUES (?,?)
               ON DUPLICATE KEY UPDATE value=VALUES(value)";
        DB::run($upsert, [$name, trim((string)$value)]);
    }
    foreach (($_POST['stats'] ?? []) as $sid => $row) {
        if (($row['label'] ?? '') === '') continue;
        DB::update('impact_stats', (int)$sid, [
            'label'  => trim($row['label']),
            'value'  => trim($row['value'] ?? ''),
            'suffix' => trim($row['suffix'] ?? ''),
        ]);
    }
    flash('Settings saved.');
    redirect('admin/settings');
}
