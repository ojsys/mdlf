<?php $s = settings(); $title = 'Give'; ?>
<section class="section" style="padding-top:3.5rem">
  <div class="wrap">
    <div class="section-head center" style="text-align:center; margin-inline:auto">
      <span class="eyebrow">Partner with us</span>
      <h2>Your giving carries this work to people</h2>
      <p class="muted">MDLF is a young foundation with growing reach and real constraints. Two needs stand out — and your partnership meets them directly.</p>
    </div>

    <div class="card-grid two" style="max-width:840px; margin-inline:auto; margin-bottom:3rem">
      <div class="give-card">
        <div class="need" style="border:0; padding-top:0">
          <span class="ic">◎</span>
          <div>
            <h3 style="font-size:1.15rem; margin-bottom:.3rem">We need partners &amp; donors</h3>
            <p class="muted" style="margin:0">A steady base of partners lets us plan, train and disciple consistently rather than gathering only when funds allow.</p>
          </div>
        </div>
      </div>
      <div class="give-card">
        <div class="need" style="border:0; padding-top:0">
          <span class="ic">⛟</span>
          <div>
            <h3 style="font-size:1.15rem; margin-bottom:.3rem">We need mobility for outreach</h3>
            <p class="muted" style="margin:0">Discipleship outreaches take this work to where people are. Reliable transport removes one of our biggest barriers.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="give-grid" style="max-width:980px; margin-inline:auto">
      <div class="give-card">
        <span class="eyebrow">Friend</span>
        <div class="amount">₦5,000</div>
        <p class="muted">Helps cover training materials for an emerging leader.</p>
      </div>
      <div class="give-card lead">
        <span class="eyebrow on-dark">Partner</span>
        <div class="amount">₦25,000</div>
        <p class="muted" style="color:rgba(247,248,251,.78)">Supports an outreach and sponsors disciples through a module.</p>
      </div>
      <div class="give-card">
        <span class="eyebrow">Patron</span>
        <div class="amount">₦100,000+</div>
        <p class="muted">Moves us toward mobility and sustained, wider outreach.</p>
      </div>
    </div>

    <div class="give-card mt-4" style="max-width:600px; margin-inline:auto; text-align:center">
      <span class="eyebrow">Give by bank transfer</span>
      <div class="stack-gap mt-2" style="font-size:1.05rem">
        <div><span class="muted">Account name</span><br><strong><?= e($s['give_account_name']) ?></strong></div>
        <div><span class="muted">Bank</span><br><strong><?= e($s['give_bank']) ?></strong></div>
        <div><span class="muted">Account number</span><br><strong style="font-size:1.3rem; letter-spacing:.05em"><?= e($s['give_account_number']) ?></strong></div>
      </div>
      <p class="muted mt-3" style="font-size:.9rem">After giving, <a href="<?= url('contact') ?>" style="color:var(--gold-deep); font-weight:700">let us know</a> so we can thank you and keep you updated.</p>
    </div>
  </div>
</section>
