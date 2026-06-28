<?php
/* =====================================================================
 *  Block registry + render pipeline (WordPress-style page builder).
 *
 *  A page is an ordered list of `blocks` rows. Each block has a `type`
 *  and a JSON `data` payload. The registry below describes every block
 *  type: a label + icon (for the builder UI), an editable `fields`
 *  schema (used by the back-office form and the live inline editor),
 *  and `defaults` (fallbacks when a field is missing).
 *
 *  Renderers live in app/views/blocks/{type}.php and receive:
 *    $b      — decoded data merged over defaults (associative array)
 *    $block  — the raw block row (id, page_id, type, sort_order, data)
 *    $edit   — true when rendered inside the live editor
 * ===================================================================== */

/**
 * The block catalog. Field types: text, textarea, html, image, link,
 * number, select. "dynamic" blocks pull live data from other tables.
 */
function block_catalog(): array
{
    static $catalog = null;
    if ($catalog !== null) return $catalog;

    return $catalog = [
        'hero' => [
            'label' => 'Hero', 'icon' => '★', 'group' => 'Headers',
            'fields' => [
                'eyebrow'        => ['type' => 'text',     'label' => 'Eyebrow'],
                'heading'        => ['type' => 'html',     'label' => 'Heading (allows <em>)'],
                'lead'           => ['type' => 'textarea', 'label' => 'Lead paragraph'],
                'cta_text'       => ['type' => 'text',     'label' => 'Primary button text'],
                'cta_link'       => ['type' => 'link',     'label' => 'Primary button link'],
                'secondary_text' => ['type' => 'text',     'label' => 'Secondary button text'],
                'secondary_link' => ['type' => 'link',     'label' => 'Secondary button link'],
                'verse'          => ['type' => 'textarea', 'label' => 'Verse'],
                'verse_ref'      => ['type' => 'text',     'label' => 'Verse reference'],
            ],
            'defaults' => [
                'eyebrow'  => 'Mipo Dadang Leadership Foundation',
                'heading'  => 'Raising leaders who <em>raise leaders.</em>',
                'lead'     => '',
                'cta_text' => 'Start the discipleship journey', 'cta_link' => 'portal',
                'secondary_text' => 'Partner with us', 'secondary_link' => 'give',
                'verse' => '', 'verse_ref' => '',
            ],
        ],

        'mission_band' => [
            'label' => 'Mission band', 'icon' => '❝', 'group' => 'Content',
            'fields' => [
                'eyebrow' => ['type' => 'text',     'label' => 'Eyebrow'],
                'heading' => ['type' => 'text',     'label' => 'Heading'],
                'body'    => ['type' => 'textarea', 'label' => 'Body'],
            ],
            'defaults' => [
                'eyebrow' => 'Our mission',
                'heading' => 'Restoring people, raising leaders.',
                'body'    => '',
            ],
        ],

        'stats' => [
            'label' => 'Impact stats', 'icon' => '▦', 'group' => 'Dynamic', 'dynamic' => true,
            'fields' => [
                'eyebrow' => ['type' => 'text', 'label' => 'Eyebrow'],
                'heading' => ['type' => 'text', 'label' => 'Heading'],
            ],
            'defaults' => [
                'eyebrow' => 'Since March 30, 2024',
                'heading' => 'A young foundation, already bearing fruit',
            ],
        ],

        'objectives' => [
            'label' => 'Objectives grid', 'icon' => '◎', 'group' => 'Dynamic', 'dynamic' => true,
            'fields' => [
                'eyebrow' => ['type' => 'text', 'label' => 'Eyebrow'],
                'heading' => ['type' => 'text', 'label' => 'Heading'],
            ],
            'defaults' => [
                'eyebrow' => 'What we are about',
                'heading' => 'Six commitments that shape everything we do',
            ],
        ],

        'feature' => [
            'label' => 'Feature (image + text)', 'icon' => '◧', 'group' => 'Content',
            'fields' => [
                'eyebrow'      => ['type' => 'text',     'label' => 'Eyebrow'],
                'heading'      => ['type' => 'text',     'label' => 'Heading'],
                'body'         => ['type' => 'textarea', 'label' => 'Body'],
                'body2'        => ['type' => 'html',     'label' => 'Second paragraph'],
                'image'        => ['type' => 'image',    'label' => 'Image'],
                'button_text'  => ['type' => 'text',     'label' => 'Button text'],
                'button_link'  => ['type' => 'link',     'label' => 'Button link'],
                'button2_text' => ['type' => 'text',     'label' => 'Second button text'],
                'button2_link' => ['type' => 'link',     'label' => 'Second button link'],
                'gallery'      => ['type' => 'gallery',  'label' => 'Gallery images'],
            ],
            'defaults' => [
                'eyebrow' => '', 'heading' => '', 'body' => '', 'body2' => '',
                'image' => '', 'button_text' => '', 'button_link' => '',
                'button2_text' => '', 'button2_link' => '', 'gallery' => [],
            ],
        ],

        'modules_grid' => [
            'label' => 'Modules grid', 'icon' => '▤', 'group' => 'Dynamic', 'dynamic' => true,
            'fields' => [
                'eyebrow'      => ['type' => 'text',     'label' => 'Eyebrow'],
                'heading'      => ['type' => 'text',     'label' => 'Heading'],
                'description'  => ['type' => 'textarea', 'label' => 'Description (blank to hide)'],
                'limit'        => ['type' => 'number',   'label' => 'How many to show (0 = all)'],
                'show_lessons' => ['type' => 'text',     'label' => 'Show lesson count? (yes/no)'],
                'show_all'     => ['type' => 'text',     'label' => 'Show “see all” button? (yes/no)'],
            ],
            'defaults' => [
                'eyebrow' => 'Discipleship Learning Portal',
                'heading' => 'Learn, grow and multiply — module by module',
                'description' => 'A structured discipleship curriculum you can work through at your own pace, with lessons and resources for each stage of the journey.',
                'limit' => 3, 'show_lessons' => 'no', 'show_all' => 'yes',
            ],
        ],

        'stories_grid' => [
            'label' => 'Stories grid', 'icon' => '◈', 'group' => 'Dynamic', 'dynamic' => true,
            'fields' => [
                'eyebrow'     => ['type' => 'text',     'label' => 'Eyebrow'],
                'heading'     => ['type' => 'text',     'label' => 'Heading'],
                'description' => ['type' => 'textarea', 'label' => 'Description'],
                'limit'       => ['type' => 'number',   'label' => 'How many to show (0 = all)'],
            ],
            'defaults' => [
                'eyebrow' => 'News & stories', 'heading' => 'From our work',
                'description' => '', 'limit' => 0,
            ],
        ],

        'cta' => [
            'label' => 'Call to action', 'icon' => '➜', 'group' => 'Content',
            'fields' => [
                'eyebrow'     => ['type' => 'text',     'label' => 'Eyebrow'],
                'heading'     => ['type' => 'text',     'label' => 'Heading'],
                'description' => ['type' => 'textarea', 'label' => 'Description'],
                'button_text' => ['type' => 'text',     'label' => 'Button text'],
                'button_link' => ['type' => 'link',     'label' => 'Button link'],
            ],
            'defaults' => [
                'eyebrow' => 'Help us go further',
                'heading' => 'The work is growing. The needs are real.',
                'description' => '',
                'button_text' => 'Become a partner', 'button_link' => 'give',
            ],
        ],

        'richtext' => [
            'label' => 'Rich text', 'icon' => '¶', 'group' => 'Content',
            'fields' => [
                'html' => ['type' => 'html', 'label' => 'Content'],
            ],
            'defaults' => ['html' => '<p>Write something…</p>'],
        ],

        'video' => [
            'label' => 'Video', 'icon' => '🎬', 'group' => 'Media',
            'fields' => [
                'src'     => ['type' => 'video', 'label' => 'Video file'],
                'poster'  => ['type' => 'image', 'label' => 'Poster image (optional)'],
                'caption' => ['type' => 'text',  'label' => 'Caption'],
            ],
            'defaults' => ['src' => '', 'poster' => '', 'caption' => ''],
        ],

        'audio' => [
            'label' => 'Audio', 'icon' => '♪', 'group' => 'Media',
            'fields' => [
                'src'   => ['type' => 'audio', 'label' => 'Audio file'],
                'title' => ['type' => 'text',  'label' => 'Title'],
            ],
            'defaults' => ['src' => '', 'title' => ''],
        ],

        'page_header' => [
            'label' => 'Page header', 'icon' => '▭', 'group' => 'Headers',
            'fields' => [
                'variant'   => ['type' => 'text',     'label' => 'Variant (dark/light)'],
                'align'     => ['type' => 'text',     'label' => 'Align (left/center)'],
                'width'     => ['type' => 'number',   'label' => 'Max width px (dark)'],
                'eyebrow'   => ['type' => 'text',     'label' => 'Eyebrow'],
                'heading'   => ['type' => 'html',     'label' => 'Heading'],
                'lead'      => ['type' => 'textarea', 'label' => 'Lead'],
                'cta_text'  => ['type' => 'text',     'label' => 'Button text'],
                'cta_link'  => ['type' => 'link',     'label' => 'Button link'],
                'cta2_text' => ['type' => 'text',     'label' => 'Second button text'],
                'cta2_link' => ['type' => 'link',     'label' => 'Second button link'],
            ],
            'defaults' => [
                'variant' => 'dark', 'align' => 'left', 'width' => 760,
                'eyebrow' => 'Eyebrow', 'heading' => 'A heading', 'lead' => '',
                'cta_text' => '', 'cta_link' => '', 'cta2_text' => '', 'cta2_link' => '',
            ],
        ],

        'about_body' => [
            'label' => 'About body', 'icon' => '❡', 'group' => 'Content',
            'fields' => [
                'prose'           => ['type' => 'html',     'label' => 'Intro prose'],
                'obj_eyebrow'     => ['type' => 'text',     'label' => 'Objectives eyebrow'],
                'objectives'      => ['type' => 'list',     'label' => 'Objectives (one per line)'],
                'founder_eyebrow' => ['type' => 'text',     'label' => 'Founder eyebrow'],
                'founder_name'    => ['type' => 'text',     'label' => 'Founder name'],
                'founder_desc'    => ['type' => 'textarea', 'label' => 'Founder description'],
            ],
            'defaults' => [
                'prose' => '', 'obj_eyebrow' => 'Our objectives', 'objectives' => [],
                'founder_eyebrow' => 'The founder', 'founder_name' => '', 'founder_desc' => '',
            ],
        ],

        'give_body' => [
            'label' => 'Give body', 'icon' => '♥', 'group' => 'Content',
            'fields' => [
                'eyebrow'     => ['type' => 'text',     'label' => 'Eyebrow'],
                'heading'     => ['type' => 'text',     'label' => 'Heading'],
                'lead'        => ['type' => 'textarea', 'label' => 'Lead'],
                'need1_title' => ['type' => 'text',     'label' => 'Need 1 title'],
                'need1_text'  => ['type' => 'textarea', 'label' => 'Need 1 text'],
                'need2_title' => ['type' => 'text',     'label' => 'Need 2 title'],
                'need2_text'  => ['type' => 'textarea', 'label' => 'Need 2 text'],
                'tier1_label' => ['type' => 'text',     'label' => 'Tier 1 label'],
                'tier1_amount'=> ['type' => 'text',     'label' => 'Tier 1 amount'],
                'tier1_text'  => ['type' => 'textarea', 'label' => 'Tier 1 text'],
                'tier2_label' => ['type' => 'text',     'label' => 'Tier 2 label'],
                'tier2_amount'=> ['type' => 'text',     'label' => 'Tier 2 amount'],
                'tier2_text'  => ['type' => 'textarea', 'label' => 'Tier 2 text'],
                'tier3_label' => ['type' => 'text',     'label' => 'Tier 3 label'],
                'tier3_amount'=> ['type' => 'text',     'label' => 'Tier 3 amount'],
                'tier3_text'  => ['type' => 'textarea', 'label' => 'Tier 3 text'],
                'bank_eyebrow'=> ['type' => 'text',     'label' => 'Bank section eyebrow'],
            ],
            'defaults' => [
                'eyebrow' => 'Partner with us', 'heading' => '', 'lead' => '',
                'need1_title' => '', 'need1_text' => '', 'need2_title' => '', 'need2_text' => '',
                'tier1_label' => 'Friend', 'tier1_amount' => '', 'tier1_text' => '',
                'tier2_label' => 'Partner', 'tier2_amount' => '', 'tier2_text' => '',
                'tier3_label' => 'Patron', 'tier3_amount' => '', 'tier3_text' => '',
                'bank_eyebrow' => 'Give by bank transfer',
            ],
        ],

        'contact' => [
            'label' => 'Contact (info + form)', 'icon' => '✉', 'group' => 'Content',
            'fields' => [
                'eyebrow' => ['type' => 'text',     'label' => 'Eyebrow'],
                'heading' => ['type' => 'text',     'label' => 'Heading'],
                'lead'    => ['type' => 'textarea', 'label' => 'Lead'],
            ],
            'defaults' => [
                'eyebrow' => 'Get in touch',
                'heading' => 'We would love to hear from you',
                'lead'    => '',
            ],
        ],
    ];
}

