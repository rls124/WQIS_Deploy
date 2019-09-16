
<?= $this->Html->css('login_register.css') ?>
<?= $this->Html->script('loginValidation.js') ?>
<div class="container roundGreyBox">

    <div class="mt-3">
        <h1 class="centeredText" id="wqisHeading">Water Quality Information Service</h1>
        <span id="wqisInfo">The Water Quality Information Service is a resource for researchers, agency officials, and the general public for organizing and presenting water quality data from the St. Joseph, St. Marys, Auglaize and Upper Maumee River watersheds. It is maintained by the St. Joseph River Watershed Initiative in collaboration with the Environmental Resources Center at Indiana University Purdue University Fort Wayne.</span>
    </div>

    <hr>
    <?php
	if ($userinfo === NULL) {
	    echo $this->Form->create('loginForm', [
		'id' => 'loginForm',
		'onsubmit' => 'return validate()'
		]
	    );
	    ?>

	    <!-- Header and server side error message -->
	    <!--<h2 class="centeredText" id="userLoginHeading">User Login</h2>-->

	    <!-- Username -->
	    <div class="form-group">
		<?=
		$this->Form->control('Username', [
		    'label' => [
			'class' => 'label-login'
		    ],
		    'templates' => [
			'inputContainer' => '{{content}}'
		    ],
		    'div' => 'false',
		    'class' => "form-control  textinput",
		    'name' => "username",
		    'id' => "username",
		    'placeholder' => "Your Username...",
		    'oninput' => "ensureInput();",
		    'onfocus' => "toggle(this);"
		]);
		?>
		<!--
		<label for="username" class="label-login">Username:</label>
		<input type="text" class="form-control textinput" name="username" id="username" placeholder="Your Username..." onfocus="toggle(this)"> -->
		<p class="errorMessage" id="usernameError"></p>
	    </div>

	    <!-- Password -->
	    <div class="form-group pb-1">
		<?=
		$this->Form->control('userpw', [
		    'label' => [
			'text' => 'Password',
			'class' => 'label-login'
		    ],
		    'type' => 'password',
		    'class' => "form-control textinput",
		    'name' => "userpw",
		    'id' => "userpw",
		    'placeholder' => "Your Password...",
		    'oninput' => "ensureInput();",
		    'onfocus' => "toggle(this);"
		]);
		?>
		<!--
		<label for="pass" class="label-login">Password:</label>
		<input type="password" class="form-control textinput" name="pass" id="pass" placeholder="Your Password..." onfocus="toggle(this)">-->
		<p class="errorMessage" id="passError"></p>
	    </div>

	    <!-- Login Button -->
	    <div class="container text-center">
		<?= $this->Form->button(__('Log In'), ['class' => 'btn mb-3  btn-basic btn-lg login-btn', 'id' => 'login-btn'], ['action' => 'login']); ?>
		<input class="btn mb-3 btn-basic btn-lg login-btn" id="createAccountBtn" type='button' value='Create Account' onclick="location.href = '<?php echo $this->Url->build(['controller' => 'users', 'action' => 'signup']) ?>';" />
	    </div>
	    <?= $this->Form->end() ?>
            <?= $this->Html->link(__('Forget Your Password?'), ['controller' => 'Users', 'action' => 'forgotpassword'], ['class' => 'forgotPass-link', 'style' => 'font-size: 10pt; width: 150px; display: block; margin-right: auto; margin-left: auto']) ?>
	<hr>
    <?php } ?>
<div class="card-deck">
    <div class="card">
	<div class="card-block">
	    <h5 class="centeredText card-title">St. Joseph River Watershed Initiative</h5>
	    <hr style="width: 75%">
	    <a href="http://sjrwi.org" target="_blank">
		<img class="mb-1 ml-2 float-md-right float-sm-none" id="sjrwiImg" src="webroot/img/sjrwi-logo.gif" alt="S.J.R.W.I logo" />
	    </a>
	    <p class="card-text" id="sjrwiInfo">
		The St. Joseph River Watershed Initiative is a not-for-profit organization working to protect and improve water quality in the St. Joseph River Watershed by promoting economically and environmentally compatible land uses and practices. Learn more at <a href="http://sjrwi.org" target="_blank">sjrwi.org</a>
	    </p>
	</div>
    </div>

    <div class="card">
	<div class="card-block">
	    <h5 class="centeredText card-title">Environmental Resources Center</h5>
	    <hr style="width: 75%">
	    <a href="http://erc.pfw.edu" target="_blank">
		<img class="mb-1 ml-2 float-md-right float-sm-none" id="ercImg" src="webroot/img/erc-turtle.jpg" alt="Turtle" />
	    </a>
	    <p class="card-text" id="ercInfo">
		The Environmental Resources Center is one of PFW's Centers of Excellence. ERC promotes understanding and conservation of the natural resources of the region through scientific research, educational opportunities and outreach. Learn more at <a href="http://erc.pfw.edu" target="_blank">erc.pfw.edu</a>
	    </p>
	</div>
    </div>
</div>

<!-- style="float: left; margin-right:10px;" -->

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src=""></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
