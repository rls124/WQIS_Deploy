open canopy.runner.classic
open canopy.classic
open CommandLine
open System

type options = {
    [<Option('P', HelpText = "Use production environment")>] prodEnvironment : bool;}

let inline (|Success|Help|Version|Fail|) (result : ParserResult<'a>) =
  match result with
  | :? Parsed<'a> as parsed -> Success(parsed.Value)
  | :? NotParsed<'a> as notParsed when notParsed.Errors.IsHelp() -> Help
  | :? NotParsed<'a> as notParsed when notParsed.Errors.IsVersion() -> Version
  | :? NotParsed<'a> as notParsed -> Fail(notParsed.Errors)
  | _ -> failwith "invalid parser result"

let args = Environment.GetCommandLineArgs()
let result = Parser.Default.ParseArguments<options>(args)
let mutable prodEnvironment = false

match result with
  | Success(opts) -> (prodEnvironment <- opts.prodEnvironment)
  | Fail(errs) -> printf "Invalid: %A, Errors: %u\n" args (Seq.length errs)
  | Help | Version -> ()

canopy.configuration.chromeDir <- System.AppContext.BaseDirectory

//settings
let baseUrl =
    if (prodEnvironment) then "http://emerald.pfw.edu/WQISBeta/"
    else "http://localhost/WQIS/"

//start an instance of chrome
start chrome

//login test
"login" &&& fun _ ->
    url baseUrl
    
    //set username and password fields
    "#userName" << "root"
    "#userPW" << "waterquality"

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
    click "#updateButton"

//run all tests
run()

printfn "press [enter] to exit"
System.Console.ReadLine() |> ignore

quit()
