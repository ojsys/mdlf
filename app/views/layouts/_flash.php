<?php $flashes = flash(); if ($flashes): ?>
<div class="flash-stack">
  <?php foreach ($flashes as $f): ?>
    <div class="flash <?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
