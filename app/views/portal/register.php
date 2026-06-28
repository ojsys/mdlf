<?php $title = 'Create account'; ?>
<h1 class="serif">Begin the journey</h1>
<p class="muted" style="margin-bottom:1.6rem">Create your free account to access the discipleship curriculum.</p>
<form method="post" action="<?= url('portal/register') ?>">
  <?= csrf_field() ?>
  <div class="field">
    <label>Full name</label>
    <input class="input" name="name" value="<?= old('name') ?>" required autofocus>
  </div>
  <div class="field">
    <label>Email</label>
    <input class="input" type="email" name="email" value="<?= old('email') ?>" required>
  </div>
  <div class="field">
    <label>Location <span class="hint">(optional)</span></label>
    <input class="input" name="location" value="<?= old('location') ?>" placeholder="City, State">
  </div>
  <div class="field">
    <label>Password <span class="hint">— at least 6 characters</span></label>
    <input class="input" type="password" name="password" required>
  </div>
  <button class="btn btn-primary btn-block" type="submit">Create account</button>
</form>
<p class="auth-switch">Already have an account? <a href="<?= url('portal/login') ?>">Sign in</a></p>
