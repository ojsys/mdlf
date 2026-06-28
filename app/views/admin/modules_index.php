<?php $heading = 'Modules & Lessons'; $title = 'Modules'; ?>
<div class="panel">
  <div class="panel-head">
    <h2>Discipleship modules</h2>
    <a class="btn btn-primary btn-sm" href="<?= url('admin/modules/new') ?>">+ New module</a>
  </div>
  <table class="tbl">
    <thead><tr><th>#</th><th>Module</th><th>Lessons</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php if (!$modules): ?>
        <tr><td colspan="5" class="muted" style="padding:1.6rem">No modules yet. <a href="<?= url('admin/modules/new') ?>">Create the first →</a></td></tr>
      <?php else: foreach ($modules as $m): ?>
        <tr>
          <td style="color:var(--ink-55)"><?= (int)$m['sort_order'] ?></td>
          <td>
            <strong><?= e($m['title']) ?></strong>
            <div class="muted" style="font-size:.84rem"><?= e($m['scripture']) ?></div>
          </td>
          <td><a href="<?= url('admin/modules/' . $m['id'] . '/lessons') ?>"><?= (int)$m['lessons'] ?> lesson<?= $m['lessons']==1?'':'s' ?></a></td>
          <td><span class="badge <?= e($m['status']) ?>"><?= e($m['status']) ?></span></td>
          <td>
            <div class="row-actions">
              <a class="btn btn-ghost btn-sm" href="<?= url('admin/modules/' . $m['id'] . '/lessons') ?>">Lessons</a>
              <a class="btn btn-ghost btn-sm" href="<?= url('admin/modules/' . $m['id'] . '/edit') ?>">Edit</a>
              <form class="inline-form" method="post" action="<?= url('admin/modules/' . $m['id'] . '/delete') ?>" onsubmit="return confirm('Delete this module and ALL its lessons?')">
                <?= csrf_field() ?><button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
