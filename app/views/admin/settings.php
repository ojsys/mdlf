<?php
$heading = 'Settings'; $title = 'Settings';
$s = settings();
$fields = [
  'site_name' => 'Site name', 'site_short' => 'Short name / acronym',
  'tagline' => 'Tagline', 'founder' => 'Founder',
  'mission' => 'Mission statement', 'verse' => 'Featured verse', 'verse_ref' => 'Verse reference',
  'contact_email' => 'Contact email', 'contact_phone' => 'Contact phone', 'contact_address' => 'Address',
  'give_account_name' => 'Giving — account name', 'give_bank' => 'Giving — bank', 'give_account_number' => 'Giving — account number',
];
$long = ['mission'];
$homeFields = [
  'hero_eyebrow' => 'Hero Eyebrow',
  'hero_heading' => 'Hero Heading',
  'hero_lead' => 'Hero Lead Text',
  'hero_cta_text' => 'Hero CTA Button Text',
  'hero_cta_link' => 'Hero CTA Button Link',
  'mission_eyebrow' => 'Mission Eyebrow',
  'mission_heading' => 'Mission Heading',
  'impact_eyebrow' => 'Impact Section Eyebrow',
  'impact_heading' => 'Impact Section Heading',
  'objectives_eyebrow' => 'Objectives Section Eyebrow',
  'objectives_heading' => 'Objectives Section Heading',
  'training_eyebrow' => 'Training Feature Eyebrow',
  'training_heading' => 'Training Feature Heading',
  'training_description' => 'Training Feature Description',
  'training_button_text' => 'Training Feature Button Text',
  'modules_eyebrow' => 'Modules Preview Eyebrow',
  'modules_heading' => 'Modules Preview Heading',
  'modules_description' => 'Modules Preview Description',
  'give_cta_eyebrow' => 'Give CTA Eyebrow',
  'give_cta_heading' => 'Give CTA Heading',
  'give_cta_description' => 'Give CTA Description',
  'give_cta_button_text' => 'Give CTA Button Text',
];
?>
<form method="post" action="<?= url('admin/settings') ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="panel">
    <div class="panel-head"><h2>Branding</h2></div>
    <div class="panel-body">
      <div class="field">
        <label>Logo</label>
        <?php if (!empty($s['logo'])): ?>
          <div style="margin-bottom:.5rem"><img src="<?= upload_url($s['logo']) ?>" alt="Logo" style="max-height:80px">
          <div class="hint">
        <?php endif; ?>
        <input type="file" name="logo" accept="image/png,image/jpeg,image/gif,image/svg+xml">
      </div>
      <div class="field">
        <label>Favicon</label>
        <?php if (!empty($s['favicon'])): ?>
          <div style="margin-bottom:.5rem"><img src="<?= upload_url($s['favicon']) ?>" alt="Favicon" style="max-height:40px">
          <div class="hint">
        <?php endif; ?>
        <input type="file" name="favicon" accept="image/x-icon,image/png">
      </div>
      <div class="form-row" style="grid-template-columns:2fr 1fr">
        <div class="field">
          <label>Header subtitle <span class="hint">— shown under the site name</span></label>
          <input class="input" name="settings[brand_subtitle]" value="<?= e($s['brand_subtitle'] ?? 'Leadership Foundation') ?>">
        </div>
        <div class="field">
          <label>Show subtitle</label>
          <select class="select" name="settings[brand_subtitle_visible]">
            <option value="yes" <?= ($s['brand_subtitle_visible'] ?? 'yes') !== 'no' ? 'selected' : '' ?>>Shown</option>
            <option value="no"  <?= ($s['brand_subtitle_visible'] ?? 'yes') === 'no' ? 'selected' : '' ?>>Hidden</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head"><h2>Site settings</h2></div>
    <div class="panel-body">
      <?php foreach ($fields as $key => $label): ?>
        <div class="field">
          <label><?= e($label) ?></label>
          <?php if (in_array($key, $long, true)): ?>
            <textarea class="textarea" name="settings[<?= e($key) ?>]"><?= e($s[$key] ?? '') ?></textarea>
          <?php else: ?>
            <input class="input" name="settings[<?= e($key) ?>]" value="<?= e($s[$key] ?? '') ?>">
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head"><h2>Home Page Sections</h2></div>
    <div class="panel-body">
      <?php foreach ($homeFields as $key => $label): ?>
        <div class="field">
          <label><?= e($label) ?></label>
          <?php if (in_array($key, ['hero_lead', 'training_description', 'modules_description', 'give_cta_description'])): ?>
            <textarea class="textarea" name="settings[<?= e($key) ?>]" style="min-height:100px"><?= e($s[$key] ?? '') ?></textarea>
          <?php else: ?>
            <input class="input" name="settings[<?= e($key) ?>]" value="<?= e($s[$key] ?? '') ?>">
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head"><h2>Impact statistics <span class="hint" style="font-weight:400; color:var(--ink-55)">— shown on the home page</span></h2></div>
    <div class="panel-body">
      <?php foreach ($stats as $st): ?>
        <div class="form-row" style="grid-template-columns:2fr 1fr 1fr; margin-bottom:.4rem">
          <div class="field" style="margin-bottom:.6rem">
            <label>Label</label>
            <input class="input" name="stats[<?= (int)$st['id'] ?>][label]" value="<?= e($st['label']) ?>">
          </div>
          <div class="field" style="margin-bottom:.6rem">
            <label>Value</label>
            <input class="input" name="stats[<?= (int)$st['id'] ?>][value]" value="<?= e($st['value']) ?>">
          </div>
          <div class="field" style="margin-bottom:.6rem">
            <label>Suffix</label>
            <input class="input" name="stats[<?= (int)$st['id'] ?>][suffix]" value="<?= e($st['suffix']) ?>" placeholder="+ , %">
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <button class="btn btn-primary" type="submit">Save settings</button>
</form>
