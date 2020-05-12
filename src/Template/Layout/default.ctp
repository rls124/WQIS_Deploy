<!DOCTYPE html>
<html>
	<head>
		<?= $this->Html->charset() ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Water Quality Information System</title>
		<?= $this->Html->meta("icon") ?>

		<?= $this->fetch("meta") ?>
		<?= $this->fetch("css") ?>
		<?= $this->fetch("script") ?>

		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<?php
		echo $this->Html->css("bootstrap-glyphicons.min.css");
		echo $this->Html->css("styling.css");
		echo $this->Html->script("navbar.js");

if (!isset($_COOKIE["ignoreBrowserCompatibility"])) { //if user has not previously clicked ok on the browser compatibility warning within this browser session
?>
<script>
function browserDetect() {
	var ua = navigator.userAgent;
	var upToDate = true;
	
	try {
		var browsers = [["Edg", "Edge", 79], ["Firefox", "Firefox", 65], ["Chrome", "Chrome", 75], ["OPR", "Opera", 60]]; //user agent identifier, browser name, lowest supported version number

		for (var browser of browsers) {
			if (ua.includes(browser[0])) {
				var verNum = parseFloat(ua.split(browser[0] + "/")[1].split(" ")[0]);
				if (verNum < browser[2]) {
					showError("Your browser is out of date and we cannot guarantee that all website functionality will work as expected. Please consider updating to the latest version of " + browser[1] + ", or a different browser.");
					upToDate = false;
					break;
				}
				break;
			}
		}
	}
	catch (e) {
		//if it failed, its a browser so ancient it we can't hope to support it
		showError("Your browser is not supported and we cannot guarantee that all website functionality will work as expected. Please consider switching to Microsoft Edge, Firefox, or Chrome.");
		upToDate = false;
	}
	
	if (upToDate) {
		//we know that this browser is compatible now, so set the cookie anyway just so this code doesn't have to be loaded subsequently. Very minor performance gain, but nonzero. Cookie is specific to 1 browser and cleared on exit, so no risk
		document.cookie = "ignoreBrowserCompatibility";
	}
}

function showError(text) {
	//build and display a footer div with the error message and ok button
	var errorNotice = document.createElement("div");
	errorNotice.setAttribute("id", "browserCompatibilityMessage");
	errorNotice.setAttribute("style", "padding: 5px; bottom:0; position:fixed; z-index:150; width:100%; _position:absolute; _top:expression(eval(document.documentElement.scrollTop+(document.documentElement.clientHeight-this.offsetHeight))); background-color:yellow;");
	errorNotice.innerHTML = text;
	
	var okButton = document.createElement("button");
	okButton.innerHTML = "OK";
	okButton.setAttribute("onclick", "hideBrowserCompatibilityMessage()");
	okButton.setAttribute("style", "float:right;");
	
	errorNotice.appendChild(okButton);
	
	document.body.appendChild(errorNotice);
}

function hideBrowserCompatibilityMessage() {
	//close the message
	document.getElementById("browserCompatibilityMessage").style.display = "none";

	//set a cookie so we don't keep annoying the user about this
	document.cookie = "ignoreBrowserCompatibility";
}
</script>
<?php } ?>
	</head>
	<body class="h-100">
		<?php
		echo $this->element("navbar");
		echo $this->fetch("navbar");
		
		echo $this->Flash->render();
		?>
		
		<div class="container content col-lg-12" style="top: 7vh">
		<?= $this->fetch("content") ?>
		</div>
	
		<!--loading spinner-->
		<div class="csscssload-load-frame loadingspinnermain" id="loadingSpinner">
			<?php
				for ($i=0; $i<24; $i++) {
					echo "<div class=\"cssload-dot\"></div>";
				}
			?>
		</div>
		
		<!--
		Produced with love by the students of IPFW/PFW.
		Bobby Nicola, Mack Crawford, Seth Snider, Nicholas Tayloe
		-->
		<script src="https://unpkg.com/@popperjs/core@2"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		
		<?php if (!isset($_COOKIE["ignoreBrowserCompatibility"])) { echo "<script>browserDetect();</script>"; } ?>
	</body>
</html>