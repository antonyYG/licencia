// Estadísticas interactivas para Chilca
(function(){
  var map, marker, circle, pieChart;
  var storesLayer = null;
  var currentRadius = 100;
  var currentCenter = null;
  var centerChilca = { lat: -12.08673, lng: -75.20785 };
  var cacheTTLms = 5 * 60 * 1000; // 5 minutos
  // Últimos datos para exportación
  var lastTiendas = [];
  var lastCoords = [];
  var lastCounts = { con: 0, sin: 0, total: 0, radius_m: 0, center: null };
  var exportLock = false;

  function initMap(){
    if (typeof L === 'undefined') return;
    // Evita error de inicialización doble del contenedor del mapa
    var container = L.DomUtil.get('estad-map');
    if (container){ container._leaflet_id = null; }
    map = L.map('estad-map').setView([centerChilca.lat, centerChilca.lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    L.control.scale().addTo(map);
    storesLayer = L.layerGroup().addTo(map);
  }

  function setSearchResult(lat, lng){
    if (!map) return;
    if (marker) { map.removeLayer(marker); }
    if (circle) { map.removeLayer(circle); }
    currentCenter = { lat: lat, lng: lng };
    marker = L.marker([lat, lng]).addTo(map);
    circle = L.circle([lat, lng], { radius: currentRadius, color: '#1976d2', fillColor: '#90caf9', fillOpacity: 0.25 }).addTo(map);
    map.setView([lat, lng], 16);
    fetchStats(lat, lng, currentRadius);
  }

  function fetchStats(lat, lng, radius){
    var url = '../controller/estadisticas.php?lat='+encodeURIComponent(lat)+'&lng='+encodeURIComponent(lng)+'&radius='+encodeURIComponent(radius);
    fetch(url).then(function(r){ return r.text(); }).then(function(text){
      var data;
      try { data = JSON.parse(text); }
      catch(e){ console.error('Respuesta no JSON:', text); showError('Error al obtener estadísticas: ' + text.slice(0, 160)); return; }
      if (data.error){ showError(data.error); return; }
      // 1) Renderizar coordenadas válidas primero
      var coords = Array.isArray(data.coords) ? data.coords : (Array.isArray(data.tiendas) ? data.tiendas.map(function(t){
        return { id: t.id_tienda || t.id || null, lat: parseFloat(t.latitud), lng: parseFloat(t.longitud), dist_km: t.dist_km };
      }) : []);
      renderCoords(coords, radius);

      // 2) Derivar las métricas y el gráfico desde la lista filtrada
      var tiendas = Array.isArray(data.tiendas) ? data.tiendas : [];
      renderPieFromList(tiendas);
      renderSummaryFromList(tiendas, radius);
      // 3) Mantener la lista y marcadores de tiendas dentro del rango
      renderStores(tiendas, radius);

      // Guardar últimos datos para exportación
      lastTiendas = tiendas;
      lastCoords = coords;
      lastCounts = computeCounts(tiendas);
      lastCounts.radius_m = radius;
      lastCounts.center = { lat: lat, lng: lng };
    }).catch(function(err){ console.error(err); showError('Error al obtener estadísticas.'); });
  }

  function computeCounts(list){
    var con = 0, sin = 0;
    list.forEach(function(t){
      var lic = parseInt(t.condicion) === 1;
      if (lic) con++; else sin++;
    });
    return { con: con, sin: sin, total: con + sin };
  }

  function renderPieFromList(list){
    var canvas = document.getElementById('estad-pie');
    if (!canvas) { console.warn('Canvas estad-pie no encontrado'); return; }
    var ctx = canvas.getContext('2d');
    if (!ctx) { console.warn('Contexto 2D no disponible para estad-pie'); return; }
    var counts = computeCounts(list);
    var dataset = {
      labels: ['Con licencia', 'Sin licencia'],
      datasets: [{
        data: [counts.con, counts.sin],
        backgroundColor: ['#2e7d32', '#c62828'],
        borderColor: ['#1b5e20', '#8e0000'],
        borderWidth: 1
      }]
    };
    var options = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'top' }, tooltip: { enabled: true } },
      animation: { duration: 800 }
    };
    // Siempre destruir y recrear para evitar estados inconsistentes
    try {
      if (pieChart && typeof pieChart.destroy === 'function') { pieChart.destroy(); }
      var existing = (typeof Chart !== 'undefined' && Chart.getChart) ? Chart.getChart(canvas) : null;
      if (existing) { existing.destroy(); }
    } catch(e) { console.warn('No se pudo destruir gráfico previo:', e); }
    pieChart = new Chart(ctx, { type: 'pie', data: dataset, options: options });
  }

  function renderSummaryFromList(list, radius){
    var el = document.getElementById('estad-summary');
    if (!el) return;
    var counts = computeCounts(list);
    var pct = counts.total > 0 ? Math.round((counts.con / counts.total) * 100) : 0;
    var radiusTxt = (typeof radius !== 'undefined') ? radius : currentRadius;
    if (counts.total === 0){
      el.innerHTML = '<div style="margin-top:8px; color:#555;">No se encontraron tiendas dentro de '+radiusTxt+' m.</div>';
      return;
    }
    el.innerHTML = ''+
      '<div style="margin-top:8px">'+
      '<b>Total tiendas:</b> '+counts.total+'<br/>'+ 
      '<b>Con licencia:</b> '+counts.con+'<br/>'+ 
      '<b>Sin licencia:</b> '+counts.sin+'<br/>'+ 
      '<b>Cumplimiento:</b> '+pct+'%'+
      '</div>';
  }

  function renderCoords(coords, radius){
    var box = document.getElementById('estad-coords-list');
    if (!box) return;
    var radiusTxt = (typeof radius !== 'undefined') ? radius : currentRadius;
    box.innerHTML = '';
    if (!coords || coords.length === 0){
      box.innerHTML = '<div style="margin-top:6px; color:#666;">No hay coordenadas dentro de '+radiusTxt+' m.</div>';
      return;
    }
    // Ordenar por distancia ascendente si está disponible
    coords.sort(function(a,b){
      var da = isFinite(parseFloat(a.dist_km)) ? parseFloat(a.dist_km) : Infinity;
      var db = isFinite(parseFloat(b.dist_km)) ? parseFloat(b.dist_km) : Infinity;
      return da - db;
    });
    var html = '<div style="margin-top:6px;">'+
      '<b>Coordenadas en '+radiusTxt+' m:</b></div>';
    html += '<ul style="list-style:none; padding-left:0; margin-top:6px; font-family: monospace;">';
    coords.forEach(function(c){
      var dKm = (typeof c.dist_km !== 'undefined') ? parseFloat(c.dist_km) : NaN;
      var dM = isFinite(dKm) ? Math.round(dKm * 1000) : null;
      var lat = (typeof c.lat !== 'undefined') ? parseFloat(c.lat) : parseFloat(c.latitud);
      var lng = (typeof c.lng !== 'undefined') ? parseFloat(c.lng) : parseFloat(c.longitud);
      html += '<li>('+lat.toFixed(6)+', '+lng.toFixed(6)+')' + (dM !== null ? ' — '+dM+' m' : '') + '</li>';
    });
    html += '</ul>';
    box.innerHTML = html;
  }

  // Construye y descarga CSV con BOM UTF-8, encabezados y datos del gráfico
  function exportCSV(){
    if (exportLock) { return; }
    exportLock = true;
    var btn = document.getElementById('estad-export');
    if (btn) { btn.disabled = true; }
    try {
      var bom = '\ufeff'; // BOM para Excel
      var lines = [];
      // Resumen del gráfico
      lines.push('Resumen');
      lines.push('Centro Lat,Centro Lng,Radio (m),Total,Con Licencia,Sin Licencia,Cumplimiento (%)');
      var pct = lastCounts.total > 0 ? Math.round((lastCounts.con / lastCounts.total) * 100) : 0;
      var centerLat = (lastCounts.center && lastCounts.center.lat) ? lastCounts.center.lat : '';
      var centerLng = (lastCounts.center && lastCounts.center.lng) ? lastCounts.center.lng : '';
      lines.push([centerLat, centerLng, lastCounts.radius_m || currentRadius, lastCounts.total, lastCounts.con, lastCounts.sin, pct].join(','));
      lines.push('');
      // Sección Tiendas
      lines.push('Tiendas');
      lines.push('ID,Latitud,Longitud,Distancia (m),Licencia,Condicion,Nombre Comercial,Propietario,Ubicacion');
      var tiendasFuente = (lastTiendas && lastTiendas.length > 0) ? lastTiendas : (lastCoords || []);
      tiendasFuente.forEach(function(t){
        var id = t.idtienda != null ? t.idtienda : '';
        var lat = isFinite(parseFloat(t.latitud)) ? parseFloat(t.latitud).toFixed(6) : '';
        var lng = isFinite(parseFloat(t.longitud)) ? parseFloat(t.longitud).toFixed(6) : '';
        var dKm = (typeof t.dist_km !== 'undefined') ? parseFloat(t.dist_km) : NaN;
        var dM = isFinite(dKm) ? Math.round(dKm * 1000) : '';
        var hasCond = (typeof t.condicion !== 'undefined' && t.condicion !== null);
        var lic = hasCond ? (parseInt(t.condicion) === 1 ? 'Con licencia' : 'Sin licencia') : '';
        var cond = hasCond ? (parseInt(t.condicion) === 1 ? 1 : 0) : '';
        function esc(v){ return String(v || '').replace(/\n/g,' ').replace(/\r/g,' ').replace(/"/g,'""'); }
        function q(v){ return '"'+esc(v)+'"'; }
        lines.push([id, lat, lng, dM, q(lic), cond, q(t.nombre_comercial), q(t.nombres_per), q(t.ubic_tienda)].join(','));
      });
      if (!tiendasFuente || tiendasFuente.length === 0){
        lines.push('No hay tiendas dentro del radio seleccionado');
      }
      lines.push('');
      // Sección Coordenadas
      lines.push('Coordenadas');
      lines.push('ID,Latitud,Longitud,Distancia (m)');
      lastCoords.forEach(function(c){
        var id = (c.idtienda != null) ? c.idtienda : (c.id || '');
        var lat = (typeof c.lat !== 'undefined') ? parseFloat(c.lat) : parseFloat(c.latitud);
        var lng = (typeof c.lng !== 'undefined') ? parseFloat(c.lng) : parseFloat(c.longitud);
        var dKm = (typeof c.dist_km !== 'undefined') ? parseFloat(c.dist_km) : NaN;
        var dM = isFinite(dKm) ? Math.round(dKm * 1000) : '';
        lines.push([id, isFinite(lat) ? lat.toFixed(6) : '', isFinite(lng) ? lng.toFixed(6) : '', dM].join(','));
      });
      var csv = bom + lines.join('\r\n');
      var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      var url = URL.createObjectURL(blob);
      var a = document.createElement('a');
      var ts = new Date();
      function pad(n){ return n<10 ? '0'+n : ''+n; }
      var fname = 'estadisticas_'+ ts.getFullYear() + pad(ts.getMonth()+1) + pad(ts.getDate()) + '_' + pad(ts.getHours()) + pad(ts.getMinutes()) + pad(ts.getSeconds()) + '.csv';
      a.href = url;
      a.download = fname;
      document.body.appendChild(a);
      a.click();
      setTimeout(function(){
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        if (btn) { btn.disabled = false; }
        exportLock = false;
      }, 200);
    } catch(e){ console.error('Error generando CSV:', e); if (btn) { btn.disabled = false; } exportLock = false; }
  }

  function renderStores(list, radius){
    var box = document.getElementById('estad-stores');
    if (!box) return;
    box.innerHTML = '';
    if (storesLayer){ storesLayer.clearLayers(); }
    var radiusTxt = radius || currentRadius;
    if (!list || list.length === 0){
      box.innerHTML = '<div style="margin-top:6px; color:#666;">No hay tiendas dentro de '+radiusTxt+' m.</div>';
      return;
    }
    var html = '<div style="margin-top:6px;">'+
      '<b>Tiendas en '+radiusTxt+' m:</b></div>';
    html += '<ul style="list-style:none; padding-left:0; margin-top:6px;">';
    list.forEach(function(t){
      var lic = parseInt(t.condicion) === 1;
      var badge = lic ? '<span style="background:#2e7d32; color:#fff; padding:2px 6px; border-radius:4px; font-size:12px;">Con licencia</span>'
                      : '<span style="background:#c62828; color:#fff; padding:2px 6px; border-radius:4px; font-size:12px;">Sin licencia</span>';
      var name = (t.nombre_comercial || t.nombres_per || '').trim();
      var addr = (t.ubic_tienda || '').trim();
      var dKm = (typeof t.dist_km !== 'undefined') ? parseFloat(t.dist_km) : NaN;
      var dM = isFinite(dKm) ? Math.round(dKm * 1000) : null;
      html += '<li style="margin-bottom:6px;">'+badge+' '+
              (name ? '<span style="color:#222;">'+name+'</span>' : '')+
              (addr ? ' <span style="color:#555;">- '+addr+'</span>' : '')+
              (dM !== null ? ' <span style="color:#777;">— '+dM+' m</span>' : '')+
              '</li>';
      var lat = parseFloat(t.latitud), lng = parseFloat(t.longitud);
      if (isFinite(lat) && isFinite(lng)){
        var marker = L.circleMarker([lat, lng], {
          radius: 6,
          color: lic ? '#2e7d32' : '#c62828',
          fillColor: lic ? '#66bb6a' : '#ef5350',
          fillOpacity: 0.9
        }).bindPopup((name ? ('<b>'+name+'</b><br/>') : '') + (addr || '') + '<br/>' + (lic ? 'Con licencia' : 'Sin licencia') + (dM !== null ? '<br/>Distancia: '+dM+' m' : ''));
        storesLayer.addLayer(marker);
      }
    });
    html += '</ul>';
    box.innerHTML = html;
  }

  function showError(msg){
    var el = document.getElementById('estad-error');
    if (el){ el.textContent = msg; el.style.display = 'block'; }
  }

  // Autocompletado con Nominatim (acotado a Chilca por viewbox)
  var typingTimer;
  function searchNominatim(q){
    var key = 'nom_'+q;
    try {
      var cached = localStorage.getItem(key);
      if (cached){
        var obj = JSON.parse(cached);
        if (Date.now() - obj.t < cacheTTLms){ renderSuggestions(obj.r); }
      }
    } catch(e){}
    var bbox = {
      minLat: -12.13, maxLat: -12.04,
      minLng: -75.25, maxLng: -75.15
    };
    var url = '../controller/nominatim_proxy.php?q=' + encodeURIComponent(q);
    fetch(url)
      .then(function(r){ return r.json(); })
      .then(function(list){
        renderSuggestions(list);
        try { localStorage.setItem(key, JSON.stringify({ t: Date.now(), r: list })); } catch(e){}
      }).catch(function(){
        var err = document.getElementById('estad-error');
        if (err){ err.textContent = 'Error al buscar direcciones. Intenta de nuevo.'; err.style.display = 'block'; }
      });
  }

  function renderSuggestions(list){
    var box = document.getElementById('estad-suggestions');
    box.innerHTML = '';
    if (!list || list.length === 0){
      var err = document.getElementById('estad-error');
      if (err){ err.textContent = 'No se encontraron resultados en Chilca.'; err.style.display = 'block'; }
      return;
    }
    var err = document.getElementById('estad-error');
    if (err){ err.style.display = 'none'; }
    list.forEach(function(item){
      var li = document.createElement('li');
      li.textContent = item.display_name;
      li.className = 'suggestion-item';
      li.addEventListener('click', function(){
        box.innerHTML = '';
        setSearchResult(parseFloat(item.lat), parseFloat(item.lon));
      });
      box.appendChild(li);
    });
  }

  document.addEventListener('DOMContentLoaded', function(){
    initMap();
    var input = document.getElementById('estad-search');
    if (input){
      input.addEventListener('input', function(){
        clearTimeout(typingTimer);
        var q = input.value.trim();
        if (!q){ document.getElementById('estad-suggestions').innerHTML = ''; return; }
        typingTimer = setTimeout(function(){ searchNominatim(q); }, 300);
      });
      // Al presionar Enter, toma la primera sugerencia si existe
      input.addEventListener('keydown', function(e){
        if (e.key === 'Enter'){
          e.preventDefault();
          var box = document.getElementById('estad-suggestions');
          var first = box && box.querySelector('.suggestion-item');
          if (first){ first.click(); }
        }
      });
    }
    var radiusInput = document.getElementById('estad-radius');
    var radiusLabel = document.getElementById('estad-radius-value');
    function updateRadius(r){
      currentRadius = parseInt(r, 10);
      if (radiusLabel){ radiusLabel.textContent = currentRadius + ' m'; }
      if (circle){ circle.setRadius(currentRadius); }
      if (currentCenter){ fetchStats(currentCenter.lat, currentCenter.lng, currentRadius); }
    }
    if (radiusInput){
      radiusInput.addEventListener('input', function(e){ updateRadius(e.target.value); });
    }
    // Exportación CSV: evitar doble registro del evento
    var exportBtn = document.getElementById('estad-export');
    if (exportBtn && !exportBtn.dataset.bound){
      exportBtn.addEventListener('click', exportCSV);
      exportBtn.dataset.bound = '1';
    }
    // Render inicial en el centro de Chilca para que el gráfico se muestre
    setSearchResult(centerChilca.lat, centerChilca.lng);
  });
})();