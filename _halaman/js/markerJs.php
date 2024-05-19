<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
   crossorigin=""></script>

 <script src="assets/js/leaflet-panel-layers-master/src/leaflet-panel-layers.js"></script>
 <script src="assets/js/leaflet.ajax.js"></script>

   <script type="text/javascript">

   	var map = L.map('mapid').setView([-6.9987208, 110.4679176], 25);

   	var LayerKita=L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 15,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
});
	map.addLayer(LayerKita);

	var myStyle2 = {
	    "color": "#ffff00",
	    "weight": 1,
	    "opacity": 0.9
	};

	function popUp(f,l){
	    var out = [];
	    if (f.properties){
	        // for(key in f.properties){
	        // 	console.log(key);

	        // }
			out.push("Provinsi: "+f.properties['PROVINSI']);
            out.push("Kota: "+f.properties['KABKOT']);
            out.push("Kecamatan: "+f.properties['KECAMATAN']);
			out.push("Kelurahan: "+f.properties['DESA']);
	        l.bindPopup(out.join("<br />"));
	    }
	}

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

    

	<?php
		$ambilKelurahan=$db->ObjectBuilder()->get('k_kelurahan');
		foreach ($ambilKelurahan as $row) {
			?>

			var myStyle<?=$row->id_kel?> = {
			    "color": "<?=$row->warna_kel?>",
			    "weight": 1,
			    "opacity": 1
			};

			<?php
			$arrayKec[]='{
			name: "'.$row->nama_kel.'",
			icon: iconByName("'.$row->warna_kel.'"),
			layer: new L.GeoJSON.AJAX(["assets/unggah/geojson/'.$row->geojson_kel.'"],{onEachFeature:popUp,style: myStyle'.$row->id_kel.',pointToLayer: featureToMarker }).addTo(map)
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

	var panelLayers = new L.Control.PanelLayers(baseLayers, overLayers,{
		collapsibleGroups: true
	});

	map.addControl(panelLayers);

    //marker
    var myIcon = L.Icon.extend({
    options: {
        iconSize:     [35, 50],
  
    }
});
    
    <?php 
    $db->join('k_kelurahan b','a.id_kel=b.id_kel','LEFT');
    $ambil_data=$db->ObjectBuilder()->get('rawan_banjir a');
    foreach ($ambil_data as $row) {
        ?>
         L.marker([<?=$row->latitude?>, <?=$row->longtitude?>],{icon: new myIcon({iconUrl: '<?=($row->marker=='')?assets('icon/red_marker.png'):assets('unggah/poin_marker/'.$row->marker)?>'})}).addTo(map).bindPopup("Kelurahan. <?=$row->nama_kel?><br>Tanggal : <?=$row->tanggal?><br>Ketinggian : <?=$row->ketinggian?><br>Kategori : <?=$row->kategori?><br>Lokasi : <?=$row->lokasi?><br>Latitude : <?=$row->latitude?><br>Longtitude : <?=$row->longtitude?>");
        <?php
    }
    ?>

   </script>