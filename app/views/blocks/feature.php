<?php /* Block: feature (image + text, optional gallery) */
$gallery = is_array($b['gallery'] ?? null) ? $b['gallery'] : []; ?>
<section class="section" style="background:var(--paper); border-block:1px solid var(--line)">
  <div class="wrap">
    <div class="feature">
      <?php if ($b['image']): ?>
        <div class="media"><img src="<?= e(block_image_url($b['image'])) ?>" alt="<?= e($b['heading']) ?>"></div>
      <?php endif; ?>
      <div>
        <span class="eyebrow"><?= e($b['eyebrow']) ?></span>
        <h2><?= e($b['heading']) ?></h2>
        <p class="muted" style="font-size:1.08rem"><?= e($b['body']) ?></p>
        <?php if ($b['body2']): ?><p class="muted"><?= $b['body2'] ?></p><?php endif; ?>
        <div class="flex wrap-flex mt-2">
          <?php if ($b['button_text']): ?>
            <a class="btn btn-primary" href="<?= url($b['button_link']) ?>"><?= e($b['button_text']) ?></a>
          <?php endif; ?>
          <?php if ($b['button2_text']): ?>
            <a class="btn btn-ghost" href="<?= url($b['button2_link']) ?>"><?= e($b['button2_text']) ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php if ($gallery): ?>
      <div class="gallery mt-4">
        <?php foreach ($gallery as $g): ?>
          <figure>
            <img src="<?= e(block_image_url($g['image'] ?? '')) ?>" alt="<?= e($g['caption'] ?? '') ?>">
            <figcaption><?= e($g['caption'] ?? '') ?></figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
