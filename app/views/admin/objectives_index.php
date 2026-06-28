<?php $heading = 'Objectives'; ?>

<div class="panel">
  <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Home Page Objectives</h2>
    <a class="btn btn-primary" href="<?= url('admin/objectives/new') ?>">+ Add Objective</a>
  </div>
  <div class="panel-body">
    <table class="tbl">
      <thead>
        <tr>
          <th>Order</th>
          <th>Title</th>
          <th>Description</th>
          <th style="text-align:right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($objectives as $obj): ?>
          <tr>
            <td><?= e($obj['sort_order']) ?></td>
            <td><?= e($obj['title']) ?></td>
            <td style="max-width:400px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($obj['description']) ?></td>
            <td style="text-align:right">
              <a class="btn btn-ghost btn-sm" href="<?= url('admin/objectives/' . $obj['id'] . '/edit') ?>">Edit</a>
              <form class="inline-form" method="post" action="<?= url('admin/objectives/' . $obj['id'] . '/delete') ?>" onsubmit="return confirm('Delete this objective?');" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
