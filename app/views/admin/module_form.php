<?php
$editing = (bool)$module;
$heading = $editing ? 'Edit module' : 'New module';
$title = $heading;
$action = $editing ? url('admin/modules/' . $module['id']) : url('admin/modules');
$v = fn($k) => e($module[$k] ?? '');
?>
<a href="<?= url('admin/modules') ?>" style="color:var(--gold-deep); text-decoration:none; font-weight:600; font-size:.9rem">← All modules</a>

<form method="post" action="<?= $action ?>" class="mt-2">
  <?= csrf_field() ?>
  <div class="panel"><div class="panel-body">
    <div class="field">
      <label>Title</label>
      <input class="input" name="title" value="<?= $v('title') ?>" required autofocus>
    </div>
    <div class="form-row">
      <div class="field">
        <label>Slug <span class="hint">— blank to auto-generate</span></label>
        <input class="input" name="slug" value="<?= $v('slug') ?>">
      </div>
      <div class="field">
        <label>Scripture <span class="hint">— shown on the cover</span></label>
        <input class="input" name="scripture" value="<?= $v('scripture') ?>" placeholder="e.g. 2 Timothy 2:2">
      </div>
    </div>
    <div class="field">
      <label>Summary <span class="hint">— one line for cards</span></label>
      <input class="input" name="summary" value="<?= $v('summary') ?>">
    </div>
    <div class="field">
      <label>Description</label>
      <input type="hidden" id="module-description" name="description" value="<?= $v('description') ?>">
      <div class="quill-editor" data-input-id="module-description" style="min-height:180px"></div>
    </div>
    <div class="form-row">
      <div class="field">
        <label>Sort order</label>
        <input class="input" type="number" name="sort_order" value="<?= e($module['sort_order'] ?? 0) ?>">
      </div>
      <div class="field">
        <label>Status</label>
        <select class="select" name="status">
          <option value="published" <?= ($module['status'] ?? 'published')==='published'?'selected':'' ?>>Published</option>
          <option value="draft" <?= ($module['status'] ?? '')==='draft'?'selected':'' ?>>Draft</option>
        </select>
      </div>
    </div>
  </div></div>
  <div class="flex">
    <button class="btn btn-primary" type="submit"><?= $editing ? 'Save changes' : 'Create & add lessons' ?></button>
    <a class="btn btn-ghost" href="<?= url('admin/modules') ?>">Cancel</a>
  </div>
</form>
