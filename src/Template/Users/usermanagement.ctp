<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<?= $this->Html->script('userManagement.js') ?>
<?= $this->Html->css('userManagement.css') ?>

<div id ='message' class="message hidden"></div>

<p class="centeredText" id="wqisHeading" style='font-size:2.5rem;'><span class="glyphicon glyphicon-user" style="font-size: 20pt;"></span>  User Management
	<a data-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
		<span class="glyphicon glyphicon-question-sign" style="font-size:18pt;" data-toggle="tooltip" title="Information" id="infoGlyph"></span>
	</a>
</p>

<hr>
<div class="collapse" id="collapseInfo">
	<div class="card card-body">
		<p> This page is used to add, edit, or delete users. </p>
            <ul>
                <li>To add a user, click the 'Add User' button.</li>
                <li>To delete a user, click the delete icon in the row containing the user to delete.</li>
                <li>To edit a user, click the edit icon in the row containing the user to edit.</li>
            </ul>
        </div>
    </div>
    <input type='button' class='addUserbtn btn-basic btn mt-2 mb-3 btn-md' value='Add User' id='addUserBtn' name='addUserBtn' data-toggle="modal" data-target="#addUserModal"/>
    <table id='tableView'  class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>User<br>Name</th>
                <th>User<br>Type</th>
                <th>Full<br>Name</th>
                <th>Email<br>Address</th>
                <th>Organization</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTable">
	    <?php
		$row = 0;
		foreach ($Users as $userData):
		    ?>
		    <tr id='tr-<?= $userData->username ?>'>
			<td><?= $userData->username ?></td>
			<td id='<?php echo 'td-' . $userData->username . '-admin'; ?>'><?php
			    if ($userData->admin == 1) {
				echo 'admin';
			    } else {
				echo 'general';
			    }
			    ?></td>
			<td id='<?php echo 'td-' . $userData->username . '-name'; ?>'><?= $userData->firstname . ' ' . $userData->lastname; ?></td>
			<td id='<?php echo 'td-' . $userData->username . '-email'; ?>'><?= $userData->email ?></td>
			<td id='<?php echo 'td-' . $userData->username . '-org'; ?>'><?= $userData->organization ?></td>
			<td id='<?php echo 'td-' . $userData->username . '-pos'; ?>'><?= $userData->position ?></td>
                        <td><a id="edit-tooltip" data-toggle="tooltip" title="Edit User">
			    <?=
			    $this->Html->tag('span', "", [
				'class' => "edit glyphicon glyphicon-pencil",
				'id' => 'edit-' . $userData->username,
				'name' => 'edit-' . $userData->username,
				'data-toggle' => "modal",
				'data-target' => "#editUserModal"
			    ])
			    ?>
                            </a><a id="delete-tooltip" data-toggle="tooltip" title="Delete User">
			    <?=
			    $this->Html->tag('span', "", [
				'class' => "delete glyphicon glyphicon-trash",
				'id' => 'delete-' . $userData->username,
				'name' => 'delete-' . $userData->username
			    ])
			    ?>
                            </a>
			</td>
			<?php
			$row++;
			?>
		    </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
    <input type='button' class='addUserbtn btn-basic btn mb-3 btn-md' value='Add User' id='addUserBtn' name='addUserBtn' style='float: right;' data-toggle="modal" data-target="#addUserModal"/>
</div>

