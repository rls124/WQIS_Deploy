module main

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
    [<Option("override", HelpText = "Override safety precautions on live site")>] overrideSafety: bool;
    [<Option("demo", HelpText = "Live demo mode. Runs through site functionality without performing tests")>] demoMode: bool;
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

let withWait action =
    //get user to confirm the program should proceed. For use with the live demo functionality
    printfn "press [ENTER] to proceed"
    System.Console.ReadLine() |> ignore
    action()

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

    if (opts.demoMode = false) then
        //login
        UserTests.loginTest baseUrl user.[0] user.[1]

        ChartSelectionTests.mapDisplayTest
        ChartSelectionTests.searchBoxToggleTest
        ChartSelectionTests.changeCategoryTest
        ChartSelectionTests.searchTest
        ChartSelectionTests.correctNumberOfRowsTest
        ChartSelectionTests.tableSortTest
        if (opts.userType = "admin") then
            ChartSelectionTests.tableEditTest opts.verbose
        else
            if (opts.verbose) then
                printf("Skipping table edit test because it must be run as an administrator\r\n")

        NavigationTests.navbarLinksWorkTest baseUrl opts.userType

        //logout works
        UserTests.logoutTest baseUrl
    else
        printf("Demo mode\r\n")
        UserTests.loginDemo baseUrl user.[0] user.[1]
        withWait (fun _ -> ChartSelectionTests.searchBoxToggleDemo())
        withWait (fun _ -> ChartSelectionTests.searchDemo())

    //run all tests
    run()

    printfn "press [ENTER] to exit"
    System.Console.ReadLine() |> ignore

    quit()

match result with
  | Success(opts) -> runTests(opts)
  | Fail(errs) -> printf "Invalid: %A, Errors: %u\n" args (Seq.length errs)
  | Help | Version -> ()
