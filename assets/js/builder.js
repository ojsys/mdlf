/* =====================================================================
 *  MDLF live page builder (edit mode only).
 *  Powers the on-page editor: edit a block's fields in a drawer, add /
 *  delete / reorder blocks. Field saves re-render the block in place;
 *  reorders reload (cheap + always correct).
 * ===================================================================== */
(function () {
  if (!window.MDLF) return;
  var M = window.MDLF;
  var canvas = document.querySelector('.mdlf-canvas');
  if (!canvas) return;

  /* ---- tiny fetch helpers (CSRF + AJAX header) ---- */
  function post(url, data) {
    var body = new URLSearchParams();
    body.set('_csrf', M.csrf);
    Object.keys(data || {}).forEach(function (k) {
      var v = data[k];
      if (Array.isArray(v)) v.forEach(function (x) { body.append(k + '[]', x); });
      else body.set(k, v);
    });
    return fetch(url, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body
    }).then(function (r) { return r.json(); });
  }
  function getJSON(url) {
    return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(function (r) { return r.json(); });
  }

  /* ---- save-state pill in the admin bar ---- */
  var pill = document.getElementById('mdlf-savestate');
  function state(text, s) { if (pill) { pill.textContent = text; pill.dataset.state = s || ''; } }

  /* ---- event delegation on the canvas ---- */
  canvas.addEventListener('click', function (e) {
    var add = e.target.closest('.mdlf-addbtn');
    if (add) { e.preventDefault(); openAddMenu(add); return; }
    var ab = e.target.closest('.mdlf-ab');
    if (!ab) return;
    e.preventDefault();
    var block = ab.closest('.mdlf-block');
    var id = block.getAttribute('data-block-id');
    var act = ab.getAttribute('data-act');
    if (act === 'edit') openDrawer(block, id);
    else if (act === 'delete') deleteBlock(block, id);
    else if (act === 'up' || act === 'down') moveBlock(id, act);
  });

  /* ---- move (reload after, to keep order + add-zones correct) ---- */
  function moveBlock(id, dir) {
    state('Moving…', 'busy');
    post(M.base + '/admin/pages/' + M.pageId + '/blocks/order', { move: id, dir: dir })
      .then(function () { location.reload(); })
      .catch(function () { state('Error', 'err'); });
  }

  /* ---- delete (remove node + the following add-zone) ---- */
  function deleteBlock(block, id) {
    if (!confirm('Delete this block?')) return;
    state('Deleting…', 'busy');
    post(M.base + '/admin/blocks/' + id + '/delete', {}).then(function (r) {
      if (r && r.ok) {
        var next = block.nextElementSibling;
        if (next && next.classList.contains('mdlf-addzone')) next.remove();
        block.remove();
        state('Saved', 'ok');
      } else state('Error', 'err');
    }).catch(function () { state('Error', 'err'); });
  }

  /* ---- add block menu ---- */
  var openMenu = null;
  function closeMenu() { if (openMenu) { openMenu.remove(); openMenu = null; } }
  document.addEventListener('click', function (e) {
    if (openMenu && !e.target.closest('.mdlf-menu') && !e.target.closest('.mdlf-addbtn')) closeMenu();
  });
  function openAddMenu(btn) {
    closeMenu();
    var zone = btn.closest('.mdlf-addzone');
    var after = zone.getAttribute('data-after');
    var menu = document.createElement('div');
    menu.className = 'mdlf-menu';
    Object.keys(M.types).forEach(function (type) {
      var def = M.types[type];
      var b = document.createElement('button');
      b.type = 'button';
      b.innerHTML = '<span class="ic">' + def.icon + '</span><span>' + def.label + '</span>';
      b.addEventListener('click', function () { addBlock(type, after, zone); });
      menu.appendChild(b);
    });
    zone.appendChild(menu);
    openMenu = menu;
  }
  function addBlock(type, after, zone) {
    state('Adding…', 'busy');
    closeMenu();
    post(M.base + '/admin/pages/' + M.pageId + '/blocks', { type: type, after: after })
      .then(function (r) {
        if (!r || !r.ok) { state('Error', 'err'); return; }
        var tmp = document.createElement('div');
        tmp.innerHTML = r.html.trim();
        var newBlock = tmp.firstElementChild;
        var newZone = document.createElement('div');
        newZone.className = 'mdlf-addzone';
        newZone.setAttribute('data-after', r.id);
        newZone.innerHTML = '<button type="button" class="mdlf-addbtn">+ Add block</button>';
        zone.after(newBlock, newZone);
        state('Saved', 'ok');
        openDrawer(newBlock, String(r.id)); // jump straight into editing it
      }).catch(function () { state('Error', 'err'); });
  }

  /* ---- edit drawer ---- */
  var drawer, overlay, drawerBody, drawerTitle, currentBlock, currentId, quills = [];
  function buildDrawer() {
    overlay = document.createElement('div');
    overlay.className = 'mdlf-overlay';
    overlay.addEventListener('click', closeDrawer);
    drawer = document.createElement('div');
    drawer.className = 'mdlf-drawer';
    drawer.innerHTML =
      '<div class="mdlf-drawer-head"><h3></h3><button class="mdlf-drawer-close" type="button">×</button></div>' +
      '<div class="mdlf-drawer-body"></div>' +
      '<div class="mdlf-drawer-foot"><button class="mdlf-save" type="button">Save block</button>' +
      '<button class="mdlf-cancel" type="button">Cancel</button></div>';
    document.body.appendChild(overlay);
    document.body.appendChild(drawer);
    drawerBody = drawer.querySelector('.mdlf-drawer-body');
    drawerTitle = drawer.querySelector('.mdlf-drawer-head h3');
    drawer.querySelector('.mdlf-drawer-close').addEventListener('click', closeDrawer);
    drawer.querySelector('.mdlf-cancel').addEventListener('click', closeDrawer);
    drawer.querySelector('.mdlf-save').addEventListener('click', saveDrawer);
  }
  function openDrawer(block, id) {
    if (!drawer) buildDrawer();
    currentBlock = block; currentId = id; quills = [];
    drawerBody.innerHTML = '<p class="hint">Loading…</p>';
    overlay.classList.add('open'); drawer.classList.add('open');
    getJSON(M.base + '/admin/blocks/' + id + '/form').then(function (r) {
      if (!r || !r.ok) { drawerBody.innerHTML = '<p class="hint">Could not load this block.</p>'; return; }
      drawerTitle.textContent = 'Edit · ' + r.label;
      drawerBody.innerHTML = r.html + (r.dynamic
        ? '<p class="hint">This block also shows live content (stats, modules, objectives or stories) automatically.</p>' : '');
      // mount Quill on any rich-text fields
      drawerBody.querySelectorAll('.quill-editor').forEach(function (el) {
        var input = document.getElementById(el.getAttribute('data-input-id'));
        var q = new Quill(el, {
          theme: 'snow',
          modules: { toolbar: [['bold', 'italic', 'underline'], [{ header: [2, 3, false] }], ['link'], [{ list: 'ordered' }, { list: 'bullet' }], ['clean']] }
        });
        if (input) { q.root.innerHTML = input.value; q.on('text-change', function () { input.value = q.root.innerHTML; }); }
        quills.push(q);
      });
      if (window.MDLFMedia) window.MDLFMedia.enhance(drawerBody);
    });
  }
  function closeDrawer() {
    if (!drawer) return;
    overlay.classList.remove('open'); drawer.classList.remove('open');
  }
  function saveDrawer() {
    quills.forEach(function (q) { /* values already synced via text-change */ });
    var data = {};
    drawerBody.querySelectorAll('input[name], textarea[name], select[name]').forEach(function (el) {
      data[el.getAttribute('name')] = el.value;
    });
    state('Saving…', 'busy');
    post(M.base + '/admin/blocks/' + currentId, data).then(function (r) {
      if (r && r.ok) {
        var body = currentBlock.querySelector('.mdlf-block-body');
        if (body) body.innerHTML = r.html;
        state('Saved', 'ok');
        closeDrawer();
      } else state('Error', 'err');
    }).catch(function () { state('Error', 'err'); });
  }
})();
