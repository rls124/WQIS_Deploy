var categoryMeasures = {
	'bacteria': {
		'Ecoli': {text: 'E. Coli (CFU/100 mil)'},
		'EcoliRawCount': {text: 'E. Coli Raw Count', visible: false}, //if these raw count columns are ever removed, it'll simplify a lot of stuff
		'TotalColiform': {text: 'Coliform (CFU/100 mil)'},
		'TotalColiformRawCount': {text: 'Coliform Raw Count', visible: false}
	},
	'nutrient': {
		'NitrateNitrite': {text: 'Nitrate/Nitrite (mg/L)'},
		'Phosphorus': {text: 'Total Phosphorus (mg/L)'},
		'DRP': {text: 'Dissolved Reactive Phosphorus (mg/L)'},
		'Ammonia': {text: 'Ammonia (mg/L)'}
	},
	'pesticide': {
		'Alachlor': {text: 'Alachlor (µg/L)'},
		'Atrazine': {text: 'Atrazine (µg/L)'},
		'Metolachlor': {text: 'Metolachlor (µg/L)'}
	},
	'physical': {
		'Conductivity': {text: 'Conductivity (mS/cm)'},
		'DO': {text: 'Dissolved Oxygen (mg/L'},
		'Bridge_to_Water_Height': {text: 'Bridge to Water Height (in)'},
		'pH': {text: 'pH'},
		'Water_Temp': {text: 'Water Temperature (°C)'},
		'TDS': {text: 'Total Dissolved Solids (g/L)'},
		'Turbidity': {text: 'Turbidity (NTU)'}
	}
};

const SITE_DATA = 'SiteData';
const BACTERIA_DATA = 'BacteriaData';
const NUTRIENT_DATA = 'NutrientData';
const PEST_DATA = 'PestData';

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
				var currentBactRow = 0;
				var currentNutrientRow = 0;
				var currentPestRow = 0;
	
				//build the table template we use to display all the data associated with a point on the map
				var templateContent = "<table>";
				for (var category in categoryMeasures) {
					for (var key in categoryMeasures[category]) {
						if (!(categoryMeasures[category][key]["visible"] == false)) {
							templateContent = templateContent + "<tr><th>" + categoryMeasures[category][key]["text"] + "</th><td>{" + key + "}</td></tr>";
						}
					}
				}
				templateContent = templateContent + "</table>";
	
				const template = {
					title: "<b>{siteNumber} - {siteName} ({siteLocation})</b>",
					content: templateContent
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
						symbol: markerSymbol,
						attributes: {}
					});
					
					pointGraphic.attributes.siteNumber = response[SITE_DATA][i]["Site_Number"];
					pointGraphic.attributes.siteName = response[SITE_DATA][i]["Site_Name"];
					pointGraphic.attributes.siteLocation = response[SITE_DATA][i]["Site_Location"];
					
					var bactLatestDate = 'No Records Found';
					var nutrientLatestDate = 'No Records Found';
					var pestLatestDate = 'No Records Found';

					//check to see if the current site has bacteria data associated with it
					if (response[BACTERIA_DATA][currentBactRow]) {
						var bactSiteNumber = response[BACTERIA_DATA][currentBactRow]['site_location_id'];
						if (pointGraphic.attributes.siteNumber == bactSiteNumber) {
							bactLatestDate = response[BACTERIA_DATA][currentBactRow]['Date'].split('T')[0];
							if (response[BACTERIA_DATA][currentBactRow]['Ecoli'] !== null) {
								pointGraphic.attributes.Ecoli = response[BACTERIA_DATA][currentBactRow]['Ecoli'];
							}
							currentBactRow++;
						}
					}

					//check to see if the current site has nutrient data associated with it
					if (response[NUTRIENT_DATA][currentNutrientRow]) {
						var nutrientSiteNumber = response[NUTRIENT_DATA][currentNutrientRow]['site_location_id'];
						if (pointGraphic.attributes.siteNumber == nutrientSiteNumber) {
							nutrientLatestDate = response[NUTRIENT_DATA][currentNutrientRow]['Date'].split('T')[0];
							if (response[NUTRIENT_DATA][currentNutrientRow]['Phosphorus'] !== null) {
								pointGraphic.attributes.Phosphorus = response[NUTRIENT_DATA][currentNutrientRow]['Phosphorus'];
							}
							if (response[NUTRIENT_DATA][currentNutrientRow]['NitrateNitrite'] !== null) {
								pointGraphic.attributes.NitrateNitrite = response[NUTRIENT_DATA][currentNutrientRow]['NitrateNitrite'];
							}
							if (response[NUTRIENT_DATA][currentNutrientRow]['DRP'] !== null) {
								pointGraphic.attributes.DRP = response[NUTRIENT_DATA][currentNutrientRow]['DRP'];
							}
							currentNutrientRow++;
						}
					}

					//check to see if the current site has pesticide data associated with it
					if (response[PEST_DATA][currentPestRow]) {
						var pestSiteNumber = response[PEST_DATA][currentPestRow]['site_location_id'];
						if (pointGraphic.attributes.siteNumber == pestSiteNumber) {
							pestLatestDate = response[PEST_DATA][currentPestRow]['Date'].split('T')[0];
							if (response[PEST_DATA][currentPestRow]['Atrazine'] !== null) {
								pointGraphic.attributes.Atrazine = response[PEST_DATA][currentPestRow]['Atrazine'];
							}
							if (response[PEST_DATA][currentPestRow]['Alachlor'] !== null) {
								pointGraphic.attributes.Alachlor = response[PEST_DATA][currentPestRow]['Alachlor'];
							}
							if (response[PEST_DATA][currentPestRow]['Metolachlor'] !== null) {
								pointGraphic.attributes.Metolachlor = response[PEST_DATA][currentPestRow]['Metolachlor'];
							}
							currentPestRow++;
						}
					}
					
					graphics.push(pointGraphic);
				}
				
				var fields = [{
					name: "ObjectID",
					type: "oid"
				}, {
					name: "siteNumber",
					type: "string"
				}, {
					name: "siteName",
					type: "string"
				}, {
					name: "siteLocation",
					type: "string"
				}];
				
				for (var category in categoryMeasures) {
					for (var key in categoryMeasures[category]) {
						fields.push({
							name: key,
							type: "string",
							defaultValue: "No Data"
						});
					}
				}
				
				var sampleSitesLayer = new FeatureLayer({
					fields: fields,
					objectIdField: "ObjectID",
					geometryType: "point",
					popupTemplate: template,
					source: graphics,
					renderer: renderer
				});
				
				var drainsLayer = new MapImageLayer("http://gis1.acimap.us/imapweb/rest/services/Engineering/Drains/MapServer", null);

				//create the map
				var map = new Map({
					basemap: "gray",
					layers: [sampleSitesLayer, drainsLayer]
				});
		
				const view = new MapView({
					container: "map",
					center: [-85, 41],
					zoom: 8,
					map: map
				});
			
				//add all our Graphics objects that represent our sample collection sites
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