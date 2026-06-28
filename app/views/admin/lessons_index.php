<?php $heading = $module['title'] . ' · Lessons'; $title = 'Lessons'; ?>
<a href="<?= url('admin/modules') ?>" style="color:var(--gold-deep); text-decoration:none; font-weight:600; font-size:.9rem">← All modules</a>

<div class="panel mt-2">
  <div class="panel-head">
    <h2>Lessons in “<?= e($module['title']) ?>”</h2>
    <a class="btn btn-primary btn-sm" href="<?= url('admin/modules/' . $module['id'] . '/lessons/new') ?>">+ New lesson</a>
  </div>
  <table class="tbl">
    <thead><tr><th>#</th><th>Lesson</th><th>Duration</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php if (!$lessons): ?>
        <tr><td colspan="5" class="muted" style="padding:1.6rem">No lessons yet. <a href="<?= url('admin/modules/' . $module['id'] . '/lessons/new') ?>">Add the first →</a></td></tr>
      <?php else: foreach ($lessons as $l): ?>
        <tr>
          <td style="color:var(--ink-55)"><?= (int)$l['sort_order'] ?></td>
          <td><strong><?= e($l['title']) ?></strong><div class="muted" style="font-size:.84rem"><?= e($l['summary']) ?></div></td>
          <td style="color:var(--ink-55)"><?= (int)$l['duration_min'] ?> min</td>
          <td><span class="badge <?= e($l['status']) ?>"><?= e($l['status']) ?></span></td>
          <td>
            <div class="row-actions">
              <a class="btn btn-ghost btn-sm" href="<?= url('admin/modules/' . $module['id'] . '/lessons/' . $l['id'] . '/edit') ?>">Edit</a>
              <form class="inline-form" method="post" action="<?= url('admin/modules/' . $module['id'] . '/lessons/' . $l['id'] . '/delete') ?>" onsubmit="return confirm('Delete this lesson?')">
                <?= csrf_field() ?><button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
