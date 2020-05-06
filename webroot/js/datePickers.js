$(document).ready(function () {
	$(".date-picker").datepicker({
		trigger: "focus",
		format: "mm/dd/yyyy",
		todayHighlight: true,
		todayBtn: "linked"
	});

	$("#startDate").datepicker().on("changeDate", function (selected) {
		var minDate = new Date(selected.date.valueOf());
		$("#endDate").datepicker("setStartDate", minDate);
	});
	$("#endDate").datepicker().on("changeDate", function (selected) {
		var maxDate = new Date(selected.date.valueOf());
		$("#startDate").datepicker("setEndDate", maxDate);
	});
});