<?php
/* Public-facing pages. */

/**
 * Look up a published page and its blocks. Returns ['page'=>…, 'blocks'=>…]
 * only when the page exists AND has at least one block; otherwise null, so
 * callers can fall back to a legacy hand-coded view.
 */
function page_with_blocks(string $slug): ?array {
    $page = DB::one("SELECT * FROM pages WHERE slug=? AND status='published'", [$slug]);
    if (!$page) return null;
    $blocks = page_blocks((int)$page['id']);
    return $blocks ? ['page' => $page, 'blocks' => $blocks] : null;
}

/** Render a page's block stack. Home keeps the site name as the doc title. */
function render_blocks_page(array $pb, string $slug): void {
    $editing = is_admin() && isset($_GET['edit']);
    // Expose page/edit context to the layout (admin bar + editor assets).
    $GLOBALS['APP']['block_page'] = true;
    $GLOBALS['APP']['editing']    = $editing;
    $GLOBALS['APP']['page_id']    = (int) $pb['page']['id'];
    $GLOBALS['APP']['page_slug']  = $slug;

    $data = $pb;
    $data['edit'] = $editing;
    if ($slug !== 'home') $data['title'] = $pb['page']['title'];
    render('public/page', $data);
}

function ctl_home(): void {
    if ($pb = page_with_blocks('home')) { render_blocks_page($pb, 'home'); return; }
    // Legacy fallback (only if the home page somehow has no blocks).
    $stats   = DB::all("SELECT * FROM impact_stats ORDER BY sort_order");
    $modules = DB::all("SELECT * FROM modules WHERE status='published' ORDER BY sort_order LIMIT 6");
    $posts   = DB::all("SELECT * FROM posts WHERE status='published' ORDER BY published_at DESC LIMIT 3");
    $objectives = DB::all("SELECT * FROM objectives ORDER BY sort_order");
    render('public/home', compact('stats', 'modules', 'posts', 'objectives'));
}

/** Catch-all renderer for custom pages (pure block pages). */
function ctl_page(string $slug): void {
    if ($pb = page_with_blocks($slug)) { render_blocks_page($pb, $slug); return; }
    http_response_code(404);
    render('public/error', ['code'=>404,'title'=>'Page not found',
        'message'=>'The page you are looking for has moved or never existed.']);
}

function ctl_about(): void {
    if ($pb = page_with_blocks('about')) { render_blocks_page($pb, 'about'); return; }
    render('public/about', []);
}

function ctl_work(): void {
    if ($pb = page_with_blocks('our-work')) { render_blocks_page($pb, 'our-work'); return; }
    $posts = DB::all("SELECT * FROM posts WHERE status='published' ORDER BY published_at DESC");
    render('public/work', compact('posts'));
}

function ctl_post(string $slug): void {
    $post = DB::one("SELECT * FROM posts WHERE slug=? AND status='published'", [$slug]);
    if (!$post) { http_response_code(404); render('public/error',
        ['code'=>404,'title'=>'Story not found','message'=>'That story is no longer available.']); return; }
    $more = DB::all("SELECT * FROM posts WHERE status='published' AND id<>? ORDER BY published_at DESC LIMIT 2", [$post['id']]);
    render('public/post', compact('post', 'more'));
}

function ctl_discipleship(): void {
    if ($pb = page_with_blocks('discipleship')) { render_blocks_page($pb, 'discipleship'); return; }
    $modules = DB::all("SELECT * FROM modules WHERE status='published' ORDER BY sort_order");
    foreach ($modules as &$m) {
        $m['lesson_count'] = (int) DB::value("SELECT COUNT(*) FROM lessons WHERE module_id=? AND status='published'", [$m['id']]);
    }
    unset($m);
    render('public/discipleship', compact('modules'));
}

function ctl_give(): void {
    if ($pb = page_with_blocks('give')) { render_blocks_page($pb, 'give'); return; }
    render('public/give', []);
}

function ctl_contact(): void {
    if ($pb = page_with_blocks('contact')) { render_blocks_page($pb, 'contact'); return; }
    render('public/contact', []);
}

function ctl_contact_submit(): void {
    csrf_check();
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subj  = trim($_POST['subject'] ?? '');
    $body  = trim($_POST['body'] ?? '');

    $errors = [];
    if ($name === '')  $errors[] = 'Please tell us your name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if ($body === '')  $errors[] = 'Please write a short message.';

    if ($errors) {
        keep_old($_POST);
        foreach ($errors as $err) flash($err, 'error');
        redirect('contact');
    }

    DB::insert('messages', [
        'name' => $name, 'email' => $email, 'subject' => $subj ?: 'Website message', 'body' => $body,
    ]);
    flash('Thank you — your message has reached us. We will be in touch.', 'success');
    redirect('contact');
}
