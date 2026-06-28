<?php
/* Discipleship Learning Portal (members). */

function ctl_portal_login(): void {
    if (is_logged_in()) redirect('portal');
    render('portal/login', [], 'auth');
}

function ctl_portal_login_post(): void {
    csrf_check();
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $user  = DB::one("SELECT * FROM users WHERE email=?", [$email]);

    if (!$user || !password_verify($pass, $user['password_hash'])) {
        keep_old(['email' => $email]);
        flash('Those details did not match our records.', 'error');
        redirect('portal/login');
    }
    login_user($user);
    flash('Welcome back, ' . explode(' ', $user['name'])[0] . '.', 'success');
    redirect($user['role'] === 'admin' ? 'admin' : 'portal');
}

function ctl_portal_register(): void {
    if (is_logged_in()) redirect('portal');
    render('portal/register', [], 'auth');
}

function ctl_portal_register_post(): void {
    csrf_check();
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $loc   = trim($_POST['location'] ?? '');

    $errors = [];
    if (mb_strlen($name) < 2) $errors[] = 'Please enter your full name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (strlen($pass) < 6) $errors[] = 'Choose a password of at least 6 characters.';
    if (DB::one("SELECT id FROM users WHERE email=?", [$email])) $errors[] = 'An account with that email already exists.';

    if ($errors) {
        keep_old($_POST);
        foreach ($errors as $err) flash($err, 'error');
        redirect('portal/register');
    }

    $id = DB::insert('users', [
        'name' => $name, 'email' => $email,
        'password_hash' => password_hash($pass, PASSWORD_DEFAULT),
        'role' => 'member', 'location' => $loc ?: null,
    ]);
    $user = DB::one("SELECT * FROM users WHERE id=?", [$id]);
    login_user($user);
    flash('Your account is ready. Welcome to the journey.', 'success');
    redirect('portal');
}

function ctl_portal_logout(): void {
    logout_user();
    redirect('portal/login');
}

function ctl_portal_dashboard(): void {
    require_login();
    $uid     = current_user()['id'];
    $modules = DB::all("SELECT * FROM modules WHERE status='published' ORDER BY sort_order");

    $totalLessons = 0; $totalDone = 0; $continue = null;
    foreach ($modules as &$m) {
        $p = module_progress($uid, (int)$m['id']);
        $m['progress'] = $p;
        $totalLessons += $p['total'];
        $totalDone    += $p['done'];
        if ($continue === null && $p['pct'] > 0 && $p['pct'] < 100) {
            $next = DB::one(
                "SELECT l.* FROM lessons l
                 WHERE l.module_id=? AND l.status='published'
                   AND l.id NOT IN (SELECT lesson_id FROM lesson_progress WHERE user_id=?)
                 ORDER BY l.sort_order LIMIT 1", [$m['id'], $uid]);
            if ($next) { $next['module'] = $m; $continue = $next; }
        }
    }
    unset($m);
    $overall = $totalLessons ? (int) round($totalDone / $totalLessons * 100) : 0;
    render('portal/dashboard', compact('modules', 'overall', 'totalDone', 'totalLessons', 'continue'), 'portal');
}

function ctl_portal_module(string $slug): void {
    require_login();
    $uid    = current_user()['id'];
    $module = DB::one("SELECT * FROM modules WHERE slug=? AND status='published'", [$slug]);
    if (!$module) { http_response_code(404); render('public/error',
        ['code'=>404,'title'=>'Module not found','message'=>'That module is not available.'], 'portal'); return; }

    $lessons = DB::all("SELECT * FROM lessons WHERE module_id=? AND status='published' ORDER BY sort_order", [$module['id']]);
    foreach ($lessons as &$l) { $l['done'] = lesson_completed($uid, (int)$l['id']); }
    unset($l);
    $progress = module_progress($uid, (int)$module['id']);
    render('portal/module', compact('module', 'lessons', 'progress'), 'portal');
}

function ctl_portal_lesson(string $slug): void {
    require_login();
    $uid    = current_user()['id'];
    $lesson = DB::one("SELECT * FROM lessons WHERE slug=? AND status='published'", [$slug]);
    if (!$lesson) { http_response_code(404); render('public/error',
        ['code'=>404,'title'=>'Lesson not found','message'=>'That lesson is not available.'], 'portal'); return; }

    $module    = DB::one("SELECT * FROM modules WHERE id=?", [$lesson['module_id']]);
    $resources = DB::all("SELECT * FROM resources WHERE lesson_id=? ORDER BY sort_order, id", [$lesson['id']]);
    $siblings  = DB::all("SELECT id, title, slug, sort_order FROM lessons WHERE module_id=? AND status='published' ORDER BY sort_order", [$lesson['module_id']]);

    $prev = $next = null;
    foreach ($siblings as $i => $s) {
        if ((int)$s['id'] === (int)$lesson['id']) {
            $prev = $siblings[$i - 1] ?? null;
            $next = $siblings[$i + 1] ?? null;
            break;
        }
    }
    $lesson['done'] = lesson_completed($uid, (int)$lesson['id']);
    render('portal/lesson', compact('lesson', 'module', 'resources', 'prev', 'next'), 'portal');
}

function ctl_portal_lesson_complete(string $slug): void {
    require_login();
    csrf_check();
    $uid    = current_user()['id'];
    $lesson = DB::one("SELECT * FROM lessons WHERE slug=? AND status='published'", [$slug]);
    if ($lesson) {
        if (!lesson_completed($uid, (int)$lesson['id'])) {
            $ignore = DB::driver() === 'sqlite' ? 'INSERT OR IGNORE' : 'INSERT IGNORE';
            DB::run("$ignore INTO lesson_progress (user_id, lesson_id) VALUES (?, ?)", [$uid, $lesson['id']]);
            flash('Lesson marked complete. Keep going!', 'success');
        }
        $next = DB::one(
            "SELECT slug FROM lessons WHERE module_id=? AND status='published' AND sort_order > ? ORDER BY sort_order LIMIT 1",
            [$lesson['module_id'], $lesson['sort_order']]);
        if (!empty($_POST['next']) && $next) redirect('portal/lesson/' . $next['slug']);
        redirect('portal/lesson/' . $lesson['slug']);
    }
    redirect('portal');
}
