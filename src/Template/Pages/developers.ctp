<?php
echo $this->Html->css("help.css");
echo $this->Html->script("help.js");
?>

<h1>Water Quality Information System for Developers</h1>
<div class="indent">
	<p>
		While the Water Quality Information System alone is a powerful tool, some users may find it limited in certain ways. To address the potential need for a wider array of analytics capabilities, WQIS exposes an API which may be used to programmatically interface with the data in more sophisticated ways. This allows practically limitless specificity in search parameters, as well as the ability to visually render that data in any way desired.
	</p>
	
	<p>
		This API is still in the early stages of development, and will continued to be expanded and refined. Check this page regularly to maintain compatibility with the latest version.
	</p>
</div>

<h2>Getting started</h2>
<div class="indent">
	<p>
		The WQIS API is an extension to the web-based application you're already using, and is hosted on the same server. In principle, it may be accessed through any client which provides the following:
		
		<ul>
			<li>A means of making HTTP requests</li>
			<li>Authentication through the <a href="https://en.wikipedia.org/wiki/Basic_access_authentication">Basic Authentication protocol</a></li>
			<li>Support for cookies</li>
			<li>Ability to generate and parse JSON data</li>
		</ul>
		
		Most modern programming languages can do these, either natively or through libraries.
	</p>
	
	<p>
		You must also have a valid username and password for WQIS. Currently standard user accounts (such as the one you are reading this in) are suitable, but in the future a dedicated API-User classification will be added.
	</p>
	
	<p>
		Making an API request involves simply making an HTTP request with POSTed parameters, and waiting for a response. In Python, this can be done in as few as 7 lines of code:
	</p>
</div>	

<code>
<pre>
	#!/usr/bin/python3

	import requests
	from requests.auth import HTTPBasicAuth

	auth = (USERNAME, PASSWORD) #fill these in with valid credentials

	postData = "{\"sites\":[\"101\"],\"startDate\":\"04/06/2004\",\"endDate\":\"10/08/2013\",\"selectedMeasures\":[\"Ecoli\",\"TotalColiform\"],\"category\":\"bacteria\",\"amount\":\"\",\"overUnderSelect\":\">=\",\"measurementSearch\":\"select\",\"aggregate\":false}"
	resp = requests.post("http://localhost/WQIS/API/samples/getRecords", data=postData, auth=auth)
	print(resp.content)
</pre>
</code>

<div class="indent">
	<p>
		The <code>requests</code> library here handles all of the heavy lifting for dealing with HTTP and authentication.
	</p>
	
	<p>
		Additional code samples are available for <a href="/WQIS/webroot/files/codeSamples/fsharpDemo.fs">F#</a> and Java (<a href="/WQIS/webroot/files/codeSamples/Driver.java">Driver</a> and <a href="/WQIS/webroot/files/codeSamples/WQIS.java">WQIS class</a>)
	</p>
</div>

