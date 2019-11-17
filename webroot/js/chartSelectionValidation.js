function changeMeasures() {
    dropMeasures();
    var chosenMeasure = document.getElementById('categorySelect');
    var bacteriaData = {'select': ['Select a measure'],
        'ecoli': ['E. Coli (CFU/100 mil)']};
    var nutrientData = {'select': ['Select a measure'],
        'nitrateNitrite': ['Nitrate/Nitrite (mg/L)'],
        'phosphorus': ['Total Phosphorus (mg/L)'],
        'drp': ['Dissolved Reactive Phosphorus (mg/L)'],
		'ammonia': ['Ammonia (mg/L)']};
    var pesticideData = {'select': ['Select a measure'],
        'alachlor': ['Alachlor (µg/L)'],
        'atrazine': ['Atrazine (µg/L)'],
        'metolachlor': ['Metolachlor (µg/L)']};
    var physProp = {'select': ['Select a measure'],
        'conductivity': ['Conductivity (mS/cm)'],
        'do': ['Dissolved Oxygen (mg/L'],
		'bridge_to_water_height': ['Bridge to Water Height (in)'],
        'ph': ['pH'],
        'water_temp': ['Water Temperature (°C)'],
        'tds': ['Total Dissolved Solids (g/L)'],
        'turbidity': ['Turbidity (NTU)']};

    switch (chosenMeasure.value) {
        case 'bacteria':
            populateMeasurementSelect(bacteriaData);
            break;
        case 'nutrient':
            populateMeasurementSelect(nutrientData);
            break;
        case 'pesticide':
            populateMeasurementSelect(pesticideData);
            break;
        case 'wqm':
            populateMeasurementSelect(physProp);
            break;
    }
}

function dropMeasures() {
    var measureSelect = document.getElementById('measurementSelect');
    while (measureSelect.options.length > 0) {
        measureSelect.remove(0);
    }
}

function populateMeasurementSelect(categoryData) {
    for (var i in categoryData) {
        var option = document.createElement('option');
        option.value = i;
        option.text = categoryData[i];
        document.getElementById('measurementSelect').appendChild(option);
    }
}

function initMap() {
	var request = new XMLHttpRequest();
	request.open('POST', './fetchSites', false); //false makes the request synchronous
	request.send(null);
	
	var string_sampleData_1 = request.responseText;
	
	//for some reason the controller is returning a JSON string *plus* the HTML for the page... I don't even know. Lets just split off the part we need
	var json_sampleData_1 = JSON.parse(string_sampleData_1.split("<!DOCTYPE html>", 2)[0]);
	
	//alert(JSON.stringify(json_sampleData_1)); //for debugging purposes
	
	//now we need to extract the relevant parts and build what qgis2web expects
	var correctFormat = {};
	
	correctFormat.type = 'FeatureCollection';
	correctFormat.name = 'sampleData_1';
	correctFormat.crs = { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } };
	
	var features = [];
	
	for (var i=0; i<json_sampleData_1["SiteData"].length; i++) {
		var latitude = json_sampleData_1["SiteData"][i]["Latitude"];
		var longitude = json_sampleData_1["SiteData"][i]["Longitude"];
		var siteLocation = json_sampleData_1["SiteData"][i]["Site_Location"];
		var siteName = json_sampleData_1["SiteData"][i]["Site_Name"];
		
		var thisFeature = {};
		thisFeature.type = "Feature";
		var properties = {};
		properties.latitude = latitude;
		properties.longitude = longitude;
		properties.siteLocation = siteLocation;
		properties.siteName = siteName;
		
		thisFeature.properties = properties;
		
		var geometry = {};
		geometry.type = "Point";
		
		var coords = [];
		coords.push(longitude);
		coords.push(latitude);
		
		geometry.coordinates = coords;
		thisFeature.geometry = geometry;
		
		features.push(thisFeature);
	}
	
	correctFormat.features = features;
	
	return correctFormat;
}

$(document).ready(function () {
    document.getElementById('categorySelect').addEventListener("change", changeMeasures);
    $(".date-picker").datepicker({
        trigger: "focus",
        format: 'mm/dd/yyyy',
        todayHighlight: true,
        todayBtn: "linked"
    });

    $("#startdate").datepicker().on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endDate').datepicker('setStartDate', minDate);
    });
    $("#enddate").datepicker().on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#startDate').datepicker('setEndDate', maxDate);
    });
});