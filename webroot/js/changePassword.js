function ensurePasswordInput() {
	if ($("#userpw").val() !== "" && $("#passConfirm").val() !== "") {
		$("#changePass-btn").removeAttr("disabled");
	}
	else {
		$("#changePass-btn").attr("disabled", "disabled");    
	}
}

$(document).ready(function() {
	$("body").on("focus", ".mainPage", function(){
		$(this).css({'backgroundColor':"white", "border":"none"});
	});
	$("body").on("focus", ".select", function(){
		$(this).css({'backgroundColor':"white", "border":"none"});
	});
	
	$("body").on("submit", "#updateUserForm", function() {
		if (!$("#firstname").length) {
			return validatePass();
		}
		else if (!validateUserCredentials() || (($("#userpw").val() !== "" || $("#passConfirm").val() !== "") && (!validatePass())) {
			//make sure the necessary fields have input, and if there is input in the password fields, assume the user is trying to change the password
			return false;
		}
		return true;
	});
});

function validateUserCredentials() {
	removeErrorMessages();
	
	if ($("#firstname").val() === "") {
		displayError("#firstname", "#firstnameError", "Please enter your first name");
		return false;
	}
	if ($("#lastname").val() === "") {
		displayError("#lastname", "#lastnameError", "Please enter your last name");
		return false;
	}
	if ($("#organization").val() === "") {
		displayError("#organization", "#organizationError", "Please enter your organization");
		return false;
	}
	if ($("#position").val() === "") {
		displayError("#position", "#positionError", "Please enter your position");
		return false;
	}
	if ($("#securityquestion1").val() === "") {
		displayError("#securityquestion1", "#securityquestion1Error", "Please select a security question");
		return false;
	}
	if($("#securityquestion2").val() === "") {
		displayError('#securityquestion2','#securityquestion2Error', 'Please select a security question');
		return false;
	}
	if($('#securityquestion3').val() === "") {
		displayError('#securityquestion3','#securityquestion3Error', 'Please select a security question');
		return false;
	}
	return true;
}

function validatePass() {
	removeErrorMessages();
	if (!validatePassword()) { 
		if(!$("#firstname").length) {
			displayError("#userpw",'#passError', 'Please enter a valid password');
		}
		else {
			displayError("#userpw",'#passError', 'Please enter a valid password if you wish to change your password');
		}
		return false;
	}
	if ($("#userpw").val() !== $("#passConfirm").val()) {
		if(!$("#firstname").length) {
			displayError("#userpw",'#passError', 'Please ensure passwords match');
			displayError("#passConfirm",'#passConfirmError', 'Please ensure passwords match');
		}
		else {
			displayError("#userpw",'#passError', 'Please ensure passwords match if you wish to change your password');
			displayError("#passConfirm",'#passConfirmError', 'Please ensure passwords match if you wish to change your password');
		}
		
		return false;
	}
	return true;
}

function displayError(formInputID, errorMessID, errorMessage){
	$(errorMessID).text(errorMessage);
	$(formInputID).css({"backgroundColor" : "#fcf7f7", "border" : "solid #a30000"});
}

function validatePassword() {
	var password = $("#userpw").val();
	var charCounter = 0;

	if (/\d/.test(password)) {
		charCounter++;
	}
	if (/[a-z]/.test(password)) {
		charCounter++;
	}
	if (/[A-Z]/.test(password)) {
		charCounter++;
	}
	if (/[\!\@\#\$\%\^\&\*\(\)\_\-\+\=\|\{\}\[\]\:\;\"\'\<\>\,\.\?\/\~\`]/.test(password)) {
		charCounter++;
	}
	if (charCounter < 3) {
		return false;
	}
	return true;
}

function removeErrorMessages() {
	$("p").each(function(){
		$(this).text("");
	});
}

//creates a popover box upon focus of the pass input field that contains instructions for creating a password
$(function () {
	$("#userpw")
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
	$("#passConfirm")
		.popover({trigger: "focus", title: 'Password Guidelines', placement: "top", html: true,
		content: "Passwords must match"})
		.blur(function () {
			$(this).popover('hide');
		});
});