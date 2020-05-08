var spinnerInhibited = false; //inhibit as needed for minor things that aren't expected to take much time (getRange())

//loading graphic
$(document).ajaxStart(function() {
	//check if loading spinner is inhibited first
	if (!spinnerInhibited) {
		$(".loadingspinnermain").css("visibility", "visible");
		$("body").css("cursor", "wait");
	}
}).ajaxStop(function() {
	$(".loadingspinnermain").css("visibility", "hidden");
	$("body").css("cursor", "default");
});

function genericError() {
	alert("We encountered a problem, try again later");
}

//page state information
var chartsDisplayMode = "in-line";
var tablePage = 1;
var numRecords = 0;
var numPages = 0;
var sortBy = "Date";
var sortDirection = "Desc";
var showBenchmarks = true;
var charts = [];
var measurementSettings; //will be filled in with the contents of the MeasurementSettings table, containing category/name/alias/benchmarks/detection limits for each
var groups;

//pointers to static page elements
const sidebarInner = document.getElementById("sidebarInner");
const main = document.getElementById("main");
const sidebarToggle = document.getElementById("sidebarToggle");
const chartDiv = document.getElementById("chartDiv");
const amountEnter = document.getElementById("amountEnter");
const measurementSelect = document.getElementById("measurementSelect");
const exportBtn = document.getElementById("exportBtn");
const aggregateGroup = document.getElementById("aggregateGroup");
const categorySelect = document.getElementById("categorySelect");
const overUnderSelect = document.getElementById("overUnderSelect");
const chartType = document.getElementById("chartType");
const allCheckbox = document.getElementById("allCheckbox");
const tableNoData = document.getElementById("tableNoData");
const chartsNoData = document.getElementById("chartsNoData");
const tableSettingsTop = document.getElementById("tableSettingsTop");
const tableSettingsBottom = document.getElementById("tableSettingsBottom");
const compareOptionsDiv = document.getElementById("compareTargetOptions");
const checkboxList = document.getElementById("checkboxList");
const numRowsDropdownTop = document.getElementById("numRowsDropdownTop");
const numRowsDropdownBottom = document.getElementById("numRowsDropdownBottom");

//global variables used by the map
var mapData;
var map;
var view;

var selectPointAction = {
	title: "Select this point",
	id: "select-point",
}

var template;
var fields = [{ //fields object the map uses for the points layer
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
var clickedPoint;
var selectedPoints = [];

const defaultPointColor = [0,150,255];
const selectedPointColor = [226, 119, 40];
const clickedPointColor = [0,255,55];

function ucfirst(str) {
	//capitalize first character of a string
	return str[0].toUpperCase() + str.slice(1);
}

function selectColor(colorIndex, palleteSize) {
	//returns color at an index of an evenly-distributed color pallete of arbitrary size. To avoid ever having the color of the line matching the color of the benchmark lines, we offset the index and pallet size by 1
	if (palleteSize < 1) {
		palleteSize = 1; //defaults to one color, can't divide by zero or the universe implodes
	}
		
	return "hsl(" + ((colorIndex+1) * (360 / (palleteSize+1)) % 360) + ",70%,50%)";
}

function benchmarkLine(val, color) {
	//builds annotation line with a given value and color
	return {
		type: "line",
		mode: "horizontal",
		scaleID: "y-axis-0",
		value: val,
		borderColor: color,
		borderWidth: 3,
		drawTime: "afterDatasetsDraw",
	};
}

function getSelectedMeasures() {
	var measures = [];
	var checkboxes = document.getElementsByClassName("measurementCheckbox");
	
	for (var i=0; i<checkboxes.length; i++) {
		if (checkboxes[i].checked) {
			measures.push(checkboxes[i].value);
		}
	}
	
	return measures;
}

//konami code detection for easter egg
var Konami = function (callback) {
	var konami = {
		addEvent: function (obj, type, fn, ref_obj) {
			if (obj.addEventListener) {
				obj.addEventListener(type, fn, false);
			}
		},
		removeEvent: function (obj, eventName, eventCallback) {
			if (obj.removeEventListener) {
				obj.removeEventListener(eventName, eventCallback);
			}
			else if (obj.attachEvent) {
				obj.detachEvent(eventName);
			}
		},
		input: "",
		pattern: "38384040373937396665",
		keydownHandler: function (e, ref_obj) {
			if (ref_obj) {
				konami = ref_obj;
			} //IE
			konami.input += e ? e.keyCode : event.keyCode;
			if (konami.input.length > konami.pattern.length) {
				konami.input = konami.input.substr((konami.input.length - konami.pattern.length));
			}
			if (konami.input === konami.pattern) {
				konami.code(konami._currentLink);
				konami.input = "";
				e.preventDefault();
				return false;
			}
		},
		load: function (link) {
			this._currentLink = link;
			this.addEvent(document, "keydown", this.keydownHandler, this);
		},
		unload: function () {
			this.removeEvent(document, "keydown", this.keydownHandler);
		},
		code: function (link) {
			window.location = link
		}
	}

	typeof callback === "string" && konami.load(callback);
	if (typeof callback === "function") {
		konami.code = callback;
		konami.load();
	}

	return konami;
};

window.Konami = Konami;

//for zoom functionality in the graphs
(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){

},{}],2:[function(require,module,exports) {
//hammer JS for touch support
var Hammer = require("hammerjs");
Hammer = typeof(Hammer) === "function" ? Hammer : window.Hammer;

//get the chart variable
var Chart = require("chart.js");
Chart = typeof(Chart) === "function" ? Chart : window.Chart;
var helpers = Chart.helpers;

//take the zoom namespace of Chart
var zoomNS = Chart.Zoom = Chart.Zoom || {};

function doZoom(chartInstance, zoom, center) {
	var ca = chartInstance.chartArea;
	if (!center) {
		center = {
			x: (ca.left + ca.right) / 2,
			y: (ca.top + ca.bottom) / 2,
		};
	}

	//do the zoom here
	helpers.each(chartInstance.scales, function(scale, id) {
		var labels = scale.chart.data.labels;
		var minIndex = scale.minIndex;
		var lastLabelIndex = labels.length - 1;
		var maxIndex = scale.maxIndex;
		const sensitivity = 0;
		var chartCenter = scale.isHorizontal() ? scale.left + (scale.width/2) : scale.top + (scale.height/2);
		var centerPointer = scale.isHorizontal() ? center.x : center.y;

		if (zoom > 1) {
			zoomNS.zoomCumulativeDelta+=1;
		}
		else {
			zoomNS.zoomCumulativeDelta-=1;
		}

		if (zoomNS.zoomCumulativeDelta < 0) {
			if (centerPointer <= chartCenter) {
				if (minIndex <= 0){
					maxIndex = Math.min(lastLabelIndex, maxIndex + 10);
				}
				else {
					minIndex = Math.max(0, minIndex - 10);
				}
			}
			else {
				if (maxIndex >= lastLabelIndex) {
					minIndex = Math.max(0, minIndex - 10);
				}
				else {
					maxIndex = Math.min(lastLabelIndex, maxIndex + 10);
				}
			}
		}
		else if (zoomNS.zoomCumulativeDelta > 0) {
			if (centerPointer <= chartCenter) {
				minIndex = minIndex < maxIndex ? minIndex = Math.min(maxIndex, minIndex + 10) : minIndex;
			}
			else if (centerPointer > chartCenter) {
				maxIndex = maxIndex > minIndex ? maxIndex = Math.max(minIndex, maxIndex - 10) : maxIndex;
			}
		}
	
		zoomNS.zoomCumulativeDelta = 0;
		scale.options.ticks.min = labels[minIndex];
		scale.options.ticks.max = labels[maxIndex];
	});
	
	chartInstance.update(0);
}

function doPan(chartInstance, deltaX, deltaY) {
	helpers.each(chartInstance.scales, function(scale, id) {
		var labels = scale.chart.data.labels;
		var lastLabelIndex = labels.length - 1;
		var offsetAmt = Math.max((scale.ticks.length - ((scale.options.gridLines.offsetGridLines) ? 0 : 1)), 1);
		var panSpeed = 100;
		var minIndex = scale.minIndex;
		var step = Math.round(scale.width / (offsetAmt * panSpeed));
		var maxIndex = scale.maxIndex;

		zoomNS.panCumulativeDelta += deltaX;
		
		if (zoomNS.panCumulativeDelta > step && minIndex > 0) {
			//dragging to lower values		
			minIndex--;
			maxIndex--;
		}
		else if (zoomNS.panCumulativeDelta < -step && maxIndex < lastLabelIndex - 1) {
			//dragging to higher values
			minIndex++;
			maxIndex++;
		}
	
		zoomNS.panCumulativeDelta = minIndex !== scale.minIndex ? 0 : zoomNS.panCumulativeDelta;
	
		scale.options.ticks.min = labels[minIndex];
		scale.options.ticks.max = labels[maxIndex];
	});
	
	chartInstance.update(0);
}

function getYAxis(chartInstance) {
	var scales = chartInstance.scales;

	for (var scaleId in scales) {
		var scale = scales[scaleId];

		if (!scale.isHorizontal()) {
			return scale;
		}
	}
}

//globals for catergory pan and zoom
zoomNS.panCumulativeDelta = 0;
zoomNS.zoomCumulativeDelta = 0;

//Chartjs Zoom Plugin
var zoomPlugin = {
	afterInit: function(chartInstance) {
		helpers.each(chartInstance.scales, function(scale) {
			scale.originalOptions = JSON.parse(JSON.stringify(scale.options));
		});

		chartInstance.resetZoom = function() {
			var scale = chartInstance.scales["x-axis-0"];
			var timeOptions = scale.options.time;
			var tickOptions = scale.options.ticks;
			
			if (timeOptions) {
				delete timeOptions.min;
				delete timeOptions.max;
			}

			if (tickOptions) {
				delete tickOptions.min;
				delete tickOptions.max;
			}
			
			scale.options = helpers.configMerge(scale.options, scale.originalOptions);

			helpers.each(chartInstance.data.datasets, function(dataset, id) {
				dataset._meta = null;
			});

			chartInstance.update(0);
		};
		
		var node = chartInstance.chart.ctx.canvas;
		
		//add buttons
		var chartNum = node.id.split("-")[1];
	
		var zoomInButton = document.createElement("button");
		zoomInButton.setAttribute("class", "btn btn-sm btn-ctrl");
		zoomInButton.innerText = "+";
		zoomInButton.id = "zoomInButton-" + chartNum;
		zoomInButton.onclick = function() {
			//zoom in
			doZoom(chartInstance, 1.1);
		};
		node.parentElement.appendChild(zoomInButton);
	
		var zoomOutButton = document.createElement("button");
		zoomOutButton.setAttribute("class", "btn btn-sm btn-ctrl");
		zoomOutButton.innerText = "-";
		zoomOutButton.id = "zoomOutButton-" + chartNum;
		zoomOutButton.onclick = function() {
			//zoom out
			doZoom(chartInstance, -1);
		}
		node.parentElement.appendChild(zoomOutButton);
	
		var resetButton = document.createElement("button");
		resetButton.setAttribute("class", "btn btn-sm btn-ctrl");
		resetButton.innerText = "reset";
		resetButton.id = "resetButton-" + chartNum;
		resetButton.onclick = function() {
			chartInstance.resetZoom();
		}
		node.parentElement.appendChild(resetButton);
	
		if ($("#sites").val().length == 1 || aggregateGroup.checked) {
			//we don't want to show this button if theres multiple sites, because it'd complicate coloring
			var compareButton = document.createElement("button");
			compareButton.setAttribute("class", "btn btn-sm btn-ctrl");
			compareButton.type = "button";
			compareButton.setAttribute("data-toggle", "modal");
			compareButton.setAttribute("data-target", "#compareToModal");
			compareButton.innerText = "Compare to other measure";
			compareButton.id = "compareButton-" + chartNum;
			compareButton.onclick = function() {
				rebuildCompareModal(chartInstance);
			}
			node.parentElement.appendChild(compareButton);
		}
	},
	beforeInit: function(chartInstance) {
		var node = chartInstance.chart.ctx.canvas;
		
		var wheelHandler = function(e) {
			var rect = e.target.getBoundingClientRect();

			var center = {
				x : e.clientX - rect.left,
				y : e.clientY - rect.top
			};

			if (e.deltaY < 0) {
				doZoom(chartInstance, 1.1, center);
			}
			else {
				doZoom(chartInstance, 0.909, center);
			}
			//prevent the event from triggering the default behavior (eg Content scrolling)
			e.preventDefault();
		};
		chartInstance._wheelHandler = wheelHandler;

		node.addEventListener("wheel", wheelHandler);

		if (Hammer) {
			var mc = new Hammer.Manager(node);
			mc.add(new Hammer.Pinch());
			mc.add(new Hammer.Pan());

			//Hammer reports the total scaling. We need the incremental amount
			var currentPinchScaling;
			var handlePinch = function handlePinch(e) {
				var diff = 1 / (currentPinchScaling) * e.scale;
				doZoom(chartInstance, diff, e.center);

				//keep track of overall scale
				currentPinchScaling = e.scale;
			};

			mc.on("pinchstart", function(e) {
				currentPinchScaling = 1; //reset tracker
			});
			mc.on("pinch", handlePinch);
			mc.on("pinchend", function(e) {
				handlePinch(e);
				currentPinchScaling = null; //reset
				zoomNS.zoomCumulativeDelta = 0;
			});

			var currentDeltaX = null, currentDeltaY = null;
			var handlePan = function handlePan(e) {
				if (currentDeltaX !== null && currentDeltaY !== null) {
					var deltaX = e.deltaX - currentDeltaX;
					var deltaY = e.deltaY - currentDeltaY;
					currentDeltaX = e.deltaX;
					currentDeltaY = e.deltaY;
					doPan(chartInstance, deltaX, deltaY);
				}
			};

			mc.on("panstart", function(e) {
				currentDeltaX = 0;
				currentDeltaY = 0;
				handlePan(e);
			});
			mc.on("panmove", handlePan);
			mc.on("panend", function(e) {
				currentDeltaX = null;
				currentDeltaY = null;
				zoomNS.panCumulativeDelta = 0;
			});
			chartInstance._mc = mc;
		}
	},

	beforeDatasetsDraw: function(chartInstance) {
		var ctx = chartInstance.chart.ctx;
		var chartArea = chartInstance.chartArea;
		ctx.save();
		ctx.beginPath();

		if (chartInstance._dragZoomEnd) {
			var yAxis = getYAxis(chartInstance);
			var beginPoint = chartInstance._dragZoomStart;
			var endPoint = chartInstance._dragZoomEnd;
			var offsetX = beginPoint.target.getBoundingClientRect().left;
			var startX = Math.min(beginPoint.x, endPoint.x) - offsetX;

			ctx.fillStyle = "rgba(225,225,225,0.3)";
			ctx.lineWidth = 5;
			ctx.fillRect(startX, yAxis.top, (Math.max(beginPoint.x, endPoint.x) - offsetX - startX), yAxis.bottom - yAxis.top);
		}

		ctx.rect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
		ctx.clip();
	},
	afterDatasetsDraw: function(chartInstance) {
		chartInstance.chart.ctx.restore();
	},
	destroy: function(chartInstance) {
		var mc = chartInstance._mc;
		if (mc) {
			mc.remove("pinchstart");
			mc.remove("pinch");
			mc.remove("pinchend");
			mc.remove("panstart");
			mc.remove("pan");
			mc.remove("panend");
		}
	}
};

Chart.pluginService.register(zoomPlugin);

},{"chart.js":1,"hammerjs":1}]},{},[2]);

