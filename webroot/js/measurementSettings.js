//loading graphic
$(document).ajaxStart(function() {
	$(".loadingspinnermain").css("visibility", "visible");
	$("body").css("cursor", "wait");
}).ajaxStop(function() {
	$(".loadingspinnermain").css("visibility", "hidden");
	$("body").css("cursor", "default");
});

$(document).ready(function() {
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

		var measure = $("#measure-" + (input.attr("id")).split("-")[1]).text();
		var parameter = input.attr("name");
		var value = input.val();

		$.ajax({
			type: "POST",
			url: "updatefield",
			datatype: "JSON",
			data: {
				"measure": measure,
				"parameter": parameter,
				"value": value
			},
			success: function () {
				var label = $('label[for="' + input.attr("id") + '"');

				input.attr("style", "display: none");
				label.attr("style", "display: in-line; cursor: pointer");

				if (value === '') {
					label.text('  ');
				}
				else {
					label.text(value);
				}
				$(".message").html("<strong>" + parameter + "</strong> for <strong>" + measure + "</strong> has been updated to <strong>" + value + "</strong>");
				$(".message").removeClass("error");
				$(".message").removeClass("hidden");
				$(".message").removeClass("success");
				$(".message").addClass("success");
			},
			error: function() {
				alert("data unable to be updated");
				$(".message").html("<strong>" + parameter + "</strong> for <strong>" + measure + "</strong> was unable to be updated");
				$(".message").removeClass("error");
				$(".message").removeClass("hidden");
				$(".message").removeClass("success");
				$(".message").addClass("error");
			}
		});
	});
});