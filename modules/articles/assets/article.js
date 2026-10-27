/* Concepts Articles: "On this page" links glide to their heading (below the fixed header),
   and the link for the section being read is highlighted. */
(function () {
  var root = document.getElementById('cga');
  if (!root) return;
  var links = [].slice.call(root.querySelectorAll('.cga-toc a, .cga-toc-m a'));
  var side = [].slice.call(root.querySelectorAll('.cga-toc a'));
  var still = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var heads = side.map(function (a) { return document.getElementById(decodeURIComponent(a.hash.slice(1))); });

  function offset() {
    var h = document.querySelector('.cgs-hd, #header');
    var bar = document.getElementById('wpadminbar');
    return (h ? h.getBoundingClientRect().height : 78) + (bar ? bar.offsetHeight : 0) + 20;
  }

  links.forEach(function (a) {
    a.addEventListener('click', function (e) {
      var t = document.getElementById(decodeURIComponent(a.hash.slice(1)));
      if (!t) return;
      e.preventDefault();
      window.scrollTo({ top: t.getBoundingClientRect().top + window.scrollY - offset(), behavior: still ? 'auto' : 'smooth' });
      history.replaceState(null, '', a.hash);
      var m = a.closest('details');
      if (m) m.open = false;
    });
  });

  /* Highlight: the last heading that has passed the line just under the header. */
  var ticking = false;
  function mark() {
    ticking = false;
    var line = offset() + 8, cur = -1;
    heads.forEach(function (h, i) { if (h && h.getBoundingClientRect().top <= line) cur = i; });
    side.forEach(function (a, i) {
      var on = i === cur;
      a.classList.toggle('on', on);
      if (on) a.setAttribute('aria-current', 'location'); else a.removeAttribute('aria-current');
    });
    var on = side[cur];
    var box = root.querySelector('.cga-side-in');
    if (on && box && (on.offsetTop < box.scrollTop || on.offsetTop > box.scrollTop + box.clientHeight - 40)) box.scrollTop = on.offsetTop - 60;
  }
  window.addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(mark); } }, { passive: true });
  mark();
})();
