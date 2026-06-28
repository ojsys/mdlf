<?php /* Block: video */ ?>
<section class="section">
  <div class="wrap" style="max-width:880px;margin-inline:auto">
    <?php if ($b['src']): ?>
      <video src="<?= e(block_image_url($b['src'])) ?>" controls playsinline
        <?= $b['poster'] ? 'poster="' . e(block_image_url($b['poster'])) . '"' : '' ?>
        style="width:100%;border-radius:14px;background:#000;display:block"></video>
    <?php endif; ?>
    <?php if ($b['caption']): ?>
      <p class="muted" style="text-align:center;margin-top:.6rem"><?= e($b['caption']) ?></p>
    <?php endif; ?>
  </div>
</section>
