<?= $this->Html->css('login_register.css') ?>
<?= $this->Html->script('createAccountValidation.js') ?>

<?=
    $this->Form->create(false, [
        'id' => 'registerForm',
        ]
    )
?>
<div id ='message' class="message hidden"></div>
<div class="container roundGreyBox mb-3">
   
    <div class="container">
        <!-- Headers and server-side error message -->
        <h1 class="mt-3 centeredText" id="accountCreationHeader">Account Creation</h1>
        <h6 class = "mt-3 centeredText requiredText">*All fields are required to register an account</h6>
        <hr>

        <!-- First Name -->
        <div class="form-group row">
	    <?=
		$this->Form->input('First Name', [
		    'label' => [
			'class' => 'col-lg-2 label-reg lol'
		    ],
		    'templates' => [
			'inputContainer' => '{{content}}'
		    ],
		    'class' => "form-control col-lg-10 textinput mainPage",
		    'name' => "firstname",
		    'id' => "firstname",
		    'placeholder' => "Your First Name...",
		    'oninput' => "ensureInput();"
		]);
	    ?>
        </div>
        <p class="errorMessage" id="firstNameError"></p>

        <!-- Last Name-->
        <div class="form-group row">
	    <?=
		$this->Form->input('Last Name', [
		    'label' => [
			'class' => 'col-sm-2 label-reg lol'
		    ],
		    'templates' => [
			'inputContainer' => '{{content}}'
		    ],
		    'class' => "form-control col-lg-10 textinput mainPage",
		    'name' => "lastname",
		    'id' => "lastname",
		    'placeholder' => "Your Last Name...",
		    'oninput' => "ensureInput();"
		]);
	    ?>
        </div>

        <p class="errorMessage" id="lastNameError"></p>

        <!-- Email -->

        <div class="form-group row">
	    <?=
		$this->Form->control('Email', [
		    'label' => [
			'class' => 'col-sm-2 label-reg lol'
		    ],
		    'templates' => [
			'inputContainer' => '{{content}}'
		    ],
		    'class' => "form-control col-lg-10 textinput mainPage",
		    'name' => "email",
		    'id' => "email",
		    'placeholder' => "Your Email...",
		    'oninput' => "ensureInput();"
		]);
	    ?>
        </div>
        <p class="errorMessage" id="emailError"></p>

        <!-- Organization -->
        <div class="form-group row">
	    <?=
		$this->Form->control('Organization', [
		    'label' => [
			'class' => 'col-sm-2 label-reg lol'
		    ],
		    'templates' => [
			'inputContainer' => '{{content}}'
		    ],
		    'class' => "form-control col-lg-10 textinput mainPage",
		    'name' => "organization",
		    'id' => "organization",
		    'placeholder' => "Your Organization...",
		    'oninput' => "ensureInput();"
		]);
	    ?>
        </div>
        <p class="errorMessage" id="organizationError"></p>

        <!-- Position at Organization -->
        <div class="form-group row">
	    <?=
		$this->Form->control('Position', [
		    'label' => [
			'class' => 'col-sm-2 label-reg lol'
		    ],
		    'templates' => [
			'inputContainer' => '{{content}}'
		    ],
		    'class' => "form-control col-lg-10 textinput mainPage",
		    'name' => "position",
		    'id' => "position",
		    'placeholder' => "Your Position...",
		    'oninput' => "ensureInput();"
		]);
	    ?>
        </div>
        <p class="errorMessage" id="positionError" style="text-align: center"></p>

        <!-- Username -->
        <div class="form-group row">
	    <?=
		$this->Form->control('Username', [
		    'label' => [
			'class' => 'col-sm-2 label-reg lol'
		    ],
		    'templates' => [
			'inputContainer' => '{{content}}'
		    ],
		    'div' => 'false',
		    'class' => "form-control col-lg-10 textinput mainPage",
		    'name' => "username",
		    'id' => "username",
		    'placeholder' => "Your Username...",
		    'oninput' => "ensureInput();"
		]);
	    ?>

        </div>
        <p class="errorMessage" id="usernameError"></p>


        <!-- Password -->
        <div class="form-group row">
	    <?=
		$this->Form->control('userpw', [
		    'label' => [
			'text' => 'Password',
			'class' => 'col-sm-2 label-reg lol'
		    ],
		    'templates' => [
			'inputContainer' => '{{content}}'
		    ],
		    'type' => 'password',
		    'class' => "form-control col-lg-10 textinput mainPage",
		    'name' => "userpw",
		    'id' => "userpw",
		    'placeholder' => "Your Password...",
		    'oninput' => "ensureInput();"
		]);
	    ?>

        </div>
        <p class="errorMessage" id="passError"></p>

        <!-- Confirm Password -->
        <div class="form-group row">
	    <?=
		$this->Form->control('Password (again)', [
		    'label' => [
			'class' => 'col-sm-2 label-reg lol'
		    ],
		    'templates' => [
			'inputContainer' => '{{content}}'
		    ],
		    'type' => 'password',
		    'class' => "form-control col-lg-10 textinput mainPage",
		    'name' => "passConfirm",
		    'id' => "passConfirm",
		    'placeholder' => "Your Password...",
		    'oninput' => "ensureInput();"
		]);
	    ?>
        </div>
        <p class="errorMessage" id="passConfirmError" style=""></p>
        <!-- Register Button -->
        <div id="registerMe">
            <button type="button" disabled="false" class="btn mb-3 btn-basic" id="registerMeBtn">Next</button>
        </div>
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
                <label>Security Question 1</label>
                <?=
                    $this->Form->select('securityquestion1',[
                        'What is the first name of the person you first kissed?' => 'What is the first name of the person you first kissed?', 
                        'What is the last name of the teacher who gave you your first failing grade?' => 'What is the last name of the teacher who gave you your first failing grade?', 
                        'What was the name of your elementary / primary school?' => 'What was the name of your elementary / primary school?', 
                        'In what city or town does your nearest sibling live?' => 'In what city or town does your nearest sibling live?', 
                        'What is your favorite book?' => 'What is your favorite book?'
                        ], [
                        'empty' => 'Choose a security question...',
                        'label' => 'Question 1',
                        'id' => 'securityquestion1',
                        'class' => 'form-control select secPage',
                        'style' => 'font-size: 9pt;',
                        'onchange' => "ensureSecurityInput();"
                        ]
                    );
                ?>
                <p class="errorMessage" id="securityquestion1Error" style=""></p>
                <?=
                    $this->Form->control('Answer 1', [
                        'label' => false,
                        'templates' => [
                            'inputContainer' => '{{content}}'
                        ],
                        'class' => "form-control modalinput secPage mb-4",
                        'name' => "securityanswer1",
                        'id' => "securityanswer1",
                        'placeholder' => "Your Answer...",
                        'oninput' => "ensureSecurityInput();"
                    ]);
                ?>
                <p class="errorMessage" id="securityanswer1Error" style=""></p>
                <label>Security Question 2</label>
                <?=
                    $this->Form->select('securityquestion2',[
                        'What is the name of the road you grew up on?' => 'What is the name of the road you grew up on?', 
                        'What is your mother’s maiden name?' => 'What is your mother’s maiden name?', 
                        'What was the name of your first/current/favorite pet?' => 'What was the name of your first/current/favorite pet?', 
                        'What was the first company that you worked for?' => 'What was the first company that you worked for?', 
                        'Where did you meet your spouse?' => 'Where did you meet your spouse?'
                        ], [
                        'empty' => 'Choose a security question...',
                        'label' => 'Question 2',
                        'id' => 'securityquestion2',
                        'class' => 'form-control select secPage',
                        'style' => 'font-size: 9pt;',
                        'onchange' => "ensureSecurityInput();"
                        ]
                    );
                ?>
                <p class="errorMessage" id="securityquestion2Error" style=""></p>
                <?=
                    $this->Form->control('Answer 2', [
                        'label' => false,
                        'templates' => [
                            'inputContainer' => '{{content}}'
                        ],
                        'class' => "form-control modalinput secPage mb-4",
                        'name' => "securityanswer2",
                        'id' => "securityanswer2",
                        'placeholder' => "Your Answer...",
                        'oninput' => "ensureSecurityInput();"
                    ]);
                ?>
                <p class="errorMessage" id="securityanswer2Error" style=""></p>
                <label>Security Question 3</label>
                <?=
                    $this->Form->select('securityquestion3',[
                        'Where did you go to high school/college?' => 'Where did you go to high school/college?', 
                        'What is your favorite food?' => 'What is your favorite food?', 
                        'What city were you born in?' => 'What city were you born in?', 
                        'Where is your favorite place to vacation?' => 'Where is your favorite place to vacation?', 
                        'What is your greatest strength?' => 'What is your greatest strength?'
                        ], [
                        'empty' => 'Choose a security question...',
                        'label' => 'Question 3',
                        'id' => 'securityquestion3',
                        'class' => 'form-control select secPage',
                        'style' => 'font-size: 9pt;',
                        'onchange' => "ensureSecurityInput();"
                        ]
                    );
                ?>
                <p class="errorMessage" id="securityquestion3Error" style=""></p>
                <?=
                    $this->Form->control('Answer 3', [
                        'label' => false,
                        'templates' => [
                            'inputContainer' => '{{content}}'
                        ],
                        'class' => "form-control modalinput secPage",
                        'name' => "securityanswer3",
                        'id' => "securityanswer3",
                        'placeholder' => "Your Answer...",
                        'oninput' => "ensureSecurityInput();"
                    ]);
                ?>
                <p class="errorMessage" id="securityanswer3Error" style=""></p>
            </div>
            <div class="modal-footer">
                <input disabled="disabled" type="submit" id='registerModal-btn' name='registerModal-btn' class="btn btn-default btn-basic btn btn-sm" value="Register"/>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>