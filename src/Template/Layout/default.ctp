<?php
$pageName = substr($this->request->getUri(), strrpos($this->request->getUri(), '/') + 1);
?>

<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Water Quality Information System</title>
        <?= $this->Html->meta('icon') ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <?= $this->Html->css('bootstrap-glyphicons.min.css') ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <?= $this->Html->script('jquery.msgbox.min.js') ?>
        <?= $this->Html->css('styling.css') ?>
        <?= $this->Html->css('cakemessages.css') ?>
		<?= $this->Html->css("loading.css") ?>

<?php
if (!isset($_COOKIE["ignoreBrowserCompatibility"])) { //if user has not previously clicked ok on the browser compatibility warning within this browser session
?>
<script>
function browserDetect() {
	var ua = navigator.userAgent;
	var upToDate = true;
	
	try {
		var browsers = [['Edge', 18.17763], ['Firefox', 65], ['Chrome', 75], ['OPR', 60]]; //browser name, lowest supported version number

		for (var i=0; i<browsers.length; i++) {
			var browser = browsers[i];
			
			if (ua.includes(browser[0])) {
				var verNum = parseFloat(ua.split(browser[0] + "/")[1].split(" ")[0]);
				if (verNum < browser[1]) {
					showError("Your browser is out of date and we cannot guarantee that all website functionality will work as expected. Please consider updating to the latest version of " + browser[0] + ", or a different browser.");
					upToDate = false;
					break;
				}
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
        <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
            <?= $this->Html->link(__('WQIS'), ['controller' => 'users', 'action' => 'login'], ['class' => 'navbar-brand']); ?>
			
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse">
				<ul class="navbar-nav mr-auto navbar-right">
					<li class="nav-item">
						<a href="/WQIS/site-locations/chartselection" class="nav-link <?php if ($pageName == "chartselection") { echo "active"; }?>">View Water Quality Data</a>
					</li>
					
					<li class="nav-item">
						<a href="/WQIS/pages/about" class="nav-link <?php if ($pageName == "about") { echo "active"; }?>">About</a>
					</li>
					
					<li class="nav-item">
						<a href="/WQIS/pages/help" class="nav-link <?php if ($pageName == "help") { echo "active"; }?>">Help</a>
					</li>
					
					<?php if ($admin) { ?>
					<li class="nav-item">
						<a href="/WQIS/pages/administratorpanel" class="nav-link <?php if ($pageName == "administratorpanel") { echo "active"; }?>">Admin Panel</a>
					</li>
					<?php
					}
					?>
					
					<?php if ($admin) { ?>
					<li class="nav-item">
						<a href="/WQIS/feedback/adminfeedback" class="nav-link <?php if ($pageName == "adminfeedback" || $pageName == "userfeedback") { echo "active"; }?>">Feedback</a>
					</li>
				<?php
				} 
				else { ?>
					<li class="nav-item">
						<a href="/WQIS/feedback/userfeedback" class="nav-link <?php if ($pageName == "userfeedback") { echo "active"; }?>">Feedback</a>
					</li>
				<?php
				}
				?>
				</ul>

				<ul class="nav pull-right">
					<li class="dropdown">
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
							<a class="dropdown-item" href='<?php echo $this->Url->build(['controller' => 'users', 'action' => 'logout']) ?>'>Log out</a>
						</div>
					</li>
				</ul>
			</div>
        </nav>
        
        <?= $this->Flash->render() ?>
		<div class="container content col-lg-12" style="min-height:500px">
		<?= $this->fetch('content') ?>
		</div>
	
		<!--loading spinner-->
		<div class="csscssload-load-frame loadingspinnermain">
			<?php
				for ($i=0; $i<24; $i++) {
					echo "<div class=\"cssload-dot\"></div>";
				}
			?>
		</div>
		<?php if (!isset($_COOKIE["ignoreBrowserCompatibility"])) { ?><script>browserDetect();</script><?php } ?>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    </body>
</html>