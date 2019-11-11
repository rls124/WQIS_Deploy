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
		'bridge_to_water_height': ['Bridge to Water Height (m)'],
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
    const SITE_DATA = 'SiteData';
    const BACTERIA_DATA = 'BacteriaData';
    const NUTRIENT_DATA = 'NutrientData';
    const PEST_DATA = 'PestData';
    
    var map; // represents the soon to be created google map
    var previnfowindow = false; // holds the previously opened info window
    var currentBactRow = 0;
    var currentNutrientRow = 0;
    var currentPestRow = 0;
    
	// Initializes the google map with a focus on fort wayne.
	map = new google.maps.Map(document.getElementById('map'), {
		//zoom: 1, // Note Zoom level does not work
		center: new google.maps.LatLng(41.0793, -85.1394),
		mapTypeId: google.maps.MapTypeId.HYBRID
	});
        
    var kmlurl = "http://emerald.pfw.edu/wqis.kml";
	var ctaLayer = new google.maps.KmlLayer({
		url: kmlurl,
		map: map
	});
        
	// Fetches site information from the database.
	$.ajax({
		type: 'Post',
		url: 'fetchSites',
		datatype: 'JSON',
		success: function(response) {
			//response = JSON.parse(response); //server is returning that as text for... some reason?
            
			// Adds markers to the Google Map at each sites longitude and latitude.
			for (var i = 0; i < response[SITE_DATA].length; i++){
				var latLng = new google.maps.LatLng(response[SITE_DATA][i]['Latitude'], response[SITE_DATA][i]['Longitude']);
				var siteNumber = response[SITE_DATA][i]['Site_Number'];
				var siteName = response[SITE_DATA][i]['Site_Name'];
				var siteLocation = response[SITE_DATA][i]['Site_Location'];

				// Picks a marker color depending on which water shed a site resides in
				var markercolor = GetMarkerColor(siteNumber);

				// Configuration for markers is done here
				var marker = CreateMarker(latLng, markercolor, map, siteName);

				// Bacteria Data
				var bactLatestDate = 'No Records Found';
				var ecoli = 'No Data';

				// Nutrient Data
				var nutrientLatestDate = 'No Records Found';
				var totPhos = 'No Data';
				var nit = 'No Data';
				var drp = 'No Data';

				// Pesticide Data
				var pestLatestDate = 'No Records Found';
				var atrazine = 'No Data';
				var alachlor = 'No Data';
				var metolachlor = 'No Data';

				// Check to see if the current site has bacteria data associated with it
				if (response[BACTERIA_DATA][currentBactRow]) {
					var bactSiteNumber = response[BACTERIA_DATA][currentBactRow]['site_location_id'];
					if (siteNumber == bactSiteNumber) {
						bactLatestDate = response[BACTERIA_DATA][currentBactRow]['Date'].split('T')[0];
						if (response[BACTERIA_DATA][currentBactRow]['Ecoli'] !== null) {
							ecoli = response[BACTERIA_DATA][currentBactRow]['Ecoli'];
						}
						currentBactRow++;
					}
				}

				// Check to see if the current site has nutrient data associated with it
				if (response[NUTRIENT_DATA][currentNutrientRow]) {
					var nutrientSiteNumber = response[NUTRIENT_DATA][currentNutrientRow]['site_location_id'];
					if (siteNumber == nutrientSiteNumber) {
						nutrientLatestDate = response[NUTRIENT_DATA][currentNutrientRow]['Date'].split('T')[0];
						if (response[NUTRIENT_DATA][currentNutrientRow]['Phosphorus'] !== null) {
							totPhos = response[NUTRIENT_DATA][currentNutrientRow]['Phosphorus'];
						}
						if (response[NUTRIENT_DATA][currentNutrientRow]['NitrateNitrite'] !== null) {
							nit = response[NUTRIENT_DATA][currentNutrientRow]['NitrateNitrite'];
						}
						if (response[NUTRIENT_DATA][currentNutrientRow]['DRP'] !== null) {
							drp = response[NUTRIENT_DATA][currentNutrientRow]['DRP'];
						}
						currentNutrientRow++;
					}
				}

				// Check to see if the current site has pesticide data associated with it
				if (response[PEST_DATA][currentPestRow]) {
					var pestSiteNumber = response[PEST_DATA][currentPestRow]['site_location_id'];
					if (siteNumber == pestSiteNumber) {
						pestLatestDate = response[PEST_DATA][currentPestRow]['Date'].split('T')[0];
						if (response[PEST_DATA][currentPestRow]['Atrazine'] !== null) {
							atrazine = response[PEST_DATA][currentPestRow]['Atrazine'];
						}
						if (response[PEST_DATA][currentPestRow]['Alachlor'] !== null) {
							alachlor = response[PEST_DATA][currentPestRow]['Alachlor'];
						}
						if (response[PEST_DATA][currentPestRow]['Metolachlor'] !== null) {
							metolachlor = response[PEST_DATA][currentPestRow]['Metolachlor'];
						}
						currentPestRow++;
					}
				}

				// Sets up an info window with most recent information pertaining to a particular site.
				marker.info = new google.maps.InfoWindow({
					content: '<strong style="font-weight:bold;">' + siteNumber + ' ' + siteName + ' - ' + siteLocation + '</strong>' +
						'<br>' +
						'<i>Bacteria (' + bactLatestDate + ')</i>' +
						'<br>' +
						'&ensp;&ensp;&ensp;Ecoli: ' + ecoli + ' CFU/100 ml' +
						'<br><br>' +
						'<i>Nutrients (' + nutrientLatestDate + ')</i>' +
						'<br>' + 
						'&ensp;&ensp;&ensp;Total Phosphorus: ' + totPhos + ' mg/L' +
						'<br>' + 
						'&ensp;&ensp;&ensp;Nitrate/Nitrite: ' + nit + ' mg/L' +
						'<br>' + 
						'&ensp;&ensp;&ensp;Dissolved Reactive Phosphorus: ' + drp + ' mg/L' +
						'<br><br>' +
						'<i>Pesticides (' + pestLatestDate + ')</i>' +
						'<br>' +
						'&ensp;&ensp;&ensp;Atrazine: ' + atrazine + ' µg/L' +
						'<br>' + 
						'&ensp;&ensp;&ensp;Alachlor: ' + alachlor + ' µg/L' +
						'<br>' + 
						'&ensp;&ensp;&ensp;Metolachlor: ' + metolachlor + ' µg/L' +
						'<br>'
					});

			// When a marker is clicked, the previously opened info window is closed and a new one is opened with the appropriate site information
			google.maps.event.addListener(marker, 'click', function() {
				if (previnfowindow) {
					previnfowindow.close();
				}
				var mark = this;
				previnfowindow = mark.info;
				mark.info.open(map, mark);

				// Whenever the Google map itself is clicked, it closes the opened info window 
				google.maps.event.addListener(map, 'click', function() {
					mark.info.close();
				});
			});
		}
	}});
}

function GetMarkerColor(siteNumber) {
    let markercolor = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|ffffff';
    switch(('' + siteNumber)[0]) {
        case '1': markercolor = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|ffca33'; 
            break;
        case '2': markercolor = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|16642A'; 
            break;
        case '3': markercolor = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|f70909';
            break;
        case '4': markercolor = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|ff7926'; 
            break;
    }
    return markercolor;
}

function CreateMarker(latLng, markercolor, map, siteName) {
    return new google.maps.Marker({
                        position: latLng,
                        icon: markercolor,
                        map: map,
                        title: siteName
                    });
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