/** Definition for a single block type, or null if unknown. */
function block_def(string $type): ?array
{
    return block_catalog()[$type] ?? null;
}

/**
 * Resolve an image reference stored in block data to a URL.
 *   - absolute URLs pass through
 *   - "uploads/…" → /storage/uploads/…  (admin uploads)
 *   - anything else → /assets/…          (bundled images, e.g. "img/x.jpeg")
 */
function block_image_url(string $v): string
{
    $v = trim($v);
    if ($v === '') return '';
    if (preg_match('~^https?://~', $v)) return $v;
    if (strpos($v, 'uploads/') === 0) return base_url() . '/storage/' . $v;
    return asset(ltrim($v, '/'));
}

/** All blocks for a page, in order. */
function page_blocks(int $pageId): array
{
    return DB::all("SELECT * FROM blocks WHERE page_id=? ORDER BY sort_order, id", [$pageId]);
}

/** Decode a block's stored JSON data, merged over its type defaults. */
function block_data(array $block): array
{
    $def  = block_def($block['type']);
    $data = json_decode($block['data'] ?? '', true);
    if (!is_array($data)) $data = [];
    return array_merge($def['defaults'] ?? [], $data);
}

/**
 * Render a single block to HTML. When $edit is true, the block is wrapped
 * with editor chrome (handled by the live editor in a later phase).
 */
