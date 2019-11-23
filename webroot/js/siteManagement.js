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

$(document).ready(function () {
	$('#add-sitenumber').val('');
	$('#add-longitude').val('');
	$('#add-latitude').val('');
	$('#add-sitelocation').val('');
	$('#add-sitename').val('');
	$(function () {
		$('[data-toggle="tooltip"]').tooltip();
	});
	$('.info').tooltip();
	$('#edit-tooltip').tooltip();
	$('#delete-tooltip').tooltip();
	$('#message').on('click', function () {
		$(this).addClass("hidden");
	});
    
	$("body").on('click', '.delete', function () {
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}
		$.confirm("Are you sure you want to delete this record?", function (deleteRecord) {
			if (deleteRecord) {
				var siteid = (input.attr('id')).split("-")[1];
				//Now send ajax data to a delete script.
				$.ajax({
					type: "POST",
					url: "deletesite",
					datatype: 'JSON',
					data: {
						'siteid': siteid
					},
					success: function () {
						var sitenumber = $('#td-' + siteid + '-siteNum').text();

						$('#tr-' + siteid).remove();
						$('.message').html('Site: <strong>' + sitenumber + '</strong> has been deleted');
						$('.message').removeClass('error');
						$('.message').removeClass('hidden');
						$('.message').removeClass('success');
						$('.message').addClass('success');
					},
					error: function () {
						$('.message').html('Site: <strong>' + siteid + '</strong> was unable to be deleted');
						$('.message').removeClass('success');
						$('.message').removeClass('error');
						$('.message').addClass('error');
					}
				});
			}
		});
	});
    
	$('body').on('change', '.checkbox', function () {
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}

		var siteid = (input.attr('id')).split("-")[1];
		console.log(siteid);
		var monitored;
        
		console.log(!$('#td-' + siteid + '-monitoredcheckbox').is(":checked"));
		console.log($('#td-' + siteid + '-monitoredcheckbox').is(":checked"));
        
		//set the boolean for whether a site is monitored or not.
		if (!$('#td-' + siteid + '-monitoredcheckbox').is(":checked")) {
			monitored = 0;
		}
		else if ($('#td-' + siteid + '-monitoredcheckbox').is(":checked")){
			monitored = 1;
		}
        
		//This is the quick and dirty way of doing it. Rather than changing the method in the sitelocation controller, we're just sending the same data to the database, along with the updated site monitoring.
		var longitude = parseFloat($('#td-' + siteid + '-longitude').text());
		var latitude = parseFloat($('#td-' + siteid + '-latitude').text());	
		var location = $('#td-' + siteid + '-siteLoc').text();
		var sitename = $('#td-' + siteid + '-siteName').text();
		var sitenumber = parseInt($('#td-' + siteid + '-siteNum').text());
        
		$.ajax({
			type: 'POST',
			url: 'updatesitedata',
			datatype: 'JSON',
			data: {
				'siteid' : siteid,
				'monitored': monitored,
				'longitude': longitude,
				'latitude': latitude,
				'location': location,
				'sitename': sitename
			},
			success: function () {
				$('.message').html('Site: <strong>' + sitenumber + '</strong> has been updated');
				$('.message').removeClass('error');
				$('.message').removeClass('hidden');
				$('.message').removeClass('success');
				$('.message').addClass('success');
			},
			error: function () {
				$('.message').html('Site: <strong>' + sitenumber + '</strong> could not be updated');
				$('.message').removeClass('success');
				$('.message').removeClass('hidden');
				$('.message').removeClass('error');
				$('.message').addClass('error');
			}
		});
	});
    
	$('body').on('click', '.edit', function () {
		$('#edit-header').text('Edit Site:');
		$('#edit-longitude').val('');
		$('#edit-latitude').val('');
		$('#edit-sitelocation').val('');
		$('#edit-sitename').val('');
        
		var input = $(this);
		if (!input.attr('id')) {
			return;
		}
        
		var siteid = (input.attr('id')).split("-")[1];
		$.ajax({
			type: 'POST',
			url: 'fetchsitedata',
			datatype: 'JSON',
			data: {
				'siteid': siteid
			},
			success: function (result) {
				$('#edit-header').text('Edit Site Number: ' + result['sitenumber']);
				$('#edit-sitenumber').text(result['sitenumber']);
				$('#edit-longitude').val(result['longitude']);
				$('#edit-latitude').val(result['latitude']);
				$('#edit-sitelocation').val(result['sitelocation']);
				$('#edit-sitename').val(result['sitename']);
				$('#edit-site').text(siteid);
			},
			error: function () {
				$('.message').html('Unable to obtain information for: <strong>' + siteid + '</strong>');
				$('.message').removeClass('success');
				$('.message').removeClass('hidden');
				$('.message').removeClass('error');
				$('.message').addClass('error');
			}
		});
	});
	
	$('#addSiteForm').on('submit', function (e) {
		e.preventDefault();
		var sitenumber = $('#add-sitenumber').val();
		var longitude = $('#add-longitude').val();
		var latitude = $('#add-latitude').val();
		var location = $('#add-sitelocation').val();
		var sitename = $('#add-sitename').val();

		if (!validateAddInput(sitenumber, longitude, latitude, location, sitename)) {
			return false;
		}

		if (!$.checkSiteNum(sitenumber)) {
			$.alert('This Site Number already exists, please create a new one');
			return false;
		}
        
		$.ajax({
			type: 'POST',
			url: 'addsite',
			datatype: 'JSON',
			data: {
				'Site_Number': sitenumber,
				'Monitored': 0,
				'Longitude': longitude,
				'Latitude': latitude,
				'Site_Location': location,
				'Site_Name': sitename
			},
			success: function (result) {
				var siteid = result['siteid'];
				$('#tableView').append('<tr id="tr-' + siteid + '"></tr>');
				$('#tr-' + siteid).append('<td id="td-' + siteid + '-siteNum">' + sitenumber + '</td>');
				$('#tr-' + siteid).append('<td id="td-' + siteid + '-monitoredcheckbox"><input type="checkbox" class="form-control checkbox"></td>');
				$('#tr-' + siteid).append('<td id="td-' + siteid + '-longitude">' + longitude + '</td>');
				$('#tr-' + siteid).append('<td id="td-' + siteid + '-latitude">' + latitude + '</td>');
				$('#tr-' + siteid).append('<td id="td-' + siteid + '-siteLoc">' + location + '</td>');
				$('#tr-' + siteid).append('<td id="td-' + siteid + '-siteName">' + sitename + '</td>');
				$('#tr-' + siteid).append('<td><span class="edit glyphicon glyphicon-pencil" id="edit-' + siteid + '" name="edit-' + siteid + '" data-toggle="modal" data-target="#editSiteModal" style="margin-right: 5px;"></span>' +
					'<span class="delete glyphicon glyphicon-trash" id = "delete-' + siteid + '" name = "delete-' + siteid + '" > </span>');

				$('#add-close').trigger('click');
				$('#add-sitenumber').val('');
				$('#add-longitude').val('');
				$('#add-latitude').val('');
				$('#add-sitelocation').val('');
				$('#add-sitename').val('');
				$('#add-monitored').val('');
				$('.message').html('Site: <strong>' + sitenumber + '</strong> has been added');
				$('.message').removeClass('error');
				$('.message').removeClass('hidden');
				$('.message').removeClass('success');
				$('.message').addClass('success');
			},
			error: function () {
				$('.message').html('Site: <strong>' + sitenumber + '</strong> could not be added');
				$('.message').removeClass('success');
				$('.message').removeClass('hidden');
				$('.message').removeClass('error');
				$('.message').addClass('error');
			}
		});
	});
    
	$.checkSiteNum = function (sitenumber) {
		var flag = true;
		$('tr').each(function() {
			var celltext = $(this).find('td:first').text();
			if (sitenumber === celltext) {
				flag = false;
			}
		});
		return flag;
	};
});

