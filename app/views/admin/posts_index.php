<?php $heading = 'News & Stories'; $title = 'Stories'; ?>
<div class="panel">
  <div class="panel-head">
    <h2>All stories</h2>
    <a class="btn btn-primary btn-sm" href="<?= url('admin/posts/new') ?>">+ New story</a>
  </div>
  <table class="tbl">
    <thead><tr><th>Title</th><th>Status</th><th>Published</th><th></th></tr></thead>
    <tbody>
      <?php if (!$posts): ?>
        <tr><td colspan="4" class="muted" style="padding:1.6rem">No stories yet. <a href="<?= url('admin/posts/new') ?>">Write your first one →</a></td></tr>
      <?php else: foreach ($posts as $p): ?>
        <tr>
          <td><strong><?= e($p['title']) ?></strong><div class="muted" style="font-size:.84rem">/story/<?= e($p['slug']) ?></div></td>
          <td><span class="badge <?= e($p['status']) ?>"><?= e($p['status']) ?></span></td>
          <td style="color:var(--ink-55); font-size:.86rem"><?= $p['published_at'] ? e(date('j M Y', strtotime($p['published_at']))) : '—' ?></td>
          <td>
            <div class="row-actions">
              <a class="btn btn-ghost btn-sm" href="<?= url('admin/posts/' . $p['id'] . '/edit') ?>">Edit</a>
              <form class="inline-form" method="post" action="<?= url('admin/posts/' . $p['id'] . '/delete') ?>" onsubmit="return confirm('Delete this story?')">
                <?= csrf_field() ?><button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
