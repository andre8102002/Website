<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
   crossorigin=""></script>

 <script src="assets/js/leaflet-panel-layers-master/src/leaflet-panel-layers.js"></script>
 <script src="assets/js/leaflet.ajax.js"></script>

   <script type="text/javascript">
    <?php
		$ambilKelurahan=$db->ObjectBuilder()->get('k_kelurahan');
		foreach ($ambilKelurahan as $row) {
			$db->where('id_kel',$row->id_kel);
		$db->get('rawan_banjir');
		$data[$row->kd_kel]=$db->count;
		}
		
			?>
			var RAWAN_BANJIR = <?=json_encode($data)?>; 

   	var map = L.map('mapid').setView([-6.9987208, 110.4679176], 12);

   	var LayerKita=L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 15,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
});
	map.addLayer(LayerKita);


	// control that shows state info on hover
	var info = L.control();

info.onAdd = function (map) {
    this._div = L.DomUtil.create('div', 'info');
    this.update();
    return this._div;
};
info.update = function (props) {
		this._div.innerHTML = '<h4>Wilayah Kelurahan Rawan Banjir Di Kecamatan Pedurungan</h4>' +  (props ?
			'<b>' + props.DESA + '</b><br />' + RAWAN_BANJIR[props.KODE] + ' titik'
			: 'Dekatkan mouse untuk melihat');
	};
    info.addTo(map);


	


	// legend

	function iconByName(name) {
		return '<i class="icon" style="background-color:'+name+';border-radius:50%"></i>';
	}

	function featureToMarker(feature, latlng) {
		return L.marker(latlng, {
			icon: L.divIcon({
				className: 'marker-'+feature.properties.amenity,
				html: iconByName(feature.properties.amenity),
				iconUrl: '../images/markers/'+feature.properties.amenity+'.png',
				iconSize: [25, 41],
				iconAnchor: [12, 41],
				popupAnchor: [1, -34],
				shadowSize: [41, 41]
			})
		});
	}

	var baseLayers = [
		{
			name: "OpenStreetMap",
			layer: LayerKita
		},
		{	
			name: "OpenCycleMap",
			layer: L.tileLayer('http://{s}.tile.opencyclemap.org/cycle/{z}/{x}/{y}.png')
		},
		{
			name: "Outdoors",
			layer: L.tileLayer('http://{s}.tile.thunderforest.com/outdoors/{z}/{x}/{y}.png')
		}
	];

    // feature
	function getColor(d) {
		return d > 20 ? '#800026' :
				d > 14  ? '#BD0026' :
				d > 10  ? '#E31A1C' :
				d > 8  ? '#FC4E2A' :
				d > 6   ? '#FD8D3C' :
				d > 4   ? '#FEB24C' :
				d > 2   ? '#FED976' :
							'#FFEDA0';
	}



	var legend = L.control({position: 'bottomright'});

	legend.onAdd = function (map) {

		var div = L.DomUtil.create('div', 'info legend'),
			grades = [0, 2, 4, 6, 8, 10, 14, 20],
			labels = [],
			from, to;

		for (var i = 0; i < grades.length; i++) {
			from = grades[i];
			to = grades[i + 1];

			labels.push(
				'<i style="background:' + getColor(from + 1) + '"></i> ' +
				from + (to ? '&ndash;' + to : '+'));
		}

		div.innerHTML = labels.join('<br>');
		return div;
	};

	legend.addTo(map);


	function style(feature) {
		return {
			weight: 2,
			opacity: 1,
			color: 'white',
			dashArray: '3',
			fillOpacity: 0.7,
			fillColor: getColor(RAWAN_BANJIR[feature.properties.KODE])
		};
	}

	function highlightFeature(e) {
		var layer = e.target;

		layer.setStyle({
			weight: 5,
			color: '#666',
			dashArray: '',
			fillOpacity: 0.7
		});

		if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
			layer.bringToFront();
		}

		info.update(layer.feature.properties);
	}

	function resetHighlight(e) {
		var layer = e.target;

		layer.setStyle({
			weight: 2,
			opacity: 1,
			color: 'white',
			dashArray: '3'
		});

		info.update();
	}
	
	function zoomToFeature(e) {
		map.fitBounds(e.target.getBounds());
	}

	function onEachFeature(feature, layer) {
		layer.on({
			mouseover: highlightFeature,
			mouseout: resetHighlight,
			click: zoomToFeature
		});
	}

	<?php
		$ambilKelurahan=$db->ObjectBuilder()->get('k_kelurahan');
		foreach ($ambilKelurahan as $row) {
			?>


			<?php
			$arrayKec[]='{
			name: "'.$row->nama_kel.'",
			icon: iconByName("'.$row->warna_kel.'"),
			layer: new L.GeoJSON.AJAX(["assets/unggah/geojson/'.$row->geojson_kel.'"],{
                style: style, onEachFeature:onEachFeature 
               }).addTo(map)
			}';
		}
	?>

	var overLayers = [{
		group: "Layer Kelurahan",
		layers: [
			<?=implode(',', $arrayKec);?>
		]
	}
	];



   </script>