module ChartSelectionTests

open canopy.runner.classic
open canopy.classic

let start userType verbose =
    "map displays" &&& fun _ ->
        displayed "#mapContainer"

    "search box toggles" &&& fun _ ->
        //initial state should be "open"
        "CLOSE" == read "#sidebarToggleLabel"
        click "#sidebarToggle"
        "OPEN" == read "#sidebarToggleLabel"

        //make sure its now open for the rest of the tests to proceed
        click "#sidebarToggle"

    "changing category sets correct measurement options" &&& fun _ ->
        "#categorySelect" << "Nutrient"
    
        //we should be able to select "Nitrate/Nitrite" from the measurementSelect dropdown now
        "#measurementSelect" << "Nitrate/Nitrite (mg/L)"
        "#measurementSelect" << "Select a measure" //reset that so we don't disrupt the search test later

        //display fields should contain that as well
        displayed "#NitrateNitriteCheckbox"

    "search works" &&& fun _ ->
        "#sites" << "100 Cedar Creek"
        sleep 1 //wait for the date fields to autopopulate
        click "#updateButton"
        sleep 1 //wait to populate
        displayed "#tableView"

    //correct number of rows display
    "correct number of table rows" &&& fun _ ->
        let el = (((element "#tableView" |> elementWithin "tbody") |> elementsWithin "tr") |> List.length)
        26 === el

    //table sort works
    "table sort works" &&& fun _ ->
        let el = ((element "#tableView" |> elementWithin "tbody") |> elementsWithin "tr")
        let row = el.[0]
        let cells = row |> elementsWithin "th"
        let sampleNumberCol = cells.[2]
        click sampleNumberCol
        //TODO: validate that this actually works, all we do for now is click on the column

    if userType = "admin" then
        //table edit works
        "table edit works" &&& fun _ ->
            let el = ((element "#tableView" |> elementWithin "tbody") |> elementsWithin "tr")
            let row = el.[1]
            let cells = row |> elementsWithin "td"
            let cell = cells.[3] |> elementWithin "div"
            let label = cell |> elementWithin "label"
            let input = cell |> elementWithin "input"

            label.Text == (read input) //these should be the same
            let originalValue = label.Text

            if verbose then
                printfn "Label = %s, input = %s" label.Text (read input)

            //validate that the label is visible to start with
            displayed label
            click label
            //validate that the label is now hidden
            notDisplayed label

            input << "50"
            click "body"
            sleep 1 //wait for the AJAX query to update the db

            //now put the original value back in
            click label
            input << originalValue
            click "body"
            sleep 1
