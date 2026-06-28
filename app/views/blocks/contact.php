<?php /* Block: contact — info column (from settings) + message form. */
$s = settings(); ?>
<section class="section" style="padding-top:3.5rem">
  <div class="wrap" style="display:grid; grid-template-columns:1fr 1.1fr; gap:3rem; align-items:start">
    <div>
      <span class="eyebrow"><?= e($b['eyebrow']) ?></span>
      <h2 style="margin:.6rem 0 1rem"><?= e($b['heading']) ?></h2>
      <p class="muted"><?= e($b['lead']) ?></p>

      <div class="stack-gap mt-4">
        <div class="flex" style="gap:.8rem"><span style="color:var(--gold-deep)">✉</span><a href="mailto:<?= e($s['contact_email']) ?>" style="text-decoration:none; color:var(--ink)"><?= e($s['contact_email']) ?></a></div>
        <div class="flex" style="gap:.8rem"><span style="color:var(--gold-deep)">☎</span><a href="tel:<?= e($s['contact_phone']) ?>" style="text-decoration:none; color:var(--ink)"><?= e($s['contact_phone']) ?></a></div>
        <div class="flex" style="gap:.8rem"><span style="color:var(--gold-deep)">⌖</span><span><?= e($s['contact_address']) ?></span></div>
      </div>
    </div>

    <div class="give-card">
      <form method="post" action="<?= url('contact') ?>">
        <?= csrf_field() ?>
        <div class="form-row">
          <div class="field">
            <label>Your name</label>
            <input class="input" name="name" value="<?= old('name') ?>" required>
          </div>
          <div class="field">
            <label>Email</label>
            <input class="input" type="email" name="email" value="<?= old('email') ?>" required>
          </div>
        </div>
        <div class="field">
          <label>Subject</label>
          <input class="input" name="subject" value="<?= old('subject') ?>" placeholder="How can we help?">
        </div>
        <div class="field">
          <label>Message</label>
          <textarea class="textarea" name="body" required><?= old('body') ?></textarea>
        </div>
        <button class="btn btn-primary btn-block" type="submit">Send message</button>
      </form>
    </div>
  </div>
</section>
