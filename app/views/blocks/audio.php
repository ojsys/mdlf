<?php /* Block: audio */ ?>
<section class="section">
  <div class="wrap" style="max-width:680px;margin-inline:auto">
    <?php if ($b['title']): ?><h3 style="margin-bottom:.6rem"><?= e($b['title']) ?></h3><?php endif; ?>
    <?php if ($b['src']): ?>
      <audio src="<?= e(block_image_url($b['src'])) ?>" controls style="width:100%"></audio>
    <?php endif; ?>
  </div>
</section>
