<?php
echo $this->Html->css("login_register.css");
echo $this->Html->script("createAccountValidation.js");

echo $this->Form->create(false, [
	"id" => "registerForm",
	"autocomplete" => "new-password"
	]
);
?>
<div id="message" class="message hidden"></div>
<div class="container">
	<h1 class="mt-3 centeredText" id="accountCreationHeader">Account Creation</h1>
	<h6 class="mt-3 centeredText requiredText">*All fields are required to register an account</h6>
	<hr>

	<?php
	$fieldNames = ["First Name", "Last Name", "Email", "Organization", "Position", "Username"];
	$fieldIDs = ["firstname", "lastname", "email", "organization", "position", "username"];
	$errorIDs = ["firstNameError", "lastNameError", "emailError", "organizationError", "positionError", "usernameError"];
		
	for ($i=0; $i<sizeof($fieldNames); $i++) {
		echo "<div class=\"form-group row\">";
		echo $this->Form->control($fieldNames[$i], [
			"label" => [
				"class" => "col-lg-2 label-reg lol"
			],
			"templates" => [
				"inputContainer" => "{{content}}"
			],
			"class" => "form-control col-lg-10 textinput mainPage",
			"name" => $fieldIDs[$i],
			"id" => $fieldIDs[$i],
			"placeholder" => "Your " . $fieldNames[$i] . "...",
			"oninput" => "ensureInput();",
			"autocomplete" => "new-password"
		]);
		echo "</div>";
		echo "<p class=\"errorMessage\" id=\"" . $errorIDs[$i] . "\"></p>";
	}
	?>

	<!--password fields need to be handled separately-->
	<div class="form-group row">
	<?=
	$this->Form->control("Password", [
		"label" => [
			"class" => "col-sm-2 label-reg lol"
		],
		"templates" => [
			"inputContainer" => "{{content}}"
		],
		"type" => "password",
		"class" => "form-control col-lg-10 textinput mainPage",
		"name" => "userpw",
		"id" => "userpw",
		"placeholder" => "Your Password...",
		"oninput" => "ensureInput();",
		"autocomplete" => "new-password"
	]);
	?>
	</div>
	<p class="errorMessage" id="passError"></p>

	<div class="form-group row">
	<?=
	$this->Form->control("Password (again)", [
		"label" => [
			"class" => "col-sm-2 label-reg lol"
		],
		"templates" => [
			"inputContainer" => "{{content}}"
		],
		"type" => "password",
		"class" => "form-control col-lg-10 textinput mainPage",
		"name" => "passConfirm",
		"id" => "passConfirm",
		"placeholder" => "Your Password...",
		"oninput" => "ensureInput();",
		"autocomplete" => "new-password"
	]);
	?>
	</div>
	<p class="errorMessage" id="passConfirmError" style=""></p>
	<!-- Register Button -->
	<div id="registerMe">
		<button type="button" disabled="false" class="btn mb-3 btn-basic" id="registerMeBtn">Next</button>
	</div>
</div>

<!-- Modal Stuff for Add User button -->
<div id="registerUserModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Security Questions</h4>
			</div>
			<div class="modal-body">
				<?php
				$questions = [ 
					[
						"What is the first name of the person you first kissed?",
						"What is the last name of the teacher who gave you your first failing grade?",
						"What was the name of your elementary / primary school?",
						"In what city or town does your nearest sibling live?",
						"What is your favorite book?"
					], [
						"What is the name of the road you grew up on?",
						"What is your mother’s maiden name?",
						"What was the name of your first/current/favorite pet?",
						"What was the first company that you worked for?",
						"Where did you meet your spouse?"
					], [
						"Where did you go to high school/college?",
						"What is your favorite food?",
						"What city were you born in?",
						"Where is your favorite place to vacation?",
						"What is your greatest strength?"
					]
				];
				
				for ($i=0; $i<sizeof($questions); $i++) {
					echo "<label>Security Question " . ($i+1) . "</label>";
					$options = [];
					for ($j=0; $j<sizeof($questions[$i]); $j++) {
						$options = array_merge($options, [$questions[$i][$j] => $questions[$i][$j]]);
					}
					
					echo $this->Form->select("securityquestion" . ($i+1),
						$options, [
						"empty" => "Choose a security question...",
						"label" => "Question " . ($i+1),
						"id" => "securityquestion" . ($i+1),
						"class" => "form-control select secPage",
						"style" => "font-size: 9pt",
						"onchange" => "ensureSecurityInput();"
						]
					);
					
					echo "<p class=\"errorMessage\" id=\"securityquestion" . ($i+1) . "Error\"></p>";
					echo $this->Form->control("Answer " . ($i+1), [
					"label" => false,
					"templates" => [
						"inputContainer" => "{{content}}"
					],
					"class" => "form-control modalinput secPage mb-4",
					"name" => "securityanswer" . ($i+1),
					"id" => "securityanswer" . ($i+1),
					"placeholder" => "Your Answer...",
					"oninput" => "ensureSecurityInput();"
				]);
				}
				?>
			</div>
			<div class="modal-footer">
				<input disabled="disabled" type="submit" id="registerModal-btn" name="registerModal-btn" class="btn btn-default btn-basic btn btn-sm" value="Register"/>
			</div>
		</div>
	</div>
</div>
<?= $this->Form->end() ?>