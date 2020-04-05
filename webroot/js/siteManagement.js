//loading graphic
$(document).ajaxStart(function() {
	$(".loadingspinnermain").css("visibility", "visible");
	$("body").css("cursor", "wait");
}).ajaxStop(function() {
    $(".loadingspinnermain").css("visibility", "hidden");
    $("body").css("cursor", "default");
});

$(document).ready(function () {
	if (typeof admin == "undefined") {
		admin = false;
	}
	
	$.ajax({
		type: "POST",
		url: "/WQIS/sitegroups/fetchGroups",
		datatype: "JSON",
		async: false,
		success: function (data) {
			var groups = data[0];
			var groupings = data[1];
			
			$(".groupSelect").select2({
				closeOnSelect: false,
				placeholder: "Select sites",
				width: "resolve"
			});
			
			//add groups list to all dropdowns
			for (i=0; i<groups.length; i++) {
				$(".groupSelect").append(new Option(groups[i].groupName, groups[i].groupKey, false, false));
			}
			
			//now add the groups assigned to each individual site
			for (i=0; i<groupings.length; i++) {
				var groupsForSiteBase = groupings[i].groups.split(",");
				
				//sanitize this. In theory there should never be a group ID without a name, but it is possible, especially in development
				var groupsForSite = [];
				for (j=0; j<groupsForSiteBase.length; j++) {
					for (k=0; k<groups.length; k++) {
						if (groups[k].groupKey == groupsForSiteBase[j]) {
							groupsForSite.push(parseInt(groupsForSiteBase[j]));
							break;
						}
					}
				}
				
				if (admin) {
					$("#" + groupings[i].Site_Number + "-groups").val(groupsForSite).trigger("change");
				}
				else {
					var groupsString = groupsForSite[0].toString();
					for (j=1; j<groupsForSite.length; j++) {
						groupsString = groupsString + ", " + groupsForSite[j];
					}
					document.getElementById("groups-" + groupings[i].Site_Number).innerText = groupsString;
				}
			}
		}
	});

	$("#message").on("click", function(){
		$(this).addClass("hidden");
	});

	$(function () {
		$('[data-toggle="tooltip"]').tooltip();
	});

	$(".info").tooltip();

	$(".inputHide").on("click", function () {
		var label = $(this);
		var input = $("#" + label.attr("for"));
		input.trigger("click");
		label.attr("style", "display: none");
		input.attr("style", "display: in-line");
	});
	
	$(".tableInput").focusout(function () {
		var input = $(this);
		if (!input.attr("id")) {
			return;
		}

		var rowNumber = parseInt((input.attr("id")).split("-")[1]) + 1;
		var siteNumber = document.getElementById("td-" + rowNumber + "-siteNum").innerText;
		var parameter = (input.attr("id")).split("-")[0];
		var value = input.val();

		$.ajax({
			type: "POST",
			url: "updatefield",
			datatype: "JSON",
			data: {
				"siteNumber": siteNumber,
				"parameter": parameter,
				"value": value
			},
			success: function () {
				var label = $('label[for="' + input.attr('id') + '"');

				input.attr("style", "display: none");
				label.attr("style", "display: in-line; cursor: pointer");

				if (value === '') {
					label.text('  ');
				}
				else {
					label.text(value);
				}
				$('.message').html('<strong>' + parameter + '</strong> for <strong>' + siteNumber + ' </strong> has been updated to <strong>' + value + '</strong>');
				$('.message').removeClass('error');
				$('.message').removeClass('hidden');
				$('.message').removeClass('success');
				$('.message').addClass('success');
			},
			error: function() {
				alert('data unable to be updated');
				$('.message').html('<strong>' + parameter + '</strong> for <strong>' + siteNumber + ' </strong> was unable to be updated');
				$('.message').removeClass('error');
				$('.message').removeClass('hidden');
				$('.message').removeClass('success');
				$('.message').addClass('error');
			}
		});
	});
	
	$(".groupSelect").change(function() {
		var input = $(this);
		if (!input.attr("id")) {
			return;
		}

		var siteNumber = parseInt((input.attr("id")).split("-")[0]);
		var value = input.val();
		
		$.ajax({
			type: "POST",
			url: "updatefield",
			datatype: "JSON",
			data: {
				"siteNumber": siteNumber,
				"parameter": "groups",
				"value": value
			},
			success: function () {
				var label = $('label[for="' + input.attr('id') + '"');

				input.attr("style", "display: none");
				label.attr("style", "display: in-line; cursor: pointer");

				if (value === '') {
					label.text('  ');
				}
				else {
					label.text(value);
				}
				$('.message').html('<strong>Groups</strong> for <strong>' + siteNumber + ' </strong> has been updated to <strong>' + value + '</strong>');
				$('.message').removeClass('error');
				$('.message').removeClass('hidden');
				$('.message').removeClass('success');
				$('.message').addClass('success');
			},
			error: function() {
				alert('data unable to be updated');
				$('.message').html('<strong>Groups</strong> for <strong>' + siteNumber + ' </strong> was unable to be updated');
				$('.message').removeClass('error');
				$('.message').removeClass('hidden');
				$('.message').removeClass('success');
				$('.message').addClass('error');
			}
		});
	});
});