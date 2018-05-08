$(document).ready(function () {
    //document.getElementById('categorySelect').addEventListener("change", changeMeasures);
    $(".date-picker").datepicker({
        trigger: "focus",
        format: 'mm/dd/yyyy',
        todayHighlight: true,
        todayBtn: "linked"
    });

    $("#startdate").datepicker().on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endDate').datepicker('setStartDate', minDate);
    });
    $("#enddate").datepicker().on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#startDate').datepicker('setEndDate', maxDate);
    });
});