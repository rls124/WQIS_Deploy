/*
 * Used to ensure the user did not leave the username or password fields empty.
 */
function validate(){
    removeErrorMessages();
    with(document.forms.loginForm){
        /*
         * If the username or password fields are empty, prompts 
         * the user for the appropriate credential while also highlighting
         * the credential in need in red.
         */
        if(username.value == "" && userpw.value == ""){
            document.getElementById("usernameError").innerHTML = "Please enter a username and password.";
            document.getElementById("passError").innerHTML = "Please enter a username and password.";
            username.style.backgroundColor = "#fcf7f7";
            username.style.border = "solid #a30000";
            userpw.style.backgroundColor = "#fcf7f7";
            userpw.style.border = "solid #a30000";
            return false;
        }
        if(username.value == ""){
            document.getElementById("usernameError").innerHTML = "Please enter a username.";
            username.style.backgroundColor = "#fcf7f7";
            username.style.border = "solid #a30000";
            return false;
        }
        else if(userpw.value == ""){
            document.getElementById("passError").innerHTML = "Please enter a password.";
            userpw.style.backgroundColor = "#fcf7f7";
            userpw.style.border = "solid #a30000";
            return false;
        }
    }
    return true;
}

/*
 * Removes the error messages underneath their appropriate forms.
 */
function removeErrorMessages(){
    for(var i = 0; i < document.getElementsByTagName("P").length; i++){
        document.getElementsByTagName("P")[i].innerHTML = "";
    }
}

/*
 * Used to un-highlight the username or password fields when the user focuses on either field.
 */
function toggle(inputForm){
    with(document.forms.loginForm){
        inputForm.style.backgroundColor = "white";
        inputForm.style.border = "none";
    }
}

function jsUpdateSize(){
    // Get the dimensions of the viewport
    var width = window.innerWidth ||
                document.documentElement.clientWidth ||
                document.body.clientWidth;
    /*var height = window.innerHeight ||
                 document.documentElement.clientHeight ||
                 document.body.clientHeight;*/

    if(width < 576){ // extra small screens
        document.getElementById('wqisInfo').style.fontSize = "70%";
        document.getElementById('wqisHeading').style.fontSize = "27px";
        document.getElementById('username').style.fontSize = "12pt";
        document.getElementById('userpw').style.fontSize = "12pt";
        document.getElementById('login-btn').style.fontSize = "11pt";
        document.getElementById('createAccountBtn').style.fontSize = "11pt";
        document.getElementById('sjrwiInfo').style.fontSize = "70%";
        document.getElementById('ercInfo').style.fontSize = "70%";
        
        document.getElementById('login-btn').style.width = "170px";
        document.getElementById('createAccountBtn').style.width = "170px";
        document.getElementById('sjrwiImg').style.height = "90px";
        document.getElementById('ercImg').style.height = "90px";
        document.getElementById('sjrwiImg').style.width = "120px";
        document.getElementById('ercImg').style.width = "120px";
    }
    else if(width >= 576 && width < 768){ // small screens
        document.getElementById('wqisInfo').style.fontSize = "90%";
        document.getElementById('wqisHeading').style.fontSize = "27px";
        document.getElementById('username').style.fontSize = "12pt";
        document.getElementById('userpw').style.fontSize = "12pt";
        document.getElementById('login-btn').style.fontSize = "14pt";
        document.getElementById('createAccountBtn').style.fontSize = "14pt";
        document.getElementById('sjrwiInfo').style.fontSize = "90%";
        document.getElementById('ercInfo').style.fontSize = "90%";
        
        document.getElementById('login-btn').style.width = "160px";
        document.getElementById('createAccountBtn').style.width = "160px";
        document.getElementById('sjrwiImg').style.height = "120px";
        document.getElementById('ercImg').style.height = "120px";
    }
    else if(width >= 768){ // medium screens and up
        document.getElementById('wqisInfo').style.fontSize = "100%";
        document.getElementById('wqisHeading').style.fontSize = "36px";
        document.getElementById('username').style.fontSize = "13pt";
        document.getElementById('userpw').style.fontSize = "13pt";
        document.getElementById('login-btn').style.fontSize = "13pt";
        document.getElementById('createAccountBtn').style.fontSize = "13pt";
        document.getElementById('sjrwiInfo').style.fontSize = "90%";
        document.getElementById('ercInfo').style.fontSize = "90%";
        
        document.getElementById('login-btn').style.width = "150px";
        document.getElementById('createAccountBtn').style.width = "150px";
        document.getElementById('sjrwiImg').style.height = "140px";
        document.getElementById('ercImg').style.height = "140px";
        document.getElementById('sjrwiImg').style.width = "160px";
        document.getElementById('ercImg').style.width = "160px";
    }
};
window.onload = jsUpdateSize;       // When the page first loads
window.onresize = jsUpdateSize;     // When the browser changes size