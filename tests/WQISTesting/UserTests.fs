module UserTests

open canopy.runner.classic
open canopy.classic

let loginAction baseUrl username password =
    //handles the login itself
    url baseUrl
    
    //set username and password fields
    "#username" << username
    "#userpw" << password
    
    click "#login-btn"

let loginTest baseUrl username password =
    "Login test" &&& fun _ ->
        loginAction baseUrl username password
    
        on (baseUrl + "site-locations/chartselection") //validate we were redirected correctly

let loginDemo baseUrl username password =
    printf "Login demo\r\n"
    loginAction baseUrl username password

let logoutTest baseUrl =
    "Logout test" &&& fun _ ->
        click "#userDropdownMenu"
        click "Log out"

        on baseUrl
