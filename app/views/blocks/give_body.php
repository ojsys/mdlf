<?php /* Block: give_body — needs, giving tiers, bank details (from settings). */
$s = settings(); ?>
<section class="section" style="padding-top:3.5rem">
  <div class="wrap">
    <div class="section-head center" style="text-align:center; margin-inline:auto">
      <span class="eyebrow"><?= e($b['eyebrow']) ?></span>
      <h2><?= e($b['heading']) ?></h2>
      <p class="muted"><?= e($b['lead']) ?></p>
    </div>

    <div class="card-grid two" style="max-width:840px; margin-inline:auto; margin-bottom:3rem">
      <div class="give-card">
        <div class="need" style="border:0; padding-top:0">
          <span class="ic">◎</span>
          <div>
            <h3 style="font-size:1.15rem; margin-bottom:.3rem"><?= e($b['need1_title']) ?></h3>
            <p class="muted" style="margin:0"><?= e($b['need1_text']) ?></p>
          </div>
        </div>
      </div>
      <div class="give-card">
        <div class="need" style="border:0; padding-top:0">
          <span class="ic">⛟</span>
          <div>
            <h3 style="font-size:1.15rem; margin-bottom:.3rem"><?= e($b['need2_title']) ?></h3>
            <p class="muted" style="margin:0"><?= e($b['need2_text']) ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="give-grid" style="max-width:980px; margin-inline:auto">
      <div class="give-card">
        <span class="eyebrow"><?= e($b['tier1_label']) ?></span>
        <div class="amount"><?= e($b['tier1_amount']) ?></div>
        <p class="muted"><?= e($b['tier1_text']) ?></p>
      </div>
      <div class="give-card lead">
        <span class="eyebrow on-dark"><?= e($b['tier2_label']) ?></span>
        <div class="amount"><?= e($b['tier2_amount']) ?></div>
        <p class="muted" style="color:rgba(247,248,251,.78)"><?= e($b['tier2_text']) ?></p>
      </div>
      <div class="give-card">
        <span class="eyebrow"><?= e($b['tier3_label']) ?></span>
        <div class="amount"><?= e($b['tier3_amount']) ?></div>
        <p class="muted"><?= e($b['tier3_text']) ?></p>
      </div>
    </div>

    <div class="give-card mt-4" style="max-width:600px; margin-inline:auto; text-align:center">
      <span class="eyebrow"><?= e($b['bank_eyebrow']) ?></span>
      <div class="stack-gap mt-2" style="font-size:1.05rem">
        <div><span class="muted">Account name</span><br><strong><?= e($s['give_account_name']) ?></strong></div>
        <div><span class="muted">Bank</span><br><strong><?= e($s['give_bank']) ?></strong></div>
        <div><span class="muted">Account number</span><br><strong style="font-size:1.3rem; letter-spacing:.05em"><?= e($s['give_account_number']) ?></strong></div>
      </div>
      <p class="muted mt-3" style="font-size:.9rem">After giving, <a href="<?= url('contact') ?>" style="color:var(--gold-deep); font-weight:700">let us know</a> so we can thank you and keep you updated.</p>
    </div>
  </div>
</section>
