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

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <?= $this->Html->css('bootstrap-glyphicons.min.css') ?>
        <!-- JQuery JS import -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <?= $this->Html->script('jquery.msgbox.min.js') ?>
        <!-- Project css -->
        <?= $this->Html->css('styling.css') ?>

        <?= $this->Html->css('cakemessages.css') ?>
        <?= $this->Html->script('ajaxlooks.js') ?>
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
    </body>
</html>