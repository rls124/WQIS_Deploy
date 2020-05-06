//loading graphic
$(document).ajaxStart(function() {
	$(".loadingspinnermain").css("visibility", "visible");
	$("body").css("cursor", "wait");
}).ajaxStop(function() {
	$(".loadingspinnermain").css("visibility", "hidden");
	$("body").css("cursor", "default");
});

$(document).ready(function () {
	$("#add-groupname").val("");
	$("#add-groupdescription").val("");
	$("#add-sites").val("");
	$(function () {
		$('[data-toggle="tooltip"]').tooltip();
	});
	$(".info").tooltip();
	$("#edit-tooltip").tooltip();
	$("#delete-tooltip").tooltip();
	$("#message").on("click", function () {
		$(this).addClass("hidden");
	});

	$("#edit-sites").select2({
		closeOnSelect: false,
		placeholder: "Select sites",
		width: "resolve"
	});

	$("#add-sites").select2({
		closeOnSelect: false,
		placeholder: "Select sites",
		width: "resolve"
	});
	
	$("body").on("click", ".delete", function () {
		var input = $(this);
		if (!input.attr("id")) {
			return;
		}
		var deleteRecord = window.confirm("Are you sure you want to delete this record?");
		if (deleteRecord) {
			var groupkey = (input.attr("id")).split("-")[1];
			var groupname = $("#td-" + groupkey + "-groupKey").text();
			$.ajax({
				type: "POST",
				url: "deletegroup",
				datatype: "JSON",
				data: {
					"groupkey": groupkey
				},
				success: function () {
					$("#tr-" + groupkey).remove();
					$(".message").html("Group: <strong>" + groupname + "</strong> has been deleted");
					$(".message").removeClass("error");
					$(".message").removeClass("hidden");
					$(".message").removeClass("success");
					$(".message").addClass("success");
				},
				error: function () {
					$(".message").html("Group: <strong>" + groupname + "</strong> was unable to be deleted");
					$(".message").removeClass("success");
					$(".message").removeClass("error");
					$(".message").addClass("error");
				}
			});
		}
	});
	
	$("body").on("click", ".edit", function () {
		$("#edit-header").text("Edit Group:");
		$("#edit-groupname").val("");
		$("#edit-groupdescription").val("");
		$("#edit-sites").val("");
		
		var input = $(this);
		if (!input.attr("id")) {
			return;
		}
		
		var groupkey = (input.attr("id")).split("-")[1];
		$.ajax({
			type: "POST",
			url: "fetchgroupdata",
			datatype: "JSON",
			data: {
				"groupkey": groupkey
			},
			success: function (result) {
				$("#edit-header").text("Edit Group: " + result["groupname"]);
				$("#edit-groupname").val(result["groupname"]);
				$("#edit-groupdescription").val(result["groupdescription"]);
				$("#edit-sites").val(result["sites"]).change();
				$("#edit-groupkey").val(groupkey);
			},
			error: function () {
				$(".message").html("Unable to obtain information for: <strong>" + groupkey + "</strong>");
				$(".message").removeClass("success");
				$(".message").removeClass("hidden");
				$(".message").removeClass("error");
				$(".message").addClass("error");
			}
		});
	});
	
	$("#addGroupForm").on("submit", function (e) {
		e.preventDefault();
		var groupname = $("#add-groupname").val();
		var groupdescription = $("#add-groupdescription").val();
		var sites = $("#add-sites").val();
		var sitesString = $("#add-sites").val().join(" ");

		if (!validateInput(groupname, groupdescription)) {
			return false;
		}

		if (!$.checkGroupName(groupname)) {
			$.alert("A group with this name already exists, please create a new one");
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: "addgroup",
			datatype: "JSON",
			data: {
				"groupname": groupname,
				"groupdescription": groupdescription,
				"sites": sites
			},
			success: function (result) {
				var groupkey = result["groupkey"];
				$('#tableView').append('<tr id="tr-' + groupkey + '"></tr>');
				$("#tr-" + groupkey).append('<td id="td-' + groupkey + '-groupKey">' + groupname + '</td>');
				$("#tr-" + groupkey).append('<td id="td-' + groupkey + '-groupDescription">' + groupdescription + '</td>');
				$("#tr-" + groupkey).append('<td id="td-' + groupkey + '-sites">' + sitesString + '</td>');
				$("#tr-" + groupkey).append('<td><a id="edit-tooltip" data-toggle="tooltip" title="Edit Group"><span class="edit glyphicon glyphicon-pencil" id="edit-' + groupkey + '" name="edit-' + groupkey + '" data-toggle="modal" data-target="#editGroupModal" style="margin-right: 5px;"></span></a><a id="delete-tooltip" data-toggle="tooltip" title="Delete Group"><span class="delete glyphicon glyphicon-trash" id="delete-' + groupkey + '" name="delete-' + groupkey + '"></span></a></td>');
				$('#add-close').trigger("click");
				$("#add-groupname").val("");
				$('#add-groupdescription').val("");
				$('#add-sites').val("");
				$(".message").html('Group: <strong>' + groupname + '</strong> has been added');
				$(".message").removeClass("error");
				$(".message").removeClass("hidden");
				$(".message").removeClass("success");
				$(".message").addClass("success");
			},
			error: function () {
				$(".message").html("Group: <strong>" + groupname + "</strong> could not be added");
				$(".message").removeClass("success");
				$(".message").removeClass("hidden");
				$(".message").removeClass("error");
				$(".message").addClass("error");
			}
		});
	});
	
	$.checkGroupName = function(groupname) {
		var flag = true;
		$("tr").each(function() {
			var celltext = $(this).find("td:first").text();
			if (groupname === celltext) {
				flag = false;
			}
		});
		return flag;
	};
});

function validateInput(groupname, groupdescription) {
	if (groupname === "") {
		$.alert("Group Name is empty");
		return false;
	}

	if (groupdescription === "") {
		$.alert("Group Description is empty");
		return false;
	}

	return true;
}

function updateButton() {
	var groupname = $('#edit-groupname').val();
	var groupdescription = $('#edit-groupdescription').val();
	var sites = $("#edit-sites").val();
	var sitesString = $("#edit-sites").val().join(" ");
	var groupkey = $("#edit-groupkey").val();
		
	if (!validateInput(groupname, groupdescription)) {
		return false;
	}
		
	$.ajax({
		type: "POST",
		url: "updategroupdata",
		datatype: "JSON",
		data: {
			"groupname": groupname,
			"groupdescription": groupdescription,
			"sites": sites,
			"groupkey": groupkey
		},
		success: function () {
			$("#td-" + groupkey + "-groupKey").text(groupname);
			$("#td-" + groupkey + "-groupDescription").text(groupdescription);
			$("#td-" + groupkey + "-sites").text(sitesString);
			$("#edit-close").trigger("click");
			$(".message").html("Group: <strong>" + groupname + "</strong> has been updated");
			$(".message").removeClass("error");
			$(".message").removeClass("hidden");
			$(".message").removeClass("success");
			$(".message").addClass("success");
		},
		error: function () {
			$(".message").html("Group: <strong>" + groupname + "</strong> could not be updated");
			$(".message").removeClass("success");
			$(".message").removeClass("hidden");
			$(".message").removeClass("error");
			$(".message").addClass("error");
		}
	});
};