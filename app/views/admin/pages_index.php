<?php $heading = 'Pages'; ?>

<div class="panel">
  <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center;">
    <h2>All Pages</h2>
    <a class="btn btn-primary" href="<?= url('admin/pages/new') ?>">+ Add Page</a>
  </div>
  <div class="panel-body">
    <table class="tbl">
      <thead>
        <tr>
          <th>Title</th>
          <th>Slug</th>
          <th>Status</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pages as $page): ?>
          <tr>
            <td><?= e($page['title']) ?></td>
            <td><code>/<?= e($page['slug']) ?></code></td>
            <td><span class="badge <?= $page['status'] ?>"><?= e($page['status']) ?></span></td>
            <td style="text-align:right;">
              <a class="btn btn-primary btn-sm" href="<?= url('admin/pages/' . $page['id'] . '/builder') ?>">Build</a>
              <a class="btn btn-ghost btn-sm" href="<?= url('admin/pages/' . $page['id'] . '/edit') ?>">Settings</a>
              <?php if (!in_array($page['slug'], ['home', 'about', 'our-work', 'discipleship', 'give', 'contact'])): ?>
                <form class="inline-form" method="post" action="<?= url('admin/pages/' . $page['id'] . '/delete') ?>" onsubmit="return confirm('Delete this page?');" style="display:inline;">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
