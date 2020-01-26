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
    User.login baseUrl user.[0] user.[1]

    ChartSelectionTests.start opts.userType opts.verbose

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

    //logout works
    User.logout

    //run all tests
    run()

    printfn "press [ENTER] to exit"
    System.Console.ReadLine() |> ignore

    quit()

match result with
  | Success(opts) -> runTests(opts)
  | Fail(errs) -> printf "Invalid: %A, Errors: %u\n" args (Seq.length errs)
  | Help | Version -> ()
