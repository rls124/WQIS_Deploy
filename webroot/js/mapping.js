$(document).ready(function () {
	require([
		"esri/Map",
		"esri/views/MapView",
		"esri/layers/MapImageLayer",
		"esri/Graphic",
		"esri/layers/FeatureLayer"
	], function(Map, MapView, MapImageLayer, Graphic, FeatureLayer) {
		//fetches site information from the database
		$.ajax({
			type: 'POST',
			url: 'fetchSites',
			datatype: 'JSON',
			async: false,
			success: function(response) {
				const template = {
					title: "Site Info",
					content: "{Ecoli} <br> {SiteID}"
				};

				const renderer = {
					type: "simple",
					symbol: {
						type: "simple-marker",
						color: "orange",
						outline: {
							color: "white"
						}
					}
				};
				
				const markerSymbol = {
					type: "simple-marker",
					color: [226, 119, 40],
					outline: {
						color: [255, 255, 255],
						width: 2
					}
				};
				
				var graphics = [];
				const SITE_DATA = 'SiteData';
				//add markers to the map at each sites longitude and latitude
				for (var i = 0; i < response[SITE_DATA].length; i++) {
					var point = {
						type: "point",
						longitude: response[SITE_DATA][i]['Longitude'],
						latitude: response[SITE_DATA][i]['Latitude']
					};
					
					var pointGraphic = new Graphic({
						ObjectID: i,
						geometry: point,
						symbol: markerSymbol
					});

					pointGraphic.attributes = {
						"SiteID": response[SITE_DATA][i]['Site_Number'],
						"Ecoli": 500 //static, for demo
					};
					
					graphics.push(pointGraphic);
				}

				var sampleSitesLayer = new FeatureLayer({
					fields: [{
						name: "ObjectID",
						alias: "ObjectID",
						type: "oid"
						}, {
							name: "SiteID",
							alias: "SiteID",
							type: "integer"
						}, {
							name: "Ecoli",
							alias: "Ecoli",
							type: "integer"
						}
					],
					objectIdField: "ObjectID",
					geometryType: "point",
					popupTemplate: template,
					source: graphics,
					renderer: renderer
				});
				
				var drainsLayer = new MapImageLayer("http://gis1.acimap.us/imapweb/rest/services/Engineering/Drains/MapServer", null);

				//create the Map
				var map = new Map({
					basemap: "gray",
					layers: [sampleSitesLayer, drainsLayer]
				});
		
				const view = new MapView({
					container: "viewDiv",
					center: [-85, 41],
					zoom: 9,
					map: map
				});
			
				view.when(function() {
					view.graphics.addMany(graphics);
				});
		
				//handle the checkboxes that toggle layer visibility
				var drainsLayerToggle = document.getElementById("drainsLayer");
				drainsLayerToggle.addEventListener("change", function() {
					drainsLayer.visible = drainsLayerToggle.checked;
				});
			}
		});
	});
});