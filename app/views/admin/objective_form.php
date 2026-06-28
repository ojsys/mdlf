<?php
$editing = (bool)$objective;
$heading = $editing ? 'Edit Objective' : 'New Objective';
$title = $heading;
$action = $editing ? url('admin/objectives/' . $objective['id']) : url('admin/objectives');
$v = fn($k) => e($objective[$k] ?? '');
?>
<a href="<?= url('admin/objectives') ?>" style="color:var(--gold-deep);text-decoration:none;font-weight:600;font-size:.9rem">← All Objectives</a>

<form method="post" action="<?= $action ?>" class="mt-2">
  <?= csrf_field() ?>
  <div class="panel">
    <div class="panel-body">
      <div class="form-row">
        <div class="field" style="flex:2">
          <label>Title</label>
          <input class="input" name="title" value="<?= $v('title') ?>" required autofocus>
        </div>
        <div class="field">
          <label>Sort Order</label>
          <input class="input" type="number" name="sort_order" value="<?= $v('sort_order') ?>">
        </div>
      </div>
      <div class="field">
        <label>Description</label>
        <textarea class="textarea" name="description" style="min-height:120px"><?= $v('description') ?></textarea>
      </div>
    </div>
  </div>
  <div class="flex">
    <button class="btn btn-primary" type="submit"><?= $editing ? 'Save Changes' : 'Create Objective' ?></button>
    <a class="btn btn-ghost" href="<?= url('admin/objectives') ?>">Cancel</a>
  </div>
</form>
