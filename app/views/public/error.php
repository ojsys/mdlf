<?php $title = ($code ?? 'Error') . ' — ' . ($title ?? 'Error'); ?>
<section class="section" style="padding-block:6rem">
  <div class="wrap center" style="max-width:560px">
    <div class="serif" style="font-size:clamp(4rem,12vw,7rem); color:var(--gold); line-height:1"><?= e($code ?? 'Oops') ?></div>
    <h2 style="margin:.6rem 0 .8rem"><?= e($title ?? 'Something went wrong') ?></h2>
    <p class="muted"><?= e($message ?? 'The page could not be found.') ?></p>
    <div class="mt-3 flex" style="justify-content:center">
      <a class="btn btn-primary" href="<?= url('/') ?>">Back to home</a>
      <a class="btn btn-ghost" href="<?= url('discipleship') ?>">Discipleship</a>
    </div>
  </div>
</section>
