$(document).ready(function () {
    $('#message').on('click', function () {
        $(this).addClass("hidden");
    });
    
    $('body').on('focus', '.mainPage', function(){
        $(this).css({'backgroundColor':'white', 'border':'none'});
    });
    
    $('body').on('click', '#confirmUsername-btn', function () {
	removeErrorMessages();
        
        if ($('#username').val() === '') {
            displayError('#username', '#usernameError', 'No username was provided');
            return false;
        }
        
        var username = $('#username').val();
        $.ajax({
            type: 'POST',
            url: 'getSecurityQuestions',
            datatype: 'JSON',
            data: {
                'username': username
            },
            success: function (result) {
                if (result['Message'] === 'Success') {
                    if (result['securityquestion1'] === null || result['securityquestion2'] === null || result['securityquestion3'] === null) {
                        $('.message').html('There are missing security questions for account <strong>' + username + '</strong>, please contact a site administrator for assistance');
                        $('.message').removeClass('hidden');
                        $('.message').addClass('error');
                        return;
                    }
                    $('#username').attr('readonly', 'readonly');
                    $('#username').css({'backgroundColor' : 'lightgray'});
                    $('#username').removeClass('mainPage');
                    $('#username').removeClass('textinput');
                    $('#confirmUsername-btn').remove();
                    
                    $('.securityQuestions').append('<hr>');
                    $('.securityQuestions').append('<label>' + result['securityquestion1'] + '</label>');
                    $('.securityQuestions').append('<input type="text" class="form-control textinput mainPage" name="securityanswer1" id="securityanswer1" placeholder="Your answer here..."/>');
                    $('.securityQuestions').append('<p class="errorMessage" id="securityanswer1Error" style=""></p>');
                    $('.securityQuestions').append('<label>' + result['securityquestion2'] + '</label>');
                    $('.securityQuestions').append('<input type="text" class="form-control textinput mainPage" name="securityanswer2" id="securityanswer2" placeholder="Your answer here..."/>');
                    $('.securityQuestions').append('<p class="errorMessage" id="securityanswer2Error" style=""></p>');
                    $('.securityQuestions').append('<label>' + result['securityquestion3'] + '</label>');
                    $('.securityQuestions').append('<input type="text" class="form-control textinput mainPage mb-4" name="securityanswer3" id="securityanswer3" placeholder="Your answer here..."/>');
                    $('.securityQuestions').append('<p class="errorMessage" id="securityanswer3Error" style=""></p>');
                    $('.securityQuestions').append('<input type="submit" class="btn mb-3 btn-basic" id="confirmSecAnswers-btn" value="Confirm" style="float: right">');
                
                    $('.message').addClass('hidden');
                } else {
                    $('.message').html('An account with username: <strong>' + username + '</strong> does not exist');
                    $('.message').removeClass('hidden');
                    $('.message').addClass('error');
                }
	    }
        });
    });
    
    $('#forgotUserPassForm').on('submit', function(e){
        var self = this;
        if(!$('#securityanswer1').length || !$('#securityanswer2').length || !$('#securityanswer3').length) {
            $('#confirmUsername-btn').trigger('click');
            return false;
        }
        
	if (!validateSecurity()) {
	    return false;
        }
	
        var username = $('#username').val();
        var securityanswer1 = $('#securityanswer1').val();
        var securityanswer2 = $('#securityanswer2').val();
        var securityanswer3 = $('#securityanswer3').val();

        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'verifySecurityQuestions',
            datatype: 'JSON',
            data: {
                'username': username,
                'securityanswer1': securityanswer1,
                'securityanswer2': securityanswer2,
                'securityanswer3': securityanswer3
            },
            success: function(result){
                if (result['responseData'] === 'GoToUserInformationPage') {
                    self.submit();
                } else {
                    //alert(result['responseData'] + ': ' + result['responseMessage']);
                    $('.message').html(result['responseMessage']);
                    $('.message').removeClass('hidden');
                    $('.message').addClass('error');
                    return false;
                }
            }
        });
    });
});

function validateSecurity() {
    removeErrorMessages();
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

function removeErrorMessages() {
    $('p').each(function(){
        $(this).text('');
    });
}