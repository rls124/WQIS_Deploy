<?= $this->Html->script('forgotPassword.js') ?>
<?= $this->Html->css('login_register.css') ?>

<div id ='message' class="message hidden"></div>

<div class="mt-3">
	<h1 class="centeredText" id="wqisHeading">Forgot Password</h1>
</div>
<hr>
<?=
	$this->Form->create(false, [
		'id' => 'forgotUserPassForm',
		'url' => ['controller' => 'Users', 'action' => 'edituserinfo']
		]
	)
?>
<?=
$this->Form->control('Username', [
	'label' => [
		'text' => 'Username',
		'class' => 'label-reg'
	],
	'templates' => [
		'inputContainer' => '{{content}}'
	],
	'class' => 'form-control mainPage textinput',
	'name' => "username",
	'id' => "username",
	'placeholder' => "Your username..."
]);
?>
<p class="errorMessage" id="usernameError" style=""></p>

<input type="button" class="btn mb-3 btn-basic" id="confirmUsername-btn" value="Confirm Username" style="float: right">

<div class="collapse.show" id="collapseInfo">
	<div class="securityQuestions"></div>
</div>
<?= $this->Form->end() ?>