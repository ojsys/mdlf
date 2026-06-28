<?php /* Block: stories_grid (dynamic — published posts) */
$limit = (int)($b['limit'] ?? 0);
$posts = DB::all("SELECT * FROM posts WHERE status='published' ORDER BY published_at DESC");
$posts = $limit > 0 ? array_slice($posts, 0, $limit) : $posts;
$hasHead = ($b['eyebrow'] ?? '') !== '' || ($b['heading'] ?? '') !== '' || ($b['description'] ?? '') !== ''; ?>
<section class="section" style="padding-top:3.5rem">
  <div class="wrap">
    <?php if ($hasHead): ?>
    <div class="section-head">
      <span class="eyebrow"><?= e($b['eyebrow']) ?></span>
      <h2><?= e($b['heading']) ?></h2>
      <?php if (($b['description'] ?? '') !== ''): ?><p class="muted"><?= e($b['description']) ?></p><?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (!$posts): ?>
      <p class="muted">No stories published yet. Check back soon.</p>
    <?php else: ?>
      <div class="card-grid">
        <?php foreach ($posts as $post): ?>
          <a class="card" href="<?= url('story/' . $post['slug']) ?>">
            <div class="thumb">
              <?php if ($post['cover_image']): ?>
                <img src="<?= asset('img/' . e($post['cover_image'])) ?>" alt="<?= e($post['title']) ?>">
              <?php else: ?>
                <div class="module-cover" style="height:100%"><span class="scr">MDLF</span></div>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <span class="kicker"><?= e(date('j M Y', strtotime($post['published_at'] ?? $post['created_at']))) ?></span>
              <h3><?= e($post['title']) ?></h3>
              <p><?= e($post['excerpt']) ?></p>
              <span class="meta">Read story →</span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