function block_render(array $block, bool $edit = false): string
{
    $type = $block['type'] ?? '';
    if (!block_def($type)) return '';
    $file = __DIR__ . '/../views/blocks/' . $type . '.php';
    if (!is_file($file)) return '';

    $b = block_data($block);
    ob_start();
    include $file;
    return ob_get_clean();
}

/** Render every block of a page in order. */
function blocks_render_all(array $blocks, bool $edit = false): string
{
    $out = '';
    foreach ($blocks as $block) {
        $out .= block_render($block, $edit);
    }
    return $out;
}

/* --------------------- Live (on-page) editor markup --------------------- */

/** An "+ Add block" drop zone after the block with id $afterId (0 = start). */
function block_add_zone(int $afterId): string
{
    return '<div class="mdlf-addzone" data-after="' . $afterId . '">'
         . '<button type="button" class="mdlf-addbtn">+ Add block</button></div>';
}

/** Wrap a single rendered block with the live-editor toolbar/chrome. */
function block_render_editable_one(array $block): string
{
    $def = block_def($block['type']);
    if (!$def) return '';
    $h  = '<div class="mdlf-block" data-block-id="' . (int)$block['id']
        . '" data-block-type="' . e($block['type']) . '">';
    $h .= '<div class="mdlf-bar">'
        . '<span class="mdlf-bar-label">' . e($def['icon'] . '  ' . $def['label']) . '</span>'
        . '<span class="mdlf-bar-actions">'
        . '<button type="button" class="mdlf-ab" data-act="edit" title="Edit fields">✎ Edit</button>'
        . '<button type="button" class="mdlf-ab" data-act="up" title="Move up">▲</button>'
        . '<button type="button" class="mdlf-ab" data-act="down" title="Move down">▼</button>'
        . '<button type="button" class="mdlf-ab mdlf-del" data-act="delete" title="Delete">✕</button>'
        . '</span></div>';
    $h .= '<div class="mdlf-block-body">' . block_render($block, true) . '</div>';
    $h .= '</div>';
    return $h;
}

