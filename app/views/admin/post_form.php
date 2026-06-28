<?php
$editing = (bool)$post;
$heading = $editing ? 'Edit story' : 'New story';
$title = $heading;
$action = $editing ? url('admin/posts/' . $post['id']) : url('admin/posts');
$v = fn($k) => e($post[$k] ?? '');
?>
<a href="<?= url('admin/posts') ?>" style="color:var(--gold-deep); text-decoration:none; font-weight:600; font-size:.9rem">← All stories</a>

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
          <label>Cover image <span class="hint">— filename in /assets/img/</span></label>
          <input class="input" name="cover_image" value="<?= $v('cover_image') ?>" placeholder="e.g. training-group.jpeg">
        </div>
      </div>
      <div class="field">
        <label>Excerpt <span class="hint">— short summary for cards</span></label>
        <input class="input" name="excerpt" value="<?= $v('excerpt') ?>">
      </div>
      <div class="field">
        <label>Body</label>
        <input type="hidden" id="post-body" name="body" value="<?= $v('body') ?>">
        <div class="quill-editor" data-input-id="post-body" style="min-height:280px"></div>
      </div>
      <div class="field" style="max-width:240px">
        <label>Status</label>
        <select class="select" name="status">
          <option value="draft" <?= ($post['status'] ?? '')==='draft'?'selected':'' ?>>Draft</option>
          <option value="published" <?= ($post['status'] ?? '')==='published'?'selected':'' ?>>Published</option>
        </select>
      </div>
    </div>
  </div>
  <div class="flex">
    <button class="btn btn-primary" type="submit"><?= $editing ? 'Save changes' : 'Create story' ?></button>
    <a class="btn btn-ghost" href="<?= url('admin/posts') ?>">Cancel</a>
  </div>
</form>
