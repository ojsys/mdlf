<?php
/* Admin page builder — compose a page from blocks. */
$title    = 'Build: ' . $page['title'];
$viewPath = $page['slug'] === 'home' ? '/' : $page['slug'];
$last     = count($blocks) - 1;
?>
<a href="<?= url('admin/pages') ?>" style="color:var(--gold-deep);text-decoration:none;font-weight:600;font-size:.9rem">← All Pages</a>

<div class="flex" style="justify-content:space-between;align-items:center;margin-top:.6rem;gap:1rem;flex-wrap:wrap">
  <div>
    <h1 style="margin:0"><?= e($page['title']) ?></h1>
    <code>/<?= e($page['slug']) ?></code>
    <span class="badge <?= e($page['status']) ?>" style="margin-left:.4rem"><?= e($page['status']) ?></span>
  </div>
  <div class="flex" style="gap:.4rem">
    <a class="btn btn-ghost" href="<?= url($viewPath) ?>" target="_blank" rel="noopener">View page ↗</a>
    <a class="btn btn-ghost" href="<?= url('admin/pages/' . $page['id'] . '/edit') ?>">Page settings</a>
  </div>
</div>

<!-- Add block -->
<form method="post" action="<?= url('admin/pages/' . $page['id'] . '/blocks') ?>" class="panel" style="margin-top:1rem">
  <?= csrf_field() ?>
  <div class="panel-body flex" style="gap:.6rem;align-items:flex-end">
    <div class="field" style="flex:1;margin:0">
      <label>Add a block</label>
      <select class="select" name="type">
        <?php foreach (block_catalog() as $type => $def): ?>
          <option value="<?= e($type) ?>"><?= e($def['icon'] . '  ' . $def['label']) ?><?= !empty($def['dynamic']) ? ' (live data)' : '' ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-primary" type="submit">+ Add block</button>
  </div>
</form>

<?php if (!$blocks): ?>
  <div class="panel"><div class="panel-body muted">No blocks yet. Add your first block above to start building this page.</div></div>
<?php endif; ?>

<?php foreach ($blocks as $idx => $block):
    $def  = block_def($block['type']);
    if (!$def) continue;
    $data = block_data($block);
?>
  <div class="panel block-edit" id="block-<?= (int)$block['id'] ?>" style="margin-top:1rem">
    <div class="panel-head flex" style="justify-content:space-between;align-items:center">
      <strong><?= e($def['icon'] . '  ' . $def['label']) ?></strong>
      <div class="flex" style="gap:.3rem;align-items:center">
        <form method="post" action="<?= url('admin/pages/' . $page['id'] . '/blocks/order') ?>" style="display:inline">
          <?= csrf_field() ?>
          <input type="hidden" name="move" value="<?= (int)$block['id'] ?>">
          <input type="hidden" name="dir" value="up">
          <button class="btn btn-ghost btn-sm" type="submit" title="Move up" <?= $idx === 0 ? 'disabled' : '' ?>>▲</button>
        </form>
        <form method="post" action="<?= url('admin/pages/' . $page['id'] . '/blocks/order') ?>" style="display:inline">
          <?= csrf_field() ?>
          <input type="hidden" name="move" value="<?= (int)$block['id'] ?>">
          <input type="hidden" name="dir" value="down">
          <button class="btn btn-ghost btn-sm" type="submit" title="Move down" <?= $idx === $last ? 'disabled' : '' ?>>▼</button>
        </form>
        <form method="post" action="<?= url('admin/blocks/' . $block['id'] . '/delete') ?>" style="display:inline"
              onsubmit="return confirm('Delete this block?');">
          <?= csrf_field() ?>
          <button class="btn btn-danger btn-sm" type="submit" title="Delete block">✕</button>
        </form>
      </div>
    </div>
    <div class="panel-body">
      <form method="post" action="<?= url('admin/blocks/' . $block['id']) ?>">
        <?= csrf_field() ?>
        <?php foreach (($def['fields'] ?? []) as $key => $field): ?>
          <?= block_field_input((int)$block['id'], $key, $field, $data[$key] ?? '') ?>
        <?php endforeach; ?>
        <?php if (!empty($def['dynamic'])): ?>
          <p class="hint muted">This block also renders live content automatically (e.g. stats, modules, objectives or stories).</p>
        <?php endif; ?>
        <button class="btn btn-primary" type="submit">Save block</button>
      </form>
    </div>
  </div>
<?php endforeach; ?>
