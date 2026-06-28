<?php $title = $post['title']; ?>
<article class="section" style="padding-top:3rem">
  <div class="wrap" style="max-width:760px">
    <a href="<?= url('our-work') ?>" style="color:var(--gold-deep); text-decoration:none; font-weight:600; font-size:.9rem">← All stories</a>
    <span class="eyebrow" style="display:block; margin-top:1.4rem"><?= e(date('j F Y', strtotime($post['published_at'] ?? $post['created_at']))) ?></span>
    <h1 style="font-size:clamp(2rem,4.4vw,3rem); margin:.6rem 0 1.2rem"><?= e($post['title']) ?></h1>

    <?php if ($post['cover_image']): ?>
      <img src="<?= asset('img/' . e($post['cover_image'])) ?>" alt="<?= e($post['title']) ?>" style="border-radius:var(--radius); box-shadow:var(--shadow); margin-bottom:2rem">
    <?php endif; ?>

    <div class="prose" style="font-size:1.12rem; line-height:1.85">
      <?php foreach (preg_split('/\n\s*\n/', trim($post['body'])) as $para): ?>
        <p class="muted" style="color:var(--ink-70)"><?= nl2br(e($para)) ?></p>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if ($more): ?>
  <div class="wrap mt-4" style="max-width:760px">
    <div class="divider-seed mt-4" style="margin-bottom:1.6rem">✦</div>
    <span class="eyebrow">Keep reading</span>
    <div class="card-grid two mt-2">
      <?php foreach ($more as $m): ?>
        <a class="card" href="<?= url('story/' . $m['slug']) ?>">
          <div class="card-body">
            <span class="kicker"><?= e(date('j M Y', strtotime($m['published_at'] ?? $m['created_at']))) ?></span>
            <h3 style="font-size:1.1rem"><?= e($m['title']) ?></h3>
            <p><?= e($m['excerpt']) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</article>
