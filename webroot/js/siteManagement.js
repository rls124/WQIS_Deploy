//loading graphic
$(document).ajaxStart(function() {
	document.getElementById("loadingSpinner").style.visibility = "visible";
	$("body").css("cursor", "wait");
}).ajaxStop(function() {
	document.getElementById("loadingSpinner").style.visibility = "hidden";
	$("body").css("cursor", "default");
});

$(document).ready(function () {
	if (typeof admin == "undefined") {
		admin = false;
	}
	
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
		if (groupings[i].groups != null) {
			var groupsForSiteBase = groupings[i].groups.split(",");
			
			if (admin) {
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
				
				$("#" + groupings[i].Site_Number + "-groups").val(groupsForSite).trigger("change");
			}
			else {
				var groupNames = [];
				for (j=0; j<groupsForSiteBase.length; j++) {
					for (k=0; k<groups.length; k++) {
						if (groups[k].groupKey == groupsForSiteBase[j]) {
							groupNames.push(groups[k].groupName);
						}
					}
				}
				
				document.getElementById("groups-" + groupings[i].Site_Number).innerText = groupNames.join(", ");
			}
		}
	}
	
	$("body").on("click", ".delete", function () {
		var input = $(this);
		if (!input.attr("id")) {
			return;
		}
		var deleteRecord = window.confirm("Are you sure you want to delete this record?")
		if (deleteRecord) {
			var siteid = (input.attr("id")).split("-")[1];
			//now send ajax data to a delete script
			$.ajax({
				type: "POST",
				url: "deletesite",
				datatype: "JSON",
				data: {
					"siteid": siteid
				},
				success: function () {
					var sitenumber = $("#td-" + siteid + "-siteNum").text();

					$("#tr-" + siteid).remove();
					$(".message").html("Site: <strong>" + sitenumber + "</strong> has been deleted");
					$(".message").removeClass("error");
					$(".message").removeClass("hidden");
					$(".message").removeClass("success");
					$(".message").addClass("success");
				},
				error: function () {
					$(".message").html("Site: <strong>" + siteid + "</strong> was unable to be deleted");
					$(".message").removeClass("success");
					$(".message").removeClass("error");
					$(".message").addClass("error");
				}
			});
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
				var label = $('label[for="' + input.attr("id") + '"');

				input.attr("style", "display: none");
				label.attr("style", "display: in-line; cursor: pointer");

				if (value === "") {
					label.text("  ");
				}
				else {
					label.text(value);
				}
				$(".message").html("<strong>" + parameter + "</strong> for <strong>" + siteNumber + "</strong> has been updated to <strong>" + value + "</strong>");
				$(".message").removeClass("error");
				$(".message").removeClass("hidden");
				$(".message").removeClass("success");
				$(".message").addClass("success");
			},
			error: function() {
				alert("Data unable to be updated");
				$(".message").html("<strong>" + parameter + "</strong> for <strong>" + siteNumber + "</strong> was unable to be updated");
				$(".message").removeClass("error");
				$(".message").removeClass("hidden");
				$(".message").removeClass("success");
				$(".message").addClass("error");
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
				var label = $('label[for="' + input.attr("id") + '"');

				input.attr("style", "display: none");
				label.attr("style", "display: in-line; cursor: pointer");

				if (value === "") {
					label.text("  ");
				}
				else {
					label.text(value);
				}
				$(".message").html("<strong>Groups</strong> for <strong>" + siteNumber + "</strong> has been updated to <strong>" + value + "</strong>");
				$(".message").removeClass("error");
				$(".message").removeClass("hidden");
				$(".message").removeClass("success");
				$(".message").addClass("success");
			},
			error: function() {
				alert("Data unable to be updated");
				$(".message").html("<strong>Groups</strong> for <strong>" + siteNumber + "</strong> was unable to be updated");
				$(".message").removeClass("error");
				$(".message").removeClass("hidden");
				$(".message").removeClass("success");
				$(".message").addClass("error");
			}
		});
	});
	
	$("#addSiteForm").on("submit", function (e) {
		e.preventDefault();
		var sitenumber = $("#add-sitenumber").val();
		var longitude = $("#add-longitude").val();
		var latitude = $("#add-latitude").val();
		var location = $("#add-sitelocation").val();
		var sitename = $("#add-sitename").val();

		if (!validateAddInput(sitenumber, longitude, latitude, location, sitename)) {
			return false;
		}

		if (!$.checkSiteNum(sitenumber)) {
			$.alert("This Site Number already exists, please create a new one");
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: "addsite",
			datatype: "JSON",
			data: {
				"Site_Number": sitenumber,
				"Longitude": longitude,
				"Latitude": latitude,
				"Site_Location": location,
				"Site_Name": sitename
			},
			success: function (result) {
				var siteid = result["siteid"];
				$("#tableView").append('<tr id="tr-' + siteid + '"></tr>');
				$("#tr-" + siteid).append('<td id="td-' + siteid + '-siteNum">' + sitenumber + "</td>");
				$("#tr-" + siteid).append('<td id="td-' + siteid + '-monitoredcheckbox"><input type="checkbox" class="form-control checkbox"></td>');
				$("#tr-" + siteid).append('<td id="td-' + siteid + '-longitude">' + longitude + "</td>");
				$("#tr-" + siteid).append('<td id="td-' + siteid + '-latitude">' + latitude + "</td>");
				$("#tr-" + siteid).append('<td id="td-' + siteid + '-siteLoc">' + location + "</td>");
				$("#tr-" + siteid).append('<td id="td-' + siteid + '-siteName">' + sitename + "</td>");
				$("#tr-" + siteid).append('<td><span class="edit glyphicon glyphicon-pencil" id="edit-' + siteid + '" name="edit-' + siteid + '" data-toggle="modal" data-target="#editSiteModal" style="margin-right: 5px;"></span><span class="delete glyphicon glyphicon-trash" id = "delete-' + siteid + '" name = "delete-' + siteid + '" > </span>');

				$("#add-close").trigger("click");
				$("#add-sitenumber").val("");
				$("#add-longitude").val("");
				$("#add-latitude").val("");
				$("#add-sitelocation").val("");
				$("#add-sitename").val("");
				$("#add-monitored").val("");
				$(".message").html("Site: <strong>" + sitenumber + "</strong> has been added");
				$(".message").removeClass("error");
				$(".message").removeClass("hidden");
				$(".message").removeClass("success");
				$(".message").addClass("success");
			},
			error: function () {
				$(".message").html("Site: <strong>" + sitenumber + "</strong> could not be added");
				$(".message").removeClass("success");
				$(".message").removeClass("hidden");
				$(".message").removeClass("error");
				$(".message").addClass("error");
			}
		});
	});
	
	$.checkSiteNum = function (sitenumber) {
		var flag = true;
		$("tr").each(function() {
			var celltext = $(this).find("td:first").text();
			if (sitenumber === celltext) {
				flag = false;
			}
		});
		return flag;
	};
	
	function validateAddInput(sitenumber, longitude, latitude, sitelocation, sitename) {
		if (sitenumber === "") {
			$.alert("Site Number is empty");
			return false;
		}

		if (!validateInput(longitude, latitude, sitelocation, sitename)) {
			return false;
		}
		return true;
	}
	
	function validateInput(longitude, latitude, sitelocation, sitename) {
		if (longitude === "") {
			$.alert("Longitude is empty");
			return false;
		}
		if (latitude === "") {
			$.alert("Latitude is empty");
			return false;
		}
		if (sitelocation === "") {
			$.alert("Site Location is empty");
			return false;
		}
		if (sitename === "") {
			$.alert("Site Name is empty");
			return false;
		}
		return true;
	}
});