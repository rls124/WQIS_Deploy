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

$(document).ready(function () {
	var chartsDisplayMode = "in-line";
	
	//list measurement names/database names available for each category
	var bacteriaData = {'select': ['Select a measure'],
		'Ecoli': ['E. Coli (CFU/100 mil)'],
		'TotalColiform': ['Coliform (CFU/100 mil)']};
	var nutrientData = {'select': ['Select a measure'],
		'NitrateNitrite': ['Nitrate/Nitrite (mg/L)'],
		'Phosphorus': ['Total Phosphorus (mg/L)'],
		'DRP': ['Dissolved Reactive Phosphorus (mg/L)'],
		'Ammonia': ['Ammonia (mg/L)']};
	var pesticideData = {'select': ['Select a measure'],
		'Alachlor': ['Alachlor (µg/L)'],
		'Atrazine': ['Atrazine (µg/L)'],
		'Metolachlor': ['Metolachlor (µg/L)']};
	var physProp = {'select': ['Select a measure'],
		'Conductivity': ['Conductivity (mS/cm)'],
		'DO': ['Dissolved Oxygen (mg/L'],
		'Bridge_to_Water_Height': ['Bridge to Water Height (in)'],
		'pH': ['pH'],
		'Water_Temp': ['Water Temperature (°C)'],
		'TDS': ['Total Dissolved Solids (g/L)'],
		'Turbidity': ['Turbidity (NTU)']};
		
	$("#sites").change(function () {
        getRange();
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
		var categoryData;
		
		//first clear all the measures currently listed
		while (measureSelect.options.length > 0) {
			measureSelect.remove(0);
		}
		checkboxList.innerHTML = "";

		switch (document.getElementById('categorySelect').value) {
			case 'bacteria':
				categoryData = bacteriaData;
				break;
			case 'nutrient':
				categoryData = nutrientData;
				break;
			case 'pesticide':
				categoryData = pesticideData;
				break;
			case 'physical':
				categoryData = physProp;
				break;
		}
	
		for (var i in categoryData) {
			//fill in the measurementSelect dropdown
			var option = document.createElement('option');
			option.value = i;
			option.text = categoryData[i];
			measureSelect.appendChild(option);
		
			//now create the checkboxes as well
			if (i != 'select') {
				var listItem = document.createElement('li');
			
				var box = document.createElement('input');
				box.value = i;
				box.id = i + "Checkbox";
				box.type = "checkbox";
		
				var boxLabel = document.createElement('label');
				boxLabel.innerText = i;
				boxLabel.for = i + "Checkbox";
		
				listItem.appendChild(box);
				listItem.appendChild(boxLabel);
		
				checkboxList.appendChild(listItem);
			}
		}
		
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
	
	$("#searchButton").click(function () {
		toggleSidebar();
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
		var measurementSelect = document.getElementById("measurementSelect").value;
		
		$.ajax({
			type: "POST",
			url: "/WQIS/generic-samples/tableRawData",
			datatype: 'JSON',
			data: {
				'sites': sites,
				'startDate': startDate,
				'endDate': endDate,
				'category': categorySelect,
				'amountEnter': amountEnter,
				'overUnderSelect': overUnderSelect,
				'measurementSelect': measurementSelect
			},
			success: function(response) {
				//create the blank table
				var table = document.createElement("table");
				table.setAttribute("class", "table table-striped table-responsive");
				table.id = "tableView";
				
				//build the header row first
				var tableHeader = table.insertRow();
				Object.keys(response[0][0]).forEach(function(key) {
					if (!(key == "ID")) {
						var newCell = tableHeader.insertCell();
						newCell.innerText = key;
					}
				});
				
				var actionCell = tableHeader.insertCell();
				actionCell.innerText = "Actions";
				
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
				
								alert(sampleNumber);
				
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

	function getGraphData(startDate, endDate) {
		var sites = $("#sites").val();
		
		//get all the selected checkboxes
		var measuresAll = [];
		
		var checkboxList = document.getElementById('checkboxList').getElementsByTagName('input');
		
		for (var k=0; k<checkboxList.length; k++) {
			if (checkboxList[k].checked == true) {
				measuresAll.push(checkboxList[k].value);
			}
		}
		
		//build the necessary canvases
		var chartDiv = document.getElementById("chartDiv");
		var nMeasures = measuresAll.length;
		
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
		for (var k=0; k<measuresAll.length; k++) {
			$.ajax({
				type: "POST",
				url: "/WQIS/generic-samples/graphdata",
				datatype: 'JSON',
				data: {
					'sites': sites,
					'startDate': startDate,
					'endDate': endDate,
					'measure': measuresAll[k]
				},
				success: function(response) {
					function selectColor(colorIndex, palleteSize) {
						//returns color at an index of an evenly-distributed color pallete of arbitrary size
						if (palleteSize < 1) {
							palleteSize = 1; //defaults to one color, can't divide by zero or the universe implodes
						}
						
						return "hsl(" + (colorIndex * (360 / palleteSize) % 360) + ",100%,50%)";
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

					if (document.getElementById("showBenchmarks").checked) {
						//add benchmark lines
						var benchmarks = response[1][0]; //max and min
						
						var benchmarkLines = [];
						if (benchmarks["max"] != null) {
							benchmarkLines.push({
								type: 'line',
								mode: 'horizontal',
								scaleID: 'y-axis-0',
								value: benchmarks["max"],
								borderColor: 'red',
								borderWidth: 1,
								label: {
									enabled: false,
									content: "max"
								}
							});
						}
						if (benchmarks["min"] != null) {
							benchmarkLines.push({
								type: 'line',
								mode: 'horizontal',
								scaleID: 'y-axis-0',
								value: benchmarks["min"],
								borderColor: 'blue',
								borderWidth: 1,
								label: {
									enabled: false,
									content: "min"
								}
							});
						}
					}
					else {
						var benchmarkLines = [{}]; //effectively null
					}

					var myChart = new Chart(ctx, {
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
										labelString: measuresAll[k]
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