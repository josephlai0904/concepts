/* CLQ site editor: media pickers, repeaters, map pin. */
jQuery(function ($) {
  /* Images and galleries: hidden input holds IDs (or URLs) in thumbnail order. */
  function sync($m) {
    $m.find('input[type=hidden]').val($m.find('.ccq-thumbs li').map(function () { return $(this).data('v'); }).get().join(','));
  }
  $('.ccq-media .ccq-thumbs').sortable({ update: function () { sync($(this).closest('.ccq-media')); } });
  $(document).on('click', '.ccq-media .ccq-x', function () {
    var $m = $(this).closest('.ccq-media');
    $(this).closest('li').remove();
    sync($m);
  });
  $(document).on('click', '.ccq-media .ccq-pick', function (e) {
    e.preventDefault();
    var $m = $(this).closest('.ccq-media'), multi = $m.data('multiple') === 1;
    var frame = wp.media({ title: multi ? 'Add images' : 'Choose image', multiple: multi ? 'add' : false, library: { type: 'image' } });
    frame.on('select', function () {
      var $list = $m.find('.ccq-thumbs');
      if (!multi) $list.empty();
      frame.state().get('selection').toJSON().forEach(function (a) {
        if (multi && $list.find('li[data-v="' + a.id + '"]').length) return;
        var src = a.sizes && a.sizes.thumbnail ? a.sizes.thumbnail.url : a.url;
        $('<li>').attr('data-v', a.id).data('v', a.id)
          .append($('<img alt="">').attr('src', src))
          .append('<button type="button" class="ccq-x" aria-label="Remove image">×</button>')
          .appendTo($list);
      });
      sync($m);
    });
    frame.open();
  });

  /* Repeaters: add, remove, drag to reorder. Row order in the page = order here. */
  $('.ccq-rrows').sortable({ handle: '.ccq-drag', axis: 'y' });
  $(document).on('click', '.ccq-rep .ccq-add', function () {
    var $r = $(this).closest('.ccq-rep'), i = 'n' + Date.now();
    var html = $r.find('.ccq-tpl').html().replace(/__i__/g, i);
    $(html).appendTo($r.find('.ccq-rrows')).find('input,select,textarea').first().trigger('focus');
  });
  $(document).on('click', '.ccq-rep .ccq-del', function () {
    $(this).closest('.ccq-rrow').remove();
  });

  /* Map pin: click to place, drag to adjust; typing coordinates moves the pin. */
  var el = document.getElementById('ccq-map-pick');
  if (el && window.L) {
    var $lat = $('#ccq-lat'), $lng = $('#ccq-lng');
    var has = $lat.val() !== '' && $lng.val() !== '';
    var start = has ? [+$lat.val(), +$lng.val()] : [3.04, 101.5];
    var map = L.map(el).setView(start, has ? 15 : 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
    var pin = null;
    function put(ll) {
      if (!pin) {
        pin = L.marker(ll, { draggable: true }).addTo(map);
        pin.on('dragend', function () { set(pin.getLatLng()); });
      } else pin.setLatLng(ll);
    }
    function set(ll) {
      $lat.val(ll.lat.toFixed(6));
      $lng.val(ll.lng.toFixed(6));
    }
    if (has) put(start);
    map.on('click', function (e) { put(e.latlng); set(e.latlng); });
    $lat.add($lng).on('change', function () {
      var a = parseFloat($lat.val()), b = parseFloat($lng.val());
      if (!isNaN(a) && !isNaN(b)) { put([a, b]); map.setView([a, b], 15); }
    });
    /* The group may start collapsed; redraw tiles when it opens. */
    $(el).closest('details').on('toggle', function () { map.invalidateSize(); });
  }
});
