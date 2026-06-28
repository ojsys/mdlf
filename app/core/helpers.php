<?php
/* Global helpers. Loaded on every request. */

/* ---- Output / escaping ---- */
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

/* ---- URLs ---- */
function base_url(): string {
    return rtrim($GLOBALS['APP']['base_url'], '/');
}
/** Build an app URL relative to the base path. url('portal/dashboard') */
function url(string $path = ''): string {
    return base_url() . '/' . ltrim($path, '/');
}
/** Asset URL. asset('css/styles.css') -> /assets/css/styles.css */
function asset(string $path): string {
    return base_url() . '/assets/' . ltrim($path, '/');
}
function redirect(string $path): void {
    header('Location: ' . (preg_match('~^https?://~', $path) ? $path : url($path)));
    exit;
}
function current_path(): string { return $GLOBALS['ROUTE_PATH'] ?? '/'; }

/* ---- Slugs ---- */
function slugify(string $text): string {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = trim(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $text) ?: $text), '-');
    $text = preg_replace('~[^-a-z0-9]+~', '', $text);
    return $text ?: 'item-' . substr(md5((string)microtime(true)), 0, 6);
}

/* ---- Sessions / flash ---- */
function flash(string $msg = null, string $type = 'success') {
    if ($msg !== null) { $_SESSION['_flash'][] = ['msg' => $msg, 'type' => $type]; return null; }
    $f = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return $f;
}

/* ---- CSRF ---- */
function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}
function csrf_check(): void {
    $ok = isset($_POST['_csrf']) && hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf']);
    if (!$ok) {
        abort(419, 'Session expired', 'Your session expired or this form is stale. Please go back and try again.');
    }
}

/* ---- AJAX ---- */
function is_ajax(): bool {
    return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
}
function json_out($data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/* ---- Auth ---- */
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function is_logged_in(): bool { return isset($_SESSION['user']); }
function is_admin(): bool { return (current_user()['role'] ?? null) === 'admin'; }

function login_user(array $user): void {
    unset($user['password_hash']);
    $_SESSION['user'] = $user;
    session_regenerate_id(true);
}
function logout_user(): void {
    $_SESSION = [];
    session_destroy();
}
function require_login(): void {
    if (!is_logged_in()) { flash('Please sign in to continue.', 'info'); redirect('portal/login'); }
}
function require_admin(): void {
    if (!is_logged_in()) { redirect('admin/login'); }
    if (!is_admin()) { abort(403, 'Admins only', 'You need an administrator account to view this area.'); }
}

/* ---- Settings (cached per request) ---- */
function settings(): array {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (DB::all("SELECT name, value FROM settings") as $r) {
            $cache[$r['name']] = $r['value'];
        }
    }
    return $cache;
}
function setting(string $key, string $default = ''): string {
    $s = settings();
    return $s[$key] ?? $default;
}

/* ---- Old form input + simple validation ---- */
function old(string $key, string $default = ''): string {
    return e($_SESSION['_old'][$key] ?? $default);
}
function keep_old(array $data): void { $_SESSION['_old'] = $data; }
function clear_old(): void { unset($_SESSION['_old']); }

/* ---- View rendering ---- */
function view(string $template, array $data = [], string $layout = 'public'): string {
    extract($data, EXTR_SKIP);
    ob_start();
    require __DIR__ . '/../views/' . $template . '.php';
    $content = ob_get_clean();

    ob_start();
    require __DIR__ . '/../views/layouts/' . $layout . '.php';
    return ob_get_clean();
}
function render(string $template, array $data = [], string $layout = 'public'): void {
    echo view($template, $data, $layout);
    clear_old();
}

/**
 * Send an error status and render the branded error page, then stop.
 * For AJAX requests, returns JSON instead of an HTML page.
 */
function abort(int $code, string $title, string $message): void {
    http_response_code($code);
    if (is_ajax()) {
        json_out(['ok' => false, 'error' => $title, 'code' => $code], $code);
    }
    echo view('public/error', ['code' => $code, 'title' => $title, 'message' => $message]);
    exit;
}

/* ---- Progress helpers for the learning portal ---- */
function module_progress(int $userId, int $moduleId): array {
    $total = (int) DB::value(
        "SELECT COUNT(*) FROM lessons WHERE module_id = ? AND status='published'", [$moduleId]);
    $done = (int) DB::value(
        "SELECT COUNT(*) FROM lesson_progress lp
         JOIN lessons l ON l.id = lp.lesson_id
         WHERE lp.user_id = ? AND l.module_id = ? AND l.status='published'", [$userId, $moduleId]);
    $pct = $total ? (int) round($done / $total * 100) : 0;
    return ['total' => $total, 'done' => $done, 'pct' => $pct];
}
function lesson_completed(int $userId, int $lessonId): bool {
    return (bool) DB::value(
        "SELECT 1 FROM lesson_progress WHERE user_id=? AND lesson_id=?", [$userId, $lessonId]);
}

/* ---- Media types ---- */
/** Allowed upload extensions grouped by media type. */
function media_extensions(): array {
    return [
        'image' => ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'ico'],
        'audio' => ['mp3', 'wav', 'ogg', 'm4a', 'aac'],
        'video' => ['mp4', 'webm', 'mov', 'm4v', 'ogv'],
    ];
}
/** Classify a file extension into image|audio|video|file. */
function media_type_for_ext(string $ext): string {
    $ext = strtolower($ext);
    foreach (media_extensions() as $type => $exts) {
        if (in_array($ext, $exts, true)) return $type;
    }
    return 'file';
}

/* ---- File upload handling ---- */
function handle_upload(string $fieldName, array $allowedTypes = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'ico'], int $maxSize = 5242880): ?string {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $file = $_FILES[$fieldName];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes)) {
        flash('Invalid file type. Allowed: ' . implode(', ', $allowedTypes), 'error');
        return null;
    }
    if ($file['size'] > $maxSize) {
        flash('File too large. Max size: 5MB', 'error');
        return null;
    }
    $uploadDir = __DIR__ . '/../../storage/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $fileName = uniqid('upload_', true) . '.' . $ext;
    $destPath = $uploadDir . $fileName;
    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        flash('Failed to upload file.', 'error');
        return null;
    }
    return $fileName;
}

function upload_url(string $fileName): string {
    return base_url() . '/storage/uploads/' . $fileName;
}
