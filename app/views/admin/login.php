<?php $title = 'Admin sign in'; ?>
<h1 class="serif">Content Studio</h1>
<p class="muted" style="margin-bottom:1.6rem">Sign in to manage the website and discipleship portal.</p>
<form method="post" action="<?= url('admin/login') ?>">
  <?= csrf_field() ?>
  <div class="field">
    <label>Admin email</label>
    <input class="input" type="email" name="email" value="<?= old('email') ?>" required autofocus>
  </div>
  <div class="field">
    <label>Password</label>
    <input class="input" type="password" name="password" required>
  </div>
  <button class="btn btn-primary btn-block" type="submit">Sign in to dashboard</button>
</form>
<p class="auth-switch"><a href="<?= url('/') ?>" style="color:var(--ink-55); font-weight:600">← Back to website</a></p>
