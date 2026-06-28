<?php $heading = 'Messages'; $title = 'Messages'; ?>
<div class="panel">
  <div class="panel-head"><h2>Contact & partner messages</h2></div>
  <table class="tbl">
    <thead><tr><th>From</th><th>Message</th><th>Received</th><th></th></tr></thead>
    <tbody>
      <?php if (!$messages): ?>
        <tr><td colspan="4" class="muted" style="padding:1.6rem">No messages yet.</td></tr>
      <?php else: foreach ($messages as $m): ?>
        <tr style="<?= $m['is_read'] ? '' : 'background:rgba(215,163,58,.05)' ?>">
          <td>
            <strong><?= e($m['name']) ?></strong> <?php if(!$m['is_read']): ?><span class="badge unread">New</span><?php endif; ?>
            <div class="muted" style="font-size:.84rem"><a href="mailto:<?= e($m['email']) ?>" style="color:var(--gold-deep)"><?= e($m['email']) ?></a></div>
          </td>
          <td>
            <strong style="font-size:.92rem"><?= e($m['subject']) ?></strong>
            <div class="muted" style="font-size:.88rem; max-width:46ch"><?= nl2br(e($m['body'])) ?></div>
          </td>
          <td style="color:var(--ink-55); font-size:.82rem; white-space:nowrap"><?= e(date('j M Y', strtotime($m['created_at']))) ?></td>
          <td>
            <div class="row-actions">
              <?php if(!$m['is_read']): ?>
              <form class="inline-form" method="post" action="<?= url('admin/messages/' . $m['id'] . '/read') ?>">
                <?= csrf_field() ?><button class="btn btn-ghost btn-sm">Mark read</button>
              </form>
              <?php endif; ?>
              <form class="inline-form" method="post" action="<?= url('admin/messages/' . $m['id'] . '/delete') ?>" onsubmit="return confirm('Delete this message?')">
                <?= csrf_field() ?><button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
