<?php
/* =====================================================================
 *  MDLF — Front controller (single entry point)
 *  All requests are routed through here by .htaccess.
 * ===================================================================== */

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/app/controllers/public.php';
require __DIR__ . '/app/controllers/portal.php';
require __DIR__ . '/app/controllers/admin.php';

$router = new Router();

/* ---------- Public site ---------- */
$router->get('/',                 'ctl_home');
$router->get('/about',            'ctl_about');
$router->get('/our-work',         'ctl_work');
$router->get('/story/{slug}',     'ctl_post');
$router->get('/discipleship',     'ctl_discipleship');
$router->get('/give',             'ctl_give');
$router->get('/contact',          'ctl_contact');
$router->post('/contact',         'ctl_contact_submit');

/* ---------- Learning portal ---------- */
$router->get('/portal',                       'ctl_portal_dashboard');
$router->get('/portal/login',                 'ctl_portal_login');
$router->post('/portal/login',                'ctl_portal_login_post');
$router->get('/portal/register',              'ctl_portal_register');
$router->post('/portal/register',             'ctl_portal_register_post');
$router->get('/portal/logout',                'ctl_portal_logout');
$router->get('/portal/module/{slug}',         'ctl_portal_module');
$router->get('/portal/lesson/{slug}',         'ctl_portal_lesson');
$router->post('/portal/lesson/{slug}/complete','ctl_portal_lesson_complete');

/* ---------- Admin / CMS ---------- */
$router->get('/admin/login',   'ctl_admin_login');
$router->post('/admin/login',  'ctl_admin_login_post');
$router->get('/admin/logout',  'ctl_admin_logout');
$router->get('/admin',         'ctl_admin_dashboard');

$router->get('/admin/posts',            'ctl_admin_posts');
$router->get('/admin/posts/new',        'ctl_admin_post_form');
$router->post('/admin/posts',           'ctl_admin_post_save');
$router->get('/admin/posts/{id}/edit',  'ctl_admin_post_form');
$router->post('/admin/posts/{id}',      'ctl_admin_post_save');
$router->post('/admin/posts/{id}/delete','ctl_admin_post_delete');

$router->get('/admin/modules',             'ctl_admin_modules');
$router->get('/admin/modules/new',         'ctl_admin_module_form');
$router->post('/admin/modules',            'ctl_admin_module_save');
$router->get('/admin/modules/{id}/edit',   'ctl_admin_module_form');
$router->post('/admin/modules/{id}',       'ctl_admin_module_save');
$router->post('/admin/modules/{id}/delete','ctl_admin_module_delete');

$router->get('/admin/modules/{moduleId}/lessons',                'ctl_admin_lessons');
$router->get('/admin/modules/{moduleId}/lessons/new',            'ctl_admin_lesson_form');
$router->post('/admin/modules/{moduleId}/lessons',               'ctl_admin_lesson_save');
$router->get('/admin/modules/{moduleId}/lessons/{id}/edit',      'ctl_admin_lesson_form');
$router->post('/admin/modules/{moduleId}/lessons/{id}',          'ctl_admin_lesson_save');
$router->post('/admin/modules/{moduleId}/lessons/{id}/delete',   'ctl_admin_lesson_delete');

$router->post('/admin/modules/{moduleId}/lessons/{lessonId}/resources',            'ctl_admin_resource_add');
$router->post('/admin/modules/{moduleId}/lessons/{lessonId}/resources/{id}/delete','ctl_admin_resource_delete');

$router->get('/admin/messages',            'ctl_admin_messages');
$router->post('/admin/messages/{id}/read', 'ctl_admin_message_read');
$router->post('/admin/messages/{id}/delete','ctl_admin_message_delete');

$router->get('/admin/members',  'ctl_admin_members');

$router->get('/admin/pages',             'ctl_admin_pages');
$router->get('/admin/pages/new',         'ctl_admin_page_form');
$router->post('/admin/pages',            'ctl_admin_page_save');
$router->get('/admin/pages/{id}/edit',   'ctl_admin_page_form');
$router->post('/admin/pages/{id}',       'ctl_admin_page_save');
$router->post('/admin/pages/{id}/delete','ctl_admin_page_delete');

$router->get('/admin/objectives',             'ctl_admin_objectives');
$router->get('/admin/objectives/new',         'ctl_admin_objective_form');
$router->post('/admin/objectives',            'ctl_admin_objective_save');
$router->get('/admin/objectives/{id}/edit',   'ctl_admin_objective_form');
$router->post('/admin/objectives/{id}',       'ctl_admin_objective_save');
$router->post('/admin/objectives/{id}/delete','ctl_admin_objective_delete');

$router->get('/admin/settings', 'ctl_admin_settings');
$router->post('/admin/settings','ctl_admin_settings_save');

/* ---------- Page-builder block endpoints (admin, AJAX) ---------- */
$router->get('/admin/pages/{id}/builder',     'ctl_admin_page_builder');
$router->post('/admin/pages/{id}/blocks',     'ctl_admin_block_add');
$router->get('/admin/blocks/{id}/form',       'ctl_admin_block_form');
$router->post('/admin/blocks/{id}',           'ctl_admin_block_save');
$router->post('/admin/blocks/{id}/delete',    'ctl_admin_block_delete');
$router->post('/admin/pages/{id}/blocks/order','ctl_admin_blocks_reorder');
$router->get('/admin/media',           'ctl_admin_media');
$router->get('/admin/media/list',       'ctl_admin_media_list');
$router->post('/admin/media/upload',    'ctl_admin_media_upload');
$router->post('/admin/media/{id}/delete','ctl_admin_media_delete');

/* ---------- Catch-all: custom CMS pages by slug (MUST be last) ---------- */
$router->get('/{slug}', 'ctl_page');

$router->dispatch($_SERVER['REQUEST_METHOD'], $GLOBALS['ROUTE_PATH']);
