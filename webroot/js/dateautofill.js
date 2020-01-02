$(function () {
    $("#sites").change(function () {
        getRange();
    });
    $("#categorySelect").change(function () {
        getRange();
    });
    function getRange() {
		//if both variables are not null, then we may submit an sql request
        var siteData = document.querySelector('#sites').value;
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
                success: function (data) {
                    var startDateData = data[0];
                    var endDateData = data[1];
                    $('#startDate').val(startDateData);
                    $('#endDate').val(endDateData);
                    $("#startDate").datepicker('update', startDateData);
                    $("#endDate").datepicker('update', endDateData);
                }
            });
        }
    }
});