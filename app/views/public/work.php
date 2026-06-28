<?php $title = 'Our Work'; ?>
<section class="section" style="padding-top:3.5rem">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Our work &amp; stories</span>
      <h2>What God is doing through the foundation</h2>
      <p class="muted">Milestones, gatherings, and the lives being changed along the way.</p>
    </div>

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
