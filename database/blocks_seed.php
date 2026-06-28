<?php
/* =====================================================================
 *  Default page blocks (page-builder seed).
 *  Reproduces the original hand-coded home page as an editable block
 *  stack. Shared by both installers (SQLite + MySQL). Idempotent: it
 *  only seeds a page that has no blocks yet, so it never clobbers edits.
 * ===================================================================== */

function seed_default_blocks(PDO $pdo): void
{
    $mission = 'The Mipo Dadang Leadership Foundation exists to meet sociological and economic '
        . 'needs through sustainable approaches, renew the way people see one another, and mentor '
        . 'emerging leaders to handle the conflicts and crises of life with grace and competence.';

    $pages = [
        'home' => [
            ['hero', [
                'eyebrow'        => 'Mipo Dadang Leadership Foundation',
                'heading'        => 'Raising leaders who <em>raise leaders.</em>',
                'lead'           => 'Raising reproducible leaders who restore people and communities. '
                                  . 'We meet real needs, renew how people see one another, and mentor '
                                  . 'emerging leaders to carry the weight of life with grace.',
                'cta_text'       => 'Start the discipleship journey', 'cta_link' => 'portal',
                'secondary_text' => 'Partner with us', 'secondary_link' => 'give',
                'verse'          => 'And the things you have heard from me entrust to faithful people '
                                  . 'who will be able to teach others also.',
                'verse_ref'      => '2 Timothy 2:2',
            ]],
            ['mission_band', [
                'eyebrow' => 'Our mission',
                'heading' => 'Restoring people, raising leaders.',
                'body'    => $mission,
            ]],
            ['stats', [
                'eyebrow' => 'Since March 30, 2024',
                'heading' => 'A young foundation, already bearing fruit',
            ]],
            ['objectives', [
                'eyebrow' => 'What we are about',
                'heading' => 'Six commitments that shape everything we do',
            ]],
            ['feature', [
                'eyebrow'      => 'This June · 20–21',
                'heading'      => '24 youth leaders, trained to reproduce',
                'body'         => 'Our Reproducible Leaders’ Discipleship Training equipped 24 young '
                                . 'leaders not only to grow, but to multiply — to pass on what they '
                                . 'received so others can teach others also.',
                'body2'        => 'It continues the work of a year in which <strong>206 young people</strong> '
                                . 'were discipled and mentored, and many came to faith in Christ.',
                'image'        => 'img/training-group.jpeg',
                'button_text'  => 'Read the story',
                'button_link'  => 'story/reproducible-leaders-training-june',
                'button2_text' => 'More from our work', 'button2_link' => 'our-work',
                'gallery'      => [
                    ['image' => 'img/session-focus.jpeg',   'caption' => 'Attentive in session'],
                    ['image' => 'img/hall-wide.jpeg',       'caption' => 'Gathered together'],
                    ['image' => 'img/fellowship-meal.jpeg', 'caption' => 'Fellowship & meals'],
                    ['image' => 'img/training-group.jpeg',  'caption' => 'The 24 leaders'],
                ],
            ]],
            ['modules_grid', [
                'eyebrow'     => 'Discipleship Learning Portal',
                'heading'     => 'Learn, grow and multiply — module by module',
                'description' => 'A structured discipleship curriculum you can work through at your own '
                               . 'pace, with lessons and resources for each stage of the journey.',
                'limit'       => 3,
            ]],
            ['cta', [
                'eyebrow'     => 'Help us go further',
                'heading'     => 'The work is growing. The needs are real.',
                'description' => 'Our greatest challenges are simple: we have few donors, and no mobility '
                               . 'for the discipleship outreaches that take this work to where people are. '
                               . 'Your partnership removes those barriers.',
                'button_text' => 'Become a partner', 'button_link' => 'give',
            ]],
        ],

        'about' => [
            ['page_header', [
                'variant' => 'dark', 'align' => 'left', 'width' => 760,
                'eyebrow' => 'About the foundation',
                'heading' => 'A vision to renew leaders and restore communities.',
                'lead'    => 'Founded by Rev. Mipo Dadang, the Mipo Dadang Leadership Foundation was launched on March 30, 2024.',
            ]],
            ['about_body', [
                'prose' => '<p class="muted"><strong style="color:var(--ink)">The Mipo Dadang Leadership Foundation (MDLF)</strong> exists to meet people at the point of their real need — sociological, economic and spiritual — and to walk with them toward wholeness and purpose.</p>' . "\n"
                         . '      <p class="muted">We believe a leader is not finished until they have raised another leader. That conviction shapes our discipleship: relationships that don’t merely add, but multiply, so that those we teach can in turn teach others also.</p>',
                'obj_eyebrow' => 'Our objectives',
                'objectives'  => [
                    'Address people’s sociological and economic needs with sustainable approaches.',
                    'Help people have a right perspective toward human beings.',
                    'Provide informal renewal and mentorship to emerging leaders.',
                    'Build the capacity of persons to handle and manage the conflicts and crises of life with appropriate measures.',
                    'Provide intervention for vulnerable persons to cope with life situations beyond temporal physical needs.',
                ],
                'founder_eyebrow' => 'The founder',
                'founder_name'    => 'Rev. Mipo Dadang',
                'founder_desc'    => 'A pastor and mentor with a heart for emerging leaders, Rev. Mipo Dadang founded MDLF to see people restored and released to restore others — through discipleship, mentorship, and practical compassion.',
            ]],
        ],

        'our-work' => [
            ['stories_grid', [
                'eyebrow'     => 'Our work & stories',
                'heading'     => 'What God is doing through the foundation',
                'description' => 'Milestones, gatherings, and the lives being changed along the way.',
                'limit'       => 0,
            ]],
        ],

        'give' => [
            ['give_body', [
                'eyebrow'      => 'Partner with us',
                'heading'      => 'Your giving carries this work to people',
                'lead'         => 'MDLF is a young foundation with growing reach and real constraints. Two needs stand out — and your partnership meets them directly.',
                'need1_title'  => 'We need partners & donors',
                'need1_text'   => 'A steady base of partners lets us plan, train and disciple consistently rather than gathering only when funds allow.',
                'need2_title'  => 'We need mobility for outreach',
                'need2_text'   => 'Discipleship outreaches take this work to where people are. Reliable transport removes one of our biggest barriers.',
                'tier1_label'  => 'Friend',  'tier1_amount' => '₦5,000',    'tier1_text' => 'Helps cover training materials for an emerging leader.',
                'tier2_label'  => 'Partner', 'tier2_amount' => '₦25,000',   'tier2_text' => 'Supports an outreach and sponsors disciples through a module.',
                'tier3_label'  => 'Patron',  'tier3_amount' => '₦100,000+', 'tier3_text' => 'Moves us toward mobility and sustained, wider outreach.',
                'bank_eyebrow' => 'Give by bank transfer',
            ]],
        ],

        'contact' => [
            ['contact', [
                'eyebrow' => 'Get in touch',
                'heading' => 'We would love to hear from you',
                'lead'    => 'Whether you want to partner with the foundation, join the discipleship journey, or simply learn more — send a message and we’ll respond.',
            ]],
        ],

        'discipleship' => [
            ['page_header', [
                'variant' => 'dark', 'align' => 'left', 'width' => 780,
                'eyebrow' => 'Discipleship Learning Portal',
                'heading' => 'A path to grow — and to multiply.',
                'lead'    => 'Work through a structured discipleship curriculum at your own pace. Track your progress, gather resources, and become someone who can disciple others.',
                'cta_text'  => 'Go to my journey', 'cta_link'  => 'portal',
                'cta2_text' => 'Create an account', 'cta2_link' => 'portal/register',
            ]],
            ['modules_grid', [
                'eyebrow'      => 'The curriculum',
                'heading'      => '6 modules, built to reproduce',
                'description'  => '',
                'limit'        => 0,
                'show_lessons' => 'yes',
                'show_all'     => 'no',
            ]],
        ],
    ];

    $find = $pdo->prepare("SELECT id FROM pages WHERE slug=?");
    $count = $pdo->prepare("SELECT COUNT(*) FROM blocks WHERE page_id=?");
    $ins = $pdo->prepare("INSERT INTO blocks (page_id, type, sort_order, data) VALUES (?,?,?,?)");

    foreach ($pages as $slug => $blocks) {
        $find->execute([$slug]);
        $pageId = (int) $find->fetchColumn();
        if (!$pageId) continue;
        $count->execute([$pageId]);
        if ((int) $count->fetchColumn() > 0) continue; // already has blocks — don't clobber

        foreach ($blocks as $i => [$type, $data]) {
            $ins->execute([
                $pageId, $type, $i + 1,
                json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]);
        }
    }
}
