$(function () {
    $("#site").change(function () {
        getRange();
    });
    $("#categorySelect").change(function () {
        getRange();
    });
    function getRange() {
		//If both variables are not null, then we may submit an sql request.
        var siteData = document.querySelector('#site').value;
        var categoryData = $('#categorySelect').val();
        if ((siteData !== null && siteData !== 'select') && categoryData !== null) {
            $.ajax({
                type: "POST",
                url: "daterange",
                data: {
                    'site': siteData,
                    'category': categoryData
                },
                datatype: 'JSON',
                success: function (dataRaw) {
                    var data = JSON.parse(dataRaw);
                    var startDateData = data[0];
                    var endDateData = data[1];
                    $('#startdate').val(startDateData);
                    $('#enddate').val(endDateData);
                    $("#startdate").datepicker('update', startDateData);
                    $("#enddate").datepicker('update', endDateData);
                }
            });
        }
    }
});