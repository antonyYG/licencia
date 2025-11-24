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
  
  // Función para calcular área de polígono (fallback si no está disponible L.GeometryUtil)
  function calcularAreaPoligono(coords) {
    if (coords.length < 3) return 0;
    
    var area = 0;
    var n = coords.length;
    
    for (var i = 0; i < n; i++) {
      var j = (i + 1) % n;
      var lat1 = coords[i][0];
      var lng1 = coords[i][1];
      var lat2 = coords[j][0];
      var lng2 = coords[j][1];
      
      area += (lng2 - lng1) * (2 + Math.sin(deg2rad(lat1)) + Math.sin(deg2rad(lat2)));
    }
    
    area = Math.abs(area) * 6371000 * 6371000 / 2; // Radio de la Tierra en metros
    return area;
  }
  
  // Función para convertir grados a radianes
  function deg2rad(deg) {
    return deg * (Math.PI/180);
  }

  function initMap(){
    if (typeof L === 'undefined') return;
    // Evita error de inicialización doble del contenedor del mapa
    var container = L.DomUtil.get('estad-map');
    if (container){ container._leaflet_id = null; }
    
    // Crear mapa con opciones de interacción mejoradas
    map = L.map('estad-map', {
      center: [centerChilca.lat, centerChilca.lng],
      zoom: 13,
      zoomControl: true,
      attributionControl: true,
      // Habilitar todas las interacciones
      dragging: true,
      touchZoom: true,
      doubleClickZoom: true,
      scrollWheelZoom: true,
      boxZoom: true,
      keyboard: true,
      tap: true
    });
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    L.control.scale().addTo(map);
    storesLayer = L.layerGroup().addTo(map);
    
    // Agregar funcionalidad de clic en el mapa - SIN CONFIRMACIÓN
    map.on('click', function(e) {
      // Mover directamente el punto de búsqueda sin confirmación
      setSearchResult(e.latlng.lat, e.latlng.lng);
    });
    
    // Agregar control de dibujo para selección de área
    addAreaSelectionControl();
  }
  
  // Función para agregar control de selección de área
  function addAreaSelectionControl() {
    // Crear un control personalizado
    var areaControl = L.Control.extend({
      options: {
        position: 'topleft'
      },
      onAdd: function(map) {
        var container = L.DomUtil.create('div', 'leaflet-control-area-selection');
        container.innerHTML = '📍 Área';
        container.title = 'Dibujar área de búsqueda';
        
        container.onclick = function() {
          startAreaSelection();
        };
        
        return container;
      }
    });
    
    map.addControl(new areaControl());
  }
  
  // Variables para la selección de área
  var areaDrawn = null;
  var areaMarkers = [];
  
  // Función para iniciar selección de área - MÁS FLUIDA Y SIN INTERRUPCIONES
  function startAreaSelection() {
    // Marcar el control como activo
    var controlBtn = document.querySelector('.leaflet-control-area-selection');
    if (controlBtn) {
      controlBtn.classList.add('active');
    }
    
    // Cambiar cursor del mapa
    var mapContainer = document.getElementById('estad-map');
    if (mapContainer) {
      mapContainer.classList.add('area-selection-mode');
    }
    
    // Mostrar instrucción temporal (se quita automáticamente después de 3 segundos)
    var instruccion = L.control({position: 'top'});
    instruccion.onAdd = function(map) {
      var div = L.DomUtil.create('div', 'instruccion-area');
      div.innerHTML = 'Haz clic para marcar puntos • Doble clic para finalizar • ESC para cancelar';
      div.style.backgroundColor = 'rgba(0,0,0,0.7)';
      div.style.color = 'white';
      div.style.padding = '8px 12px';
      div.style.borderRadius = '4px';
      div.style.fontSize = '14px';
      div.style.marginTop = '10px';
      div.style.transition = 'opacity 0.5s';
      return div;
    };
    instruccion.addTo(map);
    
    // Ocultar instrucción después de 3 segundos
    setTimeout(function() {
      var instruccionDiv = document.querySelector('.instruccion-area');
      if (instruccionDiv) {
        instruccionDiv.style.opacity = '0';
        setTimeout(function() {
          map.removeControl(instruccion);
        }, 500);
      }
    }, 3000);
    
    var areaCoords = [];
    var tempLine = null;
    var isActive = true;
    
    function onMapClickForArea(e) {
      if (!isActive) return;
      
      areaCoords.push([e.latlng.lat, e.latlng.lng]);
      
      // Marcar el punto con círculo pequeño (más rápido que marker)
      var point = L.circleMarker(e.latlng, {
        radius: 4,
        color: 'red',
        fillColor: 'red',
        fillOpacity: 1
      }).addTo(map);
      
      areaMarkers.push(point);
      
      // Actualizar línea temporal
      if (tempLine) {
        map.removeLayer(tempLine);
      }
      if (areaCoords.length > 1) {
        tempLine = L.polyline(areaCoords, {
          color: 'red', 
          weight: 2,
          dashArray: '5, 10'
        }).addTo(map);
      }
    }
    
    function finishAreaSelection(e) {
      if (!isActive) return;
      isActive = false;
      
      // Restaurar cursor y botón
      if (controlBtn) {
        controlBtn.classList.remove('active');
      }
      if (mapContainer) {
        mapContainer.classList.remove('area-selection-mode');
      }
      
      // Remover eventos
      map.off('click', onMapClickForArea);
      map.off('dblclick', finishAreaSelection);
      document.removeEventListener('keydown', cancelAreaSelection);
      
      // Remover instrucción
      map.removeControl(instruccion);
      
      if (tempLine) {
        map.removeLayer(tempLine);
      }
      
      if (areaCoords.length >= 3) {
        // Cerrar el polígono
        areaCoords.push(areaCoords[0]);
        
        // Remover polígono anterior si existe
        if (areaDrawn) {
          map.removeLayer(areaDrawn);
        }
        
        // Crear polígono del área
        areaDrawn = L.polygon(areaCoords, {
          color: '#ff0000',
          fillColor: '#ff0000',
          fillOpacity: 0.2,
          weight: 2
        }).addTo(map);
        
        // Calcular centro del área
        var center = areaDrawn.getBounds().getCenter();
        
        // Calcular área
        var area = 0;
        if (typeof L.GeometryUtil !== 'undefined') {
          area = L.GeometryUtil.geodesicArea(areaCoords);
        } else {
          area = calcularAreaPoligono(areaCoords);
        }
        
        // Radio equivalente (círculo con misma área)
        var radius = Math.sqrt(area / Math.PI); // en kilómetros
        var radiusMeters = Math.min(radius * 1000, 2000); // Limitar a 2km máximo
        
        // Actualizar controles
        currentRadius = Math.round(radiusMeters);
        document.getElementById('estad-radius').value = currentRadius;
        document.getElementById('estad-radius-value').textContent = currentRadius + ' m';
        
        // Establecer nuevo centro con animación suave
        setSearchResult(center.lat, center.lng);
        
        // Limpiar marcadores temporales con animación
        setTimeout(function() {
          areaMarkers.forEach(function(marker) {
            if (marker) map.removeLayer(marker);
          });
          areaMarkers = [];
        }, 500);
        
      } else {
        // Limpiar si no hay suficientes puntos
        areaMarkers.forEach(function(marker) {
          if (marker) map.removeLayer(marker);
        });
        areaMarkers = [];
      }
    }
    
    // Configurar eventos
    map.on('click', onMapClickForArea);
    map.on('dblclick', finishAreaSelection);
    
    // Permitir cancelar con ESC
    function cancelAreaSelection(e) {
      if (e.key === 'Escape') {
        isActive = false;
        
        // Restaurar cursor y botón
        if (controlBtn) {
          controlBtn.classList.remove('active');
        }
        if (mapContainer) {
          mapContainer.classList.remove('area-selection-mode');
        }
        
        map.off('click', onMapClickForArea);
        map.off('dblclick', finishAreaSelection);
        document.removeEventListener('keydown', cancelAreaSelection);
        
        map.removeControl(instruccion);
        if (tempLine) map.removeLayer(tempLine);
        
        areaMarkers.forEach(function(marker) {
          if (marker) map.removeLayer(marker);
        });
        areaMarkers = [];
      }
    }
    
    document.addEventListener('keydown', cancelAreaSelection);
  }

  function setSearchResult(lat, lng){
    if (!map) return;
    
    // Actualizar el centro actual
    currentCenter = { lat: lat, lng: lng };
    
    // Animación suave para mover el marcador y círculo
    if (marker) { 
      marker.setLatLng([lat, lng]); // Mover existente en lugar de recrear
    } else {
      marker = L.marker([lat, lng]).addTo(map);
    }
    
    if (circle) { 
      circle.setLatLng([lat, lng]); // Mover existente en lugar de recrear
    } else {
      circle = L.circle([lat, lng], { radius: currentRadius, color: '#1976d2', fillColor: '#90caf9', fillOpacity: 0.25 }).addTo(map);
    }
    
    // Animación suave del mapa
    map.setView([lat, lng], 16, { animate: true, duration: 0.5 });
    
    // Actualizar estadísticas
    fetchStats(lat, lng, currentRadius);
  }

  function fetchStats(lat, lng, radius){
    console.log('=== FETCH STATS DEBUG ===');
    console.log('Lat:', lat, 'Lng:', lng, 'Radius:', radius);
    
    var url = '../controller/estadisticas.php?lat='+encodeURIComponent(lat)+'&lng='+encodeURIComponent(lng)+'&radius='+encodeURIComponent(radius);
    console.log('URL:', url);
    
    fetch(url).then(function(r){ return r.text(); }).then(function(text){
      console.log('Respuesta cruda:', text);
      var data;
      try { data = JSON.parse(text); }
      catch(e){ console.error('Respuesta no JSON:', text); showError('Error al obtener estadísticas: ' + text.slice(0, 160)); return; }
      if (data.error){ showError(data.error); return; }
      
      console.log('Datos recibidos:', data);
      console.log('Tiendas:', data.tiendas ? data.tiendas.length : 0);
      console.log('Coordenadas:', data.coords ? data.coords.length : 0);
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
      var name = (t.nombre_comercial || t.nombres_per || '').trim(); // Mantener como estaba
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

  // Exportación server-side a Excel vía endpoint PHP
  function exportExcelServer(){
    console.log('=== EXPORT EXCEL DEBUG ===');
    if (exportLock) return;
    exportLock = true;
    var btn = document.getElementById('estad-export'); // Cambiar ID al botón correcto
    if (btn) btn.disabled = true;
    try {
      // Usar el centro actual del mapa o el último centro usado
      var center = currentCenter || (lastCounts && lastCounts.center) || centerChilca;
      var radius = currentRadius || (lastCounts && lastCounts.radius_m) || 100;
      
      console.log('Center:', center);
      console.log('Radius:', radius);
      console.log('Current center:', currentCenter);
      console.log('Current radius:', currentRadius);
      
      if (!center || typeof center.lat === 'undefined' || typeof center.lng === 'undefined'){
        showError('Primero selecciona un punto de búsqueda en el mapa.');
        if (btn) btn.disabled = false; exportLock = false; return;
      }
      var url = '../controller/export_excel.php?lat='+encodeURIComponent(center.lat)+'&lng='+encodeURIComponent(center.lng)+'&radius='+encodeURIComponent(radius);
      console.log('Export URL:', url);
      fetch(url).then(function(resp){
        if (!resp.ok) throw new Error('Fallo en la descarga ('+resp.status+')');
        return resp.blob();
      }).then(function(blob){
        var a = document.createElement('a');
        var url = URL.createObjectURL(blob);
        var ts = new Date();
        function pad(n){ return n<10 ? '0'+n : ''+n; }
        var fname = 'estadisticas_'+ ts.getFullYear() + pad(ts.getMonth()+1) + pad(ts.getDate()) + '_' + pad(ts.getHours()) + pad(ts.getMinutes()) + pad(ts.getSeconds());
        var isXlsx = (blob && blob.type && blob.type.indexOf('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') !== -1);
        a.href = url;
        a.download = fname + '.xlsx';
        document.body.appendChild(a);
        a.click();
        setTimeout(function(){ document.body.removeChild(a); URL.revokeObjectURL(url); if (btn) btn.disabled = false; exportLock = false; }, 200);
      }).catch(function(err){ console.error(err); showError('No se pudo generar el archivo: '+err.message); if (btn) btn.disabled = false; exportLock = false; });
    } catch(e){ console.error(e); if (btn) btn.disabled = false; exportLock = false; }
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
    // Exportación Excel: evitar doble registro del evento
    var exportBtn = document.getElementById('estad-export');
    if (exportBtn && !exportBtn.dataset.bound){
      exportBtn.addEventListener('click', exportExcelServer); // Cambiar a exportExcelServer
      exportBtn.dataset.bound = '1';
    }
    // Exportación Excel (server-side): evitar doble registro
    var exportExcelBtn = document.getElementById('exportButton');
    if (exportExcelBtn && !exportExcelBtn.dataset.bound){
      exportExcelBtn.addEventListener('click', exportExcelServer);
      exportExcelBtn.dataset.bound = '1';
    }
    // Render inicial en el centro de Chilca para que el gráfico se muestre
    setSearchResult(centerChilca.lat, centerChilca.lng);
  });
})();