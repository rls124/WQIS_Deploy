//Create the functionality to determine which field was selected, then send a query to update the field.
//Additionally, must be able to delete a row from the database.

//first we have to run "document ready" because otherwise the page can't access the inputs.
$(document).ready(function () {
    $(".inputHide").on("click", function () {
        var label = $(this);
        var input = $('#' + label.attr('for'));
        input.trigger('click');
        label.attr('style', 'display: none');
        input.attr('style', 'display: in-line');
    });

    $(".comment").on("click", function () {
        var input = $(this);

        var rowNumber = (input.attr('id')).split("-")[1];
        var commentInfo = $("#commentinfo");
        commentInfo.val($("#Comments-" + rowNumber).text());
        commentInfo.attr('data-row-number', rowNumber);

    });

    $("#saveBtn").click(function () {
        var input = $("#commentinfo");

        if (!input.attr('id')) {
            return;
        }
        var rowNumber = input.attr('data-row-number');
        var sampleNumber = $('#samplenumber-' + rowNumber).val();
        var parameter = 'Comments';
        var value = input.val();

        $.ajax({
            type: "POST",
            url: "updatefield",
            datatype: 'JSON',
            data: {
                'sampleNumber': sampleNumber,
                'parameter': parameter,
                'value': value
            },
            success: function () {
                $("#Comments-" + rowNumber).text(value);
            }
        });
    });

    $(".tableInput").focusout(function () {
        var input = $(this);

        if (!input.attr('id')) {
            return;
        }

        var rowNumber = (input.attr('id')).split("-")[1];
        var sampleNumber = $('#samplenumber-' + rowNumber).val();
        var parameter = (input.attr('name')).split("-")[0];
        var value = input.val();

        $.ajax({
            type: "POST",
            url: "updatefield",
            datatype: 'JSON',
            data: {
                'sampleNumber': sampleNumber,
                'parameter': parameter,
                'value': value
            },
            success: function () {
                var label = $('label[for="' + input.attr('id') + '"');

                input.attr('style', 'display: none');
                label.attr('style', 'display: in-line; cursor: pointer');

                if (value === '') {
                    label.text('  ');
                }
				else {
                    label.text(value);
                }
            }
        });
    });

	$(".delete").click(function () {
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}
		
		$.confirm("Are you sure you want to delete this record?", function (bool) {
			if (bool) {
				var rowNumber = (input.attr('id')).split("-")[1];
				var sampleNumber = $('#samplenumber-' + rowNumber).val();
				
				//Now send ajax data to a delete script
				$.ajax({
					type: "POST",
					url: "deleteRecord",
					datatype: 'JSON',
					data: {
						'sampleNumber': sampleNumber,
						'type': sampleType
					},
					success: function () {
						$.alert("Record deleted.", function () {
							location.reload();
						});
					}
				});
			}
		});
	});
});