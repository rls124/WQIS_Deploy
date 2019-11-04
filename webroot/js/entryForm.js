$(document).ready(function () {
    $('#infoGlyph').tooltip();
    $(function () {
		$(".date-picker").datepicker({
			trigger: "focus",
			format: 'mm/dd/yyyy',
			todayHighlight: true,
			todayBtn: "linked"
		});
    });

    $("#addSite").click(function () {
		var $table = $("#tableBody");
		var rowCounter = $("#totalrows");

		var rowNumber = parseInt(rowCounter.val()) + 1;
		rowCounter.val(rowNumber);
		var $clone = $("#row-0").clone();

		$clone.find('td').each(function () {
			var el = $(this).find(':first-child');
			var id = el.attr('id') || null;

			if (id) {
				var prefix = id.substr(0, (id.length - 2));
				prefix = prefix.replace('-', '_');

				el.attr('id', prefix + '-' + rowNumber);
				el.attr('name', prefix + '-' + rowNumber);
			}
		});

		$clone.find(':input').val('');

		$clone.attr('id', "row-" + rowNumber);
		$clone.find('span').removeAttr('hidden');
		$table.append($clone);
    });

    $("#addMonitoredSites").click(function () {
		$.ajax({
			type: "POST",
			url: "getmonitoredsites",
			datatype: 'JSON',
			success: function (response) {
				var data = JSON.parse(response);
				addMonitoredSites(data);
			}
		});
    });

    function addMonitoredSites(sites) {
		var $table = $("#tableBody");

		for (var i = 0; i < sites.length; i++) {
			var rowCounter = $("#totalrows");
			var rowNumber = parseInt(rowCounter.val()) + 1;
			rowCounter.val(rowNumber);
			var $clone = $("#row-0").clone();

			$clone.find('td').each(function () {
				var el = $(this).find(':first-child');

				var id = el.attr('id') || null;
				if (id) {
					var prefix = id.substr(0, (id.length - 2));

					prefix = prefix.replace('-', '_');

					el.attr('id', prefix + '-' + rowNumber);
					el.attr('name', prefix + '-' + rowNumber);
				}
			});

			$clone.find(':input').val('');

			$clone.attr('id', "row-" + rowNumber);
			$clone.find('span').removeAttr('hidden');
			$table.append($clone);

			var siteNumber = sites[i]["Site_Number"];
			$('select[id="site_location_id-' + rowNumber + '"] option[value="' + siteNumber + '"]').prop("selected", true);
		}

		if ($('#sample_number-0').val() === "") {
			deleteRow(0);
			$('#Delete-0').attr("hidden", "hidden");
		}
		$("#date").trigger("change");
    }

    $("#date").change(function () {
		//We must update all the site rows currently in play.
		var rowCounter = parseInt($("#totalrows").val()) + 1;
		var dateData = document.querySelector("#date").value;
		var sampleString;

		for (var i = 0; i < rowCounter; i++) {
			var rowNumber = i.toString();
			var siteData = document.querySelector("#site_location_id-" + rowNumber).value;
			var sampleString = "#sample_number-" + rowNumber;
			helpSampleNumber(siteData, dateData, sampleString);
		}
    });
	
    //This allows us to determine which element was selected.
    $(document).on('change', 'select.siteselect', (function () {
		//this retrieves the site number from the selected site.  Much easier than gleaning it from the label.
		var row = $(this);
		var siteData = document.querySelector("#" + row.attr('id')).value;
		var dateData = document.querySelector("#date").value;

		//Concatenate the row number to the samplenumber string so we can use that for later.
		var sampleString = "#sample_number-" + row.attr('id').split("-")[1];

		helpSampleNumber(siteData, dateData, sampleString);
    }));

    $(document).on('click', 'span.delete', (function () {
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}
		$.confirm("Are you sure you want to delete this row?", function (bool) {
			if (bool) {
				var rowNumber = parseInt((input.attr('id')).split("-")[1]);
				deleteRow(rowNumber);
			}
		});
    }));

    function deleteRow(rowNumber) {
		var deletedRow = $('#row-' + rowNumber);
		var rowCounter = $("#totalrows");
		var totalRows = parseInt(rowCounter.val());

		//this is all just a big shift operation.
		for (var current = deletedRow.next('tr'); rowNumber <= totalRows; rowNumber++, current = current.next('tr')) {
			current.find('td').each(function () {
				var el = $(this).find(':first-child');
				var id = el.attr('id') || null;
				if (id) {
					var i = id.substr(id.length - 1);
					var prefix = id.substr(0, (id.length - 2));
					prefix = prefix.replace('-', '_');
					el.attr('id', prefix + '-' + rowNumber);
					el.attr('name', prefix + '-' + rowNumber);
				}
			});
			current.attr('id', 'row-' + rowNumber)
		}

		deletedRow.remove();
		rowCounter.val(totalRows - 1);
    }
});

function helpSampleNumber(site, date, sample) {
    //provided that date is not null, and that the siteData field isn't null, we can auto populate a sample number.
    if (date !== null && date !== '' && site !== '' && site !== null) {
		//The date data should be formatted as dd/mm/yyyy.  So pulling out the '/' and reconcatenating shouldn't be too hard
		var tokenizedDate = date.split("/");

		//This should now be in the form of 020717, for instance.
		var dateDataConcat = tokenizedDate[0] + tokenizedDate[1] + tokenizedDate[2][2] + tokenizedDate[2][3];
		var finalText = Number(site + dateDataConcat);

		document.querySelector(sample).value = finalText;
    }

    //If one of them is null, change the sample number to nothing.
    else {
		document.querySelector(sample).value = '';
    }
};