$(document).ajaxStart(function () {
	$(".loadingspinnermain").css("visibility", "visible");
	$("body").css("cursor", "wait");
}).ajaxStop(function () {
	$('.loadingspinnermain').css("visibility", "hidden");
	$("body").css("cursor", "default");
});

$(document).ready(function () {
	$("#add-firstname").val("");
	$("#add-lastname").val("");
	$('#add-email').val("");
	$("#add-organization").val("");
	$("#add-position").val("");
	$("#add-username").val("");
	$("#add-userpw").val("");
	$("#add-passConfirm").val("");
	$(function () {
		$('[data-toggle="tooltip"]').tooltip();
	});
	$(".info").tooltip();
	$("#edit-tooltip").tooltip();
	$("#delete-tooltip").tooltip();
	$("#message").on("click", function () {
		$(this).addClass("hidden");
	});
	$("body").on("click", ".delete", function () {
		var input = $(this);
		if (!input.attr("id")) {
			return;
		}
		$.confirm("Are you sure you want to delete this record?", function (bool) {
			if (bool) {
				var username = (input.attr("id")).split("-")[1];
				//Now send ajax data to a delete script.
				$.ajax({
					type: "POST",
					url: "deleteUser",
					datatype: "JSON",
					data: {
						"username": username
					},
					success: function () {
						$("#tr-" + username).remove();
						$(".message").html("<strong>" + username + "</strong> has been deleted");
						$(".message").removeClass("error");
						$(".message").removeClass("hidden");
						$(".message").removeClass("success");
						$(".message").addClass("success");
					},
					error: function () {
						$(".message").html("<strong>" + username + "</strong> was unable to be deleted");
						$(".message").removeClass("success");
						$(".message").removeClass("error");
						$(".message").addClass("error");
						$.alert('Unable to delete user');
					}
				});
			}
		});
	});
	$("body").on("click", ".edit", function () {
		$("#edit-header").text('Edit User:');
		$("#edit-firstname").val("");
		$("#edit-lastname").val("");
		$("#edit-email").val("");
		$("#edit-organization").val("");
		$("#edit-position").val("");
		$("#edit-userpw").val("");
		$("#edit-passConfirm").val("");
		var input = $(this);
		if (!input.attr("id")) {
			return;
		}
		var username = (input.attr("id")).split("-")[1];
		$.ajax({
			type: "POST",
			url: "fetchuserdata",
			datatype: "JSON",
			data: {
				"username": username
			},
			success: function (result) {
				var admin = result['admin'];
				if (admin === true) {
					$("#edit-admin").prop("checked", true);
				}
				else {
					$("#edit-admin").prop("checked", false);
				}

				$("#edit-header").text("Edit User: " + result["username"]);
				$('#edit-username').text(result["username"]);
				$("#edit-firstname").val(result["firstname"]);
				$("#edit-lastname").val(result["lastname"]);
				$("#edit-organization").val(result["organization"]);
				$("#edit-position").val(result["position"]);
				$("#edit-email").val(result["email"]);
			},
			error: function () {
				$(".message").html("Unable to obtain information for: <strong>" + username + "</strong>");
				$(".message").removeClass("success");
				$(".message").removeClass("hidden");
				$(".message").removeClass("error");
				$(".message").addClass("error");
			}
		});
	});
	$("body").on("submit", "#updateUserForm", function (e) {
		e.preventDefault();
		var username = $("#edit-username").text();
		var firstname = $("#edit-firstname").val();
		var lastname = $("#edit-lastname").val();
		var email = $("#edit-email").val();
		var organization = $("#edit-organization").val();
		var position = $("#edit-position").val();
		var userpw = $("#edit-userpw").val();
		var passconfirm = $("#edit-passConfirm").val();
		var admin = 0;
		var adminVal = "general";
		if ($("#edit-admin").is(":checked")) {
			admin = 1;
			adminVal = "admin";
		}

		if (!validateInput(username, firstname, lastname, email, organization, position) || ((userpw !== "" || passconfirm !== "") && !validatePassword(userpw, passconfirm))) { //validation
			return false;
		}

		$.ajax({
			type: "POST",
			url: "updateuserdata",
			datatype: "JSON",
			data: {
				"username": username,
				"firstname": firstname,
				"lastname": lastname,
				"email": email,
				"organization": organization,
				"position": position,
				"userpw": userpw,
				"passconfirm": passconfirm,
				"admin": admin
			},
			success: function () {
				$("#td-" + username + "-admin").text(adminVal);
				$("#td-" + username + "-name").text(firstname + ' ' + lastname);
				$("#td-" + username + "-email").text(email);
				$("#td-" + username + "-org").text(organization);
				$("#td-" + username + "-pos").text(position);
				$("#edit-close").trigger("click");
				$("#edit-firstname").val("");
				$("#edit-lastname").val("");
				$("#edit-email").val("");
				$("#edit-organization").val("");
				$("#edit-position").val("");
				$("#edit-userpw").val("");
				$("#edit-passConfirm").val("");
				$(".message").html("<strong>" + username + "</strong> has been updated");
				$(".message").removeClass("error");
				$(".message").removeClass("hidden");
				$(".message").removeClass("success");
				$(".message").addClass("success");
			},
			error: function () {
				$(".message").html("<strong>" + username + "</strong> could not be updated");
				$(".message").removeClass("success");
				$(".message").removeClass("hidden");
				$(".message").removeClass("error");
				$(".message").addClass("error");
			}
		});
	});
	$("#addUserForm").on("submit", function (e) {
		e.preventDefault();
		var username = $("#add-username").val();
		var firstname = $("#add-firstname").val();
		var lastname = $("#add-lastname").val();
		var email = $("#add-email").val();
		var organization = $("#add-organization").val();
		var position = $("#add-position").val();
		var userpw = $("#add-userpw").val();
		var passconfirm = $("#add-passConfirm").val();
		var admin = 0;
		var adminVal = "general";
		if ($("#add-admin").is(":checked")) {
			admin = 1;
			adminVal = 'admin';
		}

		if (!validateInput(username, firstname, lastname, email, organization, position) || ((userpw !== "" || passconfirm !== "") && !validatePassword(userpw, passconfirm))) { //validation
			return false;
		}

		$.ajax({
			type: "POST",
			url: "adduser",
			datatype: "JSON",
			data: {
				"username": username,
				"firstname": firstname,
				"lastname": lastname,
				"email": email,
				"organization": organization,
				"position": position,
				"userpw": userpw,
				"passconfirm": passconfirm,
				"admin": admin
			},
			success: function () {
				$('#userTable').append('<tr id="tr-' + username + '"></tr>');
				$("#tr-" + username).append("<td>" + username + "</td>");
				$("#tr-" + username).append('<td id="td-' + username + '-admin">' + adminVal + "</td>");
				$("#tr-" + username).append('<td id="td-' + username + '-name">' + firstname + " " + lastname + "</td>");
				$("#tr-" + username).append('<td id="td-' + username + '-email">' + email + "</td>");
				$("#tr-" + username).append('<td id="td-' + username + '-org">' + organization + "</td>");
				$("#tr-" + username).append('<td id="td-' + username + '-pos">' + position + "</td>");
				$("#tr-" + username).append('<td><span class="edit glyphicon glyphicon-pencil" id="edit-' + username + '" name="edit-' + username + '" data-toggle="modal" data-target="#editUserModal" style="margin-right: 5px;"></span><span class="delete glyphicon glyphicon-trash" id = "delete-' + username + '" name = "delete-' + username + '" > </span>');

				$("#add-close").trigger("click");
				$("#add-firstname").val("");
				$("#add-lastname").val("");
				$('#add-email').val("");
				$("#add-organization").val("");
				$("#add-position").val("");
				$("#add-username").val("");
				$("#add-userpw").val("");
				$("#add-passConfirm").val("");
				$(".message").html("<strong>" + username + '</strong> has been added');
				$(".message").removeClass("error");
				$(".message").removeClass("hidden");
				$(".message").removeClass("success");
				$(".message").addClass("success");
			},
			error: function () {
				$(".message").html("<strong>" + username + "</strong> could not be added");
				$(".message").removeClass("success");
				$(".message").removeClass("hidden");
				$(".message").removeClass("error");
				$(".message").addClass("error");
			}
		});
	});
});
//Create error messages
function validateInput(username, firstname, lastname, email, organization, position) {
	if (username === "") {
		$.alert("Username is empty");
		return false;
	}
	if (firstname === "") {
		$.alert("First Name is empty");
		return false;
	}
	if (lastname === "") {
		$.alert("Last Name is empty");
		return false;
	}
	if (email === "") {
		$.alert("Email is empty");
		return false;
	}
	if (organization === "") {
		$.alert("Organization is empty");
		return false;
	}
	if (position === "") {
		$.alert("Position is empty");
		return false;
	}

	//email test
	if (!(/^([a-zA-Z0-9_\.\-\!\#\$\%\&\'\*\+\/\=\?\^\`\{\|\}\~\;])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email))) {
		$.alert("Email is not in correct format");
		return false;
	}
	//username test
	if (!(/^[a-zA-Z0-9]+$/.test(username))) {
		$.alert("Username is not in correct format");
		return false;
	}
	return true;
}

function validatePassword(userpw, passConfirm) {
	var charCounter = 0;
	if (userpw === "") {
		$.alert("Password is empty");
		return false;
	}
	if (passConfirm === "") {
		$.alert("Password (again) is empty");
		return false;
	}
	if (/\d/.test(userpw)) {
		charCounter++;
	}
	if (/[a-z]/.test(userpw)) {
		charCounter++;
	}
	if (/[A-Z]/.test(userpw)) {
		charCounter++;
	}
	if (/[\!\@\#\$\%\^\&\*\(\)\_\-\+\=\|\{\}\[\]\:\;\"\'\<\>\,\.\?\/\~\`]/.test(userpw)) {
		charCounter++;
	}
	if (charCounter < 3) {
		$.alert("Password needs more unique characters");
		return false;
	}
	if (userpw !== passConfirm) {
		$.alert("Passwords must match");
		return false;
	}
	return true;
}

//creates a popover box upon focus of the email input field that contains instructions for entering in email addresses
$(function () {
	$("#add-email")
		.popover({trigger: "focus", title: "Email Guidelines", placement: "top", html: true,
			content: "Emails must be in the following format:<br>'name@place.domain'"})
		.blur(function () {
			$(this).popover("hide");
		});
	$("#edit-email")
		.popover({trigger: "focus", title: "Email Guidelines", placement: "top", html: true,
			content: "Emails must be in the following format:<br>'name@place.domain'"})
		.blur(function () {
			$(this).popover("hide");
		});
});

//creates a popover box upon focus of the username input field that contains instructions for creating usernames
$(function () {
	$("#add-username")
		.popover({trigger: "focus", title: "Username Guidelines", placement: "top", html: true,
			content: "Usernames may only contain letters and numbers"})
		.blur(function () {
			$(this).popover("hide");
		});
});

//creates a popover box upon focus of the pass input field that contains instructions for creating a password
$(function () {
	$("#add-userpw")
		.popover({trigger: "focus", title: 'Password Guidelines', placement: "top", html: true,
			content: "Passwords must contain characters from three of the four following categories:<br>" +
				"<br>*English upper case letters (A-Z)" +
				"<br>*English lower case letters (a-z)" +
				"<br>*Base 10 digits (0-9)" +
				"<br>*Nonalphanumeric characters(e.g., !,$,#,%)"})
		.blur(function () {
			$(this).popover('hide');
		});
	$("#edit-userpw")
		.popover({trigger: "focus", title: 'Password Guidelines', placement: "top", html: true,
			content: "Passwords must contain characters from three of the four following categories:<br>" +
				"<br>*English upper case letters (A-Z)" +
				"<br>*English lower case letters (a-z)" +
				"<br>*Base 10 digits (0-9)" +
				"<br>*Nonalphanumeric characters(e.g., !,$,#,%)"})
		.blur(function () {
			$(this).popover('hide');
		});
});

$(function () {
	$("#add-passConfirm")
		.popover({trigger: "focus", title: "Password Guidelines", placement: "top", html: true,
			content: "Passwords must match"})
		.blur(function () {
			$(this).popover("hide");
		});
	$("#edit-passConfirm")
		.popover({trigger: "focus", title: "Password Guidelines", placement: "top", html: true,
			content: "Passwords must match"})
		.blur(function () {
			$(this).popover('hide');
		});
});