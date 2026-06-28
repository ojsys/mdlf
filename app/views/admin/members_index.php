<?php $heading = 'Members'; $title = 'Members'; ?>
<div class="panel">
  <div class="panel-head"><h2>Portal members</h2><span class="pill"><?= count($members) ?> total</span></div>
  <table class="tbl">
    <thead><tr><th>Name</th><th>Email</th><th>Location</th><th>Lessons done</th><th>Joined</th></tr></thead>
    <tbody>
      <?php if (!$members): ?>
        <tr><td colspan="5" class="muted" style="padding:1.6rem">No members have registered yet.</td></tr>
      <?php else: foreach ($members as $m): ?>
        <tr>
          <td><strong><?= e($m['name']) ?></strong></td>
          <td><a href="mailto:<?= e($m['email']) ?>" style="color:var(--gold-deep); text-decoration:none"><?= e($m['email']) ?></a></td>
          <td style="color:var(--ink-55)"><?= e($m['location'] ?: '—') ?></td>
          <td><span class="pill"><?= (int)$m['completed'] ?></span></td>
          <td style="color:var(--ink-55); font-size:.84rem"><?= e(date('j M Y', strtotime($m['created_at']))) ?></td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