<!-- Modal Stuff for edit button -->
<div id="editUserModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

	<?=
	    $this->Form->create(false, [
		'id' => 'updateUserForm'
		]
	    )
	?>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="edit-header">Edit User: </h4>
                <p hidden id="edit-username"></p>
            </div>
            <div class="modal-body">
		<?=
		    $this->Form->control('firstname', [
			'label' => [
			    'text' => 'First Name',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput",
			'name' => "firstname",
			'id' => "edit-firstname",
			'placeholder' => "Your First Name..."
		    ]);
		?>
		<?=
		    $this->Form->control('lastname', [
			'label' => [
			    'text' => 'Last Name',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput",
			'name' => "lastname",
			'id' => "edit-lastname",
			'placeholder' => "Your Last Name..."
		    ]);
		?>
		<?=
		    $this->Form->control('email', [
			'label' => [
			    'text' => 'Email Address',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput",
			'name' => "email",
			'id' => "edit-email",
			'placeholder' => "Your Email..."
		    ]);
		?>
		<?=
		    $this->Form->control('organization', [
			'label' => [
			    'text' => 'Organization',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput",
			'name' => "organization",
			'id' => "edit-organization",
			'placeholder' => "Your Organization..."
		    ]);
		?>
		<?=
		    $this->Form->control('position', [
			'label' => [
			    'text' => 'Position',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput",
			'name' => "position",
			'id' => "edit-position",
			'placeholder' => "Your Position..."
		    ]);
		?>
                <label for='#edit-adminChk'>Set as administrator: </label>
		<?=
		    $this->Form->checkbox('admin', [
			'checked' => true,
			'id' => 'edit-admin'
			]
		    );
		?>
                <br>
		<?=
		    $this->Form->control('userpw', [
			'label' => [
			    'text' => 'Password',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'type' => 'password',
			'class' => "form-control textinput",
			'name' => "userpw",
			'id' => "edit-userpw",
			'placeholder' => "Your Password..."
		    ]);
		?>
		<?=
		    $this->Form->control('Password (again)', [
			'label' => [
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'type' => 'password',
			'class' => "form-control textinput",
			'name' => "passConfirm",
			'id' => "edit-passConfirm",
			'placeholder' => "Your Password..."
		    ]);
		?>
            </div>
            <div class="modal-footer">
                <button type="submit" id='update-btn' name='update-btn' class="btn btn-default btn-basic btn btn-sm">Save</button>
                <button type="button" id="edit-close" class="btn btn-default btn-sm btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
	<?= $this->Form->end() ?>
    </div>
</div>


<!-- Modal Stuff for Add User button -->
<div id="addUserModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
	<?=
	    $this->Form->create(false, [
		'id' => 'addUserForm'
		]
	    )
	?>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New User</h4>
            </div>
            <div class="modal-body">
		<?=
		    $this->Form->control('firstname', [
			'label' => [
			    'text' => 'First Name',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput addinput",
			'name' => "firstname",
			'id' => "add-firstname",
			'placeholder' => "Your First Name..."
		    ]);
		?>
		<?=
		    $this->Form->control('lastname', [
			'label' => [
			    'text' => 'Last Name',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput addinput",
			'name' => "lastname",
			'id' => "add-lastname",
			'placeholder' => "Your Last Name..."
		    ]);
		?>
		<?=
		    $this->Form->control('email', [
			'label' => [
			    'text' => 'Email Address',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput addinput",
			'name' => "email",
			'id' => "add-email",
			'placeholder' => "Your Email..."
		    ]);
		?>
		<?=
		    $this->Form->control('organization', [
			'label' => [
			    'text' => 'Organization',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput addinput",
			'name' => "organization",
			'id' => "add-organization",
			'placeholder' => "Your Organization..."
		    ]);
		?>
		<?=
		    $this->Form->control('position', [
			'label' => [
			    'text' => 'Position',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput addinput",
			'name' => "position",
			'id' => "add-position",
			'placeholder' => "Your Position..."
		    ]);
		?>
		<?=
		    $this->Form->control('username', [
			'label' => [
			    'Username' => 'Username',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput addinput",
			'name' => "username",
			'id' => "add-username",
			'placeholder' => "Your Username..."
		    ]);
		?>
                <label for='#add-adminChk'>Set as administrator: </label>
		<?=
		    $this->Form->checkbox('admin', [
			'label' => [
			    'class' => 'addinput'
			],
			'checked' => false,
			'id' => 'add-admin'
			]
		    );
		?>
                <br>
		<?=
		    $this->Form->control('userpw', [
			'label' => [
			    'text' => 'Password',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'type' => 'password',
			'class' => "form-control textinput addinput",
			'name' => "userpw",
			'id' => "add-userpw",
			'placeholder' => "Your Password..."
		    ]);
		?>
		<?=
		    $this->Form->control('Password (again)', [
			'label' => [
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'type' => 'password',
			'class' => "form-control textinput addinput",
			'name' => 'passConfirm',
			'id' => "add-passConfirm",
			'placeholder' => "Your Password..."
		    ]);
		?>
            </div>
            <div class="modal-footer">
                <button type="submit" id='add-btn' name='add-btn' class="btn btn-default btn-basic btn btn-sm">Add User</button>
                <button type="button" id='add-close' class="btn btn-default btn-sm btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
	<?= $this->Form->end() ?>
    </div>
</div>