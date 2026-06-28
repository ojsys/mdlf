<?php /* Block: stats (dynamic — impact_stats) */
$stats = DB::all("SELECT * FROM impact_stats ORDER BY sort_order"); ?>
<section class="section dark">
  <div class="wrap">
    <div class="section-head center" style="text-align:center">
      <span class="eyebrow on-dark"><?= e($b['eyebrow']) ?></span>
      <h2><?= e($b['heading']) ?></h2>
    </div>
    <div class="stats">
      <?php foreach ($stats as $st): ?>
        <div class="stat">
          <div class="figure"><span data-count="<?= e($st['value']) ?>"><?= e($st['value']) ?></span><?= e($st['suffix']) ?></div>
          <div class="label"><?= e($st['label']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
