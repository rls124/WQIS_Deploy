<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            Water Quality Information System
        </title>
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
        <?= $this->Html->script('ajaxlooks.js') ?>
		
	<script>
function browserDetect() {
	var ua = navigator.userAgent;
	
	try {
		if (ua.includes("Edge")) {
			var verNum = parseFloat(ua.split("Edge/")[1]);
			if (verNum < 18.17763) { //version Mackenzie has tested on, seems to work properly that far back at least
				showError("Your browser is out of date and we cannot guarantee that all website functionality will work as expected. Please consider updating to the latest version of Edge, or a different browser");
			}
		}
		else if (ua.includes("Firefox")) {
			var verNum = parseFloat(ua.split("Firefox/")[1]);
			
			if (verNum < 65) {
				showError("Your browser is out of date and we cannot guarantee that all website functionality will work as expected. Please consider updating to the latest version of Firefox, or a different browser");
			}
		}
		else if (ua.includes("Chrome")) {
			var verNum = parseFloat(ua.split("Chrome/")[1].split(" ")[0]);
		
			if (verNum < 75) {
				showError("Your browser is out of date and we cannot guarantee that all website functionality will work as expected. Please consider updating to the latest version of Chrome, or a different browser");
			}
		}
		else if (ua.includes("Safari")) {
			var verNum = parseFloat(ua.split("Safari/")[1]);
			
			if (verNum < 604) {
				showError("Your browser is out of date and we cannot guarantee that all website functionality will work as expected. Please consider updating to the latest version of Safari, or a different browser");
			}
		}
	}
	catch (e) {
		//if it failed, its almost certainly internet explorer, which doesn't support "includes". In any case, its a browser so ancient it we can't hope to support it
		showError("Internet Explorer is no longer supported. We cannot guarantee that all website functionality will work as expected. Please continue switching to Microsoft Edge, Firefox, or Chrome.");
	}
}

function showError(text) {
	var errorNotice = document.createElement("div");
	errorNotice.setAttribute("id", "browserCompatibilityMessage");
	errorNotice.setAttribute("style", "bottom:0; position:fixed; z-index:150; _position:absolute; _top:expression(eval(document.documentElement.scrollTop+(document.documentElement.clientHeight-this.offsetHeight))); background-color:yellow;");
	errorNotice.innerHTML = text;
	
	var okButton = document.createElement("button");
	okButton.innerHTML = "OK";
	okButton.setAttribute("onclick", "hideBrowserCompatibilityMessage()");
	
	errorNotice.appendChild(okButton);
	
	document.body.appendChild(errorNotice);
}

function hideBrowserCompatibilityMessage() {
	document.getElementById("browserCompatibilityMessage").style.display = "none";
}
	</script>
    </head>
    <body class="h-100">
        <nav class="navbar fixed-top navbar-expand-lg navbar-light">
            <?= $this->Html->link(__('WQIS'), ['controller' => 'users', 'action' => 'login'], ['class' => 'navbar-brand']); ?>
            <?php if ($userinfo !== NULL) { ?>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav mr-auto navbar-right">
                            <li class="nav-item">
								<?php
								if ($this->name == "SiteLocations") {
									echo "<a href=\"/WQIS/site-locations/chartselection\" class=\"nav-link active\">View Water Quality Data</a>";
								}
								else {
									echo "<a href=\"/WQIS/site-locations/chartselection\" class=\"nav-link\">View Water Quality Data</a>";
								}
								?>
                            </li>
                            <?php if ($admin) { ?>
                                <li class="nav-item">
									<?php
									if ($this->name == "Pages") {
										//strictly, probably should have a separate value for admin. This organization is fucked, will deal with it later
										echo "<a href=\"/WQIS/pages/administratorpanel\" class=\"nav-link active\">Admin Panel</a>";
									}
									else {
										echo "<a href=\"/WQIS/pages/administratorpanel\" class=\"nav-link\">Admin Panel</a>";
									}
									?>
                                </li>
                                <?php
                            }
                            ?>
                            <li class="nav-item">
								<?php
								if ($this->name == "Users") {
									echo "<a href=\"/WQIS/users/edituserinfo\" class=\"nav-link active\">User Profile</a>";
								}
								else {
									echo "<a href=\"/WQIS/users/edituserinfo\" class=\"nav-link\">User Profile</a>";
								}
								?>
                            </li>
                            <?php if ($admin) { ?>
                            <li class="nav-item">
								<?php
								if ($this->name == "Feedback") {
									echo "<a href=\"/WQIS/feedback/adminfeedback\" class=\"nav-link active\">Feedback</a>";
								}
								else {
									echo "<a href=\"/WQIS/feedback/adminfeedback\" class=\"nav-link\">Feedback</a>";
								}
								?>
                            </li>
                            <?php 
                            } 
                            else { ?>
                            <li class="nav-item">
                                <?php
								if ($this->name == "Feedback") {
									echo "<a href=\"/WQIS/feedback/userfeedback\" class=\"nav-link active\">Feedback</a>";
								}
								else {
									echo "<a href=\"/WQIS/feedback/userfeedback\" class=\"nav-link\">Feedback</a>";
								}
								?>
                            </li>
                            <?php
                            }
                            ?>
                        </ul>

                        <input class="btn btn-outline-primary" type='button' value='Logout' onclick="location.href = '<?php echo $this->Url->build(['controller' => 'users', 'action' => 'logout']); ?>';" />
                    <?php
                }
            ?>
        </nav>
        <br>
        <br>
        <br>
        <br>
        <?= $this->Flash->render() ?>
		<div class="container roundGreyBox col-md-10" style="min-height:500px">
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
		<script>browserDetect();</script>
    </body>
</html>