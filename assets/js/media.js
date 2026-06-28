/* =====================================================================
 *  MDLF media picker + visual gallery editor.
 *  Enhances:
 *    input[data-media="image|video|audio"]  → single media picker
 *    .mdlf-gallery (backed by a hidden JSON input)  → visual gallery
 *  Exposes window.MDLFMedia.enhance(scope) for dynamically injected forms
 *  (the live-editor drawer). Reads { base, csrf } from window.MDLF.
 * ===================================================================== */
(function () {
  function cfg() { return window.MDLF || { base: '', csrf: '' }; }

  function mediaUrl(p) {
    if (!p) return '';
    if (/^https?:\/\//.test(p)) return p;
    if (p.indexOf('uploads/') === 0) return cfg().base + '/storage/' + p;
    return cfg().base + '/assets/' + p.replace(/^\//, '');
  }
  function isImg(t) { return t === 'image'; }

  /* ---------------- shared picker modal ---------------- */
  var modal, grid, fileInput, titleEl, pickerCb, pickerType;
  function build() {
    modal = document.createElement('div');
    modal.className = 'mdlf-mp-overlay';
    modal.innerHTML =
      '<div class="mdlf-mp"><div class="mdlf-mp-head">' +
      '<strong class="mdlf-mp-title">Media</strong>' +
      '<label class="mdlf-mp-upload">Upload<input type="file" hidden></label>' +
      '<button type="button" class="mdlf-mp-done">Done</button>' +
      '<button type="button" class="mdlf-mp-x" aria-label="Close">×</button>' +
      '</div><div class="mdlf-mp-grid"></div></div>';
    document.body.appendChild(modal);
    grid = modal.querySelector('.mdlf-mp-grid');
    fileInput = modal.querySelector('input[type=file]');
    titleEl = modal.querySelector('.mdlf-mp-title');
    modal.addEventListener('click', function (e) {
      if (e.target === modal || e.target.closest('.mdlf-mp-x') || e.target.closest('.mdlf-mp-done')) close();
    });
    fileInput.addEventListener('change', function () { if (fileInput.files[0]) upload(fileInput.files[0]); });
  }
  function open(opts) {
    if (!modal) build();
    pickerCb = opts.onPick; pickerType = opts.type || 'image';
    titleEl.textContent = 'Media · ' + pickerType + (opts.multi ? 's' : '');
    modal.querySelector('.mdlf-mp-done').style.display = opts.multi ? '' : 'none';
    fileInput.accept = pickerType + '/*';
    fileInput.value = '';
    modal.classList.add('open');
    load();
  }
  function close() { if (modal) modal.classList.remove('open'); }
  function load() {
    grid.innerHTML = '<p class="mdlf-mp-hint">Loading…</p>';
    fetch(cfg().base + '/admin/media/list?type=' + encodeURIComponent(pickerType), {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(function (r) { return r.json(); }).then(function (r) {
      grid.innerHTML = '';
      var items = r.items || [];
      if (!items.length) { grid.innerHTML = '<p class="mdlf-mp-hint">No ' + pickerType + ' yet — use Upload above.</p>'; return; }
      items.forEach(function (it) {
        var b = document.createElement('button');
        b.type = 'button'; b.className = 'mdlf-mp-item'; b.title = it.name;
        if (it.type === 'image') b.style.backgroundImage = 'url("' + it.url + '")';
        else b.innerHTML = '<span>' + (it.type === 'video' ? '🎬' : '♪') + '</span><small>' + it.name + '</small>';
        b.addEventListener('click', function () { if (pickerCb) pickerCb(it); });
        grid.appendChild(b);
      });
    });
  }
  function upload(file) {
    var fd = new FormData();
    fd.append('file', file); fd.set('_csrf', cfg().csrf);
    grid.innerHTML = '<p class="mdlf-mp-hint">Uploading…</p>';
    fetch(cfg().base + '/admin/media/upload', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd })
      .then(function (r) { return r.json(); })
      .then(function (r) { if (r && r.ok) { if (pickerCb) pickerCb(r); else load(); } else load(); })
      .catch(load);
  }

  /* ---------------- single media field ---------------- */
  function enhanceSingle(input) {
    if (input.dataset.enhanced) return;
    input.dataset.enhanced = '1';
    var type = input.getAttribute('data-media') || 'image';
    input.style.display = 'none';
    var ui = document.createElement('div');
    ui.className = 'mdlf-media-field';
    var thumb = document.createElement('div');
    thumb.className = 'mdlf-media-thumb';
    function paint() {
      var v = input.value;
      if (v && type === 'image') { thumb.style.backgroundImage = 'url("' + mediaUrl(v) + '")'; thumb.textContent = ''; }
      else { thumb.style.backgroundImage = ''; thumb.textContent = v ? (type === 'video' ? '🎬' : '♪') : '—'; }
    }
    var pick = document.createElement('button');
    pick.type = 'button'; pick.className = 'mdlf-btn-pick'; pick.textContent = 'Choose / Upload';
    pick.addEventListener('click', function () {
      open({ type: type, multi: false, onPick: function (it) { input.value = it.path; paint(); close(); } });
    });
    var clear = document.createElement('button');
    clear.type = 'button'; clear.className = 'mdlf-btn-clear'; clear.textContent = 'Clear';
    clear.addEventListener('click', function () { input.value = ''; paint(); });
    ui.appendChild(thumb); ui.appendChild(pick); ui.appendChild(clear);
    input.parentNode.insertBefore(ui, input.nextSibling);
    paint();
  }

  /* ---------------- gallery editor ---------------- */
  function enhanceGallery(box) {
    if (box.dataset.enhanced) return;
    box.dataset.enhanced = '1';
    var input = document.getElementById(box.getAttribute('data-input'));
    var items = [];
    try { var p = JSON.parse(input.value || '[]'); if (Array.isArray(p)) items = p; } catch (e) {}

    function sync() { input.value = JSON.stringify(items); render(); }
    function render() {
      box.innerHTML = '';
      items.forEach(function (it, i) {
        var card = document.createElement('div');
        card.className = 'mdlf-gal-item';
        var th = document.createElement('div');
        th.className = 'mdlf-gal-thumb';
        th.style.backgroundImage = 'url("' + mediaUrl(it.image || '') + '")';
        var cap = document.createElement('input');
        cap.className = 'input mdlf-gal-cap'; cap.placeholder = 'Caption'; cap.value = it.caption || '';
        cap.addEventListener('input', function () { items[i].caption = cap.value; input.value = JSON.stringify(items); });
        var ctr = document.createElement('div'); ctr.className = 'mdlf-gal-ctrls';
        ctr.innerHTML = '<button type="button" data-a="up">▲</button><button type="button" data-a="down">▼</button><button type="button" data-a="del">✕</button>';
        ctr.addEventListener('click', function (e) {
          var a = e.target.getAttribute('data-a'); if (!a) return;
          if (a === 'del') items.splice(i, 1);
          else if (a === 'up' && i > 0) { var t = items[i - 1]; items[i - 1] = items[i]; items[i] = t; }
          else if (a === 'down' && i < items.length - 1) { var u = items[i + 1]; items[i + 1] = items[i]; items[i] = u; }
          sync();
        });
        card.appendChild(th); card.appendChild(cap); card.appendChild(ctr);
        box.appendChild(card);
      });
      var add = document.createElement('button');
      add.type = 'button'; add.className = 'mdlf-gal-add'; add.textContent = '+ Add images';
      add.addEventListener('click', function () {
        open({ type: 'image', multi: true, onPick: function (it) { items.push({ image: it.path, caption: '' }); sync(); } });
      });
      box.appendChild(add);
    }
    render();
  }

  function enhance(scope) {
    scope = scope || document;
    scope.querySelectorAll('input[data-media]').forEach(enhanceSingle);
    scope.querySelectorAll('.mdlf-gallery').forEach(enhanceGallery);
  }

  window.MDLFMedia = { enhance: enhance, url: mediaUrl };
  if (document.readyState !== 'loading') enhance(document);
  else document.addEventListener('DOMContentLoaded', function () { enhance(document); });
})();
