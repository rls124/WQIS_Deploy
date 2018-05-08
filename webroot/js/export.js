$(document).ready(function () {

	$("#exportBtn").click(function () {
		var type = $('select[name=typeInput]').val();
		var startDate = $('#startdate').val();
		var endDate = $('#enddate').val();
		if (Date.parse(startDate) > Date.parse(endDate)) {
			$.alert('Start date cannot be greater than end date');
			return;
		}
		var sites = [];
		var siteDoms = $('input[type="checkbox"][name="sites\\[\\]"]:checked');
		if ($('input[type="checkbox"][id="sites-stJoe"]').prop("checked")) {
			$.merge(siteDoms, $('input[type="checkbox"][id^="sites-1"]'));
		}
		if ($('input[type="checkbox"][id="sites-stMary"]').prop("checked")) {
			$.merge(siteDoms, $('input[type="checkbox"][id^="sites-2"]'));
		}
		if ($('input[type="checkbox"][id="sites-upperMaumee"]').prop("checked")) {
			$.merge(siteDoms, $('input[type="checkbox"][id^="sites-3"]'));
		}
		if ($('input[type="checkbox"][id="sites-auglaize"]').prop("checked")) {
			$.merge(siteDoms, $('input[type="checkbox"][id^="sites-4"]'));
		}

		/*
		 sites-stJoe
		 sites-stMary
		 sites-upperMaumee
		 sites-auglaize
		 */
		siteDoms = $.uniqueSort(siteDoms);

		siteDoms.each(function () {
			sites.push($(this).val());
		});
		var measures = [];
		var measureDoms = $('input[type="checkbox"][name="measure\\[\\]"]:checked');

		measureDoms.each(function () {
			measures.push($(this).val());
		});
		$.ajax({
			type: "POST",
			url: "exportData",
			datatype: 'JSON',
			data: {
				'type': type,
				'startdate': startDate,
				'enddate': endDate,
				'sites': sites,
				'measures': measures
			},
			success: function (response) {
				downloadFile(response, type);
			}
		});
	});
	function downloadFile(fileData, type) {
		if (fileData.length < 1) {
			return;
		}
		var csvContent = "data:text/csv;charset=utf-8,";
		var fields = Object.keys(fileData[0]);
		for (var i = 0; i < fileData.length; i++) {
			fileData[i]['Date'] = fileData[i]['Date'].substring(0, 10);
		}

		//If ID field exists, remove it
		if (fields[0] === "ID") {
			fields = fields.splice(1, fields.length);
		}
		//Make null values not have text
		var replacer = function (key, value) {
			return value === null ? '' : value;
		};

		var csv = fileData.map(function (row) {
			return fields.map(function (fieldName) {
				return JSON.stringify(row[fieldName], replacer);
			}).join(',');
		});
		fields[fields.indexOf('site_location_id')] = 'Site Number';
		// add header column
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
	$(document).on('change', 'input[type="checkbox"]', (function () {
		var clicked = $(this);
		var clickedType = clicked.attr('id').split("-")[0];

		var allCheck = $('#' + clickedType + '-all');
		var checkedList = $('input[type="checkbox"][name="' + clickedType + '\\[\\]"]:checked');
		if (clickedType === "sites") {
			$.merge(checkedList, $('input[type="checkbox"][name="rivers\\[\\]"]:checked'));
		}
		if (clicked.is(':checked')) {
			if (clicked.attr('id') === clickedType + '-all') {

				checkedList.each(function () {
					$(this).prop('checked', false);
				});
				allCheck.prop('checked', true);
			} else if (allCheck.is(':checked')) {
				allCheck.prop('checked', false);

			}

		} else {

			if (checkedList.length === 0) {
				allCheck.prop('checked', true);
			}
		}

	}));
	$("select[name=typeInput]").change(function () {
		var newType = $('select[name=typeInput]').val();
		var allMeasures = "<label for='measure-all'><input checked type='checkbox' name='measure[]' value='all' id='measure-all'>All Measures</label>";
		var measureSelect = $('#measurementSelect');
		measureSelect.empty();
		var measures = [];
		switch (newType) {
			case 'bacteria':
				measures['ecoli'] = 'E. Coli (CFU/100 mil)';
				break;
			case 'nutrient':
				measures['nitrateNitrite'] = 'Nitrate/Nitrite (mg/L)';
				measures['phosphorus'] = 'Total Phosphorus (mg/L)';
				measures['drp'] = 'Total Phosphorus (mg/L)';
				break;
			case 'pesticide':

				measures['alachlor'] = 'Alachlor (µg/L)';
				measures['atrazine'] = 'Atrazine (µg/L)';
				measures['metolachlor'] = 'Metolachlor (µg/L)';
				break;
			case 'wqm':

				measures['conductivity'] = 'Conductivity (mS/cm)';
				measures['do'] = 'Dissolved Oxygen (mg/L)';
				measures['ph'] = 'pH';
				measures['water_temp'] = 'Water Temperature (°C)';
				measures['tds'] = 'Total Dissolved Solids (g/L)';
				measures['turbidity'] = 'Turbidity (NTU)';
				break;
			default:

				break;
		}

		measureSelect.append(allMeasures);
		for (var key in measures) {
			measureSelect.append("<label for='measure-" + key + "'><input type='checkbox' name='measure[]' value='"
				+ key + "' id='measure-" + key + "'>" + measures[key] + "</label>");
		}

	});
	$("#startdate, #enddate").change(function () {
		var btn = $("#exportBtn");

		if ($("#startdate").val() === "" || $("#enddate").val() === "") {
			btn.prop("disabled", true);
		} else {
			btn.prop("disabled", false);
		}
	});


});