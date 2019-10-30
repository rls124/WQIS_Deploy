<?= $this->Html->css('login_register.css') ?>
<?= $this->Html->script('changePassword.js') ?>

<div id ='message' class="message hidden"></div>

<div class="container roundGreyBox">

    <div class="mt-3">
        <h1 class="centeredText" id="wqisHeading"><span class="glyphicon glyphicon-user" style="font-size: 20pt;"></span>  Edit User Info</h1>
    </div>
    <hr>
    
    <div class="container">
        <?=
            $this->Form->create(false, [
		'id' => 'updateUserForm'
		]
	    )
	?>
        <div class="form-group row">
            <?php 
                $uname = '';
                if($userinfo !== NULL) {
                    $uname = $user->username;
                } else {
                    $uname = $username;
                }
            ?>
            <?=
                $this->Form->control('Username', [
                    'label' => [
                        'text' => 'Username',
                        'class' => 'col-lg-2 label-reg lol'
                    ],
                    'templates' => [
                        'inputContainer' => '{{content}}'
                    ],
                    'class' => "col-lg-10 form-control textinput mainPage",
                    'name' => "username",
                    'default' => $uname,
                    'id' => "username",
                    'placeholder' => "Your Username...",
                    'readonly' => 'readonly'
                ]);
            ?>
            <p class="errorMessage" id="usernameError"></p>
        </div>
        
        <?php if ($userinfo !== NULL) { ?>
            <div class="form-group row">
            <?=
                $this->Form->control('firstname', [
                    'label' => [
                        'text' => 'First Name',
                        'class' => 'col-lg-2 label-reg lol'
                    ],
                    'templates' => [
                        'inputContainer' => '{{content}}'
                    ],
                    'default' => $user->firstname,
                    'class' => "col-lg-10 form-control textinput mainPage",
                    'name' => "firstname",
                    'id' => "firstname",
                    'placeholder' => "Your First Name...",
                ]);
            ?>
            <p class="errorMessage" id="firstnameError"></p>
            </div>
            <div class="form-group row">
            <?=
                $this->Form->control('lastname', [
                    'label' => [
                        'text' => 'Last Name',
                        'class' => 'col-lg-2 label-reg lol'
                    ],
                    'templates' => [
                        'inputContainer' => '{{content}}'
                    ],
                    'default' => $user->lastname,
                    'class' => "col-lg-10 form-control textinput mainPage",
                    'name' => "lastname",
                    'id' => "lastname",
                    'placeholder' => "Your Last Name...",
                ]);
            ?>
            <p class="errorMessage" id="lastnameError"></p>
            </div>
            <div class="form-group row">
            <?=
                $this->Form->control('organization', [
                    'label' => [
                        'text' => 'Organization',
                        'class' => 'col-lg-2 label-reg lol'
                    ],
                    'templates' => [
                        'inputContainer' => '{{content}}'
                    ],
                    'default' => $user->organization,
                    'class' => "col-lg-10 form-control textinput mainPage",
                    'name' => "organization",
                    'id' => "organization",
                    'placeholder' => "Your Organization...",
                ]);
            ?>
            <p class="errorMessage" id="organizationError"></p>
            </div>
            <div class="form-group row">
            <?=
                $this->Form->control('position', [
                    'label' => [
                        'text' => 'Position',
                        'class' => 'col-lg-2 label-reg lol'
                    ],
                    'templates' => [
                        'inputContainer' => '{{content}}'
                    ],
                    'default' => $user->position,
                    'class' => "col-lg-10 form-control textinput mainPage",
                    'name' => "position",
                    'id' => "position",
                    'placeholder' => "Your Position...",
                ]);
            ?>
            <p class="errorMessage" id="positionError"></p>
            </div>
            <hr>
        <?php } ?>
        
        <div class="form-group row">
            <?=
                $this->Form->control('userpw', [
                    'label' => [
                        'text' => 'New Password',
                        'class' => 'col-lg-2 label-reg lol'
                    ],
                    'templates' => [
                        'inputContainer' => '{{content}}'
                    ],
                    'type' => 'password',
                    'class' => "col-lg-10 form-control textinput mainPage",
                    'name' => "userpw",
                    'id' => "userpw",
                    'placeholder' => "Your New Password..."
                ]);
            ?>
            <p class="errorMessage" id="passError"></p>
        </div>
        <div class="form-group row">
            <?=
                $this->Form->control('New Password (again)', [
                    'label' => [
                        'class' => 'col-lg-2 label-reg lol'
                    ],
                    'templates' => [
                        'inputContainer' => '{{content}}'
                    ],
                    'type' => 'password',
                    'class' => "col-lg-10 form-control textinput mainPage mb-1",
                    'name' => "passConfirm",
                    'id' => "passConfirm",
                    'placeholder' => "Your New Password..."
                ]);
            ?>
            <p class="errorMessage" id="passConfirmError" style=""></p>
        </div>
    </div>
    <?php if ($userinfo !== NULL) {?>
            <hr>
            <label>Security Question 1</label>
            <?=
                $this->Form->select('securityquestion1',[
                    'What is the first name of the person you first kissed?' => 'What is the first name of the person you first kissed?', 
                    'What is the last name of the teacher who gave you your first failing grade?' => 'What is the last name of the teacher who gave you your first failing grade?', 
                    'What was the name of your elementary / primary school?' => 'What was the name of your elementary / primary school?', 
                    'In what city or town does your nearest sibling live?' => 'In what city or town does your nearest sibling live?', 
                    'What is your favorite book?' => 'What is your favorite book?'
                    ], [
                    'default' => $user->securityquestion1,
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
                    'class' => "form-control modalinput secPage mainPage mb-4",
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
                    'default' => $user->securityquestion2,
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
                    'class' => "form-control modalinput secPage mainPage mb-4",
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
                    'default' => $user->securityquestion3,
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
                    'class' => "form-control modalinput secPage mainPage",
                    'name' => "securityanswer3",
                    'id' => "securityanswer3",
                    'placeholder' => "Your Answer...",
                    'oninput' => "ensureSecurityInput();"
                ]);
            ?>
            <p class="errorMessage" id="securityanswer3Error" style=""></p>
        <?php } ?>
    <input type="submit" class="btn mb-3 btn-basic" id="changePass-btn" value="Submit Changes" style="float: right">

    <?= $this->Form->end() ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>