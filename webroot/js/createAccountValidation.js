/*
 * Used to ensure that the user provides input for every form.
 * If they do, the button is enable, if not, the button is disabled.
 */
function ensureInput() {
    if($('#firstname').val() !== '' && $('#lastname').val() !== '' && $('#email').val() !== '' && 
            $('#organization').val() !== '' && $('#position').val() !== '' && $('#username').val() !== '' &&
            $('#userpw').val() !== '' && $('#passConfirm').val() !== '') {
        
        $('#registerMeBtn').removeAttr('disabled');
    } else {
        $('#registerMeBtn').attr('disabled', 'disabled');
    }
}

function ensureSecurityInput() {
    if($('#securityquestion1').val() !== '' && $('#securityquestion2').val() !== '' && 
            $('#securityquestion3').val() !== '' && $('#securityanswer1').val() !== '' &&
            $('#securityanswer2').val() !== '' && $('#securityanswer3').val() !== ''){
        
        $('#registerModal-btn').removeAttr('disabled');
    } else {
        $('#registerModal-btn').attr('disabled', 'disabled');
    }
}

/*
 * Validates the user input for correct email, username, and password formation.
 */
function validate() {
    removeErrorMessages();
    if (!(/^([a-zA-Z0-9_\.\-\!\#\$\%\&\'\*\+\/\=\?\^\`\{\|\}\~\;])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test($('#email').val()))) {
        displayError('#email', '#emailError', 'Please enter a valid email address');
        return false;
    }
    if (!(/^[a-zA-Z0-9]+$/.test($('#username').val()))) {
        displayError('#username', '#usernameError', 'Please enter a valid username');
        return false;
    }
    if (!validatePassword()) {
        displayError('#userpw','#passError', 'Please enter a valid password');
        return false;
    }
    if ($('#userpw').val() !== $('#passConfirm').val()) {
        displayError('#userpw', '#passError', 'Passwords must match');
        displayError('#passConfirm', '#passConfirmError', 'Passwords must match');
        return false;
    }
    return true;
}

function validateSecurity() {
    removeErrorMessages();
    if($('#securityquestion1').val() === '') {
        displayError('#securityquestion1', '#securityquestion1Error', 'No security question was selected');
        return false;
    } 
    if($('#securityquestion2').val() === '') {
        displayError('#securityquestion2', '#securityquestion2Error', 'No security question was selected');
        return false;
    } 
    if($('#securityquestion3').val() === '') {
        displayError('#securityquestion3', '#securityquestion3Error', 'No security question was selected');
        return false;
    }
    if($('#securityanswer1').val() === '') {
        displayError('#securityanswer1', '#securityanswer1Error', 'No answer was provided');
        return false;
    }
    if($('#securityanswer2').val() === '') {
        displayError('#securityanswer2', '#securityanswer2Error', 'No answer was provided');
        return false;
    }
    if($('#securityanswer3').val() === '') {
        displayError('#securityanswer3', '#securityanswer3Error', 'No answer was provided');
        return false;
    }
    return true;
}

function displayError(formInputID, errorMessID, errorMessage){
    $(errorMessID).text(errorMessage);
    $(formInputID).css({'backgroundColor' : '#fcf7f7', 'border' : 'solid #a30000'});
}

/*
 * Keeps track of every type of character the user inputs for their password,
 * if the total of these types of charcters is less than three, returns false
 * (signaling that the user needs to input more types of characters), otherwise,
 * returns true (signaling that the user has input the correct amount of types
 * of characters)
 */
function validatePassword() {
    var password = $('#userpw').val();
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

/*
 * Removes the error messages underneath their appropriate forms.
 */
function removeErrorMessages() {
    $('p').each(function(){
        $(this).text('');
    });
}

/*
 * Resets the background color on a particular form when that form is put into focus.
 */
$(document).ready(function(){
    $('#message').on('click', function () {
        $(this).addClass("hidden");
    });
    
    $('.mainPage').on('focus', function(){
        $(this).css({'backgroundColor':'white', 'border':'none'});
    });
    
    $('#registerMeBtn').on('click', function(){
        if(validate()){
            $('#registerUserModal').modal('show');
        }
    });
    
    $('#registerForm').on('submit', function (){
        if(validate()){ 
                $('#registerUserModal').modal('show');
                if(validateSecurity()){ 
                    return true; 
                }
            }
        return false;
    });
});

/*
 * Creates a popover box upon focus of the email input field
 * that contains instructions for entering in email addresses.
 */
$(function () {
    $("#email")
	    .popover({trigger: "focus", title: 'Email Guidelines', placement: "top", html: true,
		content: "Emails must be in the following format:<br>" +
			"'name@place.domain'"})
	    .blur(function () {
		$(this).popover('hide');
	    });
});

/*
 * Creates a popover box upon focus of the username input field
 * that contains instructions for creating usernames.
 */
$(function () {
    $("#username")
	    .popover({trigger: "focus", title: 'Username Guidelines', placement: "top", html: true,
		content: "Usernames may only contain letters and numbers"})
	    .blur(function () {
		$(this).popover('hide');
	    });
});

/*
 * Creates a popover box upon focus of the pass input field
 * that contains instructions for creating a password.
 */
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

function jsUpdateSize() {
    // Get the dimensions of the viewport
    var width = window.innerWidth ||
	    document.documentElement.clientWidth ||
	    document.body.clientWidth;
    /*  If you wanna get the height of the screen
     var height = window.innerHeight ||
     document.documentElement.clientHeight ||
     document.body.clientHeight;*/
    var labels = document.getElementsByClassName('lol');
    var inputs = document.getElementsByClassName('textinput');
    var i;

    if (width < 576) { // extra small screens
	document.getElementById('accountCreationHeader').style.fontSize = "28px";
	document.getElementById('registerMe').style.textAlign = "center";

	for (i = 0; i < labels.length; i++) {
	    labels[i].style.textAlign = "left";
	    inputs[i].style.fontSize = "12pt";
	}

    } else if (width >= 576) { // small screens and up
	document.getElementById('accountCreationHeader').style.fontSize = "30px";
	document.getElementById('registerMe').style.textAlign = "right";
	for (i = 0; i < labels.length; i++) {
	    labels[i].style.textAlign = "right";
	    inputs[i].style.fontSize = "12pt";
	}
    }
}
;
window.onload = jsUpdateSize;       // When the page first loads
window.onresize = jsUpdateSize;     // When the browser changes size