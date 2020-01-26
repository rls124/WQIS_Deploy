module User

open canopy.runner.classic
open canopy.classic

let login baseUrl username password =
    "Login test" &&& fun _ ->
        url baseUrl
    
        //set username and password fields
        "#username" << username
        "#userpw" << password
    
        click "#login-btn"
    
        //we should now be sent to the chart selection page
        on (baseUrl + "site-locations/chartselection")

let logout =
    "Logout test" &&& fun _ ->
        click "#userDropdownMenu"
        click "Log out"