function rebuildCompareModal(chartInstance) {
	var yAxes = chartInstance.options.scales.yAxes;
	var selectedTitle = yAxes[1].scaleLabel.labelString; //title of the currently selected comparison measure, if any
	
	//first clear the existing contents
	compareOptionsDiv.innerHTML = "";
	
	//set options available in the modal
	for (category in measurementSettings) {
		for (j=0; j<measurementSettings[category].length; j++) {
			var thisMeasure = measurementSettings[category][j];
			var measureTitle = thisMeasure.measureName;
			if (thisMeasure.unit != "") {
				measureTitle += " (" + thisMeasure.unit + ")";
			}
			
			//add a button for it
			var measureButton = document.createElement("button");
			if (measureTitle == selectedTitle) {
				measureButton.setAttribute("class", "btn btn-sm btn-ctrl btn-danger");
			}
			else {
				measureButton.setAttribute("class", "btn btn-sm btn-ctrl");
			}
			measureButton.innerText = thisMeasure.measureName;
			measureButton.setAttribute("measure", thisMeasure.measureKey);
			measureButton.setAttribute("category", category);
			measureButton.setAttribute("measureName", measureTitle);
			measureButton.disabled = (measureTitle == yAxes[0].scaleLabel.labelString || measureTitle == yAxes[1].scaleLabel.labelString); //eww
			measureButton.onclick = function() {
				var measure = $(this)[0].attributes.measure.value;
				var measureName = $(this)[0].attributes.measureName.value;
			
				//first remove the second dataset if present from beforeDatasetsDraw
				chartInstance.data.datasets = [chartInstance.data.datasets[0]];
			
				//also make sure the label for that dataset is the measure, not the site number, which is the default for single-measure graphing
				chartInstance.data.datasets[0].label = yAxes[0].scaleLabel.labelString;
			
				//retrieve data from the server
				$.ajax({
					type: "POST",
					url: "/WQIS/generic-samples/graphdata",
					datatype: "JSON",
					async: false,
					data: {
						"sites": $("#sites").val(),
						"startDate": $("#startDate").val(),
						"endDate": $("#endDate").val(),
						"selectedMeasures": [measure],
						"category": $(this)[0].attributes.category.value,
						"amount": null,
						"overUnderSelect": null,
						"measurementSearch": null,
						"aggregate": aggregateGroup.checked
					},
					success: function(response) {
						if (response.length == 0) {
							alert("No data for this measure over this range");
						}
						else {
							var newDataset = {
								label: measureName,
								yAxisID: "comparison",
								borderColor: selectColor(2,2),
								data: [],
								lineTension: 0,
								fill: false,
								borderWidth: 1.5,
								showLine: (chartType.value === "line"),
								spanGaps: true
							}

							for (i=0; i<response.length; i++) {
								var newRow = [];
								var date = response[i].Date.split("T")[0];
								newRow.t = date;
								newRow.y = response[i][measure];
	
								newDataset.data.push(newRow);
							}
							
							chartInstance.data.datasets.push(newDataset);
						
							//add new y-axis
							chartInstance.options.scales.yAxes[1].scaleLabel.labelString = measureName;
							chartInstance.options.scales.yAxes[1].display = true;
						}
					},
					error: function(response) {
						genericError();
					}
				});
			
				chartInstance.update();
				
				rebuildCompareModal(chartInstance); //clear and redetermine what buttons should be colored
			}
		
			compareOptionsDiv.appendChild(measureButton);
		}
	}
	
	//set up the clear button
	var clearBtn = document.getElementById("clearCompare");
	clearBtn.onclick = function() {
		//remove the second dataset and hide its axis label
		chartInstance.data.datasets = [chartInstance.data.datasets[0]];
		chartInstance.options.scales.yAxes[1].display = false;
		chartInstance.update();
	}
}