/** Render a page's blocks wrapped for live editing, with add-zones between. */
function blocks_render_editable(array $blocks, int $pageId): string
{
    $out  = '<div class="mdlf-canvas" data-page-id="' . $pageId . '">';
    $out .= block_add_zone(0);
    foreach ($blocks as $block) {
        if (!block_def($block['type'])) continue;
        $out .= block_render_editable_one($block);
        $out .= block_add_zone((int)$block['id']);
    }
    $out .= '</div>';
    return $out;
}

/**
 * Build the data array for a block from posted form fields, coercing each
 * value according to the block type's field schema. Used by save endpoints.
 */
function block_data_from_post(string $type, array $post): array
{
    $def  = block_def($type);
    $data = [];
    foreach (($def['fields'] ?? []) as $key => $field) {
        switch ($field['type'] ?? 'text') {
            case 'number':
                $data[$key] = (int) ($post[$key] ?? 0);
                break;
            case 'gallery':
                $decoded = json_decode($post[$key] ?? '', true);
                $data[$key] = is_array($decoded) ? array_values($decoded) : [];
                break;
            case 'list':
                $lines = preg_split('/\r\n|\r|\n/', (string) ($post[$key] ?? ''));
                $data[$key] = array_values(array_filter(array_map('trim', $lines), fn($v) => $v !== ''));
                break;
            default:
                $data[$key] = (string) ($post[$key] ?? '');
        }
    }
    return $data;
}

