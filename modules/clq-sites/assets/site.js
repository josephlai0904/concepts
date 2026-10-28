/* Concepts CLQ Sites — page interactions. No dependencies. */
(function () {
  var root = document.getElementById('ccq-page');
  if (!root) return;
  var still = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var area = root.dataset.area || '';
  var $ = function (s, el) { return (el || root).querySelector(s); };
  var $$ = function (s, el) { return [].slice.call((el || root).querySelectorAll(s)); };

  /* Analytics: push to dataLayer (GTM / GA4) for quote, WhatsApp, phone, form. */
  function track(ev) {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ event: 'ccq_' + ev, ccq_site: area });
  }
  root.addEventListener('click', function (e) {
    var a = e.target.closest('[data-ccq-ev]');
    if (a) track(a.getAttribute('data-ccq-ev'));
  });

  /* 1. Hero slider: 0.7s fade, 5s autoplay, arrows step and restart the timer, counter shows 01 / NN. */
  (function () {
    var slides = $$('.ccq-slide'), cur = $('.ccq-cur');
    if (slides.length < 2) return;
    var i = 0, t = null;
    function go(n) {
      i = (n + slides.length) % slides.length;
      slides.forEach(function (s, k) { s.classList.toggle('on', k === i); });
      if (cur) cur.textContent = (i + 1 < 10 ? '0' : '') + (i + 1);
    }
    function play() { if (still) return; clearInterval(t); t = setInterval(function () { go(i + 1); }, 5000); }
    var p = $('.ccq-prev'), n = $('.ccq-next');
    if (p) p.addEventListener('click', function () { go(i - 1); play(); });
    if (n) n.addEventListener('click', function () { go(i + 1); play(); });
    document.addEventListener('visibilitychange', function () { document.hidden ? clearInterval(t) : play(); });
    play();
  })();

  /* 3. Why Concepts: exactly one panel open. */
  (function () {
    var panels = $$('.ccq-panel');
    function open(p) {
      panels.forEach(function (x) {
        var on = x === p;
        x.classList.toggle('on', on);
        $('.ccq-panel-b', x).setAttribute('aria-expanded', on);
      });
    }
    function idx() { return panels.findIndex(function (x) { return x.classList.contains('on'); }); }
    panels.forEach(function (p) {
      $('.ccq-panel-b', p).addEventListener('click', function () { if (!p.classList.contains('on')) open(p); });
    });
    var wp = $('.ccq-wprev'), wn = $('.ccq-wnext');
    if (wp) wp.addEventListener('click', function () { open(panels[(idx() - 1 + panels.length) % panels.length]); });
    if (wn) wn.addEventListener('click', function () { open(panels[(idx() + 1) % panels.length]); });
  })();

  /* 4. Rent inclusion tabs: click, arrow keys, Home/End. */
  (function () {
    var tabs = $$('.ccq-tabs [role=tab]');
    function set(i, focus) {
      tabs.forEach(function (t, k) {
        var on = k === i;
        t.setAttribute('aria-selected', on);
        t.tabIndex = on ? 0 : -1;
        document.getElementById(t.getAttribute('aria-controls')).hidden = !on;
      });
      if (focus) tabs[i].focus();
      tabs[i].scrollIntoView({ block: 'nearest', inline: 'nearest' });
    }
    tabs.forEach(function (t, i) {
      t.addEventListener('click', function () { set(i); });
      t.addEventListener('keydown', function (e) {
        var n = { ArrowRight: i + 1, ArrowLeft: i - 1, Home: 0, End: tabs.length - 1 }[e.key];
        if (n === undefined) return;
        e.preventDefault();
        set((n + tabs.length) % tabs.length, true);
      });
    });
  })();

  /* 5. Surroundings: items rotate right → middle → left every 3.6s; extra items queue off-screen. */
  (function () {
    var car = $('.ccq-car');
    if (!car) return;
    var items = $$('.ccq-car-i', car), t = null, paused = false, order = items.slice();
    /* order[0] = left, order[1] = middle, order[2] = right, the rest wait off-screen.
       Each step: right -> middle, middle -> left, left -> back of the queue (with 3 items: left -> right). */
    function place() {
      order.forEach(function (it, k) {
        var p = k < 3 ? ['l', 'm', 'r'][k] : 'x';
        it.dataset.pos = p;
        it.tabIndex = p === 'x' ? -1 : 0;
      });
    }
    function step() { order.push(order.shift()); place(); }
    function back() { order.unshift(order.pop()); place(); }
    function rotate() { step(); restartBar(); }
    function toMiddle(it) {
      var guard = 0;
      while (order[1] !== it && guard++ < order.length) step();
      restartBar();
    }
    function restartBar() {
      car.classList.remove('run'); void car.offsetWidth; if (!still && !paused) car.classList.add('run');
    }
    function play() { if (still) return; clearInterval(t); t = setInterval(rotate, 3600); restartBar(); }
    function stop() { clearInterval(t); }
    var sp = $('.ccq-sprev'), sn = $('.ccq-snext');
    if (sp) sp.addEventListener('click', function () { back(); restartBar(); if (!paused) play(); });
    if (sn) sn.addEventListener('click', function () { step(); restartBar(); if (!paused) play(); });
    items.forEach(function (it) {
      it.addEventListener('click', function () { toMiddle(it); if (!paused) play(); });
      it.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toMiddle(it); } });
    });
    car.addEventListener('mouseenter', function () { paused = true; stop(); car.classList.add('paused'); });
    car.addEventListener('mouseleave', function () { paused = false; car.classList.remove('paused'); play(); });
    car.addEventListener('focusin', function () { paused = true; stop(); });
    car.addEventListener('focusout', function () { paused = false; play(); });
    document.addEventListener('visibilitychange', function () { document.hidden ? stop() : (!paused && play()); });
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (es) { es[0].isIntersecting && !paused ? play() : stop(); }).observe(car);
    } else play();
  })();

  /* 8. Map: an interactive OpenStreetMap (Leaflet). Zoom with the +/- buttons, double-click or pinch;
     the scroll wheel scrolls the page, not the map.
     Leaflet loads only when the visitor gets near the map; if it can't load, the plain embed is used. */
  (function () {
    var map = $('.ccq-map');
    if (!map || !map.dataset.lat) return;
    var la = +map.dataset.lat, ln = +map.dataset.lng, started = false;
    var LF = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/';
    function embed() {
      var d = 0.012, f = document.createElement('iframe');
      f.title = 'Map of ' + area + ' site';
      f.src = 'https://www.openstreetmap.org/export/embed.html?bbox=' + [ln - d * 2, la - d, ln + d * 2, la + d].join(',') + '&layer=mapnik&marker=' + la + ',' + ln;
      map.insertBefore(f, map.firstChild);
      map.classList.add('live');
    }
    function draw() {
      var box = document.createElement('div');
      box.className = 'ccq-lf';
      box.setAttribute('aria-label', 'Map of ' + area + ' site');
      map.insertBefore(box, map.firstChild);
      var touch = matchMedia('(pointer: coarse)').matches;
      var m = L.map(box, { center: [la, ln], zoom: 15, scrollWheelZoom: false, dragging: !touch, tap: false, zoomControl: true, attributionControl: true });
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap contributors' }).addTo(m);
      L.marker([la, ln], { icon: L.divIcon({ className: 'ccq-lf-pin', html: '<i></i>', iconSize: [26, 26], iconAnchor: [13, 30] }), keyboard: false }).addTo(m);
      m.zoomControl.setPosition('bottomright');
      map.classList.add('live');
    }
    function load() {
      if (started) return;
      started = true;
      if (window.L) return draw();
      var css = document.createElement('link');
      css.rel = 'stylesheet';
      css.href = LF + 'leaflet.min.css';
      document.head.appendChild(css);
      var js = document.createElement('script');
      js.src = LF + 'leaflet.min.js';
      js.onload = draw;
      js.onerror = embed;
      document.head.appendChild(js);
    }
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (es) { if (es[0].isIntersecting) { load(); io.disconnect(); } }, { rootMargin: '300px' });
      io.observe(map);
    } else load();
  })();

  /* 7. Enquiry form: inline errors, success replaces the form. */
  (function () {
    var form = $('.ccq-form');
    if (!form || !window.CCQ) return;
    var ok = $('.ccq-ok'), btn = $('.ccq-send', form), formErr = $('.ccq-err-form', form);
    function show(errs) {
      $$('.ccq-f [aria-invalid]', form).forEach(function (f) { f.removeAttribute('aria-invalid'); });
      $$('.ccq-err', form).forEach(function (e) { e.textContent = ''; });
      var first = null;
      Object.keys(errs).forEach(function (k) {
        if (k === 'form') { formErr.textContent = errs[k]; return; }
        var f = form.elements[k], e = document.getElementById('ccq-' + k + '-e');
        if (f) { f.setAttribute('aria-invalid', 'true'); first = first || f; }
        if (e) e.textContent = errs[k];
      });
      if (first) first.focus();
    }
    function check() {
      var e = {}, v = function (n) { return (form.elements[n].value || '').trim(); };
      if (!v('company')) e.company = 'Enter your company name.';
      if (!(+v('workers') >= 1)) e.workers = 'Enter how many workers need beds.';
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v('email'))) e.email = 'Enter an email address we can reply to.';
      return e;
    }
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      var errs = check();
      show(errs);
      if (Object.keys(errs).length) return;
      btn.disabled = true;
      var data = {};
      new FormData(form).forEach(function (v, k) { data[k] = v; });
      fetch(CCQ.endpoint, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) })
        .then(function (r) { return r.json().catch(function () { return { ok: false }; }); })
        .then(function (res) {
          btn.disabled = false;
          if (res && res.ok) {
            form.hidden = true; ok.hidden = false; ok.focus(); track('form_submit');
          } else {
            show((res && res.errors) || { form: 'Something went wrong. Please try WhatsApp or call us.' });
          }
        })
        .catch(function () { btn.disabled = false; show({ form: 'No connection. Please try again, or use WhatsApp.' }); });
    });
  })();

  /* Mobile bar: hidden while the contact block is on screen. */
  (function () {
    var bar = $('.ccq-bar'), c = $('.ccq-contact');
    if (!bar || !c || !('IntersectionObserver' in window)) return;
    new IntersectionObserver(function (es) { bar.classList.toggle('hide', es[0].isIntersecting); }, { threshold: 0.15 }).observe(c);
  })();

  /* Scroll reveals with the site's own GSAP + ScrollTrigger (loaded by the Sections plugin),
     same feel as the other pages. Without GSAP nothing is hidden; the page just doesn't animate. */
  (function () {
    if (still || !window.gsap || !window.ScrollTrigger) return;
    gsap.registerPlugin(ScrollTrigger);
    var G = [
      ['.ccq-hero-txt > *', { y: 16, st: 0.06, now: 1 }],
      ['.ccq-planfig', { y: 20, st: 0.1 }],
      ['.ccq-story > *', { y: 16, st: 0.06 }],
      ['.ccq-why-head > *', { y: 16, st: 0.08 }],
      ['.ccq-panel', { y: 20, st: 0.08 }],
      ['.ccq-inc .ccq-row', { y: 12, st: 0.04 }],
      ['.ccq-car', { y: 20 }],
      ['.ccq-explore .ccq-card', { y: 20, st: 0.08 }],
      ['.ccq-contact-in > *', { y: 20, st: 0.08 }],
      ['.ccq-faq-l > *, .ccq-acc', { y: 16, st: 0.06 }],
      ['.ccq-hub-head .ccq-wrap > *', { y: 16, st: 0.06, now: 1 }]
    ];
    G.forEach(function (g) {
      var els = $$(g[0]);
      if (!els.length) return;
      var o = g[1], v = { opacity: 0, y: o.y, x: o.x || 0, duration: o.d || 0.7, ease: 'power2.out', stagger: o.st || 0, clearProps: 'transform,opacity' };
      if (o.delay) v.delay = o.delay;
      if (!o.now) v.scrollTrigger = { trigger: els[0], start: 'top 88%', once: true };
      gsap.from(els, v);
    });
  })();

  /* FAQ: older browsers ignore <details name>; keep one open. */
  (function () {
    var ds = $$('.ccq-acc details');
    ds.forEach(function (d) { d.addEventListener('toggle', function () { if (d.open) ds.forEach(function (o) { if (o !== d) o.open = false; }); }); });
  })();
})();

/* Hub: arrows slide the property cards one card at a time. */
document.querySelectorAll('.ccq-hubx').forEach(function (s) {
	var tr = s.querySelector('.ccq-hubx-tr');
	if (!tr) { return; }
	function step(d) {
		var c = tr.querySelector('.ccq-card');
		if (!c) { return; }
		var w = c.getBoundingClientRect().width + (parseFloat(getComputedStyle(tr).columnGap) || 20);
		var end = tr.scrollWidth - tr.clientWidth - 2;
		var to = tr.scrollLeft + d * w;
		tr.scrollTo({ left: to > end + w / 2 ? 0 : (to < -w / 2 ? end : to) });
	}
	s.querySelectorAll('.ccq-hubx-pv').forEach(function (b) { b.addEventListener('click', function () { step(-1); }); });
	s.querySelectorAll('.ccq-hubx-nx').forEach(function (b) { b.addEventListener('click', function () { step(1); }); });
});
