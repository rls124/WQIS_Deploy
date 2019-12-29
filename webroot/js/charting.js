$(document).ready(function() {
	var easter_egg = new Konami(function() {
		var map = document.getElementById("map");
		map.style.display = "none";
		
		var easterEggDiv = document.getElementById("easteregg");
		easterEggDiv.style.display = "block";
		
		//dynamically download the needed code so we don't bog down the 99.9% of users who won't even see this
		import('/WQIS/js/EEGS.js')
			.then((module) => {
				module.start();
			});
	});

	//line graph stuff
	$("#lineBtn").click(function() {
		$('#injectedInfo').remove();
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}
		resetCharts();
		getGraphData($('#startDate').val(), $('#endDate').val(), $('#measurementSelect').val(), "line");
	});

	//table stuff
	$("#tableBtn").click(function() {
		$('#injectedInfo').remove();
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}
		resetCharts();
		getTableData($('#startDate').val(), $('#endDate').val(), $('#measurementSelect').val());
	});

	function resetCharts() {
		//remove the old chart and table
		document.getElementById("chartDiv").innerHTML = "";
		var sampleTable = document.getElementById("tableView");
		if (sampleTable != null) {
			sampleTable.parentNode.removeChild(sampleTable);
		}
	}
	
	function getTableData(startDate, endDate, measure) {
		var sites = $("#site").val();
		
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
				
				var tableHeader = table.insertRow();
				
				Object.keys(response[0][0]).forEach(function(key) {
					if (!(key.includes("Exception") || key == "ID")) {
						var newCell = tableHeader.insertCell();
						newCell.innerText = key;
					}
				});
				
				//fill in each row
				for (var i=0; i<response[0].length; i++) {
					var newRow = table.insertRow();
					
					Object.keys(response[0][i]).forEach(function(key) {
					if (!(key.includes("Exception") || key == "ID")) {
						var newCell = newRow.insertCell();
						
						var textDiv = document.createElement('div');
						textDiv.setAttribute("class", "input text");
						newCell.appendChild(textDiv);
						
						var label = document.createElement('label');
						label.style = "display: table-cell; cursor: pointer; white-space:normal !important;";
						label.setAttribute("class", "btn btn-thin inputHide");
						label.setAttribute("for", key + "-" + i);
						label.innerText = response[0][i][key];
						
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
						cellInput.setAttribute("value", response[0][i][key]);
						
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
				}
	
				document.getElementById("chartDiv").append(table);
			},
			fail: function(response) {
				alert("failed");
			},
			async: false
		});
	}

	function getGraphData(startDate, endDate, measure, graphType) {
		var sites = $("#site").val();
		
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
		for (var k=0; k<measuresAll.length; k++) {
			var newCanvasContainer = document.createElement("div");
			newCanvasContainer.style = "width: 80%; text-align: center; margin: auto;";
			
			var newCanvas = document.createElement("canvas");
			newCanvas.id = "chart-" + k;
			newCanvasContainer.appendChild(newCanvas);
			chartDiv.appendChild(newCanvasContainer);
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
					//format that response
					
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

					var myChart = new Chart(ctx, {
						type: 'line',
						data: {
							labels: labels,
							datasets: datasets
						}
					});
				},
				async: false
			});
		}
	}
});