<h2>Available calls</h2>
<div class="indent">
	<p>
		Functions in WQIS API are divided by the Controller they reside in. Request URLs are of the format <code>/WQIS/API/[controller]/[action]</code>. Not all functionality available in the web application is currently available through the API; API functions have been designed to be as general-purpose as possible. Currently, only read operations, not upload or edit, are possible through the API.
	</p>
	
	<h3>Meta</h3>
	<p>
		Two meta functions are available, for use during development. These can be accessed through <code>/WQIS/API/api/[action]</code>. Note the capitalization.
	</p>
	
	<h4>apitest</h4>
	<p>
		The <code>apitest</code> function is intended to allow developers to validate that they are correctly sending and receiving data. It takes any arbitrary JSON string, and returns the same string.

		<table>
			<tr>
				<th>Path</th>
				<td>/WQIS/API/api/apitest</td>
			</tr>
			<tr>
				<th>Parameters</th>
				<td>Any JSON</td>
			</tr>
			<tr>
				<th>Returns</th>
				<td>Echo of given input</td>
			</tr>
		</table>
	</p>
	
	<h4>apiversion</h4>
	<p>
		The <code>apiversion</code> function returns the latest API revision number. The WQIS API is currently on v1.0. Limited backwards compatibility will be maintained in future iterations.

		<table>
			<tr>
				<th>Path</th>
				<td>/WQIS/API/api/apiversion</td>
			</tr>
			<tr>
				<th>Parameters</th>
				<td>None</td>
			</tr>
			<tr>
				<th>Returns</th>
				<td>(Decimal) Version number</td>
			</tr>
		</table>
	</p>
	
	<h3>Samples</h3>
	<p>
		The <code>samples</code> controller handles queries relating to the sample data records in WQIS.
	</p>
	
	<h4>getRecords</h4>
	<p>
		The <code>getRecords</code> function is able to make a variety of sample-related queries. It can search by one or many sites and/or groups, one or more measurements (currently limited to a single category), aggregate results, and filter where some parameter is greater than, less than, or equal to a given value. It can also return a single record with a known sample number.
	
		<table>
			<tr>
				<th>Path</th>
				<td>/WQIS/API/samples/getRecords</td>
			</tr>
			<tr>
				<th>Parameters</th>
				<td>String category</td>
				<td></td>
				<td>Category name: "bacteria", "nutrient", "pesticide", or "physical"</td>
			</tr>
			<tr>
				<td></td>
				<td>String[] selectedMeasures</td>
				<td></td>
				<td>Array of measure keys (not names) to retrieve. All must be within the selected category</td>
			</tr>
			<tr>
				<td></td>
				<td>Integer[] sampleNumbers</td>
				<td>Optional</td>
				<td>Array of known sample numbers to retrieve full records of. Note that the <code>category</code> field must still be defined, as sample numbers can be duplicated between categories. If <code>sampleNumbers</code> is used, all fields except <code>category</code> and <code>selectedMeasures</code> will be ignored</td>
			</tr>
			<tr>
				<td></td>
				<td>Integer[] sites</td>
				<td>Required if <code>sampleNumbers</code> not set</td>
				<td>Array of site numbers to search for</td>
			</tr>
			<tr>
				<td></td>
				<td>String filterBy</td>
				<td>Optional</td>
				<td>Measure key (not name) to filter by</td>
			</tr>
			<tr>
				<td></td>
				<td>Double filterAmount</td>
				<td>Optional, required if <code>filterBy</code> is set</td>
				<td>Value for filter comparison</td>
			</tr>
			<tr>
				<td></td>
				<td>String filterDirection</td>
				<td>Optional</td>
				<td><, <=, =, >=, >. Defaults to =</td>
			</tr>
			<tr>
				<td></td>
				<td>String Date startDate</td>
				<td>Optional</td>
				<td>String containing the earliest date to return records from, in mm/dd/yyyy format</td>
			</tr>
			<tr>
				<td></td>
				<td>String Date endDate</td>
				<td>Optional</td>
				<td>String containing the latest date to return records from, in mm/dd/yyyy format</td>
			</tr>
			<tr>
				<td></td>
				<td>Boolean aggregate</td>
				<td>Optional</td>
				<td>Enable or disable aggregate mode. Defaults to false</td>
			</tr>
			<tr>
				<th>Returns</th>
				<td>Integer site_location_id</td>
				<td>Number for the site the sample was collected from. Not included if <code>aggregate</code> is true</td>
			</tr>
			<tr>
				<td></td>
				<td>String Date</td>
				<td>Date the measurement was taken, in format "yyyy-mm-ddT00:00:00+00:00". The time code at the end can be ignored</td>
			</tr>
			<tr>
				<td></td>
				<td>Integer Sample_Number</td>
				<td>Unique (within a category) identifier for a particular record</td>
			</tr>
			<tr>
				<td></td>
				<td>Double [measure data]</td>
				<td>Data for each measure listed in the <code>selectedMeasures</code> parameter</td>
			</tr>
			<tr>
				<td></td>
				<td>String Comments</td>
				<td>Any additional data associated with the record, typically added by the person collecting data in the field. Not included if <code>aggregate</code> is true</td>
			</tr>
		</table>
	</p>
	
	<h3>Site Locations</h3>
	<p>
		The <code>site-locations</code> controller provides functions relating to the sites themselves.
	</p>
	
	<h4>latestmeasures</h4>
	<p>
		The <code>latestmeasures</code> function returns the most recently-collected sample data, from all four categories, for the specified sites.
		
		<table>
			<tr>
				<th>Path</th>
				<td>/WQIS/API/site-locations/latestmeasures</td>
			</tr>
			<tr>
				<th>Parameters</th>
				<td>Integer[] sites</td>
				<td>Optional</td>
				<td>Array of site numbers to query. If none provided, will return data for all sites</td>
			</tr>
			<tr>
				<th>Returns</th>
				<td></td>
			</tr>
		</table>
	</p>
	
	<h4>daterange</h4>
	<p>
		The <code>daterange</code> function returns the first and last date for which sample data is available for a given set of sites, within a single category.
	</p>
	
	<p>
		Note that if data exists for any measurement within a category, it will be counted as in the date range. There is currently no checking for individual measures.
	</p>
	
	<table>
		<tr>
			<th>Path</th>
			<td>/WQIS/API/site-locations/daterange</td>
		</tr>
		<tr>
			<th>Parameters</th>
			<td>Integer[] sites</td>
			<td>Array of site numbers to query</td>
		</tr>
		<tr>
			<td></td>
			<td>String category</td>
			<td>Category to search for ("bacteria", "nutrient", "pesticide", "physical")</td>
		</tr>
		<tr>
			<th>Returns</th>
			<td>String[]</td>
			<td>Pair of mm/dd/yyyy-formatted dates. Index 0 is the start date, index 1 is the end date</td>
		</tr>
	</table>
	
	<h4>attributes</h4>
	<p>
		The <code>attributes</code> function allows one or more database attributes (<code>Site_Number</code>, <code>Latitude</code>, <code>Longitude</code>, <code>Site_Location</code>, <code>Site_Name</code>, <code>groups</code>) to be retrieved.	
	</p>
	<p>
		These attributes can also be accessed individually through the functions below. See the relevant sections for output format information and notes on each.
	</p>
	<p>
		<table>
			<tr>
				<th>Path</th>
				<td>/WQIS/API/site-locations/attributes</td>
			</tr>
			<tr>
				<th>Parameters</th>
				<td>Integer siteid</td>
				<td></td>
				<td>Site number for the site</td>
			</tr>
			<tr>
				<td></td>
				<td>String[] attributes</td>
				<td>Optional</td>
				<td>Array of attribute keys to request. Defaults to all</td>
			</tr>
			<tr>
				<th>Returns</th>
				<td>See below</td>
			</tr>
		</table>
	</p>
	
	<h4>latitude</h4>
	<table>
		<tr>
			<th>Path</th>
			<td>/WQIS/API/site-locations/latitude</td>
		</tr>
		<tr>
			<th>Parameters</th>
			<td>Integer site</td>
			<td>Site number for the site</td>
		</tr>
		<tr>
			<th>Returns</th>
			<td>Double latitude</td>
			<td>Latitude of the site</td>
		</tr>
	</table>
	
	<h4>longitude</h4>
	<table>
		<tr>
			<th>Path</th>
			<td>/WQIS/API/site-locations/longitude</td>
		</tr>
		<tr>
			<th>Parameters</th>
			<td>Integer site</td>
			<td>Site number for the site</td>
		</tr>
		<tr>
			<th>Returns</th>
			<td>Double longitude</td>
			<td>Longitude of the site</td>
		</tr>
	</table>
	
	<h4>sitelocation</h4>
	<table>
		<tr>
			<th>Path</th>
			<td>/WQIS/API/site-locations/sitelocation</td>
		</tr>
		<tr>
			<th>Parameters</th>
			<td>Integer site</td>
			<td>Site number for the site</td>
		</tr>
		<tr>
			<th>Returns</th>
			<td>String site_location</td>
			<td>Named location of the site</td>
		</tr>
	</table>
	
	<h4>sitename</h4>
	<table>
		<tr>
			<th>Path</th>
			<td>/WQIS/API/site-locations/site-name</td>
		</tr>
		<tr>
			<th>Parameters</th>
			<td>Integer site</td>
			<td>Site number for the site</td>
		</tr>
		<tr>
			<th>Returns</th>
			<td>String site_name</td>
			<td>Name of the site</td>
		</tr>
	</table>
	
	<h4>groups</h4>
	<table>
		<tr>
			<th>Path</th>
			<td>/WQIS/API/site-locations/groups</td>
		</tr>
		<tr>
			<th>Parameters</th>
			<td>Integer site</td>
			<td>Site number for the site</td>
		</tr>
		<tr>
			<th>Returns</th>
			<td>String groups</td>
			<td>Comma-separated list of IDs of the groups the site is a member of</td>
		</tr>
	</table>
</div>

<h2>Usage guidelines</h2>
<div class="indent">
	<p>
		Currently, no limits on usage exist. However, we request developers make efforts to minimize their impact on our server. In general, please cache data whenever practical, and make queries in as few transactions as possible (eg, a single call to <code>/site-locations/attribute</code> instead of individual requests to each attribute getter).
	</p>
</div>