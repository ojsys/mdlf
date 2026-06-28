<?php /* Block: page_header — dark hero band or light section header. */
$variant = $b['variant'] ?? 'dark';
$align   = $b['align'] ?? 'left';
if ($variant === 'dark'):
    $w = (int)($b['width'] ?? 760);
    $hasCta = ($b['cta_text'] ?? '') !== '' || ($b['cta2_text'] ?? '') !== '';
?>
<section class="hero" style="background:radial-gradient(120% 120% at 80% -10%, #16284A 0%, var(--ink) 55%, var(--ink-2) 100%)">
  <div class="wrap" style="padding-block:4.5rem 4rem; max-width:<?= $w ?>px">
    <span class="eyebrow on-dark"><?= e($b['eyebrow']) ?></span>
    <h1 style="font-size:clamp(2.2rem,4.6vw,3.3rem); margin:.8rem 0 1rem; color:var(--paper)"><?= $b['heading'] ?></h1>
    <p class="lead" style="color:rgba(247,248,251,.82)"><?= e($b['lead']) ?></p>
    <?php if ($hasCta): ?>
    <div class="hero-cta">
      <?php if (($b['cta_text'] ?? '') !== ''): ?>
        <a class="btn btn-primary" href="<?= url($b['cta_link']) ?>"><?= e($b['cta_text']) ?></a>
      <?php endif; ?>
      <?php if (($b['cta2_text'] ?? '') !== ''): ?>
        <a class="btn btn-ghost on-dark" href="<?= url($b['cta2_link']) ?>"><?= e($b['cta2_text']) ?></a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php else: ?>
<section class="section" style="padding-top:3.5rem">
  <div class="wrap">
    <div class="section-head<?= $align === 'center' ? ' center' : '' ?>"<?= $align === 'center' ? ' style="text-align:center; margin-inline:auto"' : '' ?>>
      <span class="eyebrow"><?= e($b['eyebrow']) ?></span>
      <h2><?= e($b['heading']) ?></h2>
      <?php if (($b['lead'] ?? '') !== ''): ?><p class="muted"><?= e($b['lead']) ?></p><?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>
