var wms_layers = [];

var lyr_OpenStreetMap_0 = new ol.layer.Tile({
	'title': 'OpenStreetMap',
	'type': 'base',
	'opacity': 1.000000,        
	source: new ol.source.XYZ({
		attributions: '<a href=""></a>',
		url: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png'
		})
	});
	
var format_sampleData_1 = new ol.format.GeoJSON();

var features_sampleData_1 = format_sampleData_1.readFeatures(json_sampleData_1, {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});

var jsonSource_sampleData_1 = new ol.source.Vector({
    attributions: '<a href=""></a>',
});

jsonSource_sampleData_1.addFeatures(features_sampleData_1);
var lyr_sampleData_1 = new ol.layer.Vector({
	declutter: true,
	source:jsonSource_sampleData_1, 
	style: style_sampleData_1,
	title: '<img src="styles/legend/sampleData_1.png" /> sampleData'
});

lyr_OpenStreetMap_0.setVisible(true);
lyr_sampleData_1.setVisible(true);
var layersList = [lyr_OpenStreetMap_0,lyr_sampleData_1];
lyr_sampleData_1.set('fieldAliases', {'LATITUDE': 'LATITUDE', 'LONGITUDE': 'LONGITUDE', 'WaterSampleValue': 'WaterSampleValue', 'Name': 'Name'});
lyr_sampleData_1.set('fieldImages', {'LATITUDE': '', 'LONGITUDE': '', 'WaterSampleValue': '', });
lyr_sampleData_1.set('fieldLabels', {'LATITUDE': 'inline label', 'LONGITUDE': 'inline label', 'WaterSampleValue': 'inline label', 'Name': 'inline label'});

lyr_sampleData_1.on('precompose', function(evt) {
	evt.context.globalCompositeOperation = 'normal';
});