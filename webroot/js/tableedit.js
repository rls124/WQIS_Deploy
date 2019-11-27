//Create the functionality to determine which field was selected, then send a query to update the field.
//Additionally, must be able to delete a row from the database.

//loading graphic
$(document).ajaxStart(function () {
    $('.loadingspinner-edit').css('visibility', 'visible');
    $('.loadingspinnermain').css('visibility', 'visible');
    $('.loadingspinner-add').css('visibility', 'visible');
    $('body').css('cursor', 'wait');
}).ajaxStop(function () {
    $('.loadingspinner-edit').css('visibility', 'hidden');
    $('.loadingspinnermain').css('visibility', 'hidden');
    $('.loadingspinner-add').css('visibility', 'hidden');
    $('body').css('cursor', 'default');
});

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
		
		//fill in from hidden variable
		var elementPrefix;
		if (sampleType == "bacteria") {
			elementPrefix = "BacteriaComments";
		}
		else if (sampleType == "nutrient") {
			elementPrefix = "NutrientComments";
		}
		else if (sampleType == "pesticide") {
			elementPrefix = "PesticideComments";
		}
		else if (sampleType == "physical") {
			elementPrefix = "PhysicalComments";
		}
		
		var commentText = document.getElementById(elementPrefix + "-" + rowNumber).innerHTML;
		commentInfo.val(commentText);
		
        commentInfo.attr('data-row-number', rowNumber);
    });

    $("#saveBtn").click(function () {
        var input = $("#commentinfo");

        if (!input.attr('id')) {
            return;
        }
        var rowNumber = input.attr('data-row-number');
        var sampleNumber = $('#samplenumber-' + rowNumber).val();
		
		var parameter;
		if (sampleType == "bacteria") {
			parameter = "BacteriaComments";
		}
		else if (sampleType == "nutrient") {
			parameter = "NutrientComments";
		}
		else if (sampleType == "pesticide") {
			parameter = "PesticideComments";
		}
		else if (sampleType == "physical") {
			parameter = "PhysicalComments";
		}
		
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
		var rowDiv = this;
		
		if (!$(rowDiv).attr('id')) {
			return;
		}
		
		$.confirm("Are you sure you want to delete this record?", function (bool) {
			if (bool) {
				var rowNumber = ($(rowDiv).attr('id')).split("-")[1];
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
						//remove the row from view
						rowDiv.parentNode.parentNode.style.display = "none";
						
						//future work: build a new table, to still maintain 20 total rows and have correct black/white/black sequencing after deletions
					},
					fail: function () {
						alert("Deletion failed");
					}
				});
			}
		});
	});
});