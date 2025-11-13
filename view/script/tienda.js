$(document).ready(function(){
	$("#registrar").click(function(){
    var datos = $("#formtienda").serialize();
    var ruc = $("#ruc").val();
    var nombres = $("#nombres").val();
    var apellidop = $("#apellidop").val();
    var apellidom = $("#apellidom").val();
    var ubicacion = $("#ubicacion").val();
    var area = $("#area").val();
    var latitud = $("#latitud").val();
    var longitud = $("#longitud").val();
    var zona = $("#zona").val();
    var celular = $("#celular").val();

    if (ruc.length == 0 || nombres.length == 0 || apellidop.length == 0 || apellidom.length == 0) {
        toastr.info("Ingresar los datos respectivos", "Tienda");
    } else if (latitud.length == 0 || longitud.length == 0) {
        toastr.info("Ingresar la latitud y longitud", "Tienda");
    } else {
        $.ajax({
            "url": "../controller/tienda.php?boton=insertar",
            "method": "post",
            "data": datos
        }).done(function(rsp){
            if (rsp == "1") {
                toastr.success("Se registr�� exitosamente", "Tienda");
                limpiar();
                tabla.ajax.reload();
            } else {
                toastr.error("No se pudo registrar", "Tienda");
            }
        });
    }
});


$(document).on('click','.actualizar', function(){
    $("#modaleditar").modal({backdrop:'static', keyboard:false});
    $("#modaleditar").modal("show");
    var id=$(this).data("id");
            $.ajax({
                "url":"../controller/tienda.php?boton=mostrartienda",
                "method":"post",
                "data":{idtienda:id},
                "dataType":"json"
            }).done(function(rsp){
                $("#idtienda").val(rsp.idtienda);
                $("#rucedit").val(rsp.ruc);
                $("#dniedit").val(rsp.dni);
                $("#nombresedit").val(rsp.nombres);
                $("#apellidopedit").val(rsp.apellidop);
                $("#apellidomedit").val(rsp.apellidom);
                $("#ubicacionedit").val(rsp.ubicacion);
                $("#areaedit").val(rsp.area);
                $("#latitudedit").val(rsp.latitud);
                $("#longitudedit").val(rsp.longitud);
                $("#zonaedit").val(rsp.zona);
                $("#celularedit").val(rsp.celular);
            });
});

	$("#edita").click(function(){
		var datos=$("#formtiendaedita").serialize();
			$.ajax({
					"url":"../controller/tienda.php?boton=editar",
					"method":"post",
					"data":datos
				}).done(function(rsp){
					if (rsp=="1") {
						toastr.success("Se actualizo exitosamente", "Tienda");
						$("#modaleditar").modal("hide");
						tabla.ajax.reload();
					}else{
						toastr.error("No se pudo actualizar", "Tienda");
					}
				});
			});

});

var tabla;

function init(){
	listartienda();
}

function listartienda(){
	tabla=$("#tablatienda").DataTable({
		"responsive":true,
        "destroy":true,
        "iDisplayLength":4,//Paginación
        "lengthMenu": [[4], [4]],
		"ajax":{
			"url":"../controller/tienda.php?boton=lista",
			"method":"post",
			"dataType":"json"
		},
		"columns":[
			{"data":"dni"},
			{"data":"nombres_per"},
			{"data":"apellidop_per"},
			{"data":"apellidom_per"},
		
			{"data":"edita"}
			
			
		],
		"language":{
			"url":"../public/datatables/js/espanol.js"
		}
	});
}

function abrirmodal(){
	$("#modalinsertar").modal({backdrop:'static', keyboard:false});
	$("#modalinsertar").modal("show");
}


function limpiar(){
	$("#ruc").val('');
	$("#dni").val('');
	$("#nombres").val('');
	$("#apellidop").val('');
	$("#apellidom").val('');
	$("#ubicacion").val('');
	$("#area").val('');
	$("#latitud").val('');
	$("#longitud").val('');
	$("#zona").val('');
	$("#celular").val('');
}


init();

// =====================
// Leaflet + OpenStreetMap Integración
// =====================

var insertMap = null;
var editMap = null;
var insertMarker = null;
var editMarker = null;

var chilcaCenter = { lat: -12.08673, lng: -75.20785 }; // Centro exacto Chilca

function initTiendaLeaflet() {
    try {
        if (typeof L === 'undefined') {
            mostrarErrorMapa('#map-insert-error');
            mostrarErrorMapa('#map-edit-error');
            return;
        }

        // Inicializar mapas cuando se muestran los modales (delegado por si el DOM se inyecta después)
        $(document).on('shown.bs.modal', '#modalinsertar', function () {
            if (!insertMap) {
                insertMap = crearMapaLeaflet('#map-insert');
                // Marcador inicial en el centro y autocompletar campos
                onLatLngSelected('#map-insert', chilcaCenter.lat, chilcaCenter.lng);
                setTimeout(function(){ try { insertMap.invalidateSize(); } catch(e){} }, 260);
            } else {
                insertMap.invalidateSize();
            }
        });
        $(document).on('shown.bs.modal', '#modaleditar', function () {
            var ctr = obtenerCentroDesdeCampos('#latitudedit', '#longitudedit');
            if (!editMap) {
                editMap = crearMapaLeaflet('#map-edit', ctr);
                setTimeout(function(){ try { editMap.invalidateSize(); } catch(e){} }, 260);
            } else {
                editMap.invalidateSize();
                if (ctr) editMap.setView([ctr.lat, ctr.lng], 14);
            }
            // Si hay coordenadas existentes, mostrar marcador y autocompletar dirección
            if (ctr && coordenadasValidas(ctr.lat, ctr.lng)) {
                onLatLngSelected('#map-edit', ctr.lat, ctr.lng);
            }
        });
    } catch (e) {
        mostrarErrorMapa('#map-insert-error');
        mostrarErrorMapa('#map-edit-error');
    }
}

function crearMapaLeaflet(selector, centerOverride) {
    var center = centerOverride || chilcaCenter;
    var mapEl = $(selector).get(0);
    var map = L.map(mapEl, { attributionControl: true }).setView([center.lat, center.lng], 13);

    // Capa de teselas OSM
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Control de escala como en el ejemplo de prueba
    L.control.scale().addTo(map);

    // Intentar geolocalización para mejorar UX (silencioso si falla)
    map.on('locationfound', function (e) {
        map.setView(e.latlng, 16);
    });
    map.on('locationerror', function () { /* ignorar */ });
    map.locate({ setView: false, maxZoom: 16 });

    // Click para autocompletar
    map.on('click', function (e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        onLatLngSelected(selector, lat, lng);
    });

    return map;
}

function onLatLngSelected(selector, lat, lng) {
    if (!coordenadasValidas(lat, lng)) {
        toastr.error('Coordenadas fuera de rango válido.', 'Mapa');
        return;
    }

    var latField = (selector === '#map-insert') ? '#latitud' : '#latitudedit';
    var lngField = (selector === '#map-insert') ? '#longitud' : '#longitudedit';
    var addrField = (selector === '#map-insert') ? '#ubicacion' : '#ubicacionedit';

    $(latField).val(lat.toFixed(6));
    $(lngField).val(lng.toFixed(6));

    // Colocar o mover marcador
    var map = (selector === '#map-insert') ? insertMap : editMap;
    var isInsert = (selector === '#map-insert');
    var marker = isInsert ? insertMarker : editMarker;
    if (!marker) {
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        marker.on('dragend', function () {
            var pos = marker.getLatLng();
            onLatLngSelected(selector, pos.lat, pos.lng);
        });
        if (isInsert) insertMarker = marker; else editMarker = marker;
    } else {
        marker.setLatLng([lat, lng]);
    }
    map.setView([lat, lng]);

    // Geocodificación inversa con reintentos y fallback
    reverseGeocodeWithFallback(lat, lng, addrField);
}

function reverseGeocodeWithFallback(lat, lng, addrField, attempt) {
    attempt = attempt || 1;
    // Usar exclusivamente OSM; reintentos simples
    reverseGeocodeOSM(lat, lng)
        .then(function (addr) { $(addrField).val(addr); })
        .catch(function () {
            if (attempt < 2) {
                setTimeout(function () { reverseGeocodeWithFallback(lat, lng, addrField, attempt + 1); }, 600);
            } else {
                aplicarDireccionFallback(lat, lng, addrField);
                toastr.warning('No se pudo obtener la dirección exacta. Usando coordenadas.', 'Mapa');
            }
        });
}

function aplicarDireccionFallback(lat, lng, addrField) {
    var texto = 'Coordenadas: ' + lat.toFixed(6) + ', ' + lng.toFixed(6) + ' (Chilca, Huancayo, Junín)';
    $(addrField).val(texto);
}

// Nota: con OSM no se requiere elegirMejorDireccion para Google

function reverseGeocodeOSM(lat, lng) {
    var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + encodeURIComponent(lat) + '&lon=' + encodeURIComponent(lng) + '&zoom=18';
    return fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(function (res) { if (!res.ok) throw new Error('OSM status ' + res.status); return res.json(); })
        .then(function (data) {
            if (data && data.display_name) return data.display_name;
            if (data && data.address) {
                var a = data.address;
                var parts = [a.road, a.suburb, a.city || a.town || a.village, a.state, a.country].filter(Boolean);
                if (parts.length) return parts.join(', ');
            }
            throw new Error('OSM sin datos');
        });
}

// Eliminado: Google REST. Usamos exclusivamente OSM (Nominatim)

function coordenadasValidas(lat, lng) {
    return isFinite(lat) && isFinite(lng) && lat <= 90 && lat >= -90 && lng <= 180 && lng >= -180;
}

function obtenerCentroDesdeCampos(latSelector, lngSelector) {
    var lat = parseFloat($(latSelector).val());
    var lng = parseFloat($(lngSelector).val());
    if (coordenadasValidas(lat, lng)) {
        return { lat: lat, lng: lng };
    }
    return null;
}

function mostrarErrorMapa(errorSelector) {
    $(errorSelector).show();
}

// Si Leaflet no carga, mostramos errores
setTimeout(function () {
    if (typeof L === 'undefined') {
        mostrarErrorMapa('#map-insert-error');
        mostrarErrorMapa('#map-edit-error');
    }
}, 3500);

// Inicializar Leaflet
initTiendaLeaflet();