open System.Text
open System.IO
open System.Net

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
	
//API connection test
let mutable postData = "{\"test\": 100}"
doPost baseUrl "api" "apitest" username password postData

//Site Locations attribute test
postData <- "{\"siteid\":100,\"attributes\":[\"latitude\",\"groups\"]}"
doPost baseUrl "site-locations" "attributes" username password postData