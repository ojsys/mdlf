<?php /* Block: cta (call to action, dark band) */ ?>
<section class="section dark">
  <div class="wrap" style="display:grid; grid-template-columns:1.3fr 1fr; gap:2.5rem; align-items:center">
    <div>
      <span class="eyebrow on-dark"><?= e($b['eyebrow']) ?></span>
      <h2><?= e($b['heading']) ?></h2>
      <p class="muted" style="font-size:1.05rem"><?= e($b['description']) ?></p>
    </div>
    <div style="text-align:right">
      <a class="btn btn-primary" href="<?= url($b['button_link']) ?>"><?= e($b['button_text']) ?></a>
    </div>
  </div>
</section>
