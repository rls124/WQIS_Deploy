open canopy.runner.classic
open canopy.classic
open canopy.types
open CommandLine
open System

type options = {
    [<Option('P', HelpText = "Use production environment")>] prodEnvironment : bool;
    [<Option('B', HelpText = "Use beta environment")>] betaEnvironment : bool;
    [<Option('U', HelpText = "Type of users to test with (admin, normal)")>] userType : String;
    [<Option('V', HelpText = "Verbose")>] verbose: bool;
    }

let inline (|Success|Help|Version|Fail|) (result : ParserResult<'a>) =
  match result with
  | :? Parsed<'a> as parsed -> Success(parsed.Value)
  | :? NotParsed<'a> as notParsed when notParsed.Errors.IsHelp() -> Help
  | :? NotParsed<'a> as notParsed when notParsed.Errors.IsVersion() -> Version
  | :? NotParsed<'a> as notParsed -> Fail(notParsed.Errors)
  | _ -> failwith "invalid parser result"

let args = Environment.GetCommandLineArgs()
let result = Parser.Default.ParseArguments<options>(args)

let runTests(opts) =
    let admin = [|"root"; "waterquality"|]
    let normalUser = [|"jsmith"; "Test1234"|]
    canopy.configuration.chromeDir <- System.AppContext.BaseDirectory

    //settings
    let baseUrl =
        if (opts.prodEnvironment) then "http://emerald.pfw.edu/WQIS/" //actual production environment is currently incompatible with this tool, so don't use this
        else if (opts.betaEnvironment) then "http://emerald.pfw.edu/WQISBeta/"
        else "http://localhost/WQIS/"

    let user =
        if (opts.userType = "admin") then admin
        else normalUser

    //start an instance of chrome
    start chrome
    pin FullScreen

    //login test
    "login" &&& fun _ ->
        url baseUrl

        //set username and password fields
        "#username" << user.[0]
        "#userpw" << user.[1]

        click "#login-btn"

        //we should now be sent to the chart selection page
        on (baseUrl + "site-locations/chartselection")

    //map display test
    "map displays" &&& fun _ ->
        displayed "#mapContainer"
    
    //search box can be opened
    "search box opens" &&& fun _ ->
        //this will always be true, it just clicks the button. Need to detect if the sidebar is actually open/closed, but the "displayed" operator doesn't work because we don't actually hide the sidebar, just shift it to a negative x position
        click "#searchButton"

    //changing category changes measures/display field options
    "changing category sets correct measurement options" &&& fun _ ->
        "#categorySelect" << "Nutrient"
    
        //we should be able to select "Nitrate/Nitrite" from the measurementSelect dropdown now
        "#measurementSelect" << "Nitrate/Nitrite (mg/L)"
        "#measurementSelect" << "Select a measure" //reset that so we don't disrupt the search test later

        //display fields should contain that as well
        displayed "#NitrateNitriteCheckbox"

    //search works
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

    if opts.userType = "admin" then
        //table edit works
        "table edit works" &&& fun _ ->
            let el = ((element "#tableView" |> elementWithin "tbody") |> elementsWithin "tr")
            let row = el.[1]
            let cells = row |> elementsWithin "td"
            let cell = cells.[3] |> elementWithin "div"
            let label = cell |> elementWithin "label"
            let input = cell |> elementWithin "input"
            if opts.verbose then
                printfn "Label = %s, input = %s" label.Text (read input)

            //validate that the label is visible to start with
            displayed label
            click label
            //validate that the label is now hidden
            notDisplayed label

            input << "50"
            click "body"
            sleep 1

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

        if (opts.userType = "Admin") then
            click "Admin Panel"
            on (baseUrl + "pages/administratorpanel")

    //run all tests
    run()

    printfn "press [ENTER] to exit"
    System.Console.ReadLine() |> ignore

    quit()

match result with
  | Success(opts) -> runTests(opts)
  | Fail(errs) -> printf "Invalid: %A, Errors: %u\n" args (Seq.length errs)
  | Help | Version -> ()
