<?php $title = 'Sign in'; ?>
<h1 class="serif">Welcome back</h1>
<p class="muted" style="margin-bottom:1.6rem">Sign in to continue your discipleship journey.</p>
<form method="post" action="<?= url('portal/login') ?>">
  <?= csrf_field() ?>
  <div class="field">
    <label>Email</label>
    <input class="input" type="email" name="email" value="<?= old('email') ?>" required autofocus>
  </div>
  <div class="field">
    <label>Password</label>
    <input class="input" type="password" name="password" required>
  </div>
  <button class="btn btn-primary btn-block" type="submit">Sign in</button>
</form>
<p class="auth-switch">New here? <a href="<?= url('portal/register') ?>">Create an account</a></p>
<p class="auth-switch" style="margin-top:.4rem"><a href="<?= url('/') ?>" style="color:var(--ink-55); font-weight:600">← Back to website</a></p>
