module UserTests

open canopy.runner.classic
open canopy.classic

let loginTest baseUrl username password =
    "login" &&& fun _ ->
        url baseUrl
        
        //set username and password fields
        "#username" << username
        "#userpw" << password
        
        click "#login-btn"
    
        on (baseUrl + "site-locations/chartselection") //validate we were redirected correctly

let logoutTest baseUrl =
    "Logout test" &&& fun _ ->
        click "#userDropdownMenu"
        click "Log out"

        on baseUrl
