<?php
$editing = (bool)$lesson;
$heading = $editing ? 'Edit lesson' : 'New lesson';
$title = $heading;
$base = url('admin/modules/' . $module['id'] . '/lessons');
$action = $editing ? $base . '/' . $lesson['id'] : $base;
$v = fn($k) => e($lesson[$k] ?? '');
$typeLabel = ['scripture'=>'Scripture','video'=>'Video','pdf'=>'PDF','audio'=>'Audio','link'=>'Link'];
?>
<a href="<?= e($base) ?>" style="color:var(--gold-deep); text-decoration:none; font-weight:600; font-size:.9rem">← <?= e($module['title']) ?> lessons</a>

<form method="post" action="<?= $action ?>" class="mt-2">
  <?= csrf_field() ?>
  <div class="panel"><div class="panel-body">
    <div class="field">
      <label>Lesson title</label>
      <input class="input" name="title" value="<?= $v('title') ?>" required autofocus>
    </div>
    <div class="form-row">
      <div class="field">
        <label>Slug <span class="hint">— blank to auto-generate</span></label>
        <input class="input" name="slug" value="<?= $v('slug') ?>">
      </div>
      <div class="field">
        <label>Summary</label>
        <input class="input" name="summary" value="<?= $v('summary') ?>">
      </div>
    </div>
    <div class="field">
      <label>Content</label>
      <input type="hidden" id="lesson-content" name="content" value="<?= $v('content') ?>">
      <div class="quill-editor" data-input-id="lesson-content" style="min-height:300px"></div>
    </div>
    <div class="form-row" style="grid-template-columns:1fr 1fr 1fr">
      <div class="field">
        <label>Duration (min)</label>
        <input class="input" type="number" name="duration_min" value="<?= e($lesson['duration_min'] ?? 10) ?>">
      </div>
      <div class="field">
        <label>Sort order</label>
        <input class="input" type="number" name="sort_order" value="<?= e($lesson['sort_order'] ?? 0) ?>">
      </div>
      <div class="field">
        <label>Status</label>
        <select class="select" name="status">
          <option value="published" <?= ($lesson['status'] ?? 'published')==='published'?'selected':'' ?>>Published</option>
          <option value="draft" <?= ($lesson['status'] ?? '')==='draft'?'selected':'' ?>>Draft</option>
        </select>
      </div>
    </div>
  </div></div>
  <div class="flex">
    <button class="btn btn-primary" type="submit"><?= $editing ? 'Save lesson' : 'Create lesson' ?></button>
    <a class="btn btn-ghost" href="<?= e($base) ?>">Cancel</a>
  </div>
</form>

<?php if ($editing): ?>
  <div class="panel mt-4">
    <div class="panel-head"><h2>Resources</h2></div>
    <div class="panel-body">
      <?php if ($resources): ?>
        <table class="tbl" style="margin-bottom:1.2rem">
          <tbody>
            <?php foreach ($resources as $r): ?>
              <tr>
                <td style="width:90px"><span class="res-tag"><?= e($typeLabel[$r['type']] ?? 'Link') ?></span></td>
                <td><strong><?= e($r['label']) ?></strong><div class="muted" style="font-size:.82rem"><?= e($r['url']) ?></div></td>
                <td style="text-align:right">
                  <form class="inline-form" method="post" action="<?= e($base . '/' . $lesson['id'] . '/resources/' . $r['id'] . '/delete') ?>" onsubmit="return confirm('Remove this resource?')">
                    <?= csrf_field() ?><button class="btn btn-danger btn-sm">Remove</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="muted" style="margin-bottom:1.2rem">No resources yet for this lesson.</p>
      <?php endif; ?>

      <form method="post" action="<?= e($base . '/' . $lesson['id'] . '/resources') ?>">
        <?= csrf_field() ?>
        <div class="form-row" style="grid-template-columns:1fr 160px">
          <div class="field" style="margin-bottom:.6rem">
            <label>Label</label>
            <input class="input" name="label" placeholder="e.g. Read: 2 Timothy 2:1-7" required>
          </div>
          <div class="field" style="margin-bottom:.6rem">
            <label>Type</label>
            <select class="select" name="type">
              <option value="scripture">Scripture</option>
              <option value="pdf">PDF</option>
              <option value="video">Video</option>
              <option value="audio">Audio</option>
              <option value="link">Link</option>
            </select>
          </div>
        </div>
        <div class="field">
          <label>URL</label>
          <input class="input" name="url" placeholder="https://…">
        </div>
        <button class="btn btn-ghost btn-sm" type="submit">+ Add resource</button>
      </form>
    </div>
  </div>
<?php else: ?>
  <div class="help-note mt-3">Save the lesson first — then you can attach resources (scriptures, PDFs, links).</div>
<?php endif; ?>
