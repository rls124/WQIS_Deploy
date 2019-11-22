$(document).ready(function() {
	//select the checkbox that is associated with the POST data
	$('input[type="checkbox"][name="sites\\[\\]"][value="' + $("#site").val() + '"]').prop('checked', true);

	//run the first instance of the chart
	$('#chartTitle').html('<strong style="font-size:14pt;">' + $('#measurementSelect option:selected').text() + " Histogram</strong>");
	getGraphData($('#startdate').val(), $('#enddate').val(), $('#measurementSelect').val(), "bar");

	//change the date range based off of what sites are checked
	$(document).on('change', 'input[type="checkbox"][name="sites\\[\\]"]', (function() {
		var siteDoms = $('input[type="checkbox"][name="sites\\[\\]"]:checked');
		var sites = [];
		siteDoms.each(function() {
			sites.push($(this).val());
		});
		if (sites.length > 0) {
			$.ajax({
				type: "POST",
				url: "daterange",
				datatype: 'JSON',
				data: {
					'sites': sites
				},
				success: function(response) {
					var startDateData = response[0];
					var endDateData = response[1];
					$('#startdate').val(startDateData);
					$('#enddate').val(endDateData);
					$("#startdate").datepicker('update', startDateData);
					$("#enddate").datepicker('update', endDateData);
				}
			});
		}
	}));

	//Bar Graph stuff
	$("#chartBtn").click(function() {
		$('#injectedInfo').remove();
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}
		//update chart label
		$('#chartTitle').html('<strong style="font-size:14pt;">' + $('#measurementSelect option:selected').text() + " Histogram</strong>");
		resetCharts();
		getGraphData($('#startdate').val(), $('#enddate').val(), $('#measurementSelect').val(), "bar");
	});

	//Line Graph stuff
	$("#lineBtn").click(function() {
		$('#injectedInfo').remove();
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}
		//update chart label
		$('#chartTitle').html('<strong style="font-size:14pt;">' + $('#measurementSelect option:selected').text() + " Line graph</strong>");
		resetCharts();
		getGraphData($('#startdate').val(), $('#enddate').val(), $('#measurementSelect').val(), "line");
	});

	//Table stuff
	$("#tableBtn").click(function() {
		$('#injectedInfo').remove();
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}
		//update chart label
		$('#chartTitle').html('<strong style="font-size:14pt;">' + $('#measurementSelect option:selected').text() + " Table</strong>");
		resetCharts();
		getGraphData($('#startdate').val(), $('#enddate').val(), $('#measurementSelect').val(), "table");
	});

	function resetCharts() {
		//remove the old chart and table
		$("svg").empty();
		var sampleTable = document.getElementById("sampleTable");
		if (sampleTable != null) {
			sampleTable.parentNode.removeChild(sampleTable);
		}
	}

	function getGraphData(startdate, enddate, measure, graphType) {
		//get all of the checked checkboxes
		var siteDoms = $('input[type="checkbox"][name="sites\\[\\]"]:checked');

		var sites = [];
		siteDoms.each(function() {
			sites.push($(this).val());
		});

		//if its a bar graph, only use the first site
		if (graphType === "bar") {
			sites.splice(1);
		}
		$.ajax({
			type: "POST",
			url: "graphdata",
			datatype: 'JSON',
			data: {
				'sites': sites,
				'startdate': startdate,
				'enddate': enddate,
				'measure': measure
			},
			success: function(response) {
				var data = formatData(response[0], sites);
				buildGraph(data, response[1][0], graphType, data.length / sites.length);

				var measurementUnits = $('#measurementSelect :selected').text().split('(')[1];

				if (JSON.parse(response[1][0]['min']) === null) {
					if ($('#measurementSelect').val() === 'conductivity') {
						$('.info').append('<p id="injectedInfo" style="text-align: left">There are no established benchmarks for conductivity; however, studies of inland fresh waters indicate that streams supporting good mixed fisheries have a range between 150 and 500 ms/cm.</p>');
					}
					else {
						$('.info').append('<p id="injectedInfo" style="text-align: left">Red bars indicate concentrations or levels that exceed the standard benchmark of <strong style="color: red">' + JSON.parse(response[1][0]['max']) + ' (' + measurementUnits + '</strong> recommended by IDEM or other environmental agency.</p>');
					}
				}
				else {
					if ($('#measurementSelect').val() === 'ph') {
						$('.info').append('<p id="injectedInfo" style="text-align: left">Red bars indicate concentrations or levels that exceed the standard benchmark range of <strong style="color: purple">' + JSON.parse(response[1][0]['min']) + '</strong> - <strong style="color:red">' + JSON.parse(response[1][0]['max']) + '</strong> recommended by IDEM or other environmental agency.</p>');
					}
					else {
						$('.info').append('<p id="injectedInfo" style="text-align: left">Red bars indicate concentrations or levels that exceed the standard benchmark range of <strong style="color: purple">' + JSON.parse(response[1][0]['min']) + '</strong> - <strong style="color: red">' + JSON.parse(response[1][0]['max']) + ' (' + measurementUnits + '</strong> recommended by IDEM or other environmental agency.</p>');
					}
				}
			}
		});
	}

	//returns the number of weeks between two Dates
	function weeksBetween(date1, date2) {
		let timeDiff = getMonday(date1.getTime()) - getMonday(date2.getTime());
		let daysDiff = Math.abs(timeDiff) / (1000 * 60 * 60 * 24) - 1;
		return Math.floor(daysDiff / 7);
	}

	//Get the Monday of that week
	function getMonday(d) {
		d = new Date(d);
		var day = d.getDay(), diff = d.getDate() - day + (day === 0 ? -6 : 1); //adjust when day is sunday
		return new Date(d.setDate(diff));
	}

	//add a week to the date given
	function addWeek(date) {
		var newDate = new Date(date);
		newDate.setDate(newDate.getDate() + 7);
		return newDate;
	}

	//determine if the line value is above/below the threshold
	function parseThreshold(data, benchmark) {
		return ((benchmark.max !== null && data.value >= benchmark.max) || (benchmark.min !== null && data.value <= benchmark.min)) ? "rgb(240,0,0)" : "steelblue";
	}

	function formatData(data, sites) {
		dataBySite = [];
		for (var q = 0; q < sites.length; q++) {
			dataBySite[sites[q]] = [];
		}
		data.forEach(function(item) {
			dataBySite[item.site].push(item);
		});
		var comData = [];
		for (var q = 0; q < sites.length; q++) {
			//pre-parsing data by site

			//grab the time bounds
			var beginDate = new Date($('#startdate').val().split("/"));
			var endDate = new Date($('#enddate').val().split("/"));

			//if the date selected is before the last date, insert blank values to represent weeks without data
			var firstDate;
			if (dataBySite[sites[q]].length === 0) {
				firstDate = endDate;
			}
			else {
				firstDate = new Date(dataBySite[sites[q]][0].date);
			}
			var tmpDate = firstDate;
			var i = 0;
			if (tmpDate.getTime() > beginDate.getTime()) {
				var numPoints = Math.floor(weeksBetween(tmpDate, beginDate)); //get how many data points to insert
				if (numPoints !== 0) {
					var newDataPoints = [];
					var currentDatePoint = beginDate;
					for (; i < numPoints; i++) {
						var obj = {
							date: currentDatePoint.toISOString(),
							value: "0",
							site: sites[q]
						};
						newDataPoints.push(obj);
						//Get the next week's date
						currentDatePoint = addWeek(currentDatePoint);
					}
					//append new data points to the beginning of data[]
					dataBySite[sites[q]] = newDataPoints.concat(dataBySite[sites[q]]);
				}
			}

			//data points in-between existing ones
			for (; i < dataBySite[sites[q]].length - 1; i++) {
				var numPoints = Math.floor(weeksBetween(new Date(dataBySite[sites[q]][i].date), new Date(dataBySite[sites[q]][i + 1].date)));
				if (numPoints === 0) {
					continue;
				}
				var currentDatePoint = addWeek(dataBySite[sites[q]][i].date);
				for (var j = 0; j < numPoints; j++) {
					var obj = {
						date: currentDatePoint.toISOString(),
						value: "0",
						site: sites[q]
					};
					//Get the next week's date
					currentDatePoint = addWeek(currentDatePoint);
					//Add the point at at the correct position
					dataBySite[sites[q]].splice(++i, 0, obj);
				}
			}

			//if the date selected is after the last date, insert blank values to represent weeks without data
			if (firstDate.getTime() !== endDate.getTime()) {
				tmpDate = new Date(dataBySite[sites[q]][dataBySite[sites[q]].length - 1].date);
				if (tmpDate.getTime() < endDate.getTime()) {
					var currentDatePoint = addWeek(tmpDate);
					for (; currentDatePoint.getTime() < endDate.getTime(); i++) {
						var obj = {
							date: currentDatePoint.toISOString(),
							value: "0",
							site: sites[q]
						};
						//Get the next week's date
						currentDatePoint = addWeek(currentDatePoint);
						//Add the point at the end of the data array
						dataBySite[sites[q]].push(obj);
					}
				}
			}
			comData = comData.concat(dataBySite[sites[q]]);
		}

		return comData;
	}

	function buildGraph(data, benchmark, graphType, dataLength) {
		var parseDate = d3.timeParse("%Y-%m-%d");
		//Format data so D3 can read it
		data.forEach(function(d, i) {
			d.date = parseDate(d.date.substr(0, 10));
			d.value = +d.value;
		});

		//div for the bar hover
		var div = d3.select("body").append("div")
			.attr("class", "ctooltip text-center")
			.style("opacity", 0);

		var svg = d3.select("svg"),
			margin = {
				top: 20,
				right: 20,
				bottom: 110,
				left: 60
			},
			margin2 = {
				top: 430,
				right: 20,
				bottom: 30,
				left: 60
			},
			width = Math.max(960 - margin.left - margin.right, dataLength + margin.left + margin.right),
			height = 500 - margin.top - margin.bottom,
			height2 = 500 - margin2.top - margin2.bottom,
			scale = height / height2;

		$("svg").attr("width", (width + margin.left + margin.right));

		$("svg").attr("height", (height + margin.top + margin.bottom + 60));

		var x = d3.scaleTime().range([0, width]),
			x2 = d3.scaleTime().range([0, width]),
			y = d3.scaleLinear().range([height, 0]),
			y2 = d3.scaleLinear().range([height2, 0]);

		var xAxis = d3.axisBottom(x),
			xAxis2 = d3.axisBottom(x2),
			yAxis = d3.axisLeft(y);

		var focus = svg.append("g")
			.attr("class", "focus")
			.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

		var context = svg.append("g")
			.attr("class", "context")
			.attr("transform", "translate(" + margin2.left + "," + margin2.top + ")");

		x.domain(d3.extent(data, function(d) {
			return d.date;
		}));

		y.domain([0, d3.max(data, function(d) {
			return d.value;
		})]);

		x2.domain(x.domain());
		y2.domain(y.domain());

		if (graphType === "bar") {
			buildBars();
		}
		else if (graphType === "line") {
			buildLines();
		}
		else if (graphType === "table") {
			buildTable();
		}

		//it is important that the min/max lines are last to be added to the SVG, this ensures that they are above the bars in the histogram
		addMinMaxLines(benchmark, width, focus, y);

		addAxisText(width, height, margin, svg);

		//Adjust the width of the SVG based off of the size of the graph
		$("svg").attr("width", (d3.select(".focus").node().getBoundingClientRect().width));

		function buildBars() {
			document.getElementById("chart").style.display = "block"; //ensure the chart is visible, since the table view hides it
			var brush = d3.brushX()
				.extent([
					[0, 0],
					[width, height2]
				])
				.on("brush end", brushed);

			focus.selectAll(".bar")
				.data(data)
				.enter().append("rect")
				.attr("fill", function(d) {
					let tmp = parseThreshold(d, benchmark);
					return tmp;
				})
				.attr("class", "bar")
				.attr("x", function(d) {
					return x(d.date);
				})
				.attr("y", function(d) {
					return y(d.value);
				})
				.attr("width", Math.max(width / data.length - 2, 1))
				.attr("height", function(d) {
					return height - y(d.value);
				})
				.style("z-index", "-100 !important;")
				.on("mouseover", function(d) {
					var boundingBox = this.getBoundingClientRect();
					var topDistance = $(window).scrollTop();
					var leftDistance = (boundingBox["left"] + boundingBox["right"]) / 2.0;
					var measure = $('#measurementSelect option:selected').text();
					var dateTime = new Date(d.date);
					var month = dateTime.getMonth() + 1;
					var day = dateTime.getDate();
					var year = dateTime.getFullYear();

					div.style("opacity", .9);
					div.html("<h5>" + measure + "</h5>" + d.value + " on " + month + "/" + day + "/" + year)
						.style("left", (leftDistance) + "px")
						.style("top", (topDistance + boundingBox["top"] - 100) + "px");
				})
				.on("mouseout", function(d) {
					div.style("opacity", 0);
				});

			context.selectAll(".bar")
				.attr("clip-path", "url(#clip)")
				.data(data)
				.enter().append("rect")
				.attr("class", "bar")
				.attr("x", function(d) {
					return x2(d.date);
				})
				.attr("y", function(d) {
					return y(d.value) / scale;
				})
				.attr("width", Math.max(width / data.length - 2, 1))
				.attr("height", function(d) {
					return (height - y(d.value)) / scale;
				});

			//add squares behind the left and right margins of the graph to prevent the histogram bars from showing past the axes
			addCoverBox(width, margin, focus);

			focus.append("g")
				.attr("class", "axis axis--x")
				.attr("transform", "translate(0," + height + ")")
				.call(xAxis);

			focus.append("g")
				.attr("class", "axis axis--y")
				.call(yAxis);

			context.append("g")
				.attr("class", "axis axis--x")
				.attr("transform", "translate(0," + height2 + ")")
				.call(xAxis2);

			context.append("g")
				.attr("class", "brush")
				.call(brush);

			function brushed() {
				let staticWidth = d3.select(".context").node().getBoundingClientRect().width; //the fully extended width of the selector 'zoom' bar
				var s = d3.event.selection || x2.range();
				x.domain(s.map(x2.invert, x2));
				focus.select(".axis--x").call(xAxis);

				focus.selectAll(".bar")
					.attr("x", function(d) {
						return x(d.date);
					})
					.attr("width", function(d) {
						let selectionWidth = d3.select(".selection").node().getBoundingClientRect().width;
						return Math.max(((staticWidth / selectionWidth)), 1);
					});
			}
		}

		function buildLines() {
			document.getElementById("chart").style.display = "block"; //ensure the chart is visible, since the table view hides it
			var brush = d3.brushX()
				.extent([
					[0, 0],
					[width, height2]
				])
				.on("brush end", brushed);
			var valueline = d3.line()
				.x(function(d) {
					return x(d.date);
				})
				.y(function(d) {
					return y(d.value);
				});
			var brushLine = d3.line()
				.x(function(d) {
					return x2(d.date);
				})
				.y(function(d) {
					return y2(d.value);
				});

			var dataNest = d3.nest()
				.key(function(d) {
					return d.site;
				})
				.entries(data);

			var color = d3.scaleOrdinal(d3.schemeCategory10);
			var legendSpace = width / dataNest.length;
			dataNest.forEach(function(d, i) {
				focus.selectAll("g").data(dataNest)
					.enter()
					.append("g")
					.attr("id", function(d) {
						return d.key;
					})
					.append("path")
					.attr("class", "line")
					.style("stroke", function(d) {
						return color(d.key);
					})
					.attr("d", function(d) {
						return valueline(d.values);
					});

				context.selectAll("g").data(dataNest)
					.enter()
					.append("g")
					.attr("class", "line")
					.attr("id", function(d) {
						return d.key;
					})
					.append("path")
					.attr("clip-path", "url(#clip)")
					.attr("class", "line")
					.style("stroke", function(d) {
						return color(d.key);
					})
					.attr("d", function(d) {
						return brushLine(d.values);
					});
			});

			//add squares behind the left and right margins of the graph to prevent the histogram bars from showing past the axes
			addCoverBox(width, margin, focus);

			dataNest.forEach(function(d, i) {
				focus.append("text")
					.attr("x", width + margin.right) //space legend
					.attr("y", i * 20)
					.attr("class", "legend") //style the legend
					.style("stroke", function() {
						return color(d.key);
					})
					.style("z-index", 101)
					.text(d.key);
			});

			focus.append("g")
				.attr("class", "axis axis--x")
				.attr("transform", "translate(0," + height + ")")
				.call(xAxis);

			focus.append("g")
				.attr("class", "axis axis--y")
				.call(yAxis);

			context.append("g")
				.attr("class", "axis axis--x")
				.attr("transform", "translate(" + 0 + "," + height2 + ")")
				.call(xAxis2);

			context.append("g")
				.attr("class", "brush")
				.call(brush);

			function brushed() {
				var s = d3.event.selection || x2.range();
				x.domain(s.map(x2.invert, x2));

				focus.select(".axis--x").call(yAxis);
				focus.select(".axis--x").call(xAxis);

				focus.selectAll("path.line").attr("d", function(d) {
					return valueline(d.values);
				});
			}
		}

		function buildTable() {
			//hide the dashboard element where the charts normally go, because we don't need that and trying to put this there breaks stuff
			var dashboard = document.getElementById("dashboard");
			document.getElementById("chart").style.display = "none";

			//create the table itself
			var sampleTable = document.createElement("table");
			sampleTable.setAttribute("class", "table");
			sampleTable.setAttribute("id", "sampleTable");

			//create headers
			var headerRow = document.createElement("tr");
			var columnHeaders = new Array("Site", "Date", $('#measurementSelect option:selected').text());
			for (var i = 0; i < columnHeaders.length; i++) {
				var colHeader = document.createElement("th");
				colHeader.innerHTML = columnHeaders[i];
				headerRow.append(colHeader);
			}

			sampleTable.append(headerRow);

			//now add the actual data
			for (var i = 0; i < data.length; i++) {
				var newRow = document.createElement("tr");

				var siteCell = document.createElement("td");
				siteCell.innerHTML = data[i]["site"];
				newRow.append(siteCell);

				var dateCell = document.createElement("td");
				dateCell.innerHTML = data[i]["date"];
				newRow.append(dateCell);

				var valueCell = document.createElement("td");
				valueCell.innerHTML = data[i]["value"];
				newRow.append(valueCell);

				sampleTable.append(newRow);
			}

			dashboard.append(sampleTable);
		}

		function formatDateForTable(date) {
			//do something
		}
	}

	function addAxisText(width, height, margin, svg) {
		//add the x axis text
		svg.append("text")
			.attr("transform", "translate(" + (width / 2) + " ," + (height + margin.bottom + 50) + ")")
			.style("text-anchor", "middle")
			.text("Time (Sample Date)")
			.attr("class", "labelText");

		//add the y-axis text
		svg.append("text")
			.attr("transform", "rotate(-90)")
			.attr("y", 0)
			.attr("x", 0 - (height / 2))
			.attr("dy", "1em")
			.style("text-anchor", "middle")
			.text($('#measurementSelect option:selected').text())
			.attr("class", "labelText");
	}

	function addMinMaxLines(benchmark, width, focus, y) {
		//draw the min/max lines
		if (benchmark.min !== null) {
			focus.append("g").selectAll("line") //bind data to the line
				.data([{
						y1: y(benchmark.min),
						y2: y(benchmark.min),
						x1: 0,
						x2: width,
						className: "minLineBackground",
						dashArray: "8,5",
						color: "white",
						opacity: 1,
						lineWidth: 4
					},
					{
						y1: y(benchmark.min),
						y2: y(benchmark.min),
						x1: 0,
						x2: width,
						className: "minLine",
						dashArray: "8,5",
						color: "purple",
						opacity: 0.8,
						lineWidth: 1.5
					}
				])
				.enter().append("line")
				.attr("class", function(d) {

					return d.className;
				})
				.attr("stroke", function(d) {
					return d.color;
				})
				.attr("stroke-width", function(d) {
					return d.lineWidth;
				})
				.attr("stroke-opacity", function(d) {
					return d.opacity;
				})
				.attr("stroke-dasharray", function(d) {
					return d.dashArray;
				})
				.attr("y1", function(d) {
					return d.y1;
				})
				.attr("y2", function(d) {
					return d.y2;
				})
				.attr("x1", function(d) {
					return d.x1;
				})
				.attr("x2", function(d) {
					return d.x2;
				});
		}

		if (benchmark.max !== null) {
			focus.append("g").selectAll("line")
				.data([{
						y1: y(benchmark.max),
						y2: y(benchmark.max),
						x1: 0,
						x2: width,
						className: "maxLineBackground",
						dashArray: "8,5",
						color: "white",
						opacity: 1,
						lineWidth: 4
					},
					{
						y1: y(benchmark.max),
						y2: y(benchmark.max),
						x1: 0,
						x2: width,
						className: "maxLine",
						dashArray: "8,5",
						color: "red",
						opacity: 0.8,
						lineWidth: 1.5
					}
				])
				.enter().append("line")
				.attr("class", function(d) {
					return d.c1lassName;
				})
				.attr("stroke", function(d) {
					return d.color;
				})
				.attr("stroke-width", function(d) {
					return d.lineWidth;
				})
				.attr("stroke-opacity", function(d) {
					return d.opacity;
				})
				.attr("stroke-dasharray", function(d) {
					return d.dashArray;
				})
				.attr("y1", function(d) {
					return d.y1;
				})
				.attr("y2", function(d) {
					return d.y2;
				})
				.attr("x1", function(d) {
					return d.x1;
				})
				.attr("x2", function(d) {
					return d.x2;
				});
		}
	}

	function addCoverBox(width, margin, attachedObject) {
		let objectHeight = d3.select("svg").node().getBoundingClientRect().height - margin.bottom - margin.top;
		let objectWidth = width;
		let boxColor = "#DDDDDD";
		let boxData = [{
				"x": 0 - margin.left,
				"y": 0,
				"width": margin.left,
				"height": objectHeight,
				"fill": boxColor,
				"opacity": 1
			},
			{
				"x": objectWidth,
				"y": 0,
				"width": margin.right + 30,
				"height": objectHeight,
				"fill": boxColor,
				"opacity": 1
			}
		];
		attachedObject.selectAll("coverBox")
			.data(boxData)
			.enter()
			.append("rect")
			.style("z-index", 100)
			.attr("class", "coverBox")
			.attr("x", function(d) {
				return d.x;
			})
			.attr("y", function(d) {
				return d.y;
			})
			.attr("width", function(d) {
				return d.width;
			})
			.attr("height", function(d) {
				return d.height;
			})
			.attr("fill", function(d) {
				return d.fill;
			})
			.attr("opacity", function(d) {
				return d.opacity;
			});
	}
});