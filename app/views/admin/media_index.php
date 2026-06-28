<?php
/* Admin Media Library — upload + browse all media (images, audio, video). */
$title = 'Media';
$icon = ['image' => '🖼', 'audio' => '♪', 'video' => '🎬', 'file' => '📄'];
?>
<div class="flex" style="justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap">
  <h1 style="margin:0">Media Library</h1>
  <form method="post" action="<?= url('admin/media/upload') ?>" enctype="multipart/form-data"
        class="flex" style="gap:.5rem;align-items:center">
    <?= csrf_field() ?>
    <input type="file" name="file" accept="image/*,audio/*,video/*" required
           style="font-size:.85rem"
           onchange="this.form.querySelector('button').disabled=!this.files.length">
    <button class="btn btn-primary btn-sm" type="submit">Upload</button>
  </form>
</div>
<p class="muted" style="margin:.4rem 0 1rem;font-size:.9rem">Images, audio and video. Uploaded files live in <code>storage/uploads/</code>; theme images from <code>assets/img/</code> appear here too (read-only).</p>

<?php if (!$media): ?>
  <div class="panel"><div class="panel-body muted">No media yet. Upload an image, audio or video file above.</div></div>
<?php else: ?>
<div class="media-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem">
  <?php foreach ($media as $m): ?>
    <div class="panel" style="overflow:hidden">
      <div style="aspect-ratio:4/3;background:#0E1B33;display:flex;align-items:center;justify-content:center;overflow:hidden">
        <?php if ($m['type'] === 'image'): ?>
          <img src="<?= e($m['url']) ?>" alt="<?= e($m['name']) ?>" style="width:100%;height:100%;object-fit:cover">
        <?php elseif ($m['type'] === 'video'): ?>
          <video src="<?= e($m['url']) ?>" style="width:100%;height:100%;object-fit:cover" muted></video>
        <?php else: ?>
          <span style="font-size:2.4rem"><?= $icon[$m['type']] ?? '📄' ?></span>
        <?php endif; ?>
      </div>
      <div class="panel-body" style="padding:.6rem .7rem">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:.4rem">
          <span class="badge"><?= e($m['type']) ?></span>
          <?php if ($m['system']): ?><span class="muted" style="font-size:.72rem">theme</span><?php endif; ?>
        </div>
        <div style="font-size:.78rem;margin:.35rem 0;word-break:break-all" title="<?= e($m['name']) ?>"><?= e($m['name']) ?></div>
        <?php if ($m['type'] === 'audio'): ?>
          <audio src="<?= e($m['url']) ?>" controls style="width:100%;height:32px"></audio>
        <?php endif; ?>
        <div class="flex" style="gap:.3rem;margin-top:.4rem">
          <button type="button" class="btn btn-ghost btn-sm" style="font-size:.72rem"
                  onclick="navigator.clipboard.writeText('<?= e($m['path']) ?>');this.textContent='Copied!'">Copy path</button>
          <?php if (!$m['system']): ?>
            <form method="post" action="<?= url('admin/media/' . $m['id'] . '/delete') ?>" style="display:inline"
                  onsubmit="return confirm('Delete this media file?');">
              <?= csrf_field() ?>
              <button class="btn btn-danger btn-sm" type="submit" style="font-size:.72rem">Delete</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
