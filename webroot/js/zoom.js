/*!
 * Plugin for charts.js to handle zooming/panning on graphs
 * ADAPTED FROM Chart.Zoom.js
 * http://chartjs.org/
 * Version: 0.3.0
 *
 * Copyright 2016 Evert Timberg
 * Released under the MIT license
 * https://github.com/chartjs/Chart.Zoom.js/blob/master/LICENSE.md
 */
(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){

},{}],2:[function(require,module,exports){
/*jslint browser:true, devel:true, white:true, vars:true */
/*global require*/

// hammer JS for touch support
var Hammer = require('hammerjs');
Hammer = typeof(Hammer) === 'function' ? Hammer : window.Hammer;

//get the chart variable
var Chart = require('chart.js');
Chart = typeof(Chart) === 'function' ? Chart : window.Chart;
var helpers = Chart.helpers;

//take the zoom namespace of Chart
var zoomNS = Chart.Zoom = Chart.Zoom || {};

//default options if none are provided
var defaultOptions = zoomNS.defaults = {
	pan: {
		enabled: true,
		mode: 'xy',
		speed: 20,
		threshold: 10,
	},
	zoom: {
		enabled: true,
		mode: 'xy',
		sensitivity: 0,
	}
};

function directionEnabled(mode, dir) {
	if (mode === undefined) {
		return true;
	} else if (typeof mode === 'string') {
		return mode.indexOf(dir) !== -1;
	}

	return false;
}

function zoomIndexScale(scale, zoom, center, zoomOptions) {
	var labels = scale.chart.data.labels;
	var minIndex = scale.minIndex;
	var lastLabelIndex = labels.length - 1;
	var maxIndex = scale.maxIndex;
	var sensitivity = zoomOptions.sensitivity;
	var chartCenter =  scale.isHorizontal() ? scale.left + (scale.width/2) : scale.top + (scale.height/2);
	var centerPointer = scale.isHorizontal() ? center.x : center.y;

	//zoomNS.zoomCumulativeDelta = zoom > 1 ? zoomNS.zoomCumulativeDelta + 1 : zoomNS.zoomCumulativeDelta - 1;
	if (zoom > 1) {
		zoomNS.zoomCumulativeDelta+=1;
	}
	else {
		zoomNS.zoomCumulativeDelta-=1;
	}

	if (Math.abs(zoomNS.zoomCumulativeDelta) > sensitivity){
		if(zoomNS.zoomCumulativeDelta < 0){
			if(centerPointer <= chartCenter){
				if (minIndex <= 0){
					maxIndex = Math.min(lastLabelIndex, maxIndex + 10);
				} else{
					minIndex = Math.max(0, minIndex - 10);
				}
			} else if(centerPointer > chartCenter){
				if (maxIndex >= lastLabelIndex){
					minIndex = Math.max(0, minIndex - 10);
				} else{
					maxIndex = Math.min(lastLabelIndex, maxIndex + 10);
				}
			}
			zoomNS.zoomCumulativeDelta = 0;
		}
		else if(zoomNS.zoomCumulativeDelta > 0){
			if (centerPointer <= chartCenter){
				minIndex = minIndex < maxIndex ? minIndex = Math.min(maxIndex, minIndex + 10) : minIndex;
			}
			else if(centerPointer > chartCenter){
				maxIndex = maxIndex > minIndex ? maxIndex = Math.max(minIndex, maxIndex - 10) : maxIndex;
			}
			zoomNS.zoomCumulativeDelta = 0;
		}
		scale.options.ticks.min = labels[minIndex];
		scale.options.ticks.max = labels[maxIndex];
	}
}

function addButtons(node, chartInstance) {
	var node = chartInstance.chart.ctx.canvas;
	//var options = chartInstance.options;
	//var panThreshold = helpers.getValueOrDefault(options.pan ? options.pan.threshold : undefined, zoomNS.defaults.pan.threshold);

	var chartNum = node.id.split("-")[1];
	
	var zoomInButton = document.createElement("button");
	zoomInButton.innerText = "+";
	zoomInButton.id = "zoomInButton-" + chartNum;
	zoomInButton.onclick = function() {
		//zoom in
		doZoom(chartInstance, 1.1);
	};
	node.parentElement.appendChild(zoomInButton);
	
	var zoomOutButton = document.createElement("button");
	zoomOutButton.innerText = "-";
	zoomOutButton.id = "zoomOutButton-" + chartNum;
	zoomOutButton.onclick = function() {
		//zoom out
		doZoom(chartInstance, -1);
	}
	node.parentElement.appendChild(zoomOutButton);
}

function doZoom(chartInstance, zoom, center) {
	var ca = chartInstance.chartArea;
	if (!center) {
		center = {
			x: (ca.left + ca.right) / 2,
			y: (ca.top + ca.bottom) / 2,
		};
	}

	var zoomOptions = chartInstance.options.zoom;

	if (zoomOptions && helpers.getValueOrDefault(zoomOptions.enabled, defaultOptions.zoom.enabled)) {
		//do the zoom here
		var zoomMode = helpers.getValueOrDefault(chartInstance.options.zoom.mode, defaultOptions.zoom.mode);
		zoomOptions.sensitivity = helpers.getValueOrDefault(chartInstance.options.zoom.sensitivity, defaultOptions.zoom.sensitivity);

		helpers.each(chartInstance.scales, function(scale, id) {
			zoomIndexScale(scale, zoom, center, zoomOptions)
		});

		chartInstance.update(0);
	}
}

function panIndexScale(scale, delta, panOptions) {
	var labels = scale.chart.data.labels;
	var lastLabelIndex = labels.length - 1;
	var offsetAmt = Math.max((scale.ticks.length - ((scale.options.gridLines.offsetGridLines) ? 0 : 1)), 1);
	var panSpeed = panOptions.speed;
	var minIndex = scale.minIndex;
	var step = Math.round(scale.width / (offsetAmt * panSpeed));
	var maxIndex = scale.maxIndex;

	zoomNS.panCumulativeDelta += delta;
	
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
}

function doPan(chartInstance, deltaX, deltaY) {
	var panOptions = chartInstance.options.pan;
	if (panOptions && helpers.getValueOrDefault(panOptions.enabled, defaultOptions.pan.enabled)) {
		var panMode = helpers.getValueOrDefault(chartInstance.options.pan.mode, defaultOptions.pan.mode);
		panOptions.speed = helpers.getValueOrDefault(chartInstance.options.pan.speed, defaultOptions.pan.speed);

		helpers.each(chartInstance.scales, function(scale, id) {
			if (scale.isHorizontal() && directionEnabled(panMode, 'x') && deltaX !== 0) {
				panIndexScale(scale, deltaX, panOptions)
			}
		});

		chartInstance.update(0);
	}
}

function positionInChartArea(chartInstance, position) {
	return (position.x >= chartInstance.chartArea.left && position.x <= chartInstance.chartArea.right) &&
		(position.y >= chartInstance.chartArea.top && position.y <= chartInstance.chartArea.bottom);
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

// Globals for catergory pan and zoom
zoomNS.panCumulativeDelta = 0;
zoomNS.zoomCumulativeDelta = 0;

// Chartjs Zoom Plugin
var zoomPlugin = {
	afterInit: function(chartInstance) {
		helpers.each(chartInstance.scales, function(scale) {
			scale.originalOptions = JSON.parse(JSON.stringify(scale.options));
		});

		chartInstance.resetZoom = function() {
			helpers.each(chartInstance.scales, function(scale, id) {
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
			});

			helpers.each(chartInstance.data.datasets, function(dataset, id) {
				dataset._meta = null;
			});

			chartInstance.update();
		};
		
		var node = chartInstance.chart.ctx.canvas;
		addButtons(node, chartInstance);

	},
	beforeInit: function(chartInstance) {
		var node = chartInstance.chart.ctx.canvas;
		var options = chartInstance.options;
		var panThreshold = helpers.getValueOrDefault(options.pan ? options.pan.threshold : undefined, zoomNS.defaults.pan.threshold);

		if (options.zoom && options.zoom.drag) {
			//Only want to zoom horizontal axis
			options.zoom.mode = 'x';

			node.addEventListener('mousedown', function(event){
				chartInstance._dragZoomStart = event;
			});

			node.addEventListener('mousemove', function(event){
				if (chartInstance._dragZoomStart) {
					chartInstance._dragZoomEnd = event;
					chartInstance.update(0);
				}

				chartInstance.update(0);
			});

			node.addEventListener('mouseup', function(event){
				if (chartInstance._dragZoomStart) {
					var chartArea = chartInstance.chartArea;
					var yAxis = getYAxis(chartInstance);
					var beginPoint = chartInstance._dragZoomStart;
					var offsetX = beginPoint.target.getBoundingClientRect().left;
					var startX = Math.min(beginPoint.x, event.x) - offsetX;
					var endX = Math.max(beginPoint.x, event.x) - offsetX;
					var dragDistance = endX - startX;
					var chartDistance = chartArea.right - chartArea.left;
					var zoom = 1 + ((chartDistance - dragDistance) / chartDistance );

					if (dragDistance > 0) {
						doZoom(chartInstance, zoom, {
							x: (dragDistance / 2) + startX,
							y: (yAxis.bottom - yAxis.top) / 2,
						});
					}

					chartInstance._dragZoomStart = null;
					chartInstance._dragZoomEnd = null;
				}
			});
		}
		else {
			var wheelHandler = function(e) {
				var rect = e.target.getBoundingClientRect();
				var offsetX = e.clientX - rect.left;
				var offsetY = e.clientY - rect.top;

				var center = {
					x : offsetX,
					y : offsetY
				};

				if (e.deltaY < 0) {
					doZoom(chartInstance, 1.1, center);
				} else {
					doZoom(chartInstance, 0.909, center);
				}
				// Prevent the event from triggering the default behavior (eg. Content scrolling).
				e.preventDefault();
			};
			chartInstance._wheelHandler = wheelHandler;

			node.addEventListener('wheel', wheelHandler);
		}

		if (Hammer) {
			var mc = new Hammer.Manager(node);
			mc.add(new Hammer.Pinch());
			mc.add(new Hammer.Pan({
				threshold: panThreshold
			}));

			// Hammer reports the total scaling. We need the incremental amount
			var currentPinchScaling;
			var handlePinch = function handlePinch(e) {
				var diff = 1 / (currentPinchScaling) * e.scale;
				doZoom(chartInstance, diff, e.center);

				// Keep track of overall scale
				currentPinchScaling = e.scale;
			};

			mc.on('pinchstart', function(e) {
				currentPinchScaling = 1; // reset tracker
			});
			mc.on('pinch', handlePinch);
			mc.on('pinchend', function(e) {
				handlePinch(e);
				currentPinchScaling = null; // reset
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

			mc.on('panstart', function(e) {
				currentDeltaX = 0;
				currentDeltaY = 0;
				handlePan(e);
			});
			mc.on('panmove', handlePan);
			mc.on('panend', function(e) {
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
			var endX = Math.max(beginPoint.x, endPoint.x) - offsetX;
			var rectWidth = endX - startX;

			ctx.fillStyle = 'rgba(225,225,225,0.3)';
			ctx.lineWidth = 5;
			ctx.fillRect(startX, yAxis.top, rectWidth, yAxis.bottom - yAxis.top);
		}

		ctx.rect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
		ctx.clip();
	},

	afterDatasetsDraw: function(chartInstance) {
		chartInstance.chart.ctx.restore();
	},

	destroy: function(chartInstance) {
		var node = chartInstance.chart.ctx.canvas;
		node.removeEventListener('wheel', chartInstance._wheelHandler);

		var mc = chartInstance._mc;
		if (mc) {
			mc.remove('pinchstart');
			mc.remove('pinch');
			mc.remove('pinchend');
			mc.remove('panstart');
			mc.remove('pan');
			mc.remove('panend');
		}
	}
};

Chart.pluginService.register(zoomPlugin);

},{"chart.js":1,"hammerjs":1}]},{},[2]);