$(document).ready(function () {
	if (typeof admin == "undefined") {
		admin = false;
	}
	
	//build the table template we use to display all the data associated with a point on the map
	var templateContent = "<table>";
	for (var category in measurementSettings) {
		fields.push({
			name: category + "Date",
			type: "string",
			defaultValue: "No Records Found"
		});
	
		templateContent += "<tr><th>" + ucfirst(category) + " Measurements</th><th>{" + category + "Date}</th></tr>";
		for (i=0; i<measurementSettings[category].length; i++) {
			fields.push({
				name: measurementSettings[category][i].measureKey,
				type: "string",
				defaultValue: "No Data"
			});
	
			templateContent += "<tr><th>" + measurementSettings[category][i].measureName;
			if (measurementSettings[category][i].unit != "") {
				templateContent += " (" + measurementSettings[category][i].unit + ")";
			}
			templateContent += "</th><td>{" + measurementSettings[category][i].measureKey + "}</td></tr>";
		}
	}
	templateContent += "</table>";
	
	template = {
		title: "<b>{siteNumber} - {siteName} ({siteLocation})</b>",
		content: templateContent,
		actions: [selectPointAction]
	};
	
	//map code
	require([
		"esri/Map",
		"esri/views/MapView",
		"esri/layers/MapImageLayer",
		"esri/layers/FeatureLayer",
		"esri/layers/KMLLayer",
		"esri/widgets/Home",
		"esri/widgets/Fullscreen",
		"esri/Graphic",
		"esri/layers/support/LabelClass"
	], function(Map, MapView, MapImageLayer, FeatureLayer, KMLLayer, Home, Fullscreen, Graphic, LabelClass) {
		$("#sites").append('<optgroup label="Select a Group" id="groupOpt"> </optgroup>');
		for (var group of groups) {
			$("#groupOpt").append(new Option(group.groupName, group.groupKey, false, false));
		}
		
		$("#sites").append('<optgroup label="Select Site(s)" id="siteOpt"> </optgroup>');
		for (var site of mapData["SiteData"]) {
			$("#siteOpt").append(new Option(site.Site_Number + " " + site.Site_Name, site.Site_Number, false, false));
		}
		
		var kmlurl = "http://emerald.pfw.edu/WQISBeta/img/wqis.kml";// + "?_=" + new Date().getTime(); //date/time at end is to force ESRI's server to not cache it. Remove this once dev is finished				
		var watershedsLayer = new KMLLayer({
			url: kmlurl,
			id: "watersheds"
		});
		
		var urls = [
			"https://maps.indiana.edu/arcgis/rest/services/Hydrology/Water_Bodies_Flowlines_Unclassified_LocalRes/MapServer",
			"https://maps.indiana.edu/ArcGIS/rest/services/Hydrology/Water_Bodies_Streams/MapServer",
			"https://maps.indiana.edu/arcgis/rest/services/Hydrology/Water_Quality_Impaired_Waters_303d_2016/MapServer",
			"https://maps.indiana.edu/ArcGIS/rest/services/Hydrology/Water_Bodies_Lakes/MapServer",
			"https://maps.indiana.edu/arcgis/rest/services/Hydrology/Floodplains_FIRM/MapServer",
			"https://maps.indiana.edu/ArcGIS/rest/services/Infrastructure/Dams_IDNR/MapServer",
			"https://gis.ohiodnr.gov/arcgis/rest/services/DSW_Services/Ohio_Dams/MapServer",
			"https://maps.indiana.edu/arcgis/rest/services/Hydrology/Water_Wells_IDNR/MapServer",
			"https://gisago.mcgi.state.mi.us/arcgis/rest/services/OpenData/public_health/MapServer",
			"https://gis.ohiodnr.gov/arcgis/rest/services/DSW_Services/waterwells/MapServer",
			"https://maps.indiana.edu/arcgis/rest/services/Hydrology/Wetlands_NWI/MapServer",
			"https://gis.ohiodnr.gov/ArcGIS_site2/rest/services/OIT_Services/ODNR_Lakes/MapServer",
		];
		
		//build the points layer
		var visibleSites = mapData["SiteData"];
		var graphics = [];
		const labels = new LabelClass({
			labelExpressionInfo: {
				expression: "$feature.siteName"
			},
			symbol: {
				type: "text",
				color: "white",
				font: {
					size: 10,
					font: "Playfair Display",
				},
				horizontalAlignment: "left",
				haloSize: 3,
				haloColor: "black"
			},
			labelPlacement: "above-center"
		});
		//add markers to the map at each sites longitude and latitude
		for (var i=0; i<visibleSites.length; i++) {
			var site = visibleSites[i];
			
			var pointGraphic = new Graphic({
					ObjectID: i,
					geometry: {
					type: "point",
					longitude: site.Longitude,
					latitude: site.Latitude
				},
				attributes: {
					siteNumber: site.Site_Number.toString(),
					siteName: site.Site_Name,
					siteLocation: site.Site_Location
				},
				symbol: {
					type: "simple-marker",
					color: defaultPointColor,
					outline: {
						color: [255,255,255],
						width: 2
					}
				}
			});
			
			for (var shortField in measurementSettings) {
				var field = shortField + "_samples";
				
				for (rowNum=0; rowNum<mapData[field].length; rowNum++) {
					var siteNumber = mapData[field][rowNum]["site_location_id"];
					if (pointGraphic.attributes.siteNumber == siteNumber) {
						pointGraphic.attributes[shortField + "Date"] = mapData[field][rowNum]["Date"].split("T")[0];
						for (z=0; z<measurementSettings[shortField].length; z++) {
							var key = measurementSettings[shortField][z].measureKey;
							if (mapData[field][rowNum][key] !== null) {
								pointGraphic.attributes[key] = mapData[field][rowNum][key].toString();
							}
						}
						break;
					}
				}
			}
			
			site.graphic = pointGraphic;
			
			graphics.push(pointGraphic);
		}
		
		var sampleSitesLayer = new FeatureLayer({
			fields: fields,
			objectIdField: "ObjectID",
			popupTemplate: template,
			source: graphics,
			id: "sampleSites",
			labelingInfo: [labels]
		});
		
		var mapLayers = [watershedsLayer];
		for (i=0; i<urls.length; i++) {
			mapLayers.push(new MapImageLayer({
				url: urls[i],
				visible: false,
				opacity: 0.6
			}));	
		}
		//Michigan Streams
		mapLayers.push(new MapImageLayer({
			url: "https://gisago.mcgi.state.mi.us/arcgis/rest/services/OpenData/hydro/MapServer/",
			sublayers:[{
				id:5,
				}],
			visible: false	
		}));
		//Michigan Wetlands
		mapLayers.push(new MapImageLayer({
			url: "https://gisago.mcgi.state.mi.us/arcgis/rest/services/OpenData/hydro/MapServer/",
			sublayers:[{
				id:18,
				}],
			visible: false	
		}));
		//Michigan Lakes
		mapLayers.push(new MapImageLayer({
			url: "https://gisago.mcgi.state.mi.us/arcgis/rest/services/OpenData/hydro/MapServer/",
			sublayers:[{
				id:23,
				}],
			visible: false	
		}));
		//Ohio Dams
		mapLayers.push(new MapImageLayer({
			url: "https://gis.ohiodnr.gov/arcgis/rest/services/DSW_Services/Ohio_Dams/MapServer",
			sublayers:[{
				id:6,
				}],
			visible: false	
		}));
		mapLayers.push(sampleSitesLayer);
		
		//create the map
		map = new Map({
			basemap: "satellite",
			layers: mapLayers
		});
		view = new MapView({
			container: "map",
			center: [-84.4, 41.2],
			zoom: 8,
			map: map
		});
		
		view.when(function() {
			view.graphics.addMany(graphics);

			//highlight points when they're clicked
			view.on("click", function(event) {

				view.hitTest(event.screenPoint).then(function(response) {
					clearHighlight();
					response.results.forEach(function(graphic) {
						if (graphic.graphic.ObjectID != null) { //if this is actually a site icon, not a watershed or something
							highlightPoint(graphic.graphic);
						}
					});
				});
			});
					
			//unselect when the popup is closed
			view.popup.watch("visible", function() {
				if (!view.popup.visible) {
					clearHighlight();
				}
			});
			
			//handle custom actions
			view.popup.on("trigger-action", function(event) {
				if (event.action.id === "select-point") {
					selectPoint();
				}
			});
		});
		
		//add home button to return to the default extent
		var homeButton = new Home({
			view: view
		});
		view.ui.add(homeButton, "top-left");
		
		//add fullscreen button
		var fullscreenButton = new Fullscreen({
			view: view
		});
		view.ui.add(fullscreenButton, "bottom-left");
		
		//dock the popup permanently to the bottom right, so its not hidden if the user pans away from that point on the map
		view.popup = {
			dockEnabled: true,
			dockOptions: {
				//disables the dock button from the popup
				buttonEnabled: false,
				breakpoint: false,
				position: "bottom-right"
			}
		};
		
		//handle the checkboxes that toggle layer visibility
		var watershedsLayerToggle = document.getElementById("watershedsLayer");
		watershedsLayerToggle.addEventListener("change", function() {
			mapLayers[0].visible = watershedsLayerToggle.checked;
			$("#watershedsLegend").toggle();
			
			$("#legend").toggle(mapLayers[0].visible || mapLayers[5].visible);
		});
		
		var drainsLayerToggle = document.getElementById("drainsLayer");
		drainsLayerToggle.addEventListener("change", function() {
			mapLayers[1].visible = drainsLayerToggle.checked;
		});
		
		var riverLayerToggle = document.getElementById("riverLayer");
		riverLayerToggle.addEventListener("change", function(){
			mapLayers[2].visible = riverLayerToggle.checked;
			mapLayers[13].visible = riverLayerToggle.checked;
			mapLayers[16].visible = riverLayerToggle.checked;
		});
		
		var impairedLayerToggle = document.getElementById("impairedLayer");
		impairedLayerToggle.addEventListener("change", function(){
			mapLayers[3].visible = impairedLayerToggle.checked;
		});
		
		var bodiesLayerToggle = document.getElementById("bodiesLayer");
		bodiesLayerToggle.addEventListener("change", function(){
			mapLayers[4].visible = bodiesLayerToggle.checked;
			mapLayers[15].visible = bodiesLayerToggle.checked;
			mapLayers[12].visible = bodiesLayerToggle.checked;
		});
		
		var floodLayerToggle = document.getElementById("floodLayer");
		floodLayerToggle.addEventListener("change", function(){
			mapLayers[5].visible = floodLayerToggle.checked;
			$("#floodplainsLegend").toggle();
			
			$("#legend").toggle(mapLayers[0].visible || mapLayers[5].visible);
		});
		
		var damLayerToggle = document.getElementById("damLayer");
		damLayerToggle.addEventListener("change", function(){
			mapLayers[6].visible = damLayerToggle.checked;
			mapLayers[7].visible = damLayerToggle.checked;
		});
		
		var wellLayerToggle = document.getElementById("wellLayer");
		wellLayerToggle.addEventListener("change", function(){
			mapLayers[8].visible = wellLayerToggle.checked;
			mapLayers[9].visible = wellLayerToggle.checked;
			mapLayers[10].visible = wellLayerToggle.checked;
		});
		
		var wetlandLayerToggle = document.getElementById("wetlandLayer");
		wetlandLayerToggle.addEventListener("change", function(){
			mapLayers[11].visible = wetlandLayerToggle.checked;
			mapLayers[14].visible = wetlandLayerToggle.checked;
		});
		
		//handle the dropdown that allows basemap to be changed
		var basemapSelect = document.getElementById("selectBasemap");
		basemapSelect.addEventListener("change", function() {
			map.basemap = basemapSelect.value;
		});
		
		//if theres a preselected site defined in the GET data, set it
		if (preselectSite) {
			$("#sites").val(preselectSite).trigger("change");
		}
		else if (preselectGroup) {
			$("#sites").val(preselectGroup).trigger("change");
		}
		else {
			//trigger change anyway, to make "Select sites" show since for some reason that doesn't work by default
			$("#sites").trigger("change");
		}
	});
	
	function setColor(point, color) {
		point.symbol.color = color;
		view.graphics.remove(point);
		view.graphics.add(point);
	}
	
	function highlightPoint(point) {
		clickedPoint = point;
		setColor(clickedPoint, clickedPointColor)
	}
	
	function clearHighlight() {
		if (clickedPoint != null) {
			setColor(clickedPoint, defaultPointColor);
		}
	}
	
	function selectPoint() {
		var siteNum = mapData["SiteData"][view.popup.selectedFeature.ObjectID].Site_Number;
		var currentlySelected = $("#sites").val();
		
		if (!currentlySelected.includes(siteNum.toString())) {
			//add it
			currentlySelected.push(siteNum.toString());
			$("#sites").val(currentlySelected).trigger("change");
		}
	}
	
	$("#sites").change(function() {
		var optSelected = $("option:selected", this);
		
		//first clear out the existing ones
		for (var point of selectedPoints) {
			setColor(point, defaultPointColor);
		}
		
		if (optSelected.length > 0) {
			var selected = $("#sites").val();

			if (optSelected.parent()[0].id == "groupOpt") {		
				//get all the sites that are in this group
				var inGroup = [];
				for (var site of mapData.SiteData) {
					if (site.groups.includes(selected)) {
						//select it in the sites dropdown
						inGroup.push(site.Site_Number);
					}
				}
				
				$("#sites").val(inGroup).trigger("change");
			}
			else {
				getRange();
				
				selectedPoints = [];
				
				for (i=0; i<selected.length; i++) {
					//get associated graphic for this point
					for (j=0; j<mapData["SiteData"].length; j++) {
						if (mapData["SiteData"][j].Site_Number.toString() === selected[i]) {
							setColor(mapData["SiteData"][j].graphic, selectedPointColor);
							selectedPoints.push(mapData["SiteData"][j].graphic);
							break;
						}
					}
				}
			}
		}
		else {
			selectedPoints = [];
		}
	});
	
	$("#showBenchmarks").change(function() {
		showBenchmarks = !showBenchmarks;
		resetCharts();
		getGraphData();
	});
	
	$("#allCheckbox").change(function() {
		var checkboxes = document.getElementsByClassName("measurementCheckbox");
		for (i=0; i<checkboxes.length; i++) {
			checkboxes[i].checked = allCheckbox.checked;
		}
	});
	
	function checkboxesChanged() {
		var checkboxes = document.getElementsByClassName("measurementCheckbox");
		for (i=0; i<checkboxes.length; i++) {
			if (checkboxes[i].checked === false) {
				allCheckbox.checked = false; //deselect the All checkbox
				break;
			}
		}
	}
	
	$(".measurementCheckbox").change(function() {
		checkboxesChanged();
	});

	function getRange() {
		spinnerInhibited = true;
		
		var sites = $("#sites").val();
		if (sites.length != 0) { //no point making a request if no sites are selected
			$.ajax({
				type: "POST",
				url: "daterange",
				data: {
					"sites": sites,
					"category": categorySelect.value
				},
				datatype: "JSON",
				async: false,
				success: function (data) {
					setDates(data[0], data[1]);
				},
				error: function(response) {
					genericError();
				}
			});
		}
		else {
			setDates(null, null);
		}
		
		spinnerInhibited = false;
	}
	
	function setDates(startDate, endDate) {
		$("#startDate").val(startDate);
		$("#endDate").val(endDate);
		$("#startDate").datepicker("update", startDate);
		$("#endDate").datepicker("update", endDate);
	}
	
	categorySelect.addEventListener("change", function() {
		changeMeasures();
	});
	
	measurementSelect.addEventListener("change", function() {
		var category = measurementSettings[categorySelect.value];
		var measureIndex;
		for (measureIndex=0; measureIndex<category.length; measureIndex++) {
			if (category[measureIndex].measureKey === measurementSelect.value) {
				break;
			}
		}
		
		if (category[measureIndex].benchmarkMaximum == null) {
			amountEnter.placeholder = "No Benchmark Available";
		}
		else {
			amountEnter.placeholder = "Benchmark: " + category[measureIndex].benchmarkMaximum + " " + category[measureIndex].unit;
		}
	});
	
	$(".date-picker").datepicker({
		trigger: "focus",
		format: "mm/dd/yyyy",
		todayHighlight: true,
		todayBtn: "linked"
	});

	$("#startDate").datepicker().on("changeDate", function (selected) {
		$("#endDate").datepicker("setStartDate", new Date(selected.date.valueOf()));
	});

	$("#endDate").datepicker().on("changeDate", function (selected) {
		$("#startDate").datepicker("setEndDate", new Date(selected.date.valueOf()));
	});

	$("#sites").select2({
		closeOnSelect: false,
		placeholder: "Select sites",
		width: "resolve"
	});
	
	var easter_egg = new Konami(function() {
		//dynamically download the needed code so we don't bog down the 99.9% of users who won't even see this
		import("/WQIS/js/EEGS.js")
			.then((module) => {
				module.start();
			});
	});
	
	document.addEventListener("keydown", function(e) {
		if (e.keyCode === 27) {
			//when user hits escape key, close the sidebar
			closeSearchSidebar();
		}
	}, false);
	
	$("#exportBtn").click(function () {
		var category = categorySelect.value;

		$.ajax({
			type: "POST",
			url: "/WQIS/generic-samples/exportData",
			datatype: "JSON",
			data: {
				"sites": $("#sites").val(),
				"startDate": $("#startDate").val(),
				"endDate": $("#endDate").val(),
				"category": category,
				"amountEnter": amountEnter.value,
				"overUnderSelect": overUnderSelect.value,
				"measurementSearch": measurementSelect.value,
				"selectedMeasures": getSelectedMeasures(),
				"aggregate": aggregateGroup.checked
			},
			success: function(response) {
				downloadFile(response, category);
			},
			error: function(response) {
				genericError();
			}
		});
	});
	
	function changeMeasures() {
		//when the measurement category is changed, change both lists of available measurements to match
		var measurementCheckboxes = document.getElementsByClassName("measurementCheckbox");
		var categoryData = measurementSettings[categorySelect.value];
		
		//first clear all the measures currently listed
		while (measurementSelect.options.length > 0) {
			measurementSelect.remove(0);
		}
		
		for (i=measurementCheckboxes.length-1; i>=0; i--) {
			checkboxList.removeChild(measurementCheckboxes[i].parentNode);
		}
		
		allCheckbox.checked = true;
	
		var option = document.createElement("option");
		option.value = "select";
		option.text = "Select a measure";
		measurementSelect.appendChild(option);
		
		for (i=0; i<categoryData.length; i++) {
			//fill in the measurementSelect dropdown
			var option = document.createElement("option");
			option.value = categoryData[i].measureKey;
			option.text = categoryData[i]["measureName"];
			measurementSelect.appendChild(option);
			
			//now create the checkboxes as well
			var listItem = document.createElement("li");
				
			var box = document.createElement("input");
			box.value = categoryData[i].measureKey;
			box.id = categoryData[i].measureKey + "Checkbox";
			box.type = "checkbox";
			box.setAttribute("class", "measurementCheckbox");
			box.checked = true;
			
			var boxLabel = document.createElement("label");
			boxLabel.innerText = categoryData[i]["measureName"];
			boxLabel.setAttribute("for", categoryData[i].measureKey + "Checkbox");
			
			listItem.appendChild(box);
			listItem.appendChild(boxLabel);
			
			checkboxList.appendChild(listItem);
		}
		
		amountEnter.value = "";
		
		$(".measurementCheckbox").change(function() {
			checkboxesChanged();
		});
		
		getRange(); //recalculate date range
		
		//reset the sortBy field to Date, since none of the other measures will be valid anymore
		sortBy = "Date";
	}
	
	function downloadFile(fileData, type) {
		if (fileData.length < 1) {
			return;
		}
		
		var csvContent = "data:text/csv;charset=utf-8,";
		var fields = Object.keys(fileData[0]);
		for (var i=0; i<fileData.length; i++) {
			fileData[i]["Date"] = fileData[i]["Date"].substring(0, 10);
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
		fields[fields.indexOf("site_location_id")] = "Site Number";
		
		//add header column
		csv.unshift(fields.join(','));

		csvContent += csv.join("\r\n");
		var link = document.createElement("a");
		link.setAttribute("href", encodeURI(csvContent));
		link.setAttribute("download", type + "_export.csv");
		document.body.appendChild(link);
		link.click();
	}
	
	$("#updateButton").click(function() {
		updateAll();
	});
	
	function updateAll() {
		//validation
		if ($("#sites").val() == "") { //check that at least one site is selected
			alert("You must select at least one site to view");
		}
		else if (amountEnter.value != "" && measurementSelect.value === "select") { //check that, if there is something in amountEnter, a measure is also selected
			alert("You must specify a measure to search by");
		}
		else {
			resetAll();
			getNumRecords();
			getGraphData();
			getTableData(1);
			$("#chartsLayoutSelect").show();
			exportBtn.disabled = !(numPages > 0);
		}
	}
	
	$("#resetButton").click(function() {
		//clear all parameters to default values, and clear the chart/table view
		resetAll();
		$("#sites").val(null).trigger("change");
		$("#categorySelect").val("bacteria");
		changeMeasures();
		$("#chartsLayoutSelect").hide();
		exportBtn.disabled = true;
	});
	
	$("#chartsInlineButton").click(function() {
		chartsDisplayMode = "in-line";
		resetCharts();
		getGraphData();
	});
	
	$("#chartsGridButton").click(function() {
		chartsDisplayMode = "grid";
		resetCharts();
		getGraphData();
	});
	
	$("#sidebarToggle").click(function() {
		toggleSearchSidebar();
	});
	
	function getTableData(page) {
		if (numPages > 0) { //if there is any data to display
			tableNoData.style = "display: none";
			chartsNoData.style = "display: none";
			tableSettingsTop.style = "display: block";
			tableSettingsBottom.style = "display: block";
	
			tablePage = page;
			document.getElementById("tableDiv").innerHTML = "";
			$("#pageSelectorTop").val(tablePage);
			$("#pageSelectorBottom").val(tablePage);

			$(".firstPageButton").attr("disabled", false);
			$(".previousPageButton").attr("disabled", false);
			$(".lastPageButton").attr("disabled", false);
			$(".nextPageButton").attr("disabled", false);

			if (tablePage == 1) {
				$(".previousPageButton").attr("disabled", true);
				$(".firstPageButton").attr("disabled", true);
			}
			if (tablePage == numPages) {
				$(".nextPageButton").attr("disabled", true);
				$(".lastPageButton").attr("disabled", true);
			}

			var category = categorySelect.value;
			var selectedMeasures = getSelectedMeasures();
			var aggregateMode = aggregateGroup.checked;

			//set up the column names and IDs to actually display
			if (!aggregateMode) {
				var columns = ["Site ID", "Date", "Sample Number"];
			}
			else {
				var columns = ["Date"];
			}
			for (i=0; i<selectedMeasures.length; i++) {
				//get index of this measure so we can find its printable name
				for (j=0; j<measurementSettings[category].length; j++) {
					if (measurementSettings[category][j].measureKey === selectedMeasures[i]) {
						var measureTitle = measurementSettings[category][j].measureName;
						if (measurementSettings[category][j].unit != "") {
							measureTitle += " (" + measurementSettings[category][j].unit + ")";
						}
						columns.push(measureTitle);
						break;
					}
				}
			}
			if (!aggregateMode) {
				var columnIDs = ((["site_location_id", "Date", "Sample_Number"]).concat(selectedMeasures));
				if (admin) {
					columns.push("Comments");
					columnIDs.push(ucfirst(category) + "Comments");
				}
			}
			else {
				var columnIDs = ((["Date"]).concat(selectedMeasures));
			}

			$.ajax({
				type: "POST",
				url: "/WQIS/generic-samples/tabledata",
				datatype: "JSON",
				async: false,
				data: {
					"sites": $("#sites").val(),
					"startDate": $("#startDate").val(),
					"endDate": $("#endDate").val(),
					"category": category,
					"amountEnter": amountEnter.value,
					"overUnderSelect": overUnderSelect.value,
					"measurementSearch": measurementSelect.value,
					"selectedMeasures": selectedMeasures,
					"numRows": numRowsDropdownTop.value,
					"pageNum": tablePage,
					"sortBy": sortBy,
					"sortDirection": sortDirection,
					"aggregate": aggregateMode
				},
				success: function(response) {
					//create the blank table
					var table = document.createElement("table");
					table.setAttribute("class", "table table-striped");
					table.id = "tableView";
			
					//build the header row first
					var tableHeader = table.insertRow();
			
					for (i=0; i<columns.length; i++) {
						var newCell = document.createElement("th");
						newCell.innerHTML = columns[i];
						newCell.setAttribute("class", "sort-by headerSort" + ((columnIDs[i] === sortBy) ? (" " + sortDirection) : ""));
						newCell.id = columnIDs[i];
						newCell.onclick = function() {setSort(event);};
				
						tableHeader.appendChild(newCell);
					}
					if (admin && !aggregateMode) {
						var actionsCell = document.createElement("th");
						actionsCell.innerText = "Actions";
						tableHeader.appendChild(actionsCell);
					}
				
					//fill in each row
					for (var i=0; i<response[0].length; i++) {
						var newRow = table.insertRow();
				
						Object.keys(response[0][i]).forEach(function(key) {
							if (key != "ID" && !(key.includes("Comment") && !admin)) { //this logic can be cleaned up a lot
								var newCell = newRow.insertCell();
								var value = response[0][i][key];
						
								if (key === "Date") {
									//we get the date in a weird format, parse it to something more appropriate
									value = value.split("T")[0];
								}
					
								if (admin && !aggregateMode) {
									var textDiv = document.createElement("div");
									textDiv.setAttribute("class", "input text");
									newCell.appendChild(textDiv);
										
									if (!key.includes("Comment")) {
										var label = document.createElement("label");
										label.style = "display: table-cell; cursor: pointer; white-space:normal !important";
										label.setAttribute("class", "btn btn-thin inputHide");
										label.setAttribute("for", key + "-" + i);
										label.innerText = value;
										
										label.onclick = function () {
											var label = $(this);
											var input = $("#" + label.attr("for"));
											input.trigger("click");
											label.attr("style", "display: none");
											input.attr("style", "display: in-line");
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

											if (!input.attr("id")) {
												return;
											}
		
											var value = input.val();
	
											$.ajax({
												type: "POST",
												url: "/WQIS/generic-samples/updatefield",
												datatype: "JSON",
												data: {
													"sampleNumber": $("#Sample_Number-" + (input.attr("id")).split("-")[1]).val(),
													"parameter": (input.attr("name")).split("-")[0],
													"value": value
												},
												success: function () {
													var label = $('label[for="' + input.attr('id') + '"');

													input.attr("style", "display: none");
													label.attr("style", "display: in-line; cursor: pointer");
	
													if (value === "") {
														label.text("  ");
													}
													else {
														label.text(value);
													}
												},
												error: function() {
													genericError();
												}
											});
										});
						
										textDiv.appendChild(cellInput);
									}
									else {
										//handle comments column separately because its a larger amount of text that needs to be displayed in multiple lines
										var label = document.createElement("label");
										label.style = "display: table-cell; cursor: pointer; white-space:normal !important; overflow-wrap: anywhere";
										label.setAttribute("class", "btn btn-thin inputHide");
										label.setAttribute("for", key + "-" + i);
										label.innerText = value;
										
										label.onclick = function () {
											var label = $(this);
											var input = $("#" + label.attr("for"));
											input.trigger("click");
											label.attr("style", "display: none");
											input.attr("style", "display: in-line");
										};
										
										textDiv.appendChild(label);
										
										var textArea = document.createElement("textarea");
										textArea.setAttribute("rows", "4");
										textArea.setAttribute("cols", "50");
										textArea.setAttribute("class", "tableInput");
										textArea.setAttribute("name", key + "-" + i);
										textArea.setAttribute("style", "display: none");
										textArea.setAttribute("id", key + "-" + i);
										textArea.innerText = value;
										
										textArea.onfocusout = (function () {
											var input = $(this);

											if (!input.attr("id")) {
												return;
											}

											var value = input.val();
	
											$.ajax({
												type: "POST",
												url: "/WQIS/generic-samples/updatefield",
												datatype: "JSON",
												data: {
													"sampleNumber": $("#Sample_Number-" + (input.attr("id")).split("-")[1]).val(),
													"parameter": (input.attr("name")).split("-")[0],
													"value": value
												},
												success: function () {
													var label = $('label[for="' + input.attr('id') + '"');

													input.attr("style", "display: none");
													label.attr("style", "display: in-line; cursor: pointer");
	
													if (value === "") {
														label.text("  ");
													}
													else {
														label.text(value);
													}
												},
												error: function() {
													genericError();
												}
											});
										});
										
										textDiv.appendChild(textArea);
									}
								}
								else {
									if (!key.includes("Comment")) {
										var label = document.createElement("label");
										label.style = "display: table-cell; cursor: pointer; white-space:normal !important;";
										label.setAttribute("for", key + "-" + i);
										label.innerText = value;
							
										newCell.appendChild(label);
									}
								}
							}
						});
				
						if (admin && !aggregateMode) {
							//add the deletion button
							var newCell = newRow.insertCell();
							var delButton = document.createElement("span");
							delButton.setAttribute("class", "delete glyphicon glyphicon-trash");
							delButton.setAttribute("id", "Delete-" + i);
							delButton.setAttribute("name", "Delete-" + i);
							delButton.onclick = function() {
								var rowDiv = this;
		
								if (!$(rowDiv).attr("id")) {
									return;
								}
			
								var bool = window.confirm("Are you sure you want to delete this record?");
								if (bool) {
									//delete record with this sample number and category
									$.ajax({
										type: "POST",
										url: "/WQIS/generic-samples/deleteRecord",
										datatype: "JSON",
										data: {
											"sampleNumber": $("#Sample_Number-" + ($(rowDiv).attr("id")).split("-")[1]).val(),
											"type": category
										},
										success: function () {
											//remove the row from view
											rowDiv.parentNode.parentNode.style.display = "none";
					
											//future work: build a new table, to still maintain correct total number of rows and have correct black/white/black sequencing after deletions
										},
										error: function () {
											genericError();
										}
									});
								}
							}
							newCell.append(delButton);
						}
					}

					document.getElementById("tableDiv").append(table);
				},
				error: function(response) {
					genericError();
				}
			});
		}
		else {
			tableNoData.style = "display: block";
			chartsNoData.style = "display: block";
			tableSettingsTop.style = "display: none";
			tableSettingsBottom.style = "display: none";
		}
	}
	
	$("#numRowsDropdownTop").change(function() {
		$("#numRowsDropdownBottom").val(numRowsDropdownTop.value);
		getNumRecords();
		getTableData(1);
	});
	
	$("#numRowsDropdownBottom").change(function() {
		$("#numRowsDropdownTop").val(numRowsDropdownBottom.value);
		getNumRecords();
		getTableData(1);
	});
	
	$(".firstPageButton").click(function() {
		getTableData(1);
	});
	
	$(".previousPageButton").click(function() {
		getTableData(tablePage-1);
	});
	
	$(".nextPageButton").click(function() {
		getTableData(tablePage+1);
	});
	
	$(".lastPageButton").click(function() {
		getTableData(numPages);
	});
	
	$("#pageSelectorTop").change(function() {
		getTableData($("#pageSelectorTop").val());
	});
	
	$("#pageSelectorBottom").change(function() {
		getTableData($("#pageSelectorBottom").val());
	});
	
	$("#chartType").change(function() {
		for (i=0; i<charts.length; i++) {
			var datasets = charts[i].data.datasets;
			var showLine = (chartType.value === "line");
			
			for (j=0; j<datasets.length; j++) {
				datasets[j].showLine = showLine;
			}
			
			charts[i].update(0);
		}
	});
	
	function getNumRecords() {
		//get the number of records
		$.ajax({
			type: "POST",
			url: "/WQIS/generic-samples/tablePages",
			datatype: "JSON",
			async: false,
			data: {
				"sites": $("#sites").val(),
				"startDate": $("#startDate").val(),
				"endDate": $("#endDate").val(),
				"category": categorySelect.value,
				"amountEnter": amountEnter.value,
				"overUnderSelect": overUnderSelect.value,
				"measurementSearch": measurementSelect.value,
				"selectedMeasures": getSelectedMeasures(),
				"aggregate": aggregateGroup.checked
			},
			success: function(response) {
				numResults = response[0];
				var numRows = numRowsDropdownTop.value;
				if (numRows > -1) {
					numPages = Math.ceil(numResults / numRows);
				}
				else {
					numPages = 1;
				}
				$(".totalResults").text(numResults);
				
				//build up options for the page selector dropdowns
				var topSelect = document.getElementById("pageSelectorTop");
				topSelect.innerHTML = "";
				
				var bottomSelect = document.getElementById("pageSelectorBottom");
				bottomSelect.innerHTML = "";
				
				for (i=0; i<numPages; i++) {
					var opt = document.createElement("option");
					opt.text = i+1;
					topSelect.add(opt);
					
					var opt2 = document.createElement("option");
					opt2.text = i+1;
					bottomSelect.add(opt2);
				}
			},
			error: function(response) {
				genericError();
			}
		});
	}
	
	function toggleSearchSidebar() {
		//expand the search sidebar and shift the rest of the page over, or the opposite
		if (sidebarInner.style.width == "19vw") {
			closeSearchSidebar();
		}
		else {
			openSearchSidebar();
		}
	}
	
	function openSearchSidebar() {
		sidebarInner.style.width = "19vw";
		sidebarInner.style.paddingLeft = "10px";
		sidebarInner.style.paddingRight = "10px";
		main.style.marginLeft = "19.5vw";
		main.style.width = "78vw";
		sidebarToggle.classList.toggle("change");
	}
	
	function closeSearchSidebar() {
		sidebarInner.style.width = 0;
		sidebarInner.style.paddingLeft = 0;
		sidebarInner.style.paddingRight = 0;
		main.style.marginLeft = "15px";
		main.style.width = "100%";
		sidebarToggle.classList.toggle("change");
	}
	
	//set the search sidebar open at start
	openSearchSidebar();

	function resetCharts() {
		//remove the old chart
		chartDiv.innerHTML = "";
		
		chartsNoData.style = "display: block";
	}
	
	function resetTable() {
		//remove the old table
		document.getElementById("tableDiv").innerHTML = "";
		
		var sampleTable = document.getElementById("tableView");
		if (sampleTable != null) {
			sampleTable.parentNode.removeChild(sampleTable);
		}
		
		tableNoData.style = "display: block";
		tableSettingsTop.style = "display: none";
		tableSettingsBottom.style = "display: none";
	}
	
	function resetAll() {
		resetCharts();
		resetTable();
	}
	
	function setSort(e) {
		var field = e.srcElement.id;
		
		if (field === "") {
			//we probably clicked on the sorting icon, get its parent node and try again
			field = e.srcElement.parentElement.id;
		}
		
		//check if this was already the sortBy field, if so then we swap the sort direction
		if (sortBy === field) {
			if (sortDirection === "Desc") {
				sortDirection = "Asc";
			}
			else {
				sortDirection = "Desc";
			}
		}
		
		resetTable();
		sortBy = field;
		
		getTableData(1);
	}

	function getGraphData() {
		charts = [];

		var sites = $("#sites").val();
		var measures = getSelectedMeasures();
		var category = categorySelect.value;
		var aggregateMode = aggregateGroup.checked;
		
		//build the necessary canvases
		var nMeasures = measures.length;
		
		if (chartsDisplayMode === "in-line") {
			for (var k=0; k<nMeasures; k++) {
				var newCanvasContainer = document.createElement("div");
				newCanvasContainer.style = "width: 80%; text-align: center; margin: auto";
			
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
			
			//figure out the number of rows with 2 columns each
			var nx = 2;
			var ny = Math.ceil(nMeasures/nx);
			var chartNum = 0;
			for (y=0; y<ny; y++) {
				var row = document.createElement("div");
				row.setAttribute("class", "row");
				
				for (x=0; x<nx; x++) {
					var cell = document.createElement("div");
					cell.setAttribute("class", "col-sm");
				
					var newCanvasContainer = document.createElement("div");
					newCanvasContainer.style = "width: 100%; text-align: center; margin: auto;";
				
					var newCanvas = document.createElement("canvas");
					newCanvas.id = "chart-" + chartNum;
					newCanvasContainer.appendChild(newCanvas);
				
					cell.appendChild(newCanvasContainer);
					row.appendChild(cell);
					chartNum++;
				}
				chartsGrid.appendChild(row);
			}
			chartDiv.appendChild(chartsGrid);
		}
		
		$.ajax({
			type: "POST",
			url: "/WQIS/generic-samples/graphdata",
			datatype: "JSON",
			async: false,
			data: {
				"sites": sites,
				"startDate": $("#startDate").val(),
				"endDate": $("#endDate").val(),
				"selectedMeasures": measures,
				"category": category,
				"amount": amountEnter.value,
				"overUnderSelect": overUnderSelect.value,
				"measurementSearch": measurementSelect.value,
				"aggregate": aggregateMode
			},
			success: function(response) {
				if (!aggregateMode) {
					//individual mode
					for (k=0; k<measures.length; k++) {
						var datasets = [];

						for (i=0; i<sites.length; i++) {
							var newDataset = {
								label: sites[i],
								borderColor: selectColor(i, sites.length),
								data: [],
								lineTension: 0,
								fill: false,
								borderWidth: 1.5,
								showLine: (chartType.value === "line"),
								spanGaps: true,
							};
							
							datasets.push(newDataset);
						}
						
						var labels = [];
						for (i=0; i<response.length; i++) {
							var newRow = [];
							var date = response[i].Date.split("T")[0];
							newRow.t = date;
							newRow.y = response[i][measures[k]];
							
							for (j=0; j<sites.length; j++) {
								if (response[i].site == sites[j]) {
									datasets[j].data.push(newRow);
									break;
								}
							}
						
							//make sure there isn't already a label created for this date, or things break in weird ways
							if (!labels.includes(date)) {
								labels.push(date);
							}
						}
						
						buildChart(k, category, measures, labels, datasets);
					}
				}
				else {
					//aggregate mode
					for (k=0; k<measures.length; k++) {
						var dataset = {
							label: "Average",
							borderColor: selectColor(0, 1),
							data: [],
							lineTension: 0,
							fill: false,
							borderWidth: 1.5,
							showLine: (chartType.value === "line"),
							spanGaps: true,
						};
	
						var labels = [];
						for (i=0; i<response.length; i++) {
							var newRow = [];
							var date = response[i].Date.split("T")[0];
							newRow.t = date;
							newRow.y = response[i][measures[k]];
		
							dataset.data.push(newRow);
		
							//make sure there isn't already a label created for this date, or things break in weird ways
							if (!labels.includes(date)) {
								labels.push(date);
							}
						}
	
						buildChart(k, category, measures, labels, [dataset]);
					}
				}
			}
		});
	}

	function buildChart(k, category, measures, labels, datasets) {
		var ctx = document.getElementById("chart-" + k).getContext("2d");
	
		var measureTitle;
		//get index of this measure so we can find its printable name
		var thisMeasure;
		for (var i=0; i<measurementSettings[category].length; i++) {
			thisMeasure = measurementSettings[category][i];
			if (thisMeasure.measureKey === measures[k]) {
				measureTitle = thisMeasure.measureName;
				if (thisMeasure.unit != "") {
					measureTitle += " (" + thisMeasure.unit + ")";
				}
				break;
			}
		}
	
		var benchmarkAnnotation = {};
		if (showBenchmarks) {
			benchmarkAnnotation = {annotations: [benchmarkLine(thisMeasure.benchmarkMaximum, "red"), benchmarkLine(thisMeasure.benchmarkMinimum, "blue")]};
		}
		
		charts.push(new Chart(ctx, {
			type: "line",
			data: {
				labels: labels,
				datasets: datasets
			},
			options: {
				annotation: benchmarkAnnotation,
				scales: {
					yAxes: [
						{
							scaleLabel: {
								display: true,
								labelString: measureTitle,
								position: "left"
							}
						},
						{ //second label for comparison, if used
							id: "comparison",
							scaleLabel: {
								display: true,
							},
							gridLines: {
								display: false
							},
							display: false,
							position: "right"
						}
					]
				},
				responsive: true
			}
		}));
	}
});