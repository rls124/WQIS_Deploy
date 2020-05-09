<?php $this->start("navbar"); ?>
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
<?php $this->end(); ?>