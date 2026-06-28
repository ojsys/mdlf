<?php
$editing = (bool)$page;
$heading = $editing ? 'Edit Page' : 'New Page';
$title = $heading;
$action = $editing ? url('admin/pages/' . $page['id']) : url('admin/pages');
$v = fn($k) => e($page[$k] ?? '');
?>
<a href="<?= url('admin/pages') ?>" style="color:var(--gold-deep); text-decoration:none; font-weight:600; font-size:.9rem;">← All Pages</a>

<form method="post" action="<?= $action ?>" class="mt-2">
  <?= csrf_field() ?>
  <div class="panel">
    <div class="panel-body">
      <div class="field">
        <label>Title</label>
        <input class="input" name="title" value="<?= $v('title') ?>" required autofocus>
      </div>
      <div class="form-row">
        <div class="field">
          <label>Slug <span class="hint">— leave blank to auto-generate</span></label>
          <input class="input" name="slug" value="<?= $v('slug') ?>" placeholder="auto-from-title">
        </div>
        <div class="field">
          <label>Sort Order</label>
          <input class="input" type="number" name="sort_order" value="<?= e($page['sort_order'] ?? 0) ?>">
        </div>
        <div class="field">
          <label>Status</label>
          <select class="select" name="status">
            <option value="published" <?= ($page['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published</option>
            <option value="draft" <?= ($page['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
          </select>
        </div>
      </div>
      <div class="field">
        <label>Content</label>
        <input type="hidden" id="page-content" name="content" value="<?= $v('content') ?>">
        <div class="quill-editor" data-input-id="page-content" style="min-height:350px;"></div>
      </div>
    </div>
  </div>
  <div class="flex">
    <button class="btn btn-primary" type="submit"><?= $editing ? 'Save Changes' : 'Create Page' ?></button>
    <a class="btn btn-ghost" href="<?= url('admin/pages') ?>">Cancel</a>
  </div>
</form>
