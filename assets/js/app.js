/* MDLF — light progressive enhancement, no dependencies. */
(function () {
  // Mobile nav
  var toggle = document.querySelector('.nav-toggle');
  var links = document.querySelector('.nav-links');
  if (toggle && links) {
    toggle.addEventListener('click', function () { links.classList.toggle('open'); });
  }

  // Auto-dismiss flash messages
  document.querySelectorAll('.flash').forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity .4s, transform .4s';
      el.style.opacity = '0';
      el.style.transform = 'translateX(20px)';
      setTimeout(function () { el.remove(); }, 400);
    }, 4500);
  });

  // Count-up stats when scrolled into view
  var counters = document.querySelectorAll('[data-count]');
  if (counters.length && 'IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target, target = el.getAttribute('data-count');
        io.unobserve(el);
        if (isNaN(parseFloat(target))) { el.textContent = target; return; }
        var end = parseFloat(target), start = 0, dur = 1100, t0 = null;
        function step(ts) {
          if (!t0) t0 = ts;
          var p = Math.min((ts - t0) / dur, 1);
          el.textContent = Math.floor(p * end).toLocaleString();
          if (p < 1) requestAnimationFrame(step); else el.textContent = end.toLocaleString();
        }
        requestAnimationFrame(step);
      });
    }, { threshold: 0.4 });
    counters.forEach(function (c) { io.observe(c); });
  }

  // Fill progress rings (--p set inline via style already; this animates from 0)
  document.querySelectorAll('.ring[data-p]').forEach(function (ring) {
    var p = parseFloat(ring.getAttribute('data-p')) || 0;
    ring.style.setProperty('--p', 0);
    requestAnimationFrame(function () {
      ring.style.transition = '--p 1s';
      ring.style.setProperty('--p', p);
    });
  });
})();