/** Encode block data as JSON for storage (UTF-8 friendly). */
function block_data_encode(array $data): string
{
    return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/** Render one editor form field for a block, based on its field schema. */
function block_field_input(int $blockId, string $key, array $field, $value): string
{
    $type  = $field['type']  ?? 'text';
    $label = $field['label'] ?? $key;
    $id    = "blk{$blockId}_{$key}";
    $h  = '<div class="field">';
    $h .= '<label for="' . e($id) . '">' . e($label) . '</label>';

    switch ($type) {
        case 'textarea':
            $h .= '<textarea class="textarea" id="' . e($id) . '" name="' . e($key)
                . '" style="min-height:90px">' . e((string) $value) . '</textarea>';
            break;
        case 'html':
            // Quill mounts on .quill-editor (admin layout) and syncs to this hidden input.
            $h .= '<input type="hidden" id="' . e($id) . '" name="' . e($key)
                . '" value="' . e((string) $value) . '">'
                . '<div class="quill-editor" data-input-id="' . e($id)
                . '" style="min-height:160px"></div>';
            break;
        case 'number':
            $h .= '<input class="input" type="number" id="' . e($id) . '" name="' . e($key)
                . '" value="' . e((string) $value) . '">';
            break;
        case 'image':
        case 'video':
        case 'audio':
            // Single media field — enhanced into a visual picker by media.js.
            $h .= '<input class="input" id="' . e($id) . '" name="' . e($key) . '" value="'
                . e((string) $value) . '" data-media="' . e($type) . '">';
            break;
        case 'gallery':
            // Visual gallery editor (media.js) backed by a hidden JSON input.
            $json = is_array($value)
                ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : (string) $value;
            $h .= '<input type="hidden" id="' . e($id) . '" name="' . e($key) . '" value="' . e($json) . '">'
                . '<div class="mdlf-gallery" data-input="' . e($id) . '" data-media="image"></div>';
            break;
        case 'list':
            $text = is_array($value) ? implode("\n", $value) : (string) $value;
            $h .= '<textarea class="textarea" id="' . e($id) . '" name="' . e($key)
                . '" style="min-height:110px">' . e($text) . '</textarea>'
                . '<span class="hint">One item per line</span>';
            break;
        case 'link':
        case 'text':
        default:
            $h .= '<input class="input" id="' . e($id) . '" name="' . e($key)
                . '" value="' . e((string) $value) . '">';
    }
    $h .= '</div>';
    return $h;
}
