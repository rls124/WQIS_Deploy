module NavigationTests

open canopy.runner.classic
open canopy.classic

let navbarWorksTest baseUrl userType =
    //navbar links work
    "navbar links work" &&& fun _ ->
        click "View Water Quality Data"
        sleep 1
        on (baseUrl + "site-locations/chartselection")
        click "About"
        sleep 1
        on (baseUrl + "pages/about")
        click "Help"
        sleep 1
        on (baseUrl + "pages/help")

        if (userType = "Admin") then
            click "Admin Panel"
            on (baseUrl + "pages/administratorpanel")
