module ChartSelectionTests

open canopy.runner.classic
open canopy.classic

let mapDisplayTest =
    "map displays" &&& fun _ ->
        displayed "#mapContainer"

let searchBoxToggleTest =
    "search box toggles" &&& fun _ ->
        //initial state should be "open"
        assert (((element "#sidebarInner").GetAttribute("style")).Contains("width: 20vw")) //open
        click "#sidebarToggle"
        assert (((element "#sidebarInner").GetAttribute("style")).Contains("width: 0px")) //closed

        //make sure its now open for the rest of the tests to proceed
        click "#sidebarToggle"
        assert (((element "#sidebarInner").GetAttribute("style")).Contains("width: 20vw")) //open again

let changeCategoryTest =
    "changing category sets correct measurement options" &&& fun _ ->
        "#categorySelect" << "Nutrient"
    
        //we should be able to select "Nitrate/Nitrite" from the measurementSelect dropdown now
        "#measurementSelect" << "Nitrate/Nitrite (mg/L)"
        "#measurementSelect" << "Select a measure" //reset that so we don't disrupt the search test later

        //display fields should contain that as well
        displayed "#NitrateNitriteCheckbox"

let searchTest =
    "search works" &&& fun _ ->
        "#sites" << "100 Cedar Creek"
        sleep 1 //wait for the date fields to autopopulate
        click "#updateButton"
        sleep 1 //wait to populate
        displayed "#tableView"

let numRows () =
    (((element "#tableView" |> elementWithin "tbody") |> elementsWithin "tr") |> List.length)

let tablePaginationTest =
    //"Show ___ results" dropdown correctly works, prev/next page buttons work, and number of rows and pages displayed is correct
    "table pagination works" &&& fun _ ->
        //should always start on page 1, and these should always match
        "1" === read "#pageSelectorTop"
        "1" === read "#pageSelectorBottom"

        //change numRowsDropdown to all to get true number of total rows (future work: directly access the db and run queries ourselves for this sort of thing)
        "#numRowsDropdownTop" << "All"
        "All" === read "#numRowsDropdownBottom" //changing the top one should also affect the bottom
        sleep 1 //wait to populate
        let totalRowsCounted = numRows()

        let recordsPerPage = 10
        //change back to 10 rows
        "#numRowsDropdownTop" << recordsPerPage.ToString()
        sleep 1 //wait to populate

        let numPagesReturned = read((element "#pageSelectorTop" |> elementWithin "option:last-child"))
        printfn "Last page = %s" numPagesReturned

        let numRecordsDisplayed = read(".totalResults")
        printfn "Shows %s results" numRecordsDisplayed

        //check number of results is correct
        (totalRowsCounted-1).ToString() == read(".totalResults")

        //check number of pages is correct
        let correctNumPages = (totalRowsCounted + recordsPerPage - 1) / recordsPerPage;
        assert (correctNumPages.ToString() = numPagesReturned)

let tableSortTest =
    //table sort works
    "table sort works" &&& fun _ ->
        let el = ((element "#tableView" |> elementWithin "tbody") |> elementsWithin "tr")
        let row = el.[0]
        let cells = row |> elementsWithin "th"
        let sampleNumberCol = cells.[2]
        click sampleNumberCol
        //TODO: validate that this actually works, all we do for now is click on the column

        sleep 1 //wait to populate

let tableEditTest persistEdits verbose =
    //table edit works
    "table edit works" &&& fun _ ->
        let el = ((element "#tableView" |> elementWithin "tbody") |> elementsWithin "tr")
        let row = el.[1]
        let cells = row |> elementsWithin "td"
        let cell = cells.[4] |> elementWithin "div"
        let label = cell |> elementWithin "label"
        let input = cell |> elementWithin "input"

        //handle scenario where the cell currently has no data, and the input box incorrectly displays null (currently being worked on)
        let mutable inputText = (read input)
        if inputText = "null" then
            inputText <- ""

        let mutable originalValue = "null"

        if inputText <> "" then
            let lText = label.Text

            lText == inputText //these should be the same
            originalValue <- lText

            if verbose then
                printfn "Label=%s, input=%s," lText inputText
    
        //validate that the label is visible to start with
        displayed label
        click label
        //validate that the label is now hidden
        notDisplayed label

        input << "50"
        click "body"
        sleep 1 //wait for the AJAX query to update the db

        label.Text == "50" //these should be the same

        if not persistEdits then
            //now put the original value back in
            click label
            input << originalValue
            click "body"
            sleep 1

let preselectSiteTest baseUrl =
    //preselecting a site via GET request works
    "preselecting site works" &&& fun _ ->
        url (baseUrl + "site-locations/chartselection?site=401")

        //validate that this site is selected
        "401 Flatrock Creek/Auglaize River" == read("#sites")
