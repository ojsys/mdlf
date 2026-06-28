<?php /* Block: objectives (dynamic — objectives table) */
$objectives = DB::all("SELECT * FROM objectives ORDER BY sort_order, id"); ?>
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow"><?= e($b['eyebrow']) ?></span>
      <h2><?= e($b['heading']) ?></h2>
    </div>
    <div class="obj-grid">
      <?php foreach ($objectives as $i => $o): ?>
        <div class="obj">
          <div class="num"><?= sprintf('%02d', $i + 1) ?></div>
          <h3><?= e($o['title']) ?></h3>
          <p><?= e($o['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
