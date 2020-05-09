module main

open canopy.runner.classic
open canopy.classic
open canopy.types
open CommandLine
open System

type options = {
    [<Option('P', HelpText = "Use production environment")>] prodEnvironment: bool;
    [<Option('B', HelpText = "Use beta environment")>] betaEnvironment: bool;
    [<Option('U', HelpText = "Type of users to test with (admin, normal)")>] userType: String;
    [<Option('V', HelpText = "Verbose")>] verbose: bool;
    [<Option("override", HelpText = "Override safety precautions on live site")>] overrideSafety: bool;
    [<Option("persistedits", HelpText = "Do not revert changes made to the database")>] persistEdits: bool;
    [<Option("browser", HelpText = "Browser to use for testing. Supports Chrome and Firefox")>] browserChoice: String;
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
    let normalUser = [|"demo"; "Test1234"|]
    canopy.configuration.chromeDir <- System.AppContext.BaseDirectory

    //settings
    let baseUrl =
        if (opts.prodEnvironment) then "http://wqis.cityoffortwayne.org/"
        else if (opts.betaEnvironment) then "http://emerald.pfw.edu/WQISBeta/"
        else "http://localhost/WQIS/"

    let user =
        if (opts.userType = "admin") then admin
        else normalUser

    //start browser
    if (opts.browserChoice = "firefox") then
        start firefox
    else
        start chrome

    pin FullScreen

    UserTests.loginTest baseUrl user.[0] user.[1]

    ChartSelectionTests.mapDisplayTest
    ChartSelectionTests.searchBoxToggleTest
    ChartSelectionTests.changeCategoryTest
    ChartSelectionTests.searchTest
    ChartSelectionTests.tablePaginationTest opts.verbose
    ChartSelectionTests.tableSortTest
    if (not opts.prodEnvironment) || (opts.prodEnvironment && opts.overrideSafety) then
        if (opts.userType = "admin") then
            ChartSelectionTests.tableEditTest opts.persistEdits opts.verbose
        else if (opts.verbose) then
            printf("Skipping table edit test because it must be run as an administrator\r\n")
    else if (opts.verbose) then
        printf("Skipping table edit test because we are targetting production. Use --override to force\r\n")

    ChartSelectionTests.preselectSiteTest baseUrl
    ChartSelectionTests.preselectGroupTest baseUrl

    NavigationTests.navbarLinksWorkTest baseUrl opts.userType

    //logout works
    UserTests.logoutTest baseUrl

    //run all tests
    run()

    printfn "press [ENTER] to exit"
    System.Console.ReadLine() |> ignore

    quit()

match result with
  | Success(opts) -> runTests(opts)
  | Fail(errs) -> printf "Invalid: %A, Errors: %u\n" args (Seq.length errs)
  | Help | Version -> ()