function validateAddInput(sitenumber, longitude, latitude, sitelocation, sitename) {
	if (sitenumber === '') {
		$.alert('Site Number is empty');
		return false;
	}

	if (!validateInput(longitude, latitude, sitelocation, sitename)) {
		return false;
	}
	return true;
}

function validateInput(longitude, latitude, sitelocation, sitename) {
	if (longitude === '') {
		$.alert('Longitude is empty');
		return false;
	}
	if (latitude === '') {
		$.alert('Latitude is empty');
		return false;
	}
	if (sitelocation === '') {
		$.alert('Site Location is empty');
		return false;
	}
	if (sitename === '') {
		$.alert('Site Name is empty');
		return false;
	}
	return true;
}

function updateButton() {
	var siteid = $('#edit-site').text();
	var sitenumber = $('#edit-sitenumber').text();
	var longitude = $('#edit-longitude').val();
	var latitude = $('#edit-latitude').val();
	var location = $('#edit-sitelocation').val();
	var sitename = $('#edit-sitename').val();
	var monitored = $('#td-' + siteid + '-monitoredcheckbox').is(":checked");
		
	if (!validateInput(longitude, latitude, location, sitename)) {
		return false;
	}
        
	$.ajax({
		type: 'POST',
		url: 'updatesitedata',
		datatype: 'JSON',
		data: {
			'siteid' : siteid,
			'monitored': monitored,
			'longitude': longitude,
			'latitude': latitude,
			'location': location,
			'sitename': sitename
		},
		success: function () {
			$('#td-' + siteid + '-longitude').text(longitude);
			$('#td-' + siteid + '-latitude').text(latitude);
			$('#td-' + siteid + '-siteLoc').text(location);
			$('#td-' + siteid + '-siteName').text(sitename);
			$('#edit-close').trigger('click');
                
			$('.message').html('Site: <strong>' + sitenumber + '</strong> has been updated');
			$('.message').removeClass('error');
			$('.message').removeClass('hidden');
			$('.message').removeClass('success');
			$('.message').addClass('success');
		},
		error: function () {
			$('.message').html('Site: <strong>' + sitenumber + '</strong> could not be updated');
			$('.message').removeClass('success');
			$('.message').removeClass('hidden');
			$('.message').removeClass('error');
			$('.message').addClass('error');
		}
	});
};