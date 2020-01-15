var spinnerInhibited = true; //inhibit this initially so basic setup tasks that are done through AJAX, like loading the map, can be done without showing this. Can also inhibit as needed for minor things that aren't expected to take much time

//loading graphic
$(document).ajaxStart(function() {
	//check if loading spinner is inhibited first
	if (!spinnerInhibited) {
		$('.loadingspinnermain').css('visibility', 'visible');
		$('body').css('cursor', 'wait');
	}
}).ajaxStop(function() {
    $('.loadingspinnermain').css('visibility', 'hidden');
    $('body').css('cursor', 'default');
});

const SITE_DATA = 'SiteData';
const BACTERIA_DATA = 'BacteriaData';
const NUTRIENT_DATA = 'NutrientData';
const PEST_DATA = 'PestData';

var chartsDisplayMode = "in-line";
var tablePage = 1;
var numPages = -1;

//measurement names/database names available for each category
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

$(document).ready(function () {
	//map code
	require([
		"esri/Map",
		"esri/views/MapView",
		"esri/layers/MapImageLayer",
		"esri/Graphic",
		"esri/layers/FeatureLayer",
		"esri/layers/KMLLayer"
	], function(Map, MapView, MapImageLayer, Graphic, FeatureLayer, KMLLayer) {
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

				var kmlurl = "http://emerald.pfw.edu/WQIS/img/wqisDev.kml?_=" + new Date().getTime(); //date/time at end is to force ESRI's server to not cache it. Remove this once dev is finished
				var watershedsLayer = new KMLLayer({
					url: kmlurl
				});

				//create the map
				var map = new Map({
					basemap: "gray",
					layers: [sampleSitesLayer, drainsLayer, watershedsLayer]
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
				var watershedsLayerToggle = document.getElementById("watershedsLayer");
				watershedsLayerToggle.addEventListener("change", function() {
					watershedsLayer.visible = watershedsLayerToggle.checked;
				});
				
				drainsLayer.visible = false;
				var drainsLayerToggle = document.getElementById("drainsLayer");
				drainsLayerToggle.addEventListener("change", function() {
					drainsLayer.visible = drainsLayerToggle.checked;
				});
				
				//handle the dropdown that allows basemap to be changed
				var basemapSelect = document.getElementById("selectBasemap");
				basemapSelect.addEventListener("change", function() {
					map.basemap = basemapSelect.value;
				});
			}
		});
	});
		
	$("#sites").change(function() {
        getRange();
    });
	
	$("#allCheckbox").change(function() {
		var checkboxList = document.getElementsByClassName("measurementCheckbox");
		for (i=0; i<checkboxList.length; i++) {
			checkboxList[i].checked = document.getElementById("allCheckbox").checked;
		}
	});
	
	function checkboxesChanged() {
		var checkboxList = document.getElementsByClassName("measurementCheckbox");
		
		var allSelected = true;
		for (i=0; i<checkboxList.length; i++) {
			if (checkboxList[i].checked == false) {
				allSelected = false;
				break;
			}
		}
		
		if (allSelected == false) {
			//deselect the All checkbox
			document.getElementById("allCheckbox").checked = false;
		}
	}
	
	$(".measurementCheckbox").change(function() {
		checkboxesChanged();
	});

    function getRange() {
		spinnerInhibited = true;
		
		//if both variables are not null, then we may submit an sql request
        var siteData = document.querySelector('#sites').value;
        var categoryData = $('#categorySelect').val();
        if ((siteData !== null && siteData !== 'select') && categoryData !== null) {
            $.ajax({
                type: "POST",
                url: "daterange",
                data: {
                    'site': siteData,
                    'category': categoryData
                },
                datatype: 'JSON',
                success: function (data) {
                    var startDateData = data[0];
                    var endDateData = data[1];
                    $('#startDate').val(startDateData);
                    $('#endDate').val(endDateData);
                    $("#startDate").datepicker('update', startDateData);
                    $("#endDate").datepicker('update', endDateData);
                }
            });
        }
		
		spinnerInhibited = false;
    }
	
    document.getElementById('categorySelect').addEventListener("change", changeMeasures);
    $(".date-picker").datepicker({
        trigger: "focus",
        format: 'mm/dd/yyyy',
        todayHighlight: true,
        todayBtn: "linked"
    });

    $("#startDate").datepicker().on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endDate').datepicker('setStartDate', minDate);
    });
    $("#endDate").datepicker().on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#startDate').datepicker('setEndDate', maxDate);
    });

	$('#sites').select2({
		closeOnSelect: false,
		placeholder: "Select sites",
		width: 'resolve'
	});
	
	var easter_egg = new Konami(function() {
		//dynamically download the needed code so we don't bog down the 99.9% of users who won't even see this
		import('/WQIS/js/EEGS.js')
			.then((module) => {
				module.start();
			});
	});
	
	document.addEventListener('keydown', function(e) {
		if (e.keyCode == 27) {
			//when user hits escape key, close the sidebar
			sidebarSize("0");
		}
	}, false);
	
	$("#exportBtn").click(function () {
		var sampleType = $('#categorySelect').val();		
		var startDate = $('#startDate').val();
		var endDate = $('#endDate').val();
		var sites = $('#sites').val();
		
		var amountEnter = $("#amountEnter").val();
		var overUnderSelect = $("#overUnderSelect").val();
		
		var measures = ['all'];
		
		var measurementSelect = $("#measurementSelect").val();

		$.ajax({
			type: "POST",
			url: "/WQIS/export/exportData",
			datatype: 'JSON',
			data: {
				'type': sampleType,
				'startDate': startDate,
				'endDate': endDate,
				'sites': sites,
				'measures': measures,
				'amountEnter': amountEnter,
				'overUnderSelect': overUnderSelect,
				'measurementSelect': measurementSelect
			},
			success: function (response) {
				downloadFile(response, sampleType);
			},
			failure: function (response) {
				alert("Failed");
			}
		});
	});
	
	function changeMeasures() {
		//when the measurement category is changed, change both lists of available measurements to match
		var measureSelect = document.getElementById('measurementSelect');
		var checkboxList = document.getElementById('checkboxList');
		var measurementCheckboxes = document.getElementsByClassName("measurementCheckbox");
		var categoryData = categoryMeasures[document.getElementById('categorySelect').value];
		
		//first clear all the measures currently listed
		while (measureSelect.options.length > 0) {
			measureSelect.remove(0);
		}
		
		for (i=measurementCheckboxes.length-1; i>=0; i--) {
			checkboxList.removeChild(measurementCheckboxes[i].parentNode);
		}
		
		document.getElementById("allCheckbox").checked = true;
	
		var option = document.createElement('option');
		option.value = "select";
		option.text = "Select a measure";
		measureSelect.appendChild(option);
		
		for (var i in categoryData) {
			//fill in the measurementSelect dropdown
			if (!(categoryData[i]["visible"] == false)) {
				var option = document.createElement('option');
				option.value = i;
				option.text = categoryData[i]["text"];
				measureSelect.appendChild(option);
			
				//now create the checkboxes as well
				var listItem = document.createElement('li');
				
				var box = document.createElement('input');
				box.value = i;
				box.id = i + "Checkbox";
				box.type = "checkbox";
				box.setAttribute("class", "measurementCheckbox");
				box.checked = true;
			
				var boxLabel = document.createElement('label');
				boxLabel.innerText = categoryData[i]["text"];
				boxLabel.setAttribute("for", i + "Checkbox");
			
				listItem.appendChild(box);
				listItem.appendChild(boxLabel);
			
				checkboxList.appendChild(listItem);
			}
		}
		
		$(".measurementCheckbox").change(function() {
			checkboxesChanged();
		});
		
        getRange(); //recalculate date range
	}
	
	function downloadFile(fileData, type) {
		if (fileData.length < 1) {
			return;
		}
		
		var csvContent = "data:text/csv;charset=utf-8,";
		var fields = Object.keys(fileData[0]);
		for (var i = 0; i < fileData.length; i++) {
			fileData[i]['Date'] = fileData[i]['Date'].substring(0, 10);
		}

		//if ID field exists, remove it
		if (fields[0] === "ID") {
			fields = fields.splice(1, fields.length);
		}
		
		//make null values not have text
		var replacer = function (key, value) {
			return value === null ? '' : value;
		};

		var csv = fileData.map(function (row) {
			return fields.map(function (fieldName) {
				return JSON.stringify(row[fieldName], replacer);
			}).join(',');
		});
		fields[fields.indexOf('site_location_id')] = 'Site Number';
		
		//add header column
		csv.unshift(fields.join(','));

		csvContent += csv.join('\r\n');
		var encodedUri = encodeURI(csvContent);
		var link = document.createElement("a");
		link.setAttribute("href", encodedUri);
		var name = type + '_export.csv';
		link.setAttribute("download", name);
		document.body.appendChild(link);
		link.click();
	}
	
	$("#updateButton").click(function() {
		resetCharts();
		getGraphData($('#startDate').val(), $('#endDate').val());
		getTableData($('#startDate').val(), $('#endDate').val());
		$("#chartsLayoutSelect").show();
	});
	
	$("#resetButton").click(function() {
		//clear all parameters to default values, and clear the chart/table view
		resetCharts();
		$("#sites").val(null).trigger("change");
		$("#categorySelect").val("bacteria");
		changeMeasures();
		$("#chartsLayoutSelect").hide();
	});
	
	$("#chartsInlineButton").click(function() {
		chartsDisplayMode = "in-line";
		document.getElementById("chartDiv").innerHTML = "";
		getGraphData($('#startDate').val(), $('#endDate').val());
	});
	
	$("#chartsGridButton").click(function() {
		chartsDisplayMode = "grid";
		document.getElementById("chartDiv").innerHTML = "";
		getGraphData($('#startDate').val(), $('#endDate').val());
	});
	
	$("#searchButton").click(function() {
		toggleSidebar();
	});
	
	function setResultsPage(page) {
		tablePage = page;
		document.getElementById("tableDiv").innerHTML = "";
		document.getElementById("pageNumBox").value = tablePage;
		getTableData($('#startDate').val(), $('#endDate').val());
		
		$("#firstPageButton").attr("disabled", false);
		$("#previousPageButton").attr("disabled", false);
		$("#lastPageButton").attr("disabled", false);
		$("#nextPageButton").attr("disabled", false);
		
		if (tablePage == 1) {
			$("#previousPageButton").attr("disabled", true);
			$("#firstPageButton").attr("disabled", true);
		}
		else if (tablePage == numPages) {
			$("#nextPageButton").attr("disabled", true);
			$("#lastPageButton").attr("disabled", true);
		}
	}
	
	$("#firstPageButton").click(function() {
		setResultsPage(1);
	});
	
	$("#previousPageButton").click(function() {
		setResultsPage(tablePage-1);
	});
	
	$("#nextPageButton").click(function() {
		setResultsPage(tablePage+1);
	});
	
	$("#lastPageButton").click(function() {
		setResultsPage(numPages);
	});
	
	function toggleSidebar() {
		//expand the search sidebar and shift the rest of the page over, or the opposite
		if (document.getElementById("mySidebar").style.width == "450px") {
			sidebarSize("0")
		}
		else {
			sidebarSize("450px");
		}
	}

	function sidebarSize(width) {
		document.getElementById("mySidebar").style.width = width;
		document.getElementById("main").style.marginLeft = width;
		document.getElementById("navbar").style.marginLeft = width;
	}

	function resetCharts() {
		//remove the old chart and table
		document.getElementById("chartDiv").innerHTML = "";
		document.getElementById("tableDiv").innerHTML = "";
		
		var sampleTable = document.getElementById("tableView");
		if (sampleTable != null) {
			sampleTable.parentNode.removeChild(sampleTable);
		}
	}
	
	function getTableData(startDate, endDate) {
		var sites = $("#sites").val();
		var categorySelect = document.getElementById("categorySelect").value;
		var amountEnter = document.getElementById("amountEnter").value;
		var overUnderSelect = document.getElementById("overUnderSelect").value;
		var measurementSearch = document.getElementById("measurementSelect").value;
		var numRows = document.getElementById("numRowsDropdown").value;
		var selectedMeasures = getSelectedMeasures();
		
		//check if there are associated RawCount columns we should include for those selected measures as well
		var hasRawCount = ["Ecoli", "TotalColiform"];
		
		//we want to keep these in order, so we make a queue first containing all the column names and positions that need to be inserted...
		var queue = [];
		for (i=0; i<selectedMeasures.length; i++) {
			if (hasRawCount.includes(selectedMeasures[i])) {
				queue.push([selectedMeasures[i] + "RawCount", i+1]);
			}
		}
		
		//now add the queue contents to the selectedMeasures array itself, in the correct locations
		for (i=0; i<queue.length; i++) {
			selectedMeasures.splice(queue[i][1] + i, 0, queue[i][0]); //+i to account for the number of columns already inserted
		}
		
		//get the number of records
		$.ajax({
			type: "POST",
			url: "/WQIS/generic-samples/tablePages",
			datatype: 'JSON',
			data: {
				'sites': sites,
				'startDate': startDate,
				'endDate': endDate,
				'category': categorySelect,
				'amountEnter': amountEnter,
				'overUnderSelect': overUnderSelect,
				'measurementSearch': measurementSearch,
				'selectedMeasures': selectedMeasures,
			},
			success: function(response) {
				numPages = Math.ceil(response[0] / numRows);
				document.getElementById("totalPages").innerText = numPages;
			}
		});
			
		$.ajax({
			type: "POST",
			url: "/WQIS/generic-samples/tabledata",
			datatype: 'JSON',
			data: {
				'sites': sites,
				'startDate': startDate,
				'endDate': endDate,
				'category': categorySelect,
				'amountEnter': amountEnter,
				'overUnderSelect': overUnderSelect,
				'measurementSearch': measurementSearch,
				'selectedMeasures': selectedMeasures,
				'numRows': numRows,
				'pageNum': tablePage
			},
			success: function(response) {
				//create the blank table
				var table = document.createElement("table");
				table.setAttribute("class", "table table-striped table-responsive");
				table.id = "tableView";
				
				//build the header row first
				var tableHeader = table.insertRow();

				tableHeader.insertCell().innerText = "Site ID";
				tableHeader.insertCell().innerText = "Date";
				tableHeader.insertCell().innerText = "Sample Number";
				var colNames = categoryMeasures[categorySelect];
				for (i=0; i<selectedMeasures.length; i++) {
					var newCell = tableHeader.insertCell();
					newCell.innerText = colNames[selectedMeasures[i]]["text"];
				}
				tableHeader.insertCell().innerText = "Comments";
				tableHeader.insertCell().innerText = "Actions";
				
				//fill in each row
				for (var i=0; i<response[0].length; i++) {
					var newRow = table.insertRow();
					
					Object.keys(response[0][i]).forEach(function(key) {
						if (!(key == "ID")) {
							var newCell = newRow.insertCell();
							var value = response[0][i][key];
							
							if (key == "Date") {
								//we get the date in a weird format, parse it to something more appropriate
								value = value.split("T")[0];
							}
						
							var textDiv = document.createElement('div');
							textDiv.setAttribute("class", "input text");
							newCell.appendChild(textDiv);
						
							var label = document.createElement('label');
							label.style = "display: table-cell; cursor: pointer; white-space:normal !important;";
							label.setAttribute("class", "btn btn-thin inputHide");
							label.setAttribute("for", key + "-" + i);
							label.innerText = value;
						
							label.onclick = function () {
								var label = $(this);
								var input = $('#' + label.attr('for'));
								input.trigger('click');
								label.attr('style', 'display: none');
								input.attr('style', 'display: in-line');
							};
						
							textDiv.appendChild(label);
							
							var cellInput = document.createElement("input");
							cellInput.type = "text";
							cellInput.name = key + "-" + i;
							cellInput.setAttribute("maxlength", 20);
							cellInput.size = 20;
							cellInput.setAttribute("class", "inputfields tableInput");
							cellInput.style = "display: none";
							cellInput.id = key + "-" + i;
							cellInput.setAttribute("value", value);
						
							cellInput.onfocusout = (function () {
								var input = $(this);

								if (!input.attr('id')) {
									return;
								}
	
								var rowNumber = (input.attr('id')).split("-")[1];
								var sampleNumber = $('#Sample_Number-' + rowNumber).val();
	
								var parameter = (input.attr('name')).split("-")[0];
								var value = input.val();

								$.ajax({
									type: "POST",
									url: "/WQIS/generic-samples/updatefield",
									datatype: 'JSON',
									data: {
										'sampleNumber': sampleNumber,
										'parameter': parameter,
										'value': value
									},
									success: function () {
										var label = $('label[for="' + input.attr('id') + '"');

										input.attr('style', 'display: none');
										label.attr('style', 'display: in-line; cursor: pointer');

										if (value === '') {
											label.text('  ');
										}
										else {
											label.text(value);
										}
									},
									failure: function() {
										alert("failed");
									}
								});
							});
						
							textDiv.appendChild(cellInput);
						}
					});
					
					//add the deletion button
					var newCell = newRow.insertCell();
					var delButton = document.createElement("span");
					delButton.setAttribute("class", "delete glyphicon glyphicon-trash");
					delButton.setAttribute("id", "Delete-" + i);
					delButton.setAttribute("name", "Delete-" + i);
					delButton.onclick = function() {
						var rowDiv = this;
		
						if (!$(rowDiv).attr('id')) {
							return;
						}
		
						$.confirm("Are you sure you want to delete this record?", function (bool) {
							if (bool) {
								var rowNumber = ($(rowDiv).attr('id')).split("-")[1];
								var sampleNumber = $('#Sample_Number-' + rowNumber).val();
				
								//Now send ajax data to a delete script
								$.ajax({
									type: "POST",
									url: "/WQIS/generic-samples/deleteRecord",
									datatype: 'JSON',
									data: {
										'sampleNumber': sampleNumber,
										'type': categorySelect
									},
									success: function () {
										//remove the row from view
										rowDiv.parentNode.parentNode.style.display = "none";
						
										//future work: build a new table, to still maintain 20 total rows and have correct black/white/black sequencing after deletions
									},
									fail: function () {
										alert("Deletion failed");
									}
								});
							}
						});
					}
					newCell.append(delButton);
				}
	
				document.getElementById("tableDiv").append(table);
			},
			fail: function(response) {
				alert("failed");
			},
			async: false
		});
	}
	
	function getSelectedMeasures() {
		var measures = [];
		
		var checkboxList = document.getElementsByClassName('measurementCheckbox');
		
		for (var i=0; i<checkboxList.length; i++) {
			if (checkboxList[i].checked) {
				measures.push(checkboxList[i].value);
			}
		}
		
		return measures;
	}

	function getGraphData(startDate, endDate) {
		var sites = $("#sites").val();
		var measures = getSelectedMeasures();
		var category = $('#categorySelect').val();
		
		//build the necessary canvases
		var chartDiv = document.getElementById("chartDiv");
		var nMeasures = measures.length;
		
		if (chartsDisplayMode == "in-line") {
			for (var k=0; k<nMeasures; k++) {
				var newCanvasContainer = document.createElement("div");
				newCanvasContainer.style = "width: 80%; text-align: center; margin: auto;";
			
				var newCanvas = document.createElement("canvas");
				newCanvas.id = "chart-" + k;
				newCanvasContainer.appendChild(newCanvas);
				chartDiv.appendChild(newCanvasContainer);
			}
		}
		else {
			//grid view
			var chartsGrid = document.createElement("div");
			chartsGrid.setAttribute("class", "container");
			
			//figure out the number of rows, assuming 2 columns each
			var nx = 2;
			var ny = Math.ceil(nMeasures/nx);
			for (y=0; y<ny; y++) {
				var row = document.createElement("div");
				row.setAttribute("class", "row");
				
				for (x=0; x<nx; x++) {
					var chartNum = (y*x + x);
					if (chartNum < nMeasures) {
						var cell = document.createElement("div");
						cell.setAttribute("class", "col-sm");
					
						var newCanvasContainer = document.createElement("div");
						newCanvasContainer.style = "width: 100%; text-align: center; margin: auto;";
					
						var newCanvas = document.createElement("canvas");
						newCanvas.id = "chart-" + chartNum;
						newCanvasContainer.appendChild(newCanvas);
					
						cell.appendChild(newCanvasContainer);
						row.appendChild(cell);
					}
				}
				chartsGrid.appendChild(row);
			}
			chartDiv.appendChild(chartsGrid);
		}
		
		//get data and fill the charts in
		for (var k=0; k<nMeasures; k++) {
			$.ajax({
				type: "POST",
				url: "/WQIS/generic-samples/graphdata",
				datatype: 'JSON',
				data: {
					'sites': sites,
					'startDate': startDate,
					'endDate': endDate,
					'measure': measures[k],
					"category": category
				},
				success: function(response) {
					function selectColor(colorIndex, palleteSize) {
						//returns color at an index of an evenly-distributed color pallete of arbitrary size
						if (palleteSize < 1) {
							palleteSize = 1; //defaults to one color, can't divide by zero or the universe implodes
						}
						
						return "hsl(" + (colorIndex * (360 / palleteSize) % 360) + ",70%,50%)";
					}
					
					var datasets = [];
					for (i=0; i<sites.length; i++) {
						var newDataset = {
							label: sites[i],
							borderColor: selectColor(i, sites.length),
							data: []
						};
						
						datasets.push(newDataset);
					}
					
					var labels = [];
					
					for (i=0; i<response[0].length; i++) {
						var newRow = []
						
						var date = response[0][i].date.split("T")[0];
						
						newRow.t = date;
						newRow.y = response[0][i].value;
						
						for (j=0; j<sites.length; j++) {
							if (response[0][i].site == sites[j]) {
								datasets[j].data.push(newRow);
								break;
							}
						}
						
						//make sure there isn't already a label created for this date, or things break in weird ways
						var found = false;
						for (j=0; j<labels.length; j++) {
							if (labels[j] == date) {
								found = true;
								break;
							}
						}
						if (found == false) {
							labels.push(date);
						}
					}
					
					var ctx = document.getElementById("chart-" + k).getContext("2d");
					var benchmarkLines = [];

					if (document.getElementById("showBenchmarks").checked) {
						//add benchmark lines
						var benchmarks = response[1][0]; //max and min
						
						function bench(val, color) {
							return {
								type: 'line',
								mode: 'horizontal',
								scaleID: 'y-axis-0',
								value: val,
								borderColor: color,
								borderWidth: 3,
								drawTime: 'afterDatasetsDraw',
							};
						}
						
						if (benchmarks["max"] != null) {
							benchmarkLines.push(bench(benchmarks["max"], "red"));
						}
						if (benchmarks["min"] != null) {
							benchmarkLines.push(bench(benchmarks["min"], "blue"));
						}
					}

					new Chart(ctx, {
						type: 'line',
						data: {
							labels: labels,
							datasets: datasets
						},
						options: {
							annotation: {
								annotations: benchmarkLines
							},
							scales: {
								yAxes: [{
									scaleLabel: {
										display: true,
										labelString: categoryMeasures[category][measures[k]]["text"]
									}
								}]
							}
						}
					});
				},
				async: false
			});
		}
	}
	
	spinnerInhibited = false;
});