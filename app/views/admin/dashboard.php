<?php $heading = 'Dashboard'; $title = 'Dashboard'; ?>

<div class="kpi-grid">
  <div class="kpi"><div class="n"><?= (int)$stats['members'] ?></div><div class="l">Portal members</div></div>
  <div class="kpi"><div class="n"><?= (int)$stats['modules'] ?></div><div class="l">Modules</div></div>
  <div class="kpi"><div class="n"><?= (int)$stats['lessons'] ?></div><div class="l">Lessons</div></div>
  <div class="kpi"><div class="n"><?= (int)$stats['posts'] ?></div><div class="l">Stories</div></div>
  <div class="kpi"><div class="n"><?= (int)$stats['completions'] ?></div><div class="l">Lessons completed</div></div>
  <div class="kpi <?= $stats['messages'] ? 'alert' : '' ?>"><div class="n"><?= (int)$stats['messages'] ?></div><div class="l">Unread messages</div></div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem">
  <div class="panel">
    <div class="panel-head"><h2>Recent messages</h2><a class="btn btn-ghost btn-sm" href="<?= url('admin/messages') ?>">View all</a></div>
    <table class="tbl">
      <tbody>
        <?php if (!$recentMsgs): ?>
          <tr><td class="muted" style="padding:1.4rem">No messages yet.</td></tr>
        <?php else: foreach ($recentMsgs as $m): ?>
          <tr>
            <td>
              <strong><?= e($m['name']) ?></strong> <?php if(!$m['is_read']): ?><span class="badge unread">New</span><?php endif; ?>
              <div class="muted" style="font-size:.85rem"><?= e(mb_strimwidth($m['subject'] ?: $m['body'], 0, 42, '…')) ?></div>
            </td>
            <td style="text-align:right; white-space:nowrap; color:var(--ink-55); font-size:.82rem"><?= e(date('j M', strtotime($m['created_at']))) ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <div class="panel">
    <div class="panel-head"><h2>New members</h2><a class="btn btn-ghost btn-sm" href="<?= url('admin/members') ?>">View all</a></div>
    <table class="tbl">
      <tbody>
        <?php if (!$recentMembers): ?>
          <tr><td class="muted" style="padding:1.4rem">No members yet.</td></tr>
        <?php else: foreach ($recentMembers as $m): ?>
          <tr>
            <td><strong><?= e($m['name']) ?></strong><div class="muted" style="font-size:.85rem"><?= e($m['email']) ?></div></td>
            <td style="text-align:right; white-space:nowrap; color:var(--ink-55); font-size:.82rem"><?= e($m['location'] ?: '—') ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="help-note mt-3">
  <strong>Quick start:</strong> Add a <a href="<?= url('admin/modules/new') ?>">module</a>, write a <a href="<?= url('admin/posts/new') ?>">story</a>, or review your <a href="<?= url('admin/settings') ?>">site settings</a>. Remember to change the default admin password.
</div>
