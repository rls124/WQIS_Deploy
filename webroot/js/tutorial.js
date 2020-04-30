const driver = new Driver({
	animate: true,
	doneBtnText: "Finish Tour",
	closeBtnText: "Exit Tour",
	keyboardControl: true,
	allowClose: false,
	opacity: .75,
});

driver.defineSteps([
{
	element: ".h-100",
	stageBackground: "#00000000",
	popover: {
		title: "Welcome!",
		description: "Welcome to the Water Quality Information System. This is a resource dedicated to analyzing and cataloging water quality samples taken within various watersheds located in Indiana, Michigan, and Ohio. More can be found in our About page",
		position: "mid-center",
	}
},{
	element: "#navbar",
	stageBackground: "#BF000000",
	popover: {
		title: "About",
		description: "The About page may help answer various questions you may have about the initiative or aspects of the project",
		position: "bottom-left",
		offset: 180
	}
},{
	element: "#mapCard",
	popover: {
		title: "The Map",
		description: "In the map you will see several blue points and colored outlines. The colorful outlines signify different watersheds, and the blue dots represent water collection sites",
		position: "left",
	}
},{
	element: "#map",
	popover: {
		title: "Try selecting one!",
		description: "Try selecting one of the collection sites by clicking on any of the blue dots on the map. Doing so will display all the data from the last collected water sample at that selected site",
		position: "left",
	}
},{
	element: "#layerBar",
	popover: {
		title: "Layers",
		description: "These are the different layers. They can be toggled on or off by clicking the checkboxes. Selecting these layers will show additional highlighted features on the map",
		position: "left",
	}
},{
	element: "#selectBasemap",
	popover: {
		title: "Basemaps",
		description: "This dropdown menu contains a list of different basemap views that are available. These contain different types of geographical information displayed in the map",
		position: "left",
	}, onNext: () => {
		document.getElementById("driver-page-overlay").style.opacity = "0";
	},
},{
	element: ".sidebarContainer",
	stageBackground: "#BF000000",
	popover: {
		title: "Sidebar Menu",
		description: "The sidebar menu contains all of the search controls",
		position: "right",
	}
},{
	element: "#sidebarToggle",
	stageBackground: "#BF000000",
	popover: {
		title: "Sidebar Toggle",
		description: "The sidebar can be opened and closed",
		position: "right",
	}
},{
	element: "#sites",
	stageBackground: "#BF000000",
	popover: {
		title: "Site selection",
		description: "Select one or more sites to search for, or a group of sites",
		position: "bottom-left",
	}
},{
	element: "#aggregateGroup",
	stageBackground: "#BF000000",
	popover: {
		title: "Aggregate tool",
		description: "The Aggregate tool takes the average value of each selected measure for all selected sites, making it easier to see trends across multiple adjacent sites or within a group",
		position: "bottom-left",
	}
	
},{
	element: "#categorySelect",
	stageBackground: "#BF000000",
	popover: {
		title: "Selecting a Category",
		description: "Here is where you will select a measurement category you would like to search by. All water quality data is classified under these four measurement categories",
		position: "right",
	},
},{
	element: "#checkboxList",
	stageBackground: "#BF000000",
	popover: {
		title: "Selecting a Measurement",
		description: "Here is where you will select a measurement you would like to search by, you may select as many checkboxes as there are available. These selections will determine the type of data you recieve",
		position: "right",
	}
},{
	element: "#startDate",
	stageBackground: "#BF000000",
	popover: {
		title: "Selecting a Date Range",
		description: "You may define a date range to view records within. This is automatically filled with the maximum range for the sites selected, but you can select a different date range if needed",
		position: "right",
	}
},{
	element: "#measurementSelect",
	stageBackground: "#BF000000",
	popover: {
		title: "Filtering your search results",
		description: "This section is completely optional. Here is where you will be able to refine the data you will recieve. The measurement box will already be filled in and always match the same measurement criteria set above",
		position: "right",
	}
},{
	element: "#overUnderSelect",
	stageBackground: "#BF000000",
	popover: {
		title: "Searching Over, Under, or Equal to a specified amount",
		description: "This is where you will specify if we would like to search over, under, or equal to a certain amount of a measure. For example, if you search for Ecoli Over 2000, we would only recieve data where ecoli was over 2000",
		position: "right",
	}
},{
	element: "#amountEnter",
	stageBackground: "#BF000000",
	popover: {
		title: "Entering an amount",
		description: "Here you can enter the amount you would like to search by. The number that appears in this textbox by default is the set benchmark for that given measure, this is to give the user a better sense of the range they should be searching by",
		position: "right",
	}
},{
	element: "#updateButton",
	stageBackground: "#BF000000",
	popover: {
		title: "Updating the graphs based on your search criteria",
		description: "When you are completely finished filling out the form in the side panel, click the update function to get a visual and numerical representation of the data",
		position: "right",
	}
},{
	element: "#resetButton",
	stageBackground: "#BF000000",
	popover: {
		title: "Reseting the form",
		description: "If you would like to start a new blank search, click the reset button and everything will be reset to its default state",
		position: "right",
	}, onNext: () => {
		document.getElementById("driver-page-overlay").style.opacity = ".75";
	},
},{
	element: "#timelineCard",
	popover: {
		title: "Viewing Graphs",
		description: "A visual represnetation of your searched data will appear here in the timeline section",
		position: "left",
	}
},{
	element: "#tableCard",
	popover: {
		title: "Viewing Data Numerically",
		description: "All enteries in the system that match your search criteria will appear here in chronological descending order. This can be changed by clicking on the table headers",
		position: "left",
	}
},
]);

driver.start();