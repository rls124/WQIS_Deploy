<?php
$pageName = substr($this->request->getUri(), strrpos($this->request->getUri(), "/") + 1);
?>

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
		<?= $this->Html->css("bootstrap-glyphicons.min.css") ?>
		<?= $this->Html->css("styling.css") ?>
		<?= $this->Html->css("cakemessages.css") ?>
		<?= $this->Html->css("loading.css") ?>

<script>
<?php
if (!isset($_COOKIE["ignoreBrowserCompatibility"])) { //if user has not previously clicked ok on the browser compatibility warning within this browser session
?>
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
<?php } ?>

$(document).ready(function () {
	//allow dropdowns that have a link as the root element to be clicked
	$(".dropdown").click(function(event) {
		var tgt = event.target.href;
		if (tgt[tgt.length-1] != "#") { //if the last char is # that means its a dropdown that has to be clicked to activate and doesn't actually have a destination
			location.href = tgt;
		}
	});
	
	<?php if (!isset($_COOKIE["ignoreBrowserCompatibility"])) { echo "browserDetect();"; } ?>
});
</script>
	</head>
	<body class="h-100">
		<nav class="navbar navbar-expand navbar-dark fixed-top" id="navbar" style="background-color: #5085A5; height: 7vh">
			<?= $this->Html->link(__("WQIS"), ["controller" => "users", "action" => "login"], ["class" => "navbar-brand"]); ?>
			
			<div class="navbar-collapse">
				<ul class="navbar-nav mr-auto">
					<?php if ($userinfo) {?>
					<li class="nav-item">
						<a href="/WQIS/site-locations/chartselection" class="nav-link <?php if ($pageName == "chartselection") { echo "active"; }?>">View Data</a>
					</li>
					<?php
					}

					if ($userinfo) {?>
					<li class="nav-item dropdown hoverDropdown">
						<a href="/WQIS/pages/about" class="nav-link <?php if ($pageName == "about") { echo "active"; }?>" data-toggle="dropdown">About</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="/WQIS/site-locations/sitemanagement">Sites</a></li>
							<li><a class="dropdown-item" href="/WQIS/measurement-settings/measurements">Measurements</a></li>
							<li><a class="dropdown-item" href="/WQIS/site-groups/sitegroups">Groups</a></li>
						</ul>
					</li>
					<?php
					}
					else {
					?>
					<li class="nav-item">
						<a href="/WQIS/pages/about" class="nav-link <?php if ($pageName == "about") { echo "active"; }?>">About</a>
					</li>
					<?php
					}
					
					if ($userinfo) {?>
					<li class="nav-item">
						<a href="/WQIS/pages/help" class="nav-link <?php if ($pageName == "help") { echo "active"; }?>">Help</a>
					</li>
					<?php
					}
					
					if ($admin) { ?>
					<li class="nav-item">
						<a href="/WQIS/pages/administratorpanel" class="nav-link <?php if ($pageName == "administratorpanel") { echo "active"; }?>">Administration</a>
					</li>
					<?php
					}
					
					if ($admin) {?>
					<li class="nav-item dropdown hoverDropdown">
						<a href="/WQIS/contact/contact" class="nav-link <?php if ($pageName == "about") { echo "active"; }?>" data-toggle="dropdown">Contact</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="/WQIS/contact/viewfeedback">View feedback</a></li>
						</ul>
					</li>
				<?php
				} 
				else { ?>
					<li class="nav-item">
						<a href="/WQIS/contact/contact" class="nav-link <?php if ($pageName == "contact") { echo "active"; }?>">Contact</a>
					</li>
				<?php
				}
				?>
				</ul>

				<ul class="navbar-nav text-right pull-right" style="list-style: none;">
				<?php if ($userinfo) { ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="userDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?php
							echo $userinfo["username"];
							if ($admin) {
								echo " (Administrator)";
							}
						?>
						</a>
						<div class="dropdown-menu" style="right: 0; left: auto;" aria-labelledby="userDropdownMenu">
							<a class="dropdown-item" href="/WQIS/users/edituserinfo">User profile</a>
							<a class="dropdown-item" href="<?php echo $this->Url->build(["controller" => "users", "action" => "logout"]) ?>">Log out</a>
						</div>
					</li>
				<?php
				}
				else { ?>
					<li class="nav-item">
						<a href="/WQIS/" class="nav-link">Log in</a>
					</li>
				<?php } ?>
				</ul>
			</div>
		</nav>
				
		<?= $this->Flash->render() ?>
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
	</body>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</html>