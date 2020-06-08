module APITests

open System.Text
open System.IO
open System.Net
open canopy.runner.classic //strictly speaking we aren't actually using Canopy in this class, but I make use of its test notation for consistency

let doPost baseUrl controller action username password postData =
    let url = baseUrl + "API/" + controller + "/" + action

    //create and configure HTTP web request
    let req = HttpWebRequest.Create(url) :?> HttpWebRequest 
    req.ProtocolVersion <- HttpVersion.Version10
    req.Method <- "POST"
    
    //encode body with POST data as array of bytes
    let postBytes = Encoding.ASCII.GetBytes(postData + "") //hacky
    req.ContentType <- "application/json"
    req.CookieContainer <- new CookieContainer()
    let encoded = System.Convert.ToBase64String(System.Text.Encoding.GetEncoding("UTF-8").GetBytes(username + ":" + password))
    req.Headers.Add("Authorization", "Basic " + encoded)
    req.ContentLength <- int64 postBytes.Length

    //write data to the request
    let reqStream = req.GetRequestStream() 
    reqStream.Write(postBytes, 0, postBytes.Length)
    reqStream.Close()
    
    //obtain response
    let resp = req.GetResponse() 
    let stream = resp.GetResponseStream() 
    let reader = new StreamReader(stream) 

    printfn "%A" (reader.ReadToEnd())

//API meta tests
let APIConnectionWorks baseUrl username password =
    //we can make the API connection with authentication and passing POST data
    "API connection can be made" &&& fun _ ->
        let postData = "{\"test\": 100}"
        doPost baseUrl "api" "apitest" username password postData

//Site Locations tests
let DateRangeTest baseUrl username password =
    "site-locations/daterange" &&& fun _ ->
        let postData = "{\"sites\":[100],\"category\":\"bacteria\"}"
        doPost baseUrl "site-locations" "daterange" username password postData

let LatestMeasuresTest baseUrl username password =
    "site-locations/latestmeasures" &&& fun _ ->
        let postData = "{\"sites\":[101, 102]}"
        //doPost baseUrl "site-locations" "latestmeasures" username password "" //with no params, should return all
        doPost baseUrl "site-locations" "latestmeasures" username password postData //with two sites selected

let SiteAttributesTest baseUrl username password =
    "site-locations/attributes" &&& fun _ ->
        let postData = "{\"site\":100,\"attributes\":[\"latitude\",\"groups\"]}"
        doPost baseUrl "site-locations" "attributes" username password postData

let SiteAttributesIndividualTest baseUrl username password =
    "site-locations individual attribute getters" &&& fun _ ->
        let postData = "{\"site\":100}"
        doPost baseUrl "site-locations" "latitude" username password postData
        doPost baseUrl "site-locations" "longitude" username password postData
        doPost baseUrl "site-locations" "sitelocation" username password postData
        doPost baseUrl "site-locations" "sitename" username password postData
        doPost baseUrl "site-locations" "groups" username password